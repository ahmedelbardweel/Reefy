<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;

class CropController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $crops = auth()->user()->crops()->with('tasks')->latest()->paginate(9);
        return view('crops.index', compact('crops'));
    }

    public function create()
    {
        return view('crops.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'area' => 'required|numeric',
            'planting_date' => 'required|date',
            'expected_harvest_date' => 'required|date|after:planting_date',
            'soil_type' => 'nullable|string',
            'irrigation_method' => 'nullable|string',
            'yield_estimate' => 'nullable|numeric',
        ]);

        $crop = auth()->user()->crops()->create([
            'name' => $request->name,
            'type' => $request->type,
            'area' => $request->area,
            'soil_type' => $request->soil_type,
            'irrigation_method' => $request->irrigation_method,
            'seed_source' => $request->seed_source,
            'yield_estimate' => $request->yield_estimate,
            'planting_date' => $request->planting_date,
            'expected_harvest_date' => $request->expected_harvest_date,
            'notes' => $request->notes,
        ]);

        // Handle Multiple Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imagePath = $image->store('crops/images', 'public');
                    $crop->images()->create([
                        'image_path' => $imagePath,
                    ]);
                }
            }
        }

        // Smart Logic: Auto-generate tasks
        $crop->tasks()->create([
            'title' => 'Initial Irrigation (الرية الأولى)',
            'type' => 'water',
            'due_date' => $crop->planting_date->addDays(1),
            'status' => 'pending',
        ]);

        $crop->tasks()->create([
            'title' => 'Fertilizer Application (تسميد)',
            'type' => 'fertilizer',
            'due_date' => $crop->planting_date->addDays(14),
            'status' => 'pending',
        ]);

        return redirect()->route('crops.index')->with('success', 'Crop added and smart tasks generated!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);
        return view('crops.edit', compact('crop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'area' => 'required|numeric',
            'planting_date' => 'required|date',
            'expected_harvest_date' => 'required|date|after:planting_date',
            'soil_type' => 'nullable|string',
            'irrigation_method' => 'nullable|string',
            'yield_estimate' => 'nullable|numeric',
            'seed_source' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $crop->update($validated);

        // Handle Additional Multiple Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imagePath = $image->store('crops/images', 'public');
                    $crop->images()->create([
                        'image_path' => $imagePath,
                    ]);
                }
            }
        }

        return redirect()->route('crops.index')->with('success', 'Crop details updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);
        $crop->delete();
        return redirect()->route('crops.index')->with('success', 'Crop removed.');
    }

    /**
     * Store a new task for a crop manually.
     */
    public function storeTask(Request $request, Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:water,fertilizer,pest,harvest,other',
            'due_date' => 'required|date',
            'reminder_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,completed',
            // Detailed fields
            'water_amount' => 'nullable|numeric',
            'duration_minutes' => 'nullable|integer',
            'material_name' => 'nullable|string|max:255',
            'dosage' => 'nullable|numeric',
            'dosage_unit' => 'nullable|string|max:50',
            'harvest_quantity' => 'nullable|numeric',
            'harvest_unit' => 'nullable|string|max:50',
            'system_notes' => 'nullable|string',
        ]);

        // Auto-extract reminder_time from due_date if not provided
        if (!$request->filled('reminder_time')) {
            $validated['reminder_time'] = \Carbon\Carbon::parse($validated['due_date'])->format('H:i');
        }

        $task = $crop->tasks()->create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'due_date' => $validated['due_date'],
            'reminder_time' => $validated['reminder_time'],
            'status' => $validated['status'] ?? 'pending',
            'notes' => $validated['notes'] ?? null,
            'water_amount' => $validated['water_amount'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'material_name' => $validated['material_name'] ?? null,
            'dosage' => $validated['dosage'] ?? null,
            'dosage_unit' => $validated['dosage_unit'] ?? null,
            'harvest_quantity' => $validated['harvest_quantity'] ?? null,
            'harvest_unit' => $validated['harvest_unit'] ?? null,
            'system_notes' => $validated['system_notes'] ?? null,
        ]);

        // Create instant notification if task is due today/soon
        $dueDate = \Carbon\Carbon::parse($validated['due_date']);
        $now = \Carbon\Carbon::now();
        
        if ($dueDate->isToday() || $dueDate->isTomorrow()) {
            \App\Models\Notification::create([
                'user_id' => auth()->id(),
                'task_id' => $task->id,
                'title' => '✅ تمت إضافة مهمة جديدة',
                'message' => "تم جدولة المهمة '{$task->title}' للمحصول '{$crop->name}' - التذكير {$dueDate->format('Y-m-d')} الساعة {$task->reminder_time}.",
                'type' => 'task_due',
            ]);
        }

        // AUTO-UPDATE CROP STATUS ON HARVEST
        if ($validated['type'] === 'harvest') {
            $crop->update(['status' => 'harvested']);
        }

        return back()->with('success', $validated['type'] === 'harvest' 
            ? 'تم تسجيل الحصاد وتحديث حالة المحصول بنجاح!' 
            : 'New task added successfully!');
    }

    /**
     * Mark a task as complete.
     */
    public function completeTask(Request $request, $taskId)
    {
        $task = \App\Models\Task::findOrFail($taskId);
        
        // Ownership check (via crop)
        if ($task->crop->user_id !== auth()->id()) abort(403);

        $task->update(['status' => 'completed']);

        // INTERACTIVE GROWTH: Increment growth percentage on completion of key tasks
        if (in_array($task->type, ['water', 'fertilizer', 'pest'])) {
            $task->crop->increment('growth_percentage', 5);
            // Cap at 100
            if ($task->crop->growth_percentage > 100) {
                $task->crop->update(['growth_percentage' => 100]);
            }
        }

        return back()->with('success', 'تم إتمام المهمة! زادت نسبة نمو المحصول بفضل اهتمامك.');
    }

    /**
     * Update crop growth percentage manually.
     */
    public function updateGrowth(Request $request, Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'growth_percentage' => 'required|integer|min:0|max:100',
        ]);

        $data = ['growth_percentage' => $validated['growth_percentage']];

        // Auto-update status based on growth
        if ($validated['growth_percentage'] == 100) {
            $data['status'] = 'harvested';
        } elseif ($validated['growth_percentage'] > 0) {
            $data['status'] = 'growing';
        }

        $crop->update($data);

        return back()->with('success', 'تم تحديث مرحلة النمو والحالة بنجاح!');
    }
}
