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

        $history[] = [
            'role' => 'user',
            'content' => $request->message,
            'image_url' => $imageUrl,
            'time' => now()->format('H:i'),
        ];

        try {
            $prompt = $this->buildPrompt();

           $aiResponse = $this->callAI($request, $prompt);

            $formatted = $this->simpleMarkdown($aiResponse);

            $aiMsg = [
                'role' => 'ai',
                'content' => $formatted,
                'time' => now()->format('H:i'),
            ];

            $history[] = $aiMsg;
            session(['ai_chat_history' => $history]);

            return response()->json([
                'success' => true,
                'message' => $aiMsg
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'خطأ في النظام'], 500);
        }

    }

    public function executeAction(Request $request)
    {
        try {
            if ($request->action_type === 'CREATE_CROP') {
                $data = $request->validate([
                    'name' => 'required',
                    'type' => 'required',
                    'area' => 'required|numeric',
                    'planting_date' => 'required|date',
                ]);

                auth()->user()->crops()->create($data);
            }

            if ($request->action_type === 'CREATE_TASK') {
                $data = $request->validate([
                    'crop_id' => 'required|exists:crops,id',
                    'title' => 'required',
                    'type' => 'required',
                    'due_date' => 'required|date',
                    'notes' => 'nullable',
                ]);

                Task::create($data);
            }

            return back()->with('success', 'تم التنفيذ بنجاح ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل التنفيذ');
        }
    }

    public function clear()
    {
        session()->forget('ai_chat_history');
        return back();
    }

    private function buildPrompt()
    {
        $crops = auth()->user()->crops()
            ->get()
            ->map(fn($c) => "ID: {$c->id}, Name: {$c->name}")
            ->implode(' | ');

        return "أنت خبير زراعي. محاصيل المستخدم: [{$crops}].

        - إذا صورة: حلل المرض والحل
        - إذا طلب إضافة محصول:
        ACTION:{\"action\":\"CREATE_CROP\",\"data\":{\"name\":\"...\",\"type\":\"...\",\"area\":1,\"planting_date\":\"YYYY-MM-DD\"}}

        - إذا طلب مهمة:
        ACTION:{\"action\":\"CREATE_TASK\",\"data\":{\"crop_id\":ID,\"title\":\"...\",\"type\":\"water\",\"due_date\":\"YYYY-MM-DD\"}}";
    }

    private function simpleMarkdown($text)
    {
        $form = '';

        if (preg_match('/ACTION:\s*(\{.*\})/s', $text, $match)) {
            $data = json_decode($match[1], true);

            if ($data && isset($data['action'])) {
                $text = str_replace($match[0], '', $text);

                $form = '
                <form method="POST" action="' . route('farmer.ai.action') . '" class="mt-4 p-4 border rounded-xl">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="action_type" value="' . $data['action'] . '">

                    <button class="bg-green-600 text-white px-4 py-2 rounded">
                        تنفيذ العملية
                    </button>
                </form>';
            }
        }

        return nl2br(e($text)) . $form;
    }

private function callAI($request, $prompt)
{
    // 🟢 أول محاولة: Gemini
    try {
        $gemini = $this->callGeminiOnly($request, $prompt);

        if ($gemini && !str_contains($gemini, 'ERROR')) {
            return $gemini;
        }
    } catch (\Exception $e) {
        // تجاهل
    }

    // 🔥 fallback → OpenAI
    return $this->callOpenAI($request, $prompt);
}

private function callGeminiOnly($request, $prompt)
{
    $apiKey = config('services.gemini.key');

    $parts = [
        ['text' => $prompt . "\nUser: " . ($request->message ?? '')]
    ];

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $parts[] = [
            'inline_data' => [
                'mime_type' => $image->getMimeType(),
                'data' => base64_encode(file_get_contents($image->path()))
            ]
        ];
    }

    $res = Http::post(
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}",
        ['contents' => [['role' => 'user', 'parts' => $parts]]]
    );

    if ($res->status() == 429) {
        return 'ERROR: quota';
    }

    if (!$res->successful()) {
        return 'ERROR: failed';
    }

    return $res->json('candidates.0.content.parts.0.text');
}

private function callOpenAI($request, $prompt)
{
    $apiKey = config('services.openai.key');

    $message = $prompt . "\nUser: " . ($request->message ?? '');

    $res = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'user', 'content' => $message]
        ]
    ]);

    if (!$res->successful()) {
        return '❌ فشل الاتصال بكل خدمات الذكاء الاصطناعي';
    }

    return $res->json('choices.0.message.content');
}
}
