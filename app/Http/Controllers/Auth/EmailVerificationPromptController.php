<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * كونترولر المطالبة بالتحقق من البريد - Email Verification Prompt Controller
 * 
 * هذا الكونترولر يعرض رسالة تطلب من المستخدم التحقق من بريده الإلكتروني
 * إذا حاول الوصول إلى صفحات تتطلب التحقق (verified middleware)
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * عرض صفحة المطالبة بالتحقق من البريد الإلكتروني
     * 
     * الدالة __invoke تعني أن الكونترولر يحتوي على إجراء واحد فقط
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق مما إذا كان المستخدم قد أكد بريده بالفعل
     *   * إذا نعم: إعادة التوجيه إلى الصفحة المقصودة أو الرئيسية
     *   * إذا لا: عرض صفحة 'auth.verify-email' التي تطلب التحقق
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email');
    }
}
