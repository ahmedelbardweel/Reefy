<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Crop;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * كونترولر المهام API - Task API Controller
 * 
 * العلاقات:
 * - Task: belongsTo Crop (المحصول)
 * - Crop: belongsTo User (المزارع)
 * 
 * هذا الكونترولر يدير المهام المرتبطة بالمحاصيل عبر API
 */
class TaskController extends ApiController
{
    /**
     * عرض قائمة مهام محصول معين
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من وجود المحصول
     * - التحقق من ملكية المحصول للمستخدم الحالي
     * - جلب جميع مهام المحصول
     * - ترتيب المهام من الأحدث للأقدم
     * - إرجاع المهام في JSON response
     * 
     * @param int $cropId رقم المحصول
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($cropId)
    {
        $crop = Crop::find($cropId);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $tasks = $crop->tasks()->latest()->get();

        return $this->successResponse($tasks, 'Tasks retrieved successfully.');
    }

    /**
     * إضافة مهمة جديدة لمحصول
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من وجود المحصول وملكيته
     * - التحقق من صحة البيانات المدخلة
     * - إنشاء مهمة جديدة مرتبطة بالمحصول
     * - تعيين حالة المهمة إلى 'pending' (قيد الانتظار)
     * - إرجاع المهمة المنشأة في JSON response
     * 
     * أنواع المهام:
     * - water: ري
     * - fertilizer: تسميد
     * - pest: مكافحة آفات
     * - harvest: حصاد
     * - general: عامة
     * 
     * الأولويات:
     * - low: منخفضة
     * - medium: متوسطة
     * - high: عالية
     * 
     * البيانات التفصيلية حسب نوع المهمة:
     * - water: water_amount (كمية الماء), duration (المدة)
     * - fertilizer: material_name (اسم المادة), dosage (الجرعة), dosage_unit (وحدة القياس)
     * - pest: material_name, dosage, dosage_unit
     * - harvest: harvest_quantity (كمية الحصاد), harvest_unit (وحدة القياس)
     * 
     * @param Request $request
     * @param int $cropId رقم المحصول
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $cropId)
    {
        $crop = Crop::find($cropId);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:water,fertilizer,pest,harvest,general',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'due_time' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            // حقول متخصصة حسب نوع المهمة
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

        // إنشاء المهمة
        $input = $request->all();
        $input['crop_id'] = $cropId;
        $input['status'] = 'pending';
        
        // Use provided time or default to current time
        if (!isset($input['due_time']) || empty($input['due_time'])) {
            $input['due_time'] = now()->format('H:i:s');
        }

        $task = Task::create($input);

        // إنشاء إشعار للمزارع
        Notification::create([
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'type' => 'task_created',
            'title' => 'مهمة جديدة',
            'message' => 'تم إضافة مهمة جديدة: ' . $task->title,
        ]);

        return $this->successResponse($task, 'Task created successfully.');
    }

    /**
     * تحديد مهمة كمكتملة
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من وجود المهمة
     * - التحقق من ملكية المحصول المرتبط بالمهمة
     * - تحديث حالة المهمة إلى 'completed'
     * - إذا كانت المهمة من نوع حصاد (harvest):
     *   * تحديث حالة المحصول إلى 'harvested'
     * - إرجاع المهمة المحدثة في JSON response
     * 
     * ملاحظة: يمكن إضافة منطق تحديث نسبة النمو هنا (مبسط في API)
     * 
     * @param Request $request
     * @param int $taskId رقم المهمة
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        if (is_null($task)) {
            return $this->errorResponse('Task not found.');
        }

        // التحقق من الملكية عبر المحصول
        if ($task->crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        // تحديث حالة المهمة
        $task->status = 'completed';
        $task->save();

        // يمكن تحديث نسبة نمو المحصول هنا (منطق مبسط للـ API)
        
        // إذا كانت مهمة حصاد، تحديث حالة المحصول
        if ($task->type === 'harvest') {
            $task->crop->status = 'harvested';
            $task->crop->save();
        }

        return $this->successResponse($task, 'Task marked as completed.');
    }

    /**
     * جلب المهام القادمة للمستخدم الحالي (لجدولة الإشعارات محلياً)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function upcoming()
    {
        $userId = auth()->id();

        $tasks = Task::whereHas('crop', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', '>=', Carbon::now()->toDateString())
            ->with('crop:id,name') // نحتاج اسم المحصول للإشعار
            ->get(['id', 'crop_id', 'title', 'due_date', 'due_time', 'type']);

        return $this->successResponse($tasks, 'Upcoming tasks retrieved successfully.');
    }

    /**
     * جلب المهام المتأخرة للمستخدم الحالي
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function overdue()
    {
        $userId = auth()->id();
        $now = Carbon::now();

        $tasks = Task::whereHas('crop', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', '!=', 'completed')
            ->where(function ($query) use ($now) {
                $query->whereDate('due_date', '<', $now->toDateString())
                      ->orWhere(function ($q) use ($now) {
                          $q->whereDate('due_date', '=', $now->toDateString())
                            ->whereTime('due_time', '<', $now->toTimeString());
                      });
            })
            ->with('crop:id,name')
            ->get(['id', 'crop_id', 'title', 'due_date', 'due_time', 'type']);

        return $this->successResponse($tasks, 'Overdue tasks retrieved successfully.');
    }
}
