<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Task;

/**
 * كونترولر أنظمة المزارع API - Farmer System API Controller
 * 
 * يدير هذا الكونترولر واجهات الأنظمة المتخصصة (الري، المعالجة، الحصاد)
 */
class FarmerSystemController extends ApiController
{
    /**
     * جلب مهام نظام الري
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function irrigation()
    {
        $user = auth()->user();
        
        $tasks = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'water')
            ->with(['crop:id,name,image_path'])
            ->latest()
            ->paginate(10);

        $totalWater = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'water')
            ->where('status', 'completed')
            ->sum('water_amount');

        return $this->successResponse([
            'tasks' => $tasks,
            'total_water_liter' => $totalWater
        ], 'Irrigation data retrieved.');
    }

    /**
     * جلب مهام نظام المعالجة (تسميد + آفات)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function treatment()
    {
        $user = auth()->user();
        
        $tasks = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->whereIn('type', ['fertilizer', 'pest'])
            ->with(['crop:id,name,image_path'])
            ->latest()
            ->paginate(10);

        return $this->successResponse([
            'tasks' => $tasks
        ], 'Treatment data retrieved.');
    }

    /**
     * جلب مهام نظام الحصاد
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function harvesting()
    {
        $user = auth()->user();
        
        $tasks = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'harvest')
            ->with(['crop:id,name,image_path'])
            ->latest()
            ->paginate(10);

        $totalYield = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'harvest')
            ->where('status', 'completed')
            ->sum('harvest_quantity');

        return $this->successResponse([
            'tasks' => $tasks,
            'total_yield' => $totalYield
        ], 'Harvesting data retrieved.');
    }
}
