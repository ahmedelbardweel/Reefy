<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * كونترولر إشعار التحقق من البريد الإلكتروني - Email Verification Notification Controller
 * 
 * هذا الكونترولر مسؤول عن إعادة إرسال رابط التحقق من البريد الإلكتروني
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * إرسال إشعار تحقق جديد
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق مما إذا كان البريد الإلكتروني للمستخدم قد تم التحقق منه بالفعل
     *   * إذا نعم: إعادة التوجيه إلى الصفحة الرئيسية
     * - إذا لا:
     *   * إرسال إشعار التحقق مرة أخرى إلى المستخدم
     *   * إعادة التوجيه مع رسالة حالة 'verification-link-sent'
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // التحقق مما إذا كان قد تم التحقق من البريد بالفعل
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // إرسال إشعار التحقق
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
