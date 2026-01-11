<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Crop;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TaskController extends ApiController
{
    /**
     * Display a listing of tasks for a specific crop.
     *
     * @param  int  $cropId
     * @return \Illuminate\Http\Response
     */
    public function index($cropId)
    {
        $crop = Crop::find($cropId);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $tasks = $crop->tasks()->latest()->get();

        return $this->successResponse($tasks, 'Tasks retrieved successfully.');
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $cropId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $cropId)
    {
        $crop = Crop::find($cropId);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:water,fertilizer,pest,harvest,general',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            // Specialized fields validation
            'water_amount' => 'nullable|numeric',
            'duration' => 'nullable|numeric',
            'material_name' => 'nullable|string',
            'dosage' => 'nullable|numeric',
            'dosage_unit' => 'nullable|string',
            'harvest_quantity' => 'nullable|numeric',
            'harvest_unit' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['crop_id'] = $cropId;
        $input['status'] = 'pending';

        $task = Task::create($input);

        return $this->successResponse($task, 'Task created successfully.');
    }

    /**
     * Mark task as complete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $taskId
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        if (is_null($task)) {
            return $this->errorResponse('Task not found.');
        }

        // Check ownership through crop relationship
        if ($task->crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $task->status = 'completed';
        $task->save();

        // Update crop growth if logic exists (simplified here for API)
        
        // If harvest task, update crop status
        if ($task->type === 'harvest') {
            $task->crop->status = 'harvested';
            $task->crop->save();
        }

        return $this->successResponse($task, 'Task marked as completed.');
    }
}
