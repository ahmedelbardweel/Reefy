<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Crop;
use App\Models\Notification;

class ConsultationController extends Controller
{
    /**
     * Display list of consultations for the farmer.
     */
    public function index()
    {
        $consultations = auth()->user()->consultations()->latest()->get();
        return view('consultations.index', compact('consultations'));
    }

    /**
     * Show form to create a new consultation.
     */
    public function create()
    {
        $crops = auth()->user()->crops;
        return view('consultations.create', compact('crops'));
    }

    /**
     * Store a new consultation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'crop_id' => 'nullable|exists:crops,id',
            'category' => 'required|string',
        ]);

        Consultation::create([
            'user_id' => auth()->id(),
            'crop_id' => $request->crop_id,
            'subject' => $request->subject,
            'question' => $request->question,
            'category' => $request->category,
            'status' => 'pending',
        ]);

        return redirect()->route('consultations.index')->with('success', 'تم إرسال استشارتك بنجاح! سيقوم خبير بالرد عليك قريباً.');
    }

    /**
     * Display specified consultation.
     */
    public function show(Consultation $consultation)
    {
        if ($consultation->user_id !== auth()->id() && auth()->user()->role !== 'expert') {
            abort(403);
        }
        return view('consultations.show', compact('consultation'));
    }

    /**
     * Expert Dashboard: List pending consultations.
     */
    public function expertIndex()
    {
        $consultations = Consultation::where('status', 'pending')->latest()->get();
        return view('expert.consultations.index', compact('consultations'));
    }

    /**
     * Expert: Answer a consultation.
     */
    public function answer(Request $request, Consultation $consultation)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        $consultation->update([
            'expert_id' => auth()->id(),
            'response' => $request->response,
            'status' => 'answered',
        ]);

        // Notify Farmer
        Notification::create([
            'user_id' => $consultation->user_id,
            'title' => 'تم الرد على استشارتك! 🎓',
            'message' => "قام الخبير بالإجابة على استشارتك: {$consultation->subject}",
            'type' => 'advice',
        ]);

        return redirect()->back()->with('success', 'تم إرسال إجابتك بنجاح. شكراً لمساهمتك!');
    }
}
