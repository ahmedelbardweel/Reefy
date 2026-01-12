<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

/**
 * كونترولر لوحة تحكم المزارع API - Farmer Dashboard API Controller
 * 
 * هذا الكونترولر يوفر بيانات الإحصائيات والطقس للوحة تحكم المزارع في التطبيق
 */
class FarmerDashboardController extends ApiController
{
    /**
     * عرض بيانات لوحة التحكم (Home Screen)
     * 
     * تقوم هذه الدالة بـ:
     * - جلب إحصائيات سريعة (عدد المحاصيل، المهام المعلقة)
     * - جلب ملخص للأنظمة (مهام الري العالقة، مهام التسميد العالقة)
     * - جلب حالة الطقس (بيانات وهمية حالياً أو متصلة بـ WeatherService)
     * - جلب آخر النصائح من الخبراء
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'farmer') {
            return $this->errorResponse('Unauthorized. Only farmers allowed.', [], 403);
        }

        // إحصائيات عامة
        $activeCropsCount = $user->crops()->where('status', 'active')->count();
        
        $pendingTasksCount = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('status', 'pending')
            ->count();

        // إحصائيات الأنظمة
        $pendingIrrigation = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'water')
            ->where('status', 'pending')
            ->count();
            
        $pendingTreatments = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->whereIn('type', ['fertilizer', 'pest'])
            ->where('status', 'pending')
            ->count();

        // بيانات الطقس (محاكاة)
        $weatherData = [
            'city' => optional($user->farmerProfile)->city ?? 'Gaza',
            'temp' => 25,
            'condition' => 'Sunny',
            'humidity' => 60,
            'wind_speed' => 15
        ];

        // آخر النصائح
        $recentTips = \App\Models\ExpertTip::with('user:id,name')->latest()->take(3)->get();

        $data = [
            'stats' => [
                'active_crops' => $activeCropsCount,
                'pending_tasks' => $pendingTasksCount,
                'pending_irrigation' => $pendingIrrigation,
                'pending_treatments' => $pendingTreatments,
            ],
            'weather' => $weatherData,
            'tips' => $recentTips,
            'user_name' => $user->name,
        ];

        return $this->successResponse($data, 'Dashboard data retrieved successfully.');
    }
}
