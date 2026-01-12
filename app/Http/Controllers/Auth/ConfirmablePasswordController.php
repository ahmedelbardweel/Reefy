<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * كونترولر تأكيد كلمة المرور - Confirmable Password Controller
 * 
 * هذا الكونترولر مسؤول عن التعامل مع إجراءات التأكيد الأمني بكلمة المرور
 * قبل تنفيذ إجراءات حساسة (مثل تغيير إعدادات الأمان أو الفوترة)
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * عرض صفحة تأكيد كلمة المرور
     * 
     * تقوم هذه الدالة بـ:
     * - عرض النموذج الذي يطلب من المستخدم تأكيد كلمة المرور الخاصة به
     * 
     * @return \Illuminate\View\View
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * تأكيد كلمة مرور المستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة كلمة المرور المدخلة عبر Auth::guard('web')->validate
     * - إذا كانت غير صحيحة:
     *   * رمي استثناء ValidationException مع رسالة خطأ
     * - إذا كانت صحيحة:
     *   * تخزين وقت التأكيد في الجلسة (session)
     *   * إعادة التوجيه إلى الصفحة المقصودة
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
