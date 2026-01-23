<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * كونترولر الملف الشخصي العام - Profile Controller
 * 
 * هذا الكونترولر يدير الملف الشخصي الأساسي للمستخدم (الاسم، البريد الإلكتروني، كلمة المرور)
 * وليس الملف الشخصي التفصيلي للمزارع (FarmerProfileController)
 * 
 * يستخدم Laravel Breeze للتعامل مع الملفات الشخصية
 */
class ProfileController extends Controller
{
    /**
     * عرض صفحة تعديل الملف الشخصي للمستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - عرض صفحة تعديل الملف الشخصي الأساسي
     * - تمرير بيانات المستخدم الحالي إلى الصفحة
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * تحديث معلومات الملف الشخصي للمستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات عبر ProfileUpdateRequest
     * - تحديث بيانات المستخدم (الاسم، البريد الإلكتروني، إلخ)
     * - إذا تم تغيير البريد الإلكتروني:
     *   * إعادة تعيين email_verified_at إلى null
     *   * لطلب تأكيد البريد الجديد
     * - حفظ التغييرات
     * - إعادة التوجيه مع رسالة النجاح
     * 
     * @param ProfileUpdateRequest $request طلب مخصص للتحقق من البيانات
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }
            
            $file = $request->file('avatar');
            $filename = time() . '_avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profiles/avatars'), $filename);
            $user->avatar = 'profiles/avatars/' . $filename;
        }

        // Handle Cover Image Upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover if exists
            if ($user->cover_image && file_exists(public_path($user->cover_image))) {
                @unlink(public_path($user->cover_image));
            }
            
            $file = $request->file('cover_image');
            $filename = time() . '_cover_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profiles/covers'), $filename);
            $user->cover_image = 'profiles/covers/' . $filename;
        }

        // تعبئة البيانات المحققة (Name and Email)
        $user->fill($request->safe()->only(['name', 'email']));

        // إذا تم تغيير البريد الإلكتروني، إعادة تعيين التحقق
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // حفظ التغييرات
        $user->save();

        return Redirect::route('profile.edit')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * حذف حساب المستخدم
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة كلمة المرور الحالية
     * - تسجيل خروج المستخدم
     * - حذف الحساب من قاعدة البيانات
     * - إلغاء الجلسة الحالية (session)
     * - إنشاء توكن جديد للحماية من CSRF
     * - إعادة التوجيه إلى الصفحة الرئيسية
     * 
     * ملاحظة: سيتم حذف جميع البيانات المرتبطة بالمستخدم (حسب إعدادات cascade في قاعدة البيانات)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // التحقق من كلمة المرور
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // تسجيل الخروج
        Auth::logout();

        // حذف المستخدم
        $user->delete();

        // إلغاء الجلسة
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
