<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends ApiController
{
    /**
     * Display a listing of consultations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'farmer') {
            $consultations = $user->consultations()->with(['expert', 'crop'])->latest()->get();
        } elseif ($user->role === 'expert') {
            // Experts see pending consultations or ones they answered
            $consultations = Consultation::where('status', 'pending')
                ->orWhere('expert_id', $user->id)
                ->with(['farmer', 'crop'])
                ->latest()
                ->get();
        } else {
            return $this->errorResponse('Unauthorized role.', [], 403);
        }

        return $this->successResponse($consultations, 'Consultations retrieved successfully.');
    }

    /**
     * Store a newly created consultation (Farmer only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'farmer') {
            return $this->errorResponse('Only farmers can create consultations.', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'question' => 'required|string',
            'crop_id' => 'nullable|exists:crops,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['farmer_id'] = auth()->id();
        $input['status'] = 'pending';

        $consultation = Consultation::create($input);

        return $this->successResponse($consultation, 'Consultation request created successfully.');
    }

    /**
     * Display the specified consultation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consultation = Consultation::with(['farmer', 'expert', 'crop'])->find($id);

        if (is_null($consultation)) {
            return $this->errorResponse('Consultation not found.');
        }

        // Access Control
        $user = auth()->user();
        if ($user->role === 'farmer' && $consultation->farmer_id !== $user->id) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }
        // Experts can view any consultation to answer it (implied) or only pending/assigned
        
        return $this->successResponse($consultation, 'Consultation retrieved successfully.');
    }

    /**
     * Reply to a consultation (Expert only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, $id)
    {
        if (auth()->user()->role !== 'expert') {
            return $this->errorResponse('Only experts can reply.', [], 403);
        }

        $consultation = Consultation::find($id);

        if (is_null($consultation)) {
            return $this->errorResponse('Consultation not found.');
        }

        $validator = Validator::make($request->all(), [
            'response' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $consultation->response = $request->response;
        $consultation->expert_id = auth()->id();
        $consultation->status = 'answered';
        $consultation->save();

        // Should notify farmer here (omitted for brevity)

        return $this->successResponse($consultation, 'Reply posted successfully.');
    }
}
