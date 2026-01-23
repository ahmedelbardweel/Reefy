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

        // إحصائيات عامة الحقيقية
        $activeCropsCount = $user->crops()->whereIn('status', ['active', 'growing'])->count();
        $allCrops = $user->crops;
        $cropIds = $allCrops->pluck('id');
        
        $pendingTasksCount = \App\Models\Task::whereIn('crop_id', $cropIds)
            ->where('status', 'pending')
            ->count();

        // إحصائيات الموارد (آخر 7 أيام)
        $last7Days = now()->subDays(7);
        $waterUsageWeekly = (float)\App\Models\Task::whereIn('crop_id', $cropIds)
            ->where('type', 'water')
            ->where('created_at', '>=', $last7Days)
            ->sum('water_amount') ?: 0;

        $harvestActivityCount = (int)\App\Models\Task::whereIn('crop_id', $cropIds)
            ->where('type', 'harvest')
            ->where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        // بيانات الطقس الحقيقية (Real weather data)
        $weatherService = new \App\Services\WeatherService();
        $city = optional($user->farmerProfile)->city ?? 'Gaza';
        $weatherDetails = $weatherService->getWeather($city);
        
        $weatherData = [
            'city' => $weatherDetails['city'] ?? $city,
            'temp' => (int)($weatherDetails['temp'] ?? 22),
            'condition' => $weatherDetails['condition'] ?? 'مشمس',
            'humidity' => (int)($weatherDetails['humidity'] ?? 50),
            'wind_speed' => (int)($weatherDetails['wind_speed'] ?? 10),
            'uv_index' => (int)5,
            'soil_moisture' => (int)15,
            'unit' => '°C'
        ];

        // بيانات المخططات (Real data logic with high-fidelity demo fallback)
        $mCrops = $allCrops->whereBetween('growth_percentage', [71, 100])->count();
        $fCrops = $allCrops->whereBetween('growth_percentage', [41, 70])->count();
        $vCrops = $allCrops->whereBetween('growth_percentage', [0, 40])->count();

        if ($allCrops->count() == 0 || ($mCrops + $fCrops + $vCrops) == 0) {
            $lifecycle = [
                ['label' => 'إنتاج ناضج', 'value' => 45, 'color' => '#1B5E20'],
                ['label' => 'مرحلة التزهير', 'value' => 30, 'color' => '#4CAF50'],
                ['label' => 'نمو خضري', 'value' => 25, 'color' => '#81C784'],
            ];
        } else {
            $lifecycle = [
                ['label' => 'إنتاج ناضج', 'value' => (float)$mCrops, 'color' => '#1B5E20'],
                ['label' => 'مرحلة التزهير', 'value' => (float)$fCrops, 'color' => '#4CAF50'],
                ['label' => 'نمو خضري', 'value' => (float)$vCrops, 'color' => '#81C784'],
            ];
        }

        // مخطط تدفق الموارد
        $labels = [];
        $waterDataPoints = [];
        $fertDataPoints = [];
        $hasData = false;
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            
            $waterDataPoints[] = (float)\App\Models\Task::whereIn('crop_id', $cropIds)
                ->where('type', 'water')
                ->whereDate('created_at', $date->toDateString())
                ->sum('water_amount') ?: 0.0;
            
            $fertDataPoints[] = (float)\App\Models\Task::whereIn('crop_id', $cropIds)
                ->whereIn('type', ['fertilizer', 'pest', 'treatment', 'activity'])
                ->whereDate('created_at', $date->toDateString())
                ->count() ?: 0.0;
        }

        $charts = [
            'lifecycle' => $lifecycle,
            'resources' => [
                'labels' => $labels,
                'water' => $waterDataPoints,
                'fertilizer' => $fertDataPoints
            ]
        ];

        // جلب نصيحة الخبراء
        $tip = \App\Models\ExpertTip::with('user:id,name')->latest()->first();

        // سجل العمليات
        $recentLogs = \App\Models\Task::whereIn('crop_id', $cropIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($task) {
                return [
                    'id' => (int)$task->id,
                    'title' => $task->title,
                    'time_ago' => $task->created_at->diffForHumans(),
                    'status' => $task->status,
                    'type' => $task->type
                ];
            });

        $data = [
            'user_name' => $user->name,
            'stats' => [
                'active_crops' => (int)$activeCropsCount,
                'pending_tasks' => (int)$pendingTasksCount,
                'water_usage' => (float)$waterUsageWeekly,
                'harvest_activity' => (int)$harvestActivityCount,
            ],
            'weather' => $weatherData,
            'tip' => $tip ? [
                'title' => 'توجيهات الخبراء',
                'content' => $tip->content,
                'author' => $tip->user->name
            ] : [
                'title' => 'توجيهات الخبراء',
                'content' => 'يرجى مراجعة حالة الري للمحاصيل اليوم.',
                'author' => 'النظام'
            ],
            'charts' => $charts,
            'recent_logs' => $recentLogs
        ];

        return $this->successResponse($data, 'Dashboard data retrieved successfully.');
    }
}
