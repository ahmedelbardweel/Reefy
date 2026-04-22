<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Task;

class AiAssistantController extends Controller
{
    public function index()
    {
        return view('farmer.ai.index', [
            'messages' => session('ai_chat_history', []),
            'crops' => auth()->user()->crops()->select('id', 'name')->get()
        ]);
    }

public function chatApi(Request $request)
{
    $request->validate([
        'image' => 'nullable|image|max:10240',
        'message' => 'nullable|string|max:2000',
    ]);

    if (!$request->hasFile('image') && !$request->filled('message')) {
        return response()->json(['error' => 'يرجى إدخال رسالة أو صورة'], 422);
    }

    $history = session('ai_chat_history', []);
    $imageUrl = null;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('ai_uploads', 'public');
        $imageUrl = asset('storage/' . $path);
    }

    try {
        $prompt = $this->buildPrompt();

        // 1. الاتصال بالذكاء الاصطناعي أولاً قبل حفظ رسالة المستخدم في الجلسة
        $aiRawResponse = $this->callAI($request, $prompt, $history);

        // 2. الآن نقوم بحفظ رسالة المستخدم في التاريخ (History)
        $history[] = [
            'role' => 'user',
            'content' => $request->message ?? 'صورة مرفقة',
            'image_url' => $imageUrl,
            'time' => now()->format('H:i'),
        ];

        // 3. معالجة وتنسيق رد الذكاء الاصطناعي
        $formattedData = $this->processAIResponse($aiRawResponse);

        $aiMsg = [
            'role' => 'ai',
            'content' => $formattedData['text'],
            'action_form' => $formattedData['form'],
            'time' => now()->format('H:i'),
        ];

        // 4. حفظ رد الـ AI في التاريخ
        $history[] = $aiMsg;
        session(['ai_chat_history' => $history]);

        return response()->json([
            'success' => true,
            'message' => $aiMsg
        ]);

    } catch (\Exception $e) {
        // طباعة الخطأ الفعلي في ملف الـ Log لكي نعرف المشكلة لو تكررت
        \Log::error('AI Error: ' . $e->getMessage());
        return response()->json(['error' => 'خطأ: ' . $e->getMessage()], 500);
    }
}

private function callAI($request, $prompt, $history)
{
    $apiKey = config('services.gemini.key');

    $contents = [];

    // ترتيب المحادثات السابقة بشكل صحيح
    foreach ($history as $msg) {
        $role = ($msg['role'] === 'ai') ? 'model' : 'user';
        $text = trim(strip_tags($msg['content']));
        $contents[] = [
            'role' => $role,
            'parts' => [['text' => !empty($text) ? $text : 'صورة مرفقة']]
        ];
    }

    // تجهيز الرسالة الحالية
    $currentText = $request->message ?? 'الرجاء تحليل هذه الصورة الزراعية';
    $currentParts = [['text' => $currentText]];

    if ($request->hasFile('image')) {
        $currentParts[] = [
            'inline_data' => [
                'mime_type' => $request->file('image')->getMimeType(),
                'data' => base64_encode(file_get_contents($request->file('image')->path()))
            ]
        ];
    }

    $contents[] = [
        'role' => 'user',
        'parts' => $currentParts
    ];

    // إرسال الطلب مع إيقاف فلاتر الأمان التي قد تحجب الردود
    $payload = [
        'system_instruction' => [
            'parts' => [['text' => $prompt]]
        ],
        'contents' => $contents,
        'safetySettings' => [
            ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
            ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
            ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
            ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE']
        ]
    ];

    $res = Http::withHeaders(['Content-Type' => 'application/json'])
        ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", $payload);

    // 1. التحقق من أخطاء الاتصال (مثل خطأ 400 أو 404)
    if (!$res->successful()) {
        throw new \Exception('API Google Error: ' . $res->body());
    }

    // 2. محاولة استخراج النص من الرد
    $text = $res->json('candidates.0.content.parts.0.text');

    // 3. كشف سبب إخفاء النص (الآن سيظهر لك السبب الحقيقي في نافذة Alert بدلاً من الصمت)
    if (empty($text)) {
        throw new \Exception('الطلب نجح، لكن جوجل رفضت إعطاء نص! الرد الكامل: ' . $res->body());
    }

    return $text;
}

        private function buildPrompt()
    {
        $crops = auth()->user()->crops()->get()->map(fn($c) => "ID:{$c->id}, Name:{$c->name}")->implode('|');
        return "أنت خبير زراعي ذكي. محاصيل المستخدم الحالية: [{$crops}].
        أجب باختصار. إذا طلب إضافة محصول أو مهمة، ألحق الرد بـ ACTION: كالتالي:
        ACTION:{\"action\":\"CREATE_CROP\",\"data\":{\"name\":\"..\",\"type\":\"..\",\"area\":1,\"planting_date\":\"YYYY-MM-DD\"}}";
    }
    private function processAIResponse($text)
    {
        $form = '';
        if (preg_match('/ACTION:\s*(\{.*\})/s', $text, $match)) {
            $jsonData = json_decode($match[1], true);
            if ($jsonData) {
                $text = str_replace($match[0], '', $text);
                $form = view('components.ai-action-form', ['data' => $jsonData])->render();
            }
        }
        return ['text' => nl2br(e(trim($text))), 'form' => $form];
    }
    public function clear() { session()->forget('ai_chat_history'); return back(); }

    public function executeAction(Request $request)
    {
        $actionType = $request->input('action_type');
        $data = $request->input('data');

        if ($actionType === 'CREATE_CROP') {
            // إضافة المحصول لقاعدة البيانات وربطه بالمستخدم المسجل دخول حالياً
            auth()->user()->crops()->create([
                'name'          => $data['name'],
                'type'          => $data['type'] ?? 'general',
                'area'          => $data['area'] ?? 0,
                'planting_date' => $data['planting_date'] ?? now(),
            ]);

            return back()->with('success', 'تم إضافة محصول ' . $data['name'] . ' بنجاح!');
        }

        // يمكنك إضافة حالات أخرى هنا مثل CREATE_TASK
        return back()->with('error', 'إجراء غير معروف');
    }
}
