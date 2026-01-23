<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Crop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageOptimization;

/**
 * كونترولر المحاصيل API - Crop API Controller
 * 
 * العلاقات:
 * - Crop: belongsTo User (المزارع)
 * - Crop: hasMany Task (المهام)
 * - Crop: hasMany CropImage (الصور - في النسخة الويب)
 * 
 * هذا الكونترولر يوفر CRUD operations كاملة للمحاصيل عبر API
 */
class CropController extends ApiController
{
    use ImageOptimization;
    /**
     * عرض قائمة محاصيل المستخدم الحالي
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع المحاصيل الخاصة بالمستخدم المصادق عليه
     * - إرجاع المحاصيل في JSON response
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $crops = auth()->user()->crops()->with('images')->latest()->get();
        return $this->successResponse($crops, 'Crops retrieved successfully.');
    }

    /**
     * إنشاء محصول جديد
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات المدخلة
     * - ربط المحصول بالمستخدم الحالي
     * - تعيين القيم الافتراضية:
     *   * status: 'active' (نشط)
     *   * growth_stage: 'seedling' (شتلة)
     *   * health_status: 'good' (جيد)
     * - رفع صورة المحصول إن وجدت
     * - إنشاء المحصول في قاعدة البيانات
     * - إرجاع المحصول المنشأ في JSON response
     * 
     * البيانات المطلوبة:
     * - name: اسم المحصول
     * - type: نوع المحصول
     * - planting_date: تاريخ الزراعة
     * - area_size: حجم المساحة
     * - area_unit: وحدة القياس
     * - expected_harvest_date: تاريخ الحصاد المتوقع
     * 
     * البيانات الاختيارية:
     * - variety: الصنف
     * - image: صورة المحصول
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'required|date',
            'area_size' => 'required|numeric',
            'area_unit' => 'required|string',
            'expected_harvest_date' => 'required|date',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        // إعداد البيانات
        $input = $request->all();
        $input['user_id'] = auth()->id();
        $input['status'] = 'active';
        $input['growth_stage'] = 'seedling';
        $input['health_status'] = 'good';

        $crop = Crop::create($input);

        // رفع صور (يدعم الملف الواحد أو المصفوفة)
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $image) {
                $path = $this->optimizeAndStore($image, 'crops');
                $crop->images()->create(['image_path' => $path]);
            }
        }

        // تحميل الصور للرد
        $crop->load('images');

        return $this->successResponse($crop, 'Crop created successfully.');
    }

    /**
     * عرض تفاصيل محصول معين
     * 
     * تقوم هذه الدالة بـ:
     * - جلب المحصول من قاعدة البيانات
     * - التحقق من ملكية المحصول للمستخدم الحالي
     * - إرجاع المحصول في JSON response
     * 
     * @param int $id رقم المحصول
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $crop = Crop::find($id);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        return $this->successResponse($crop->load('images'), 'Crop retrieved successfully.');
    }

    /**
     * تحديث بيانات محصول موجود
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من وجود المحصول وملكيته
     * - التحقق من صحة البيانات المدخلة
     * - إذا تم رفع صورة جديدة:
     *   * حذف الصورة القديمة
     *   * رفع وحفظ الصورة الجديدة
     * - تحديث بيانات المحصول
     * - إرجاع المحصول المحدث في JSON response
     * 
     * @param Request $request
     * @param int $id رقم المحصول
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $crop = Crop::find($id);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'type' => 'string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'date',
            'area_size' => 'numeric',
            'area_unit' => 'string',
            'expected_harvest_date' => 'date',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();

        $crop->update($input);

        // إضافة صور (يدعم الملف الواحد أو المصفوفة)
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $image) {
                $path = $this->optimizeAndStore($image, 'crops');
                $crop->images()->create(['image_path' => $path]);
            }
        }

        return $this->successResponse($crop->load('images'), 'Crop updated successfully.');
    }

    /**
     * حذف محصول
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من وجود المحصول وملكيته
     * - حذف الصورة من التخزين إن وجدت
     * - حذف المحصول من قاعدة البيانات
     * - حذف جميع المهام المرتبطة (cascade)
     * - إرجاع استجابة نجاح
     * 
     * @param int $id رقم المحصول
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $crop = Crop::find($id);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        // التحقق من الملكية
        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        // حذف الصورة
        if ($crop->image_path) {
            Storage::disk('public')->delete($crop->image_path);
        }
        
        $crop->delete();

        return $this->successResponse([], 'Crop deleted successfully.');
    }

    /**
     * تسجيل عملية زراعية (ري، معالجة، حصاد، نمو)
     * 
     * @param Request $request
     * @param int $id رقم المحصول
     */
    public function recordLog(Request $request, $id)
    {
        $crop = Crop::find($id);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $type = $request->input('type'); 
        $notes = $request->input('notes');

        $taskType = 'general';
        $titlePrefix = 'Action: ';
        
        switch ($type) {
            case 'IRRIGATION':
                $taskType = 'water';
                $titlePrefix = 'عملية ري: ';
                break;
            case 'TREATMENT':
                $taskType = 'fertilizer';
                $titlePrefix = 'معالجة: ';
                break;
            case 'HARVEST':
                $taskType = 'harvest';
                $titlePrefix = 'حصاد: ';
                $crop->status = 'harvested';
                break;
            case 'GROWTH':
                $taskType = 'general';
                $titlePrefix = 'تحديث نمو: ';
                $stage = $request->input('stage');
                if ($stage === 'stage1') $crop->growth_percentage = 25;
                elseif ($stage === 'stage2') $crop->growth_percentage = 60;
                elseif ($stage === 'stage3') $crop->growth_percentage = 100;
                break;
        }

        $crop->save();

        // إنشاء مهمة مكتملة للسجل
        $crop->tasks()->create([
            'title' => $titlePrefix . ($notes ?: 'Agricultural Action'),
            'type' => $taskType,
            'status' => 'completed',
            'due_date' => $request->input('due_date') ?: now(),
            'priority' => 'medium',
            'notes' => $notes,
            'water_amount' => $request->input('water_amount'),
            'duration_minutes' => $request->input('duration'),
            'material_name' => $request->input('material_name'),
            'dosage' => $request->input('dose'),
            'dosage_unit' => $request->input('unit'),
            'harvest_quantity' => $request->input('quantity'),
            'harvest_unit' => $request->input('unit'),
        ]);

        return $this->successResponse([], 'Log recorded successfully.');
    }
}
