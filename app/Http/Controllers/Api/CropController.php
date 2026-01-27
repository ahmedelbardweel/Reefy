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
        return $this->successResponse($crops, 'Crops retrieved successfully');
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
        // التحقق من البيانات - جعل الحقول اختيارية لتسهيل الإدخال للمزارع
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'nullable|date',
            'area_size' => 'nullable|numeric',
            'area_unit' => 'nullable|string',
            'expected_harvest_date' => 'nullable|date',
            'importance' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // زيادة الحجم لـ 5 ميجا
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors(), 422);
        }

        // إعداد البيانات مع قيم افتراضية إذا كانت الحقول فارغة
        $input = $request->all();
        $input['user_id'] = auth()->id();
        $input['name'] = $request->name ?: 'محصول جديد ' . (auth()->user()->crops()->count() + 1);
        $input['status'] = 'active';
        $input['growth_stage'] = 'seedling';
        $input['health_status'] = 'good';
        $input['planting_date'] = $request->planting_date ?: now();
        $input['growth_percentage'] = 0;

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

        return $this->successResponse($crop, 'Crop created successfully');
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

        return $this->successResponse($crop->load('images'), 'Crop retrieved successfully');
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

        // التحقق من البيانات - جميعها اختيارية
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'nullable|date',
            'area_size' => 'nullable|numeric',
            'area_unit' => 'nullable|string',
            'expected_harvest_date' => 'nullable|date',
            'status' => 'nullable|string',
            'growth_stage' => 'nullable|string',
            'health_status' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
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

        return $this->successResponse($crop->load('images'), 'Crop updated successfully');
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

        return $this->successResponse([], 'Crop deleted successfully');
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

    /**
     * الحصول على اقتراحات للحقول بناءً على بيانات المستخدم السابقة واقتراحات عامة
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions()
    {
        $user = auth()->user();
        
        $names = $user->crops()->pluck('name')->unique()->values();
        $types = $user->crops()->pluck('type')->unique()->values();
        $varieties = $user->crops()->pluck('variety')->whereNotNull('variety')->unique()->values();
        $units = $user->crops()->pluck('area_unit')->unique()->values();

        // اقتراحات عامة شائعة في المنطقة العربية
        $commonTypes = ['قمح', 'ذرة', 'بطاطس', 'طماطم', 'خيار', 'نخيل', 'برسيم', 'زيتون', 'حمضيات'];
        $commonUnits = ['فدان', 'دونم', 'هكتار', 'متر مربع'];

        return $this->successResponse([
            'names' => $names,
            'types' => $types->merge($commonTypes)->unique()->values(),
            'varieties' => $varieties,
            'units' => $units->merge($commonUnits)->unique()->values(),
        ], 'Suggestions retrieved successfully.');
    }

    /**
     * حذف صورة معينة للمحصول
     * 
     * @param int $id رقم الصورة
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage($id)
    {
        $image = \App\Models\CropImage::with('crop')->find($id);

        if (!$image) {
            return $this->errorResponse('Image not found.', [], 404);
        }

        // التحقق من ملكية المحصول المرتبط بالصورة
        if ($image->crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        // حذف الملف من التخزين
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // حذف السجل من قاعدة البيانات
        $image->delete();

        return $this->successResponse([], 'Image deleted successfully.');
    }
}
