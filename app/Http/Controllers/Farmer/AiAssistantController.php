<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Crop;
use App\Models\Task;
use Carbon\Carbon;

class AiAssistantController extends Controller
{
    /**
     * Display the AI Assistant page.
     */
    public function index()
    {
        $messages = session('ai_chat_history', []);
        $crops = auth()->user()->crops()->select('id', 'name')->get();
        return view('farmer.ai.index', compact('messages', 'crops'));
    }

    public function chat(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:10240',
            'message' => 'nullable|string|max:2000',
        ]);

        if (!$request->hasFile('image') && !$request->filled('message')) {
            return back()->with('error', __('Please provide an image or a message.'));
        }

        $history = session('ai_chat_history', []);
        $userMsg = $request->input('message');
        $imageUrl = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ai_uploads', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        $history[] = [
            'role' => 'user',
            'content' => $userMsg,
            'image_url' => $imageUrl,
            'time' => now()->format('H:i'),
        ];

        try {
            $crops = auth()->user()->crops()->select('id', 'name')->get()->map(function ($crop) {
                return "ID: {$crop->id}, Name: {$crop->name}";
            })->implode(' | ');

            $systemPrompt = "أنت خبير زراعي ذكي ومساعد تقني. محاصيل المستخدم الحالية: [ {$crops} ].
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

            if ($request->hasFile('image')) {
                $aiResponse = $this->handleGeminiVision($request, $systemPrompt);
            }
            else {
                $aiResponse = $this->handleGeminiText($request, $systemPrompt);
            }

            // Remove thought process or extra tags if necessary
            $aiResponse = preg_replace('/<thought>.*?<\/thought>/s', '', $aiResponse);

            $history[] = [
                'role' => 'ai',
                'content' => $this->simpleMarkdown($aiResponse),
                'time' => now()->format('H:i'),
            ];

            session(['ai_chat_history' => $history]);

        }
        catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            $history[] = [
                'role' => 'ai',
                'content' => 'عذراً، حدث خطأ في النظام. يرجى التأكد من مفتاح Gemini في ملف .env',
                'time' => now()->format('H:i'),
            ];
            session(['ai_chat_history' => $history]);
        }

        return redirect(route('farmer.ai.index') . '#bottom');
    }

    public function chatApi(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:10240',
            'message' => 'nullable|string|max:2000',
        ]);

        if (!$request->hasFile('image') && !$request->filled('message')) {
            return response()->json(['error' => __('Please provide an image or a message.')], 422);
        }

        $history = session('ai_chat_history', []);
        $userMsg = $request->input('message');
        $imageUrl = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ai_uploads', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        $history[] = [
            'role' => 'user',
            'content' => $userMsg,
            'image_url' => $imageUrl,
            'time' => now()->format('H:i'),
        ];

        try {
            $crops = auth()->user()->crops()->select('id', 'name')->get()->map(function ($crop) {
                return "ID: {$crop->id}, Name: {$crop->name}";
            })->implode(' | ');

            $systemPrompt = "أنت خبير زراعي ذكي ومساعد تقني. محاصيل المستخدم الحالية: [ {$crops} ].
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

            if ($request->hasFile('image')) {
                $aiResponse = $this->handleGeminiVision($request, $systemPrompt);
            }
            else {
                $aiResponse = $this->handleGeminiText($request, $systemPrompt);
            }

            // Remove thought process or extra tags if necessary
            $aiResponse = preg_replace('/<thought>.*?<\/thought>/s', '', $aiResponse);

            $formattedResponse = $this->simpleMarkdown($aiResponse);

            $newAiMsg = [
                'role' => 'ai',
                'content' => $formattedResponse,
                'time' => now()->format('H:i'),
            ];

            $history[] = $newAiMsg;
            session(['ai_chat_history' => $history]);

            return response()->json([
                'success' => true,
                'message' => $newAiMsg,
                'raw_content' => $aiResponse
            ]);

        }
        catch (\Exception $e) {
            Log::error('AI API Error: ' . $e->getMessage());
            return response()->json(['error' => 'عذراً، حدث خطأ في النظام.'], 500);
        }
    }

    public function executeAction(Request $request)
    {
        $actionType = $request->action_type;
        $actionId = $request->action_id;

        try {
            if ($actionType === 'CREATE_CROP') {
                $data = $request->validate([
                    'name' => 'required|string|max:255',
                    'type' => 'required|string|max:255',
                    'area' => 'required|numeric|min:0.1',
                    'planting_date' => 'required|date',
                ]);
                auth()->user()->crops()->create($data);
                $successMsg = 'تم إضافة المحصول الجديد بنجاح! 🎉';
            }

            if ($actionType === 'CREATE_TASK') {
                $data = $request->validate([
                    'crop_id' => 'required|exists:crops,id',
                    'title' => 'required|string|max:255',
                    'type' => 'required|in:water,fertilizer,pest,harvest,other',
                    'due_date' => 'required|date',
                    'notes' => 'nullable|string',
                ]);
                Task::create($data);
                $successMsg = 'تم جدولة المهمة الجديدة بنجاح! ✅';
            }

            // Update chat history to hide the form
            if (isset($successMsg) && $actionId) {
                $history = session('ai_chat_history', []);
                $replacement = '
                <div class="mt-4 p-4 bg-emerald-500/10 border border-emerald-500 rounded-2xl text-emerald-600 font-bold text-center animate-in zoom-in duration-500">
                    <i class="bi bi-check-circle-fill text-xl block mb-1"></i>
                    ' . $successMsg . '
                </div>';

                foreach ($history as &$msg) {
                    if ($msg['role'] === 'ai') {
                        $pattern = '/<!-- ACTION_FORM_START_' . preg_quote($actionId, '/') . ' -->.*?<!-- ACTION_FORM_END_' . preg_quote($actionId, '/') . ' -->/s';
                        if (preg_match($pattern, $msg['content'])) {
                            $msg['content'] = preg_replace($pattern, $replacement, $msg['content']);
                        }
                    }
                }
                session(['ai_chat_history' => $history]);
                return back()->with('success', $successMsg);
            }

        }
        catch (\Exception $e) {
            Log::error('Action Execution Error: ' . $e->getMessage());
            return back()->with('error', 'تعذر تنفيذ الإجراء: ' . $e->getMessage());
        }

        return back();
    }

    public function clear()
    {
        session()->forget('ai_chat_history');
        return redirect()->route('farmer.ai.index');
    }

    private function simpleMarkdown($text)
    {
        $actionForm = '';

        // 1. Robust ACTION extraction
        if (preg_match('/ACTION:\s*(.*)/s', $text, $outerMatches)) {
            $potentialJson = trim($outerMatches[1]);

            // Try to find the JSON block within the remaining text
            // We look for anything that starts with { and ends with }
            if (preg_match('/({.*})/s', $potentialJson, $innerMatches)) {
                $json = $innerMatches[1];

                // Aggressive cleanup: remove common AI artifacts
                $json = preg_replace('/^```(json)?\s*/i', '', $json);
                $json = preg_replace('/```\s*$/', '', $json);
                $json = trim($json);

                $data = json_decode($json, true);

                if ($data && isset($data['action'])) {
                    // Success! Remove the entire ACTION line from text
                    $text = str_replace($outerMatches[0], '', $text);

                    $isCrop = $data['action'] === 'CREATE_CROP';
                    $actionName = $isCrop ? 'CREATE_CROP' : 'CREATE_TASK';
                    $title = $isCrop ? 'إضافة محصول جديد' : 'إضافة مهمة جديدة';
                    $actionId = md5($json . time()); // Unique ID for this specific form instance

                    // Ultra-visible Premium Form
                    $formHtml = '
                    <!-- ACTION_FORM_START_' . $actionId . ' -->
                    <div class="mt-6 -mx-2 sm:-mx-4 p-5 sm:p-7 bg-emerald-50 dark:bg-emerald-950/20 border-y-2 sm:border-2 border-emerald-500 rounded-none sm:rounded-[32px] shadow-xl relative overflow-hidden">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-lg">
                                <i class="bi ' . ($isCrop ? 'bi-tree-fill' : 'bi-calendar2-plus-fill') . ' text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">تأكيد البيانات</h4>
                                <h3 class="text-sm font-black text-slate-800 dark:text-white">' . $title . '</h3>
                            </div>
                        </div>

                        <form action="' . route('farmer.ai.action') . '" method="POST" class="space-y-4">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="action_type" value="' . $actionName . '">
                            <input type="hidden" name="action_id" value="' . $actionId . '">

                            <div class="grid grid-cols-1 gap-4">';

                    if ($isCrop) {
                        $formHtml .= '
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">الاسم</label>
                                    <input type="text" name="name" value="' . ($data['data']['name'] ?? '') . '" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">النوع</label>
                                    <input type="text" name="type" value="' . ($data['data']['type'] ?? '') . '" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">المساحة</label>
                                    <input type="number" step="0.5" name="area" value="' . ($data['data']['area'] ?? 1) . '" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">تاريخ الزراعة</label>
                                    <input type="date" name="planting_date" value="' . ($data['data']['planting_date'] ?? date('Y-m-d')) . '" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">
                                </div>
                            </div>';
                    }
                    else {
                        $formHtml .= '
                            <input type="hidden" name="crop_id" value="' . ($data['data']['crop_id'] ?? '') . '">
                            <div class="grid grid-cols-1 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">المهمة</label>
                                    <input type="text" name="title" value="' . ($data['data']['title'] ?? '') . '" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">التاريخ</label>
                                    <input type="date" name="due_date" value="' . ($data['data']['due_date'] ?? date('Y-m-d')) . '" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">النوع</label>
                                    <select name="type" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">
                                        <option value="water" ' . ($data['data']['type'] == 'water' ? 'selected' : '') . '>ري</option>
                                        <option value="fertilizer" ' . ($data['data']['type'] == 'fertilizer' ? 'selected' : '') . '>تسميد</option>
                                        <option value="pest" ' . ($data['data']['type'] == 'pest' ? 'selected' : '') . '>مكافحة</option>
                                        <option value="harvest" ' . ($data['data']['type'] == 'harvest' ? 'selected' : '') . '>حصاد</option>
                                        <option value="other" ' . ($data['data']['type'] == 'other' ? 'selected' : '') . '>أخرى</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">ملاحظات (اختياري)</label>
                                <textarea name="notes" rows="2" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-black dark:text-white focus:border-emerald-500 outline-none">' . ($data['data']['notes'] ?? '') . '</textarea>
                            </div>';
                    }

                    $formHtml .= '</div>
                            <button type="submit" class="w-full mt-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-black rounded-2xl font-black text-sm shadow-xl shadow-blue-500/30 flex items-center justify-center gap-3 transition-all active:scale-[0.98] group">
                                <i class="bi bi-check-circle-fill text-lg transition-transform group-hover:scale-110"></i>
                                حفظ في النظام الآن
                            </button>
                        </form>
                    </div>
                    <!-- ACTION_FORM_END_' . $actionId . ' -->';
                    $actionForm = $formHtml;
                }
                else {
                    Log::warning('AI JSON Parse Failed: ' . json_last_error_msg() . ' | Content: ' . $json);
                }
            }
        }

        // 2. Escape text but PRESERVE our generated HTML
        $text = htmlspecialchars($text);
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\* (.*?)\n/', '<li>$1</li>', $text);
        $text = str_replace("\n", "<br>", $text);
        $text = str_replace(['&quot;', '&gt;', '&lt;'], ['"', '>', '<'], $text);

        // Safety tip if it looks like AI tried to do an action but failed
        if (str_contains($text, 'ACTION:') && empty($actionForm)) {
            $text .= '<div class="mt-4 p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 rounded-xl text-[10px] font-bold text-amber-700 dark:text-amber-400">⚠️ تعذر تجهيز الطلب تلقائياً، يرجى المحاولة بجملة أكثر وضوحاً.</div>';
        }

        return $text . $actionForm;
    }

    private function handleGeminiVision($request, $systemPrompt)
    {
        $apiKey = config('services.gemini.key');
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
            Log::warning('Gemini Quota Exhausted: ' . $response->body());
            return 'عذراً، تم استهلاك الحصة المجانية لليوم من خدمة الذكاء الاصطناعي. يرجى المحاولة لاحقاً أو غداً.';
        }

        if (!$response->successful()) {
            Log::error('Gemini Vision API Error: ' . $response->body());
            return 'تعذر الحصول على رد من Gemini. الحالة: ' . $response->status();
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'رد فارغ من Gemini Vision';
    }

    private function handleGeminiText($request, $systemPrompt)
    {
        $apiKey = config('services.gemini.key');

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
            Log::warning('Gemini Quota Exhausted: ' . $response->body());
            return 'عذراً، تم استهلاك الحصة المجانية لليوم من خدمة الذكاء الاصطناعي. يرجى المحاولة لاحقاً أو غداً.';
        }

        if (!$response->successful()) {
            Log::error('Gemini Text API Error: ' . $response->body());
            return 'تعذر الحصول على رد من التلقائي. يرجى المحاولة لاحقاً.';
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'رد فارغ من المساعد';
    }
}
