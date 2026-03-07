<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Validator;

/**
 * كونترولر الاستشارات API - Consultation API Controller
 * 
 * العلاقات:
 * - Consultation: belongsTo User (farmer_id - المزارع)
 * - Consultation: belongsTo User (expert_id - الخبير)
 * - Consultation: belongsTo Crop (المحصول - اختياري)
 * 
 * هذا الكونترولر يوفر API endpoints لإدارة الاستشارات الزراعية
 */
class ConsultationController extends ApiController
{
    /**
     * عرض قائمة الاستشارات حسب دور المستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - إذا كان المستخدم مزارعاً (farmer):
     *   * جلب جميع استشاراته الخاصة
     *   * تحميل علاقات: الخبير والمحصول
     * - إذا كان المستخدم خبيراً (expert):
     *   * جلب الاستشارات المعلقة (pending) أو التي أجاب عليها
     *   * تحميل علاقات: المزارع والمحصول
     * - إذا كان الدور غير مصرح به:
     *   * إرجاع خطأ 403
     * - ترتيب الاستشارات من الأحدث للأقدم
     * - إرجاع الاستشارات في JSON response
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'farmer') {
            // جلب استشارات المزارع
            $consultations = $user->consultations()->with(['expert', 'crop'])->latest()->get();
        } elseif ($user->role === 'expert') {
            // جلب الاستشارات المعلقة أو التي أجاب عليها الخبير
            $consultations = Consultation::where('status', 'pending')
                ->orWhere('expert_id', $user->id)
                ->with(['farmer', 'crop'])
                ->latest()
                ->get();
        } else {
            return $this->errorResponse('Unauthorized role.', [], 403);
        }

        return $this->successResponse($consultations, 'Consultations retrieved successfully.');
    }

    /**
     * إنشاء استشارة جديدة (للمزارعين فقط)
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن المستخدم مزارع
     * - التحقق من صحة البيانات (الموضوع، الفئة، السؤال، المحصول)
     * - إنشاء استشارة جديدة مرتبطة بالمزارع الحالي
     * - تعيين حالة الاستشارة إلى 'pending' (قيد الانتظار)
     * - إرجاع الاستشارة المنشأة في JSON response
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // التحقق من دور المزارع
        if (auth()->user()->role !== 'farmer') {
            return $this->errorResponse('Only farmers can create consultations.', [], 403);
        }

        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'question' => 'required|string',
            'crop_id' => 'nullable|exists:crops,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        // إنشاء الاستشارة
        $input = $request->all();
        $input['farmer_id'] = auth()->id();
        $input['status'] = 'pending';

        $consultation = Consultation::create($input);

        return $this->successResponse($consultation, 'Consultation request created successfully.');
    }

    /**
     * عرض تفاصيل استشارة معينة
     * 
     * تقوم هذه الدالة بـ:
     * - جلب الاستشارة مع علاقاتها (المزارع، الخبير، المحصول)
     * - التحقق من صلاحية الوصول:
     *   * المزارع: يمكنه فقط رؤية استشاراته
     *   * الخبير: يمكنه رؤية جميع الاستشارات (للرد عليها)
     * - إرجاع الاستشارة في JSON response
     * 
     * @param int $id رقم الاستشارة
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $consultation = Consultation::with(['farmer', 'expert', 'crop'])->find($id);

        if (is_null($consultation)) {
            return $this->errorResponse('Consultation not found.');
        }

        // التحقق من الصلاحية
        $user = auth()->user();
        if ($user->role === 'farmer' && $consultation->farmer_id !== $user->id) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }
        // الخبراء يمكنهم رؤية أي استشارة
        
        return $this->successResponse($consultation, 'Consultation retrieved successfully.');
    }

    /**
     * الرد على استشارة (للخبراء فقط)
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن المستخدم خبير
     * - التحقق من وجود الاستشارة
     * - التحقق من صحة البيانات (الرد)
     * - تحديث الاستشارة بـ:
     *   * response: نص الرد
     *   * expert_id: رقم الخبير
     *   * status: تغيير الحالة إلى 'answered'
     * - إرجاع الاستشارة المحدثة في JSON response
     * 
     * ملاحظة: يمكن إضافة إشعار للمزارع هنا
     * 
     * @param Request $request
     * @param int $id رقم الاستشارة
     * @return \Illuminate\Http\JsonResponse
     */
    public function reply(Request $request, $id)
    {
        // التحقق من دور الخبير
        if (auth()->user()->role !== 'expert') {
            return $this->errorResponse('Only experts can reply.', [], 403);
        }

        $consultation = Consultation::find($id);

        if (is_null($consultation)) {
            return $this->errorResponse('Consultation not found.');
        }

        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'response' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        // تحديث الاستشارة بالرد
        $consultation->response = $request->response;
        $consultation->expert_id = auth()->id();
        $consultation->status = 'answered';
        $consultation->save();

        // يمكن إضافة إشعار للمزارع هنا (تم حذفه للاختصار)

        return $this->successResponse($consultation, 'Reply posted successfully.');
    }
}
