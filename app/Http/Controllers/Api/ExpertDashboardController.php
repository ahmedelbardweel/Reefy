<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Consultation;

/**
 * كونترولر لوحة تحكم الخبير API - Expert Dashboard API Controller
 */
class ExpertDashboardController extends ApiController
{
    /**
     * عرض بيانات لوحة تحكم الخبير
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'expert') {
            return $this->errorResponse('Unauthorized. Only experts allowed.', [], 403);
        }

        // إحصائيات
        $pendingCount = Consultation::where('status', 'pending')->count();
        $answeredCount = $user->expertAdvice()->count();
        
        // استشارات معلقة حديثة
        $recentConsultations = Consultation::where('status', 'pending')
            ->with(['user', 'crop'])
            ->latest()
            ->take(5)
            ->get();
        
        // نصائحي
        $myTips = $user->expertTips()->latest()->take(5)->get();

        $data = [
            'stats' => [
                'pending_consultations' => $pendingCount,
                'answered_consultations' => $answeredCount,
            ],
            'recent_requests' => $recentConsultations,
            'my_tips' => $myTips,
            'user_name' => $user->name,
        ];

        return $this->successResponse($data, 'Expert dashboard data retrieved.');
    }
}
