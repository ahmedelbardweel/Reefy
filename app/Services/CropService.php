<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Crop;
use Exception;

class CropService
{
    /**
     * Mark a specific task as done.
     * Triggered by: "تم الري" or similar UI actions.
     * 
     * @param int $taskId
     * @return Task
     * @throws Exception
     */
    public function markTaskAsDone(int $taskId): Task
    {
        $task = Task::findOrFail($taskId);
        
        $task->update([
            'status' => 'completed',
        ]);

        // Logic expansion: If it's a watering task, maybe update some crop stats?
        // For now, just marking it as done as requested.
        
        return $task;
    }

    /**
     * Get tasks for a specific crop.
     */
    public function getCropTasks(int $cropId)
    {
        return Task::where('crop_id', $cropId)->orderBy('due_date', 'asc')->get();
    }
}
