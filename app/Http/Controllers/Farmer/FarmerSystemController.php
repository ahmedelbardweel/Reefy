<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class FarmerSystemController extends Controller
{
    public function irrigation()
    {
        $user = auth()->user();
        $cropIds = $user->crops->pluck('id');

        $tasks = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'water')
            ->with('crop')
            ->latest()
            ->paginate(10);

        $totalWater = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'water')
            ->where('status', 'completed')
            ->sum('water_amount');

        return view('farmer.systems.irrigation', compact('tasks', 'totalWater'));
    }

    public function treatment()
    {
        $user = auth()->user();
        $cropIds = $user->crops->pluck('id');

        $tasks = Task::whereIn('crop_id', $cropIds)
            ->whereIn('type', ['fertilizer', 'pest'])
            ->with('crop')
            ->latest()
            ->paginate(10);

        return view('farmer.systems.treatment', compact('tasks'));
    }

    public function harvesting()
    {
        $user = auth()->user();
        $cropIds = $user->crops->pluck('id');

        $tasks = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'harvest')
            ->with('crop')
            ->latest()
            ->paginate(10);

        $totalYield = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'harvest')
            ->where('status', 'completed')
            ->sum('harvest_quantity');

        return view('farmer.systems.harvesting', compact('tasks', 'totalYield'));
    }
}
