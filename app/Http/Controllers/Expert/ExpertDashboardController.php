<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;

/**
 * كونترولر لوحة تحكم الخبير - Expert Dashboard Controller
 * 
 * العلاقات:
 * - Consultation: belongsTo User (farmer_id)
 * - Consultation: belongsTo User (expert_id)
 * - Consultation: belongsTo Crop
 * - ExpertTip: belongsTo User (expert)
 * 
 * هذا الكونترولر يعرض لوحة التحكم الرئيسية للخبير مع الإحصائيات والاستشارات
 */
class ExpertDashboardController extends Controller
{
    /**
     * عرض لوحة تحكم الخبير
     * 
     * تقوم هذه الدالة بـ:
     * 
     * 1. حساب الإحصائيات:
     *    - pendingCount: عدد الاستشارات المعلقة (pending) بانتظار الرد
     *    - answeredCount: عدد الاستشارات التي أجاب عليها الخبير الحالي
     * 
     * 2. جلب الاستشارات الأخيرة:
     *    - استشارات المعلقة فقط (pending)
     *    - تحميل علاقات: المزارع والمحصول
     *    - ترتيبها من الأحدث للأقدم
     *    - أخذ آخر 5 استشارات
     * 
     * 3. جلب نصائح الخبير الحالي:
     *    - جميع النصائح التي كتبها الخبير
     *    - ترتيبها من الأحدث للأقدم
     * 
     * 4. عرض لوحة التحكم مع جميع البيانات
     * 
     * العلاقات المستخدمة:
     * - Consultation -> User (farmer), Crop
     * - User (expert) -> expertAdvice (الاستشارات التي أجاب عليها)
     * - User (expert) -> expertTips (النصائح)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // عدد الاستشارات المعلقة (بانتظار الرد)
        $pendingCount = Consultation::where('status', 'pending')->count();
        
        // عدد الاستشارات التي أجاب عليها الخبير الحالي
        $answeredCount = auth()->user()->expertAdvice()->count();
        
        // آخر 5 استشارات معلقة
        $recentConsultations = Consultation::where('status', 'pending')
            ->with(['user', 'crop'])
            ->latest()
            ->take(5)
            ->get();
        
        // نصائح الخبير الحالي
        $myTips = auth()->user()->expertTips()->latest()->get();

        return view('expert.dashboard', compact('pendingCount', 'answeredCount', 'recentConsultations', 'myTips'));
    }
}
