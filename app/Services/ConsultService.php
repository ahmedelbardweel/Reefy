<?php

namespace App\Services;

use App\Models\Consultation;
use Exception;

class ConsultService
{
    /**
     * Submit a reply from an expert to a user's consultation.
     * Triggered by: "إرسال رد خبير"
     * 
     * @param int $consultationId
     * @param array $data ['response']
     * @return Consultation
     * @throws Exception
     */
    public function submitExpertReply(int $consultationId, array $data): Consultation
    {
        $consultation = Consultation::findOrFail($consultationId);
        
        $consultation->update([
            'response' => $data['response'],
            'status' => 'answered', // Assuming 'answered' is the status for replied consultations
            'expert_id' => $data['expert_id'] ?? auth()->id(),
        ]);

        // Logic expansion: Send notification to the user who asked the question
        // This can be integrated with FcmService later.

        return $consultation;
    }

    /**
     * Get all pending consultations for an expert.
     */
    public function getPendingConsultations(int $expertId)
    {
        return Consultation::where('expert_id', $expertId)
            ->where('status', 'pending')
            ->get();
    }
}
