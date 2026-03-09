<?php

namespace App\Http\Controllers;

use App\Models\CropImage;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CropController extends Controller
{
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
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string',
            'area' => 'nullable|numeric',
            'planting_date' => 'nullable|date',
            'expected_harvest_date' => 'nullable|date',
            'soil_type' => 'nullable|string',
            'irrigation_method' => 'nullable|string',
            'yield_estimate' => 'nullable|numeric',
        ]);

        $crop = auth()->user()->crops()->create([
            'name' => $request->name ?: 'محصول جديد ' . (auth()->user()->crops()->count() + 1),
            'type' => $request->type ?: 'غير محدد',
            'area' => $request->area ?: 1,
            'soil_type' => $request->soil_type,
            'irrigation_method' => $request->irrigation_method,
            'seed_source' => $request->seed_source,
            'yield_estimate' => $request->yield_estimate,
            'planting_date' => $request->planting_date ?: now(),
            'expected_harvest_date' => $request->expected_harvest_date,
            'notes' => $request->notes,
            'growth_percentage' => 0,
            'status' => 'active',
            'growth_stage' => 'seedling',
            'health_status' => 'good',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imagePath = $image->store('crops/images', 'public');
                    $crop->images()->create(['image_path' => $imagePath]);
                }
            }
        }

        // مهام تلقائية
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

    public function show(string $id)
    {
        //
    }

    public function edit(Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);
        return view('crops.edit', compact('crop'));
    }

    public function update(Request $request, Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string',
            'area' => 'nullable|numeric',
            'planting_date' => 'nullable|date',
            'expected_harvest_date' => 'nullable|date',
            'soil_type' => 'nullable|string',
            'irrigation_method' => 'nullable|string',
            'yield_estimate' => 'nullable|numeric',
            'seed_source' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
            'growth_percentage' => 'nullable|integer',
        ]);

        $crop->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imagePath = $image->store('crops/images', 'public');
                    $crop->images()->create(['image_path' => $imagePath]);
                }
            }
        }

        return redirect()->route('crops.index')->with('success', 'Crop details updated.');
    }

    public function destroy(Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);
        $crop->delete();
        return redirect()->route('crops.index')->with('success', 'Crop removed.');
    }

    public function destroyImage(CropImage $image)
    {

    if (Storage::disk('public')->exists($image->image_path)) {
        Storage::disk('public')->delete($image->image_path);
    }
        $image->delete();
        return back()->with('success', 'Image deleted successfully');
        }

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
            'water_amount' => 'nullable|numeric',
            'duration_minutes' => 'nullable|integer',
            'material_name' => 'nullable|string|max:255',
            'dosage' => 'nullable|numeric',
            'dosage_unit' => 'nullable|string|max:50',
            'harvest_quantity' => 'nullable|numeric',
            'harvest_unit' => 'nullable|string|max:50',
            'system_notes' => 'nullable|string',
        ]);

        if (!$request->filled('reminder_time')) {
            $validated['reminder_time'] = Carbon::parse($validated['due_date'])->format('H:i');
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

        $dueDate = Carbon::parse($validated['due_date']);
        $now = Carbon::now();

        if ($dueDate->isToday() || $dueDate->isTomorrow()) {
            \App\Models\Notification::create([
                'user_id' => auth()->id(),
                'task_id' => $task->id,
                'title' => '✅ تمت إضافة مهمة جديدة',
                'message' => "تم جدولة المهمة '{$task->title}' للمحصول '{$crop->name}' - التذكير {$dueDate->format('Y-m-d')} الساعة {$task->reminder_time}.",
                'type' => 'task_due',
            ]);
        }

        if ($validated['type'] === 'harvest') {
            $crop->update(['status' => 'harvested']);
        }

        return back()->with('success', $validated['type'] === 'harvest'
            ? 'تم تسجيل الحصاد وتحديث حالة المحصول بنجاح!'
            : 'New task added successfully!');
    }

    public function completeTask(Request $request, $taskId)
    {
        $task = \App\Models\Task::findOrFail($taskId);
        if ($task->crop->user_id !== auth()->id()) abort(403);

        $task->update(['status' => 'completed']);

        if (in_array($task->type, ['water', 'fertilizer', 'pest'])) {
            $task->crop->increment('growth_percentage', 5);
            if ($task->crop->growth_percentage > 100) {
                $task->crop->update(['growth_percentage' => 100]);
            }
        }

        return back()->with('success', 'تم إتمام المهمة! زادت نسبة نمو المحصول بفضل اهتمامك.');
    }

    public function updateGrowth(Request $request, Crop $crop)
    {
        if ($crop->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'growth_percentage' => 'required|integer|min:0|max:100',
        ]);

        $data = ['growth_percentage' => $validated['growth_percentage']];

        if ($validated['growth_percentage'] == 100) {
            $data['status'] = 'harvested';
        } elseif ($validated['growth_percentage'] > 0) {
            $data['status'] = 'growing';
        }

        $crop->update($data);

        return back()->with('success', 'تم تحديث مرحلة النمو والحالة بنجاح!');
    }

    public function getAjaxSuggestions()
    {
        $user = auth()->user();

        $names = $user->crops()->pluck('name')->unique();
        $types = $user->crops()->pluck('type')->unique();

        $commonTypes = ['Wheat', 'Corn', 'Rice', 'Tomato', 'Potato', 'Cotton', 'Other', 'Cucumber', 'Palm', 'Clover', 'Olive', 'Citrus'];
        $commonNames = ['North Field', 'South Field', 'Greenhouse 1', 'Home Farm'];

        $translatedNames = $names->merge($commonNames)->unique()->map(fn($item) => __($item))->values();
        $translatedTypes = $types->merge($commonTypes)->unique()->map(fn($item) => __($item))->values();

        return response()->json([
            'success' => true,
            'data' => [
                'names' => $translatedNames,
                'types' => $translatedTypes,
            ]
        ]);
    }
}
