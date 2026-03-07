<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * كونترولر تعيين كلمة المرور الجديدة - New Password Controller
 * 
 * هذا الكونترولر مسؤول عن التعامل مع عملية إعادة تعيين كلمة المرور (Reset Password)
 * بعد نقر المستخدم على الرابط المرسل لبريده الإلكتروني
 */
class NewPasswordController extends Controller
{
    /**
     * عرض صفحة إعادة تعيين كلمة المرور
     * 
     * تقوم هذه الدالة بـ:
     * - عرض النموذج الذي يطلب من المستخدم إدخال كلمة المرور الجديدة
     * - تمرير الطلب (Request) الذي يحتوي على التوكن والبريد الإلكتروني للبيو (View)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * معالجة طلب تعيين كلمة المرور الجديدة
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات (الرمز، البريد، كلمة المرور وتأكيدها)
     * - محاولة إعادة تعيين كلمة المرور باستخدام Password Broker
     * - في حالة النجاح:
     *   * تحديث كلمة المرور وتشفيرها
     *   * تحديث remember token
     *   * إطلاق حدث PasswordReset
     *   * إعادة التوجيه إلى صفحة تسجيل الدخول مع رسالة نجاح
     * - في حالة الفشل:
     *   * إعادة التوجيه للخلف مع رسالة الخطأ
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // التحقق من البيانات
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // محاولة إعادة التعيين
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // التعامل مع النتيجة (نجاح أو فشل)
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
