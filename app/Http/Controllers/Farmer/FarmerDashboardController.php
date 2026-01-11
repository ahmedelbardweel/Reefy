<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\Task;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FarmerDashboardController extends Controller
{
    protected $weatherService;

    public function __construct(\App\Services\WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        $user = auth()->user();

        $city = optional($user->farmerProfile)->city ?? 'Gaza';
        $weatherData = $this->weatherService->getWeather($city);
        
        // Stats Overview
        $activeCropsCount = $user->crops()->where('status', 'active')->count();
        $pendingTasksCount = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('status', 'pending')
            ->count();

        // Specialized System Stats
        $pendingIrrigation = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'water')
            ->where('status', 'pending')
            ->count();
            
        $pendingTreatments = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->whereIn('type', ['fertilizer', 'pest'])
            ->where('status', 'pending')
            ->count();

        // Recently Harvested (Last 30 days)
        $recentHarvestCount = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'harvest')
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // Weekly Water Consumption (Total liters)
        $cropIds = $user->crops->pluck('id');
        $weeklyWater = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'water')
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::now()->startOfWeek(Carbon::SUNDAY))
            ->sum('water_amount');

        // Multi-Day Patterns for Charts (Rolling 7 days for realism)
        $weeklyWaterData = [];
        $weeklyFertData = [];
        $chartLabels = [];
        
        $arabicDays = [
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت'
        ];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dayName = $day->format('l');
            $chartLabels[] = $arabicDays[$dayName] ?? $dayName;
            
            $waterSum = Task::whereIn('crop_id', $cropIds)
                ->where('type', 'water')
                ->where('status', 'completed')
                ->whereDate('updated_at', $day)
                ->sum('water_amount');
                
            $fertSum = Task::whereIn('crop_id', $cropIds)
                ->where('type', 'fertilizer')
                ->where('status', 'completed')
                ->whereDate('updated_at', $day)
                ->sum('dosage');

            $weeklyWaterData[] = (int)$waterSum;
            $weeklyFertData[] = (int)$fertSum;
        }

        // Get crops with their tasks
        $crops = $user->crops()->with(['tasks' => function($q) {
            $q->where('status', 'pending')->orderBy('due_date', 'asc');
        }])->latest()->get();

        return view('farmer.dashboard', compact(
            'activeCropsCount', 
            'pendingTasksCount', 
            'pendingIrrigation', 
            'pendingTreatments',
            'recentHarvestCount',
            'weeklyWater',
            'weeklyWaterData',
            'weeklyFertData',
            'chartLabels',
            'crops',
            'weatherData'
        ));
    }
}
