<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Crop;
use App\Models\Task;
use Carbon\Carbon;

class AiAssistantController extends ApiController
{
    /**
     * Chat with the AI assistant.
     * Expected JSON body:
     * {
     *   "message": "User query...",
     *   "image": (optional file),
     *   "history": (optional array of messages)
     * }
     */
    public function chat(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:10240',
            'message' => 'nullable|string|max:2000',
            'history' => 'nullable|array',
        ]);

        if (!$request->hasFile('image') && !$request->filled('message')) {
            return $this->errorResponse('Please provide an image or a message.', [], 422);
        }

        try {
            // Get user's crops for context
            $crops = auth()->user()->crops()->select('id', 'name')->get()->map(function ($crop) {
                return "ID: {$crop->id}, Name: {$crop->name}";
            })->implode(' | ');

            $systemPrompt = "أنت خبير زراعي ذكي ومساعد تقني لتطبيق Reefy. محاصيل المستخدم الحالية: [ {$crops} ].
            قواعد الرد:
            1. إذا رفع صورة: حللها واذكر المرض والحل.
            2. إذا أراد إضافة محصول جديد (مثل 'ضف محصول ذرة'):
               - رد بجملة تأكيد.
               - ثم ضع الأمر التالي في سطر جديد تماماً (بدون أي علامات مقتبسة):
               ACTION:{\"action\":\"CREATE_CROP\",\"data\":{\"name\":\"اسم المحصول\",\"type\":\"نوعه\",\"area\":1,\"planting_date\":\"YYYY-MM-DD\"}}
            3. إذا أراد إضافة مهمة (مثل 'روي الذرة غدا'): 
               - ابحث عن ID المحصول.
               - ثم ضع الأمر في سطر منفصل:
               ACTION:{\"action\":\"CREATE_TASK\",\"data\":{\"crop_id\":ID,\"title\":\"عنوان المهمة\",\"type\":\"water|fertilizer|pest|harvest|other\",\"due_date\":\"YYYY-MM-DD\",\"notes\":\"..\"}}
            4. تنبيه: يجب كتابة الـ JSON كاملاً وبدقة داخل سطر الـ ACTION:.";

            // If history is provided, we can prepend it or use it in the prompt.
            // Gemini Flash supports a certain format. For now, we'll keep it simple
            // and append the user message to the system prompt context or use a basic history string.
            $historyPrompt = "";
            if ($request->has('history')) {
                foreach ($request->history as $msg) {
                    $role = ($msg['role'] ?? 'user') === 'user' ? 'User' : 'Assistant';
                    $content = $msg['content'] ?? '';
                    $historyPrompt .= "{$role}: {$content}\n";
                }
            }

            $fullPrompt = $systemPrompt . "\n" . $historyPrompt;

            if ($request->hasFile('image')) {
                $aiResponse = $this->handleGeminiVision($request, $fullPrompt);
            } else {
                $aiResponse = $this->handleGeminiText($request, $fullPrompt);
            }

            // Cleanup AI response (remove thought process)
            $aiResponse = preg_replace('/<thought>.*?<\/thought>/s', '', $aiResponse);

            // Extract Action if exists
            $action = null;
            if (preg_match('/ACTION:\s*(.*)/s', $aiResponse, $matches)) {
                $potentialJson = trim($matches[1]);
                if (preg_match('/({.*})/s', $potentialJson, $innerMatches)) {
                    $json = $innerMatches[1];
                    $json = preg_replace('/^```(json)?\s*/i', '', $json);
                    $json = preg_replace('/```\s*$/', '', $json);
                    $decoded = json_decode(trim($json), true);
                    if ($decoded && isset($decoded['action'])) {
                        $action = $decoded;
                        // Strip the ACTION line from the text reply
                        $aiResponse = trim(str_replace($matches[0], '', $aiResponse));
                    }
                }
            }

            return $this->successResponse([
                'reply' => $aiResponse,
                'action' => $action,
                'time' => now()->format('H:i'),
            ], 'AI Response received');

        } catch (\Exception $e) {
            Log::error('API AI Error: ' . $e->getMessage());
            return $this->errorResponse('عذراً، حدث خطأ في النظام عند معالجة طلب الذكاء الاصطناعي.', [$e->getMessage()], 500);
        }
    }

    private function handleGeminiVision($request, $systemPrompt)
    {
        $apiKey = config('gemini.api_key') ?? config('services.gemini.key');
        $image = $request->file('image');
        $imageData = base64_encode(file_get_contents($image->path()));

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\nUser Message: " . ($request->message ?? 'قم بتحليل هذه الصورة زراعياً')],
                        [
                            'inline_data' => [
                                'mime_type' => $image->getMimeType(),
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'topK' => 32,
                'topP' => 1,
            ]
        ];

        $response = Http::timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", $payload);

        if ($response->status() === 429) {
            return 'عذراً، تم استهلاك الحصة المجانية لليوم من خدمة الذكاء الاصطناعي. يرجى المحاولة لاحقاً أو غداً.';
        }

        if (!$response->successful()) {
            Log::error('Gemini Vision API Error: ' . $response->body());
            return 'تعذر الحصول على رد من خدمة تحليل الصور حالياً.';
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'رد فارغ من المساعد الرقمي';
    }

    private function handleGeminiText($request, $systemPrompt)
    {
        $apiKey = config('gemini.api_key') ?? config('services.gemini.key');

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\nUser Message: " . $request->message]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2048,
            ]
        ];

        $response = Http::timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", $payload);

        if ($response->status() === 429) {
            return 'عذراً، تم استهلاك الحصة المجانية لليوم من خدمة الذكاء الاصطناعي.';
        }

        if (!$response->successful()) {
            Log::error('Gemini Text API Error: ' . $response->body());
            return 'تعذر الحصول على رد من المساعد الذكي حالياً.';
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'رد فارغ من المساعد';
    }
}
