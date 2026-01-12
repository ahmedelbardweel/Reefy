<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Crop;
use App\Models\Notification;

/**
 * كونترولر الاستشارات - Consultation Controller
 * 
 * العلاقات:
 * - Consultation (الاستشارة): belongsTo User (المزارع الذي طرح السؤال)
 * - Consultation: belongsTo Expert (الخبير الذي أجاب)
 * - Consultation: belongsTo Crop (المحصول المرتبط بالاستشارة - اختياري)
 */
class ConsultationController extends Controller
{
    /**
     * عرض قائمة استشارات المزارع الحالي
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع الاستشارات الخاصة بالمستخدم الحالي
     * - تحميل علاقات: الخبير الذي أجاب والمحصول المرتبط
     * - ترتيب الاستشارات من الأحدث للأقدم
     * - عرض صفحة قائمة الاستشارات
     * 
     * العلاقة: Consultation belongsTo User, Expert, Crop
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $consultations = auth()->user()->consultations()->with(['expert', 'crop'])->latest()->get();
        return view('consultations.index', compact('consultations'));
    }

    /**
     * عرض نموذج إنشاء استشارة جديدة
     * 
     * تقوم هذه الدالة بـ:
     * - جلب محاصيل المستخدم الحالي لعرضها في القائمة المنسدلة
     * - عرض صفحة نموذج إنشاء الاستشارة
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $crops = auth()->user()->crops;
        return view('consultations.create', compact('crops'));
    }

    /**
     * حفظ استشارة جديدة
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات المدخلة (العنوان، السؤال، المحصول، الفئة)
     * - إنشاء استشارة جديدة مرتبطة بالمستخدم الحالي
     * - تعيين حالة الاستشارة إلى 'pending' (قيد الانتظار)
     * - إعادة التوجيه إلى قائمة الاستشارات مع رسالة نجاح
     * 
     * الحالات: pending (قيد الانتظار), answered (تم الرد)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'crop_id' => 'nullable|exists:crops,id',
            'category' => 'required|string',
        ]);

        // إنشاء الاستشارة
        Consultation::create([
            'user_id' => auth()->id(),
            'crop_id' => $request->crop_id,
            'subject' => $request->subject,
            'question' => $request->question,
            'category' => $request->category,
            'status' => 'pending',
        ]);

        return redirect()->route('consultations.index')->with('success', 'تم إرسال استشارتك بنجاح! سيقوم خبير بالرد عليك قريباً.');
    }

    /**
     * عرض تفاصيل استشارة معينة
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صلاحية الوصول (صاحب الاستشارة أو خبير)
     * - عرض صفحة تفاصيل الاستشارة
     * 
     * @param Consultation $consultation الاستشارة المراد عرضها
     * @return \Illuminate\View\View
     */
    public function show(Consultation $consultation)
    {
        // التحقق من الصلاحية: فقط صاحب الاستشارة أو الخبراء
        if ($consultation->user_id !== auth()->id() && auth()->user()->role !== 'expert') {
            abort(403);
        }
        return view('consultations.show', compact('consultation'));
    }

    /**
     * عرض قائمة الاستشارات المعلقة للخبراء
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع الاستشارات ذات الحالة 'pending' (قيد الانتظار)
     * - ترتيبها من الأحدث للأقدم
     * - عرض صفحة الاستشارات للخبير
     * 
     * هذه الصفحة خاصة بالخبراء فقط
     * 
     * @return \Illuminate\View\View
     */
    public function expertIndex()
    {
        $consultations = Consultation::where('status', 'pending')->latest()->get();
        return view('expert.consultations.index', compact('consultations'));
    }

    /**
     * إضافة رد من الخبير على استشارة
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات (الرد)
     * - تحديث الاستشارة بإضافة: رقم الخبير، الرد، وتغيير الحالة إلى 'answered'
     * - إنشاء إشعار للمزارع صاحب الاستشارة لإعلامه بالرد
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * العلاقة: Notification belongsTo User و Task (اختياري)
     * 
     * @param Request $request
     * @param Consultation $consultation الاستشارة المراد الرد عليها
     * @return \Illuminate\Http\RedirectResponse
     */
    public function answer(Request $request, Consultation $consultation)
    {
        // التحقق من البيانات
        $request->validate([
            'response' => 'required|string',
        ]);

        // تحديث الاستشارة بالرد
        $consultation->update([
            'expert_id' => auth()->id(),
            'response' => $request->response,
            'status' => 'answered',
        ]);

        // إنشاء إشعار للمزارع
        Notification::create([
            'user_id' => $consultation->user_id,
            'title' => 'تم الرد على استشارتك! 🎓',
            'message' => "قام الخبير بالإجابة على استشارتك: {$consultation->subject}",
            'type' => 'advice',
        ]);

        return redirect()->back()->with('success', 'تم إرسال إجابتك بنجاح. شكراً لمساهمتك!');
    }
}
