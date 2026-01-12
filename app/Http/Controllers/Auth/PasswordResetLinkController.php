<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * كونترولر رابط إعادة تعيين كلمة المرور - Password Reset Link Controller
 * 
 * هذا الكونترولر مسؤول عن إرسال روابط إعادة تعيين كلمة المرور (Forgot Password)
 */
class PasswordResetLinkController extends Controller
{
    /**
     * عرض صفحة طلب رابط إعادة التعيين
     * 
     * تقوم هذه الدالة بـ:
     * - عرض النموذج الذي يطلب البريد الإلكتروني لإرسال رابط إعادة التعيين
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * معالجة طلب إرسال رابط إعادة التعيين
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من وجود البريد الإلكتروني وصحته
     * - إرسال رابط إعادة التعيين إلى البريد الإلكتروني
     * - في حالة النجاح:
     *   * إعادة التوجيه مع رسالة نجاح
     * - في حالة الفشل:
     *   * إعادة التوجيه مع رسالة خطأ (مثلاً إذا لم يتم العثور على المستخدم)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // التحقق من البريد
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // إرسال الرابط
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
