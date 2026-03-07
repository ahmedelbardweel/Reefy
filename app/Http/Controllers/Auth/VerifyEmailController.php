<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * كونترولر التحقق من البريد الإلكتروني - Verify Email Controller
 * 
 * هذا الكونترولر يعالج الرابط الذي ينقر عليه المستخدم في رسالة التحقق
 * لتأكيد بريده الإلكتروني
 */
class VerifyEmailController extends Controller
{
    /**
     * تأكيد البريد الإلكتروني للمستخدم
     * 
     * الدالة __invoke تعني أن الكونترولر يحتوي على إجراء واحد فقط
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق مما إذا كان البريد قد تم تأكيده مسبقاً
     *   * إذا نعم: إعادة التوجيه إلى الصفحة الرئيسية
     * - إذا لا:
     *   * تحديد البريد كمؤكد (verified)
     *   * إطلاق حدث Verified
     *   * إعادة التوجيه إلى الصفحة الرئيسية مع معامل 'verified=1'
     * 
     * @param EmailVerificationRequest $request طلب خاص للتحقق من التوقيع وصحة الرابط
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
