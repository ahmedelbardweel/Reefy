<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * كونترولر الجلسات المصادق عليها - Authenticated Session Controller
 * 
 * هذا الكونترولر يدير عمليات تسجيل الدخول والخروج للمستخدمين
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     * 
     * تقوم هذه الدالة بـ:
     * - عرض نموذج تسجيل الدخول
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * معالجة طلب تسجيل الدخول
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من بيانات الاعتماد (البريد الإلكتروني وكلمة المرور)
     * - محاولة المصادقة عبر LoginRequest->authenticate()
     * - إنشاء جلسة جديدة (session regeneration) للحماية من session fixation
     * - إعادة التوجيه إلى الصفحة المقصودة أو الصفحة الرئيسية
     * 
     * يستخدم LoginRequest للتحقق من البيانات والمصادقة
     * 
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // المصادقة (التحقق من البريد وكلمة المرور)
        $request->authenticate();

        // إنشاء جلسة جديدة (للأمان)
        $request->session()->regenerate();

        // إعادة التوجيه إلى الصفحة المقصودة
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * تسجيل خروج المستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - تسجيل خروج المستخدم من الـ guard الخاص بالويب
     * - إلغاء الجلسة الحالية (session invalidation)
     * - إنشاء CSRF token جديد (regenerateToken) للحماية
     * - إعادة التوجيه إلى الصفحة الرئيسية
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // تسجيل الخروج
        Auth::guard('web')->logout();

        // إلغاء الجلسة
        $request->session()->invalidate();

        // إنشاء توكن CSRF جديد
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
