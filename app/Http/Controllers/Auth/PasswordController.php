<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * كونترولر كلمة المرور - Password Controller
 * 
 * هذا الكونترولر مسؤول عن تحديث كلمة المرور للمستخدم المسجل دخوله
 * (تغيير كلمة المرور من الإعدادات)
 */
class PasswordController extends Controller
{
    /**
     * تحديث كلمة مرور المستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات المدخلة:
     *   * كلمة المرور الحالية (current_password) يجب أن تكون صحيحة
     *   * كلمة المرور الجديدة يجب أن تكون قوية ومطابقة للتأكيد
     * - تحديث كلمة المرور في قاعدة البيانات بعد تشفيرها
     * - إعادة التوجيه للخلف مع رسالة نجاح 'password-updated'
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        // التحقق من البيانات (الحالية والجديدة)
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // تحديث كلمة المرور
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
