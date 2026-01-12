<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * كونترولر تسجيل المستخدمين الجدد - Registered User Controller
 * 
 * العلاقات:
 * - User: hasOne FarmerProfile (للمزارعين)
 * - User: hasOne ExpertProfile (للخبراء)
 * 
 * هذا الكونترولر يدير عملية التسجيل للمستخدمين الجدد في النظام
 */
class RegisteredUserController extends Controller
{
    /**
     * عرض صفحة التسجيل
     * 
     * تقوم هذه الدالة بـ:
     * - عرض نموذج التسجيل للمستخدمين الجدد
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * معالجة طلب التسجيل وإنشاء المستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات المدخلة (الاسم، البريد، كلمة المرور، الدور)
     * - تحديد حالة المستخدم:
     *   * المزارعون (farmer): حالة 'pending' (بانتظار إكمال الملف الشخصي)
     *   * باقي الأدوار: حالة 'active' (نشط)
     * - تشفير كلمة المرور
     * - إنشاء المستخدم في قاعدة البيانات
     * - إنشاء ملف شخصي حسب الدور:
     *   * farmer: إنشاء FarmerProfile فارغ
     *   * expert: إنشاء ExpertProfile مع التخصص الافتراضي 'General'
     * - إطلاق حدث Registered (لإرسال بريد التحقق)
     * - تسجيل دخول المستخدم تلقائياً
     * - إعادة التوجيه إلى الصفحة الرئيسية
     * 
     * الأدوار المتاحة: farmer (مزارع), buyer (مشتري), expert (خبير)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // التحقق من البيانات
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:farmer,buyer,expert'],
        ]);

        // تحديد الحالة: المزارعون بانتظار إكمال الملف
        $status = $request->role === 'farmer' ? 'pending' : 'active';

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $status,
        ]);

        // إنشاء الملف الشخصي حسب الدور
        if ($request->role === 'farmer') {
             $user->farmerProfile()->create([]);
        } elseif ($request->role === 'expert') {
             $user->expertProfile()->create(['specialization' => 'General']); // التخصص الافتراضي
        }

        // إطلاق حدث التسجيل
        event(new Registered($user));

        // تسجيل الدخول تلقائياً
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
