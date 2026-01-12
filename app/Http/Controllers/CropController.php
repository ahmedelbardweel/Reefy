<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;

/**
 * كونترولر المحاصيل - Crop Controller
 * 
 * العلاقات:
 * - Crop (المحصول): belongsTo User (المزارع)
 * - Crop: hasMany Task (المهام المرتبطة بالمحصول)
 * - Crop: hasMany CropImage (صور المحصول)
 * - Crop: hasMany Consultation (الاستشارات المرتبطة بالمحصول)
 */
class CropController extends Controller
{
    /**
     * عرض قائمة محاصيل المزارع الحالي
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع المحاصيل الخاصة بالمستخدم الحالي
     * - تحميل علاقة المهام (tasks) لكل محصول
     * - ترتيب المحاصيل من الأحدث للأقدم
     * - تقسيم النتائج إلى صفحات (9 محاصيل في كل صفحة)
     * 
     * العلاقة: Crop belongsTo User, hasMany Task
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $crops = auth()->user()->crops()->with('tasks')->latest()->paginate(9);
        return view('crops.index', compact('crops'));
    }

    /**
     * عرض نموذج إضافة محصول جديد
     * 
     * تقوم هذه الدالة بـ:
     * - عرض صفحة نموذج إنشاء محصول جديد
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('crops.create');
    }

    /**
     * حفظ محصول جديد مع توليد مهام تلقائية
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات المدخلة
     * - إنشاء محصول جديد مرتبط بالمستخدم الحالي
     * - رفع الصور المرفقة وحفظها في جدول CropImage
     * - توليد مهام تلقائية للمحصول:
     *   * مهمة الري الأولى (بعد يوم من الزراعة)
     *   * مهمة التسميد (بعد 14 يوم من الزراعة)
     * - إعادة التوجيه إلى قائمة المحاصيل مع رسالة نجاح
     * 
     * البيانات المطلوبة: الاسم، النوع، المساحة، تاريخ الزراعة، تاريخ الحصاد المتوقع
     * البيانات الاختيارية: نوع التربة، طريقة الري، مصدر البذور، الإنتاج المتوقع، ملاحظات، صور
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
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

        // إنشاء المحصول
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

        // رفع وحفظ الصور المتعددة
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

        // توليد مهام تلقائية ذكية
        // مهمة الري الأولى
        $crop->tasks()->create([
            'title' => 'Initial Irrigation (الرية الأولى)',
            'type' => 'water',
            'due_date' => $crop->planting_date->addDays(1),
            'status' => 'pending',
        ]);

        // مهمة التسميد
        $crop->tasks()->create([
            'title' => 'Fertilizer Application (تسميد)',
            'type' => 'fertilizer',
            'due_date' => $crop->planting_date->addDays(14),
            'status' => 'pending',
        ]);

        return redirect()->route('crops.index')->with('success', 'Crop added and smart tasks generated!');
    }

    /**
     * عرض تفاصيل محصول معين
     * 
     * هذه الدالة غير مفعلة حالياً
     * 
     * @param string $id
     */
    public function show(string $id)
    {
        //
    }

    /**
     * عرض نموذج تعديل محصول
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن المحصول يخص المستخدم الحالي
     * - عرض صفحة تعديل المحصول
     * 
     * @param Crop $crop المحصول المراد تعديله
     * @return \Illuminate\View\View
     */
    public function edit(Crop $crop)
    {
        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) abort(403);
        return view('crops.edit', compact('crop'));
    }

    /**
     * تحديث بيانات محصول موجود
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صلاحية المستخدم
     * - التحقق من صحة البيانات المدخلة
     * - تحديث بيانات المحصول
     * - رفع وإضافة صور إضافية إن وجدت (لا تحذف الصور القديمة)
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * @param Request $request
     * @param Crop $crop المحصول المراد تحديثه
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Crop $crop)
    {
        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) abort(403);

        // التحقق من البيانات
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

        // تحديث المحصول
        $crop->update($validated);

        // إضافة صور جديدة (لا يحذف القديمة)
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
     * حذف محصول
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صلاحية المستخدم
     * - حذف المحصول (سيتم حذف المهام والصور تلقائياً بسبب cascade)
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * @param Crop $crop المحصول المراد حذفه
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Crop $crop)
    {
        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) abort(403);
        $crop->delete();
        return redirect()->route('crops.index')->with('success', 'Crop removed.');
    }

    /**
     * إضافة مهمة جديدة للمحصول يدوياً
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صلاحية المستخدم
     * - التحقق من صحة البيانات المدخلة
     * - إنشاء مهمة جديدة مرتبطة بالمحصول
     * - استخراج وقت التذكير تلقائياً من تاريخ الاستحقاق إن لم يتم تحديده
     * - إنشاء إشعار فوري للمهام المستحقة اليوم أو غداً
     * - تحديث حالة المحصول تلقائياً إلى 'harvested' عند إضافة مهمة حصاد
     * 
     * أنواع المهام: water (ري), fertilizer (تسميد), pest (مكافحة آفات), harvest (حصاد), other (أخرى)
     * الحالات: pending (قيد الانتظار), completed (مكتملة)
     * 
     * البيانات التفصيلية حسب نوع المهمة:
     * - للري: كمية الماء، مدة الري بالدقائق
     * - للتسميد: اسم المادة، الجرعة، وحدة القياس
     * - للآفات: اسم المادة، الجرعة، وحدة القياس
     * - للحصاد: كمية الحصاد، وحدة القياس
     * 
     * @param Request $request
     * @param Crop $crop المحصول المراد إضافة مهمة له
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTask(Request $request, Crop $crop)
    {
        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) abort(403);

        // التحقق من البيانات
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:water,fertilizer,pest,harvest,other',
            'due_date' => 'required|date',
            'reminder_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,completed',
            // حقول تفصيلية
            'water_amount' => 'nullable|numeric',
            'duration_minutes' => 'nullable|integer',
            'material_name' => 'nullable|string|max:255',
            'dosage' => 'nullable|numeric',
            'dosage_unit' => 'nullable|string|max:50',
            'harvest_quantity' => 'nullable|numeric',
            'harvest_unit' => 'nullable|string|max:50',
            'system_notes' => 'nullable|string',
        ]);

        // استخراج وقت التذكير تلقائياً من تاريخ الاستحقاق
        if (!$request->filled('reminder_time')) {
            $validated['reminder_time'] = \Carbon\Carbon::parse($validated['due_date'])->format('H:i');
        }

        // إنشاء المهمة
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

        // إنشاء إشعار فوري للمهام القريبة (اليوم أو غداً)
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

        // تحديث حالة المحصول تلقائياً عند الحصاد
        if ($validated['type'] === 'harvest') {
            $crop->update(['status' => 'harvested']);
        }

        return back()->with('success', $validated['type'] === 'harvest' 
            ? 'تم تسجيل الحصاد وتحديث حالة المحصول بنجاح!' 
            : 'New task added successfully!');
    }

    /**
     * تحديد مهمة كمكتملة وزيادة نسبة نمو المحصول
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صلاحية المستخدم (عبر المحصول)
     * - تحديث حالة المهمة إلى 'completed'
     * - زيادة نسبة نمو المحصول بـ 5% للمهام الأساسية (ري، تسميد، مكافحة آفات)
     * - التأكد من عدم تجاوز نسبة النمو 100%
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * المهام التي تزيد نسبة النمو: water, fertilizer, pest
     * 
     * @param Request $request
     * @param int $taskId رقم المهمة المراد إكمالها
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeTask(Request $request, $taskId)
    {
        $task = \App\Models\Task::findOrFail($taskId);
        
        // التحقق من الملكية عبر المحصول
        if ($task->crop->user_id !== auth()->id()) abort(403);

        // تحديث حالة المهمة
        $task->update(['status' => 'completed']);

        // زيادة نسبة النمو للمهام المهمة
        if (in_array($task->type, ['water', 'fertilizer', 'pest'])) {
            $task->crop->increment('growth_percentage', 5);
            // التأكد من عدم تجاوز 100%
            if ($task->crop->growth_percentage > 100) {
                $task->crop->update(['growth_percentage' => 100]);
            }
        }

        return back()->with('success', 'تم إتمام المهمة! زادت نسبة نمو المحصول بفضل اهتمامك.');
    }

    /**
     * تحديث نسبة نمو المحصول يدوياً
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صلاحية المستخدم
     * - التحقق من صحة القيمة المدخلة (0-100)
     * - تحديث نسبة النمو
     * - تحديث حالة المحصول تلقائياً:
     *   * إذا وصلت النسبة 100%: تغيير الحالة إلى 'harvested'
     *   * إذا كانت أكبر من 0: تغيير الحالة إلى 'growing'
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * حالات المحصول: growing (ينمو), harvested (تم الحصاد)
     * 
     * @param Request $request
     * @param Crop $crop المحصول المراد تحديث نموه
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateGrowth(Request $request, Crop $crop)
    {
        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) abort(403);

        // التحقق من البيانات
        $validated = $request->validate([
            'growth_percentage' => 'required|integer|min:0|max:100',
        ]);

        $data = ['growth_percentage' => $validated['growth_percentage']];

        // تحديث الحالة تلقائياً بناءً على نسبة النمو
        if ($validated['growth_percentage'] == 100) {
            $data['status'] = 'harvested';
        } elseif ($validated['growth_percentage'] > 0) {
            $data['status'] = 'growing';
        }

        $crop->update($data);

        return back()->with('success', 'تم تحديث مرحلة النمو والحالة بنجاح!');
    }
}
