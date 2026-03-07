<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * كونترولر الملف الشخصي للمزارع - Farmer Profile Controller
 * 
 * العلاقات:
 * - FarmerProfile (ملف المزارع): belongsTo User
 * 
 * هذا الكونترولر يدير الملف الشخصي التفصيلي للمزارع بما في ذلك:
 * - البيانات الشخصية (رقم الهوية، السيرة الذاتية، المدينة، الدولة)
 * - وثائق التحقق (صورة الهوية، وثيقة المزرعة)
 */
class FarmerProfileController extends Controller
{
    /**
     * عرض صفحة تعديل الملف الشخصي للمزارع
     * 
     * تقوم هذه الدالة بـ:
     * - التأكد من وجود ملف شخصي للمزارع أو إنشاءه إذا لم يكن موجوداً
     * - عرض صفحة تعديل الملف الشخصي
     * 
     * العلاقة: User hasOne FarmerProfile
     * 
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // التأكد من وجود الملف الشخصي أو إنشاءه
        $profile = auth()->user()->farmerProfile()->firstOrCreate([]);
        return view('farmer.profile.edit', compact('profile'));
    }

    /**
     * تحديث الملف الشخصي للمزارع
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات المدخلة
     * - التأكد من وجود الملف الشخصي أو إنشاءه
     * - رفع وحفظ صورة الهوية إن وجدت
     * - رفع وحفظ وثيقة المزرعة إن وجدت
     * - تحديث البيانات الشخصية (رقم الهوية، السيرة، المدينة، الدولة)
     * - تفعيل حساب المستخدم تلقائياً بعد إكمال الملف الشخصي (status = active)
     * - إعادة التوجيه إلى لوحة تحكم المزارع مع رسالة نجاح
     * 
     * الوثائق المطلوبة للتحقق:
     * - national_id_image: صورة الهوية الوطنية
     * - farm_document_image: وثيقة ملكية أو تشغيل المزرعة
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'national_id_image' => 'nullable|image',
            'farm_document_image' => 'nullable|image',
            'national_id' => 'required|string',
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        // التأكد من وجود الملف الشخصي
        $profile = $user->farmerProfile()->firstOrCreate([]);

        // رفع وحفظ صورة الهوية
        if ($request->hasFile('national_id_image')) {
            $path = $request->file('national_id_image')->store('verification', 'public');
            $profile->national_id_image = $path;
        }

        // رفع وحفظ وثيقة المزرعة
        if ($request->hasFile('farm_document_image')) {
            $path = $request->file('farm_document_image')->store('verification', 'public');
            $profile->farm_document_image = $path;
        }

        // تحديث البيانات الشخصية
        $profile->national_id = $request->national_id;
        $profile->bio = $request->bio;
        $profile->city = $request->city;
        $profile->country = $request->country;
        $profile->save();

        // تفعيل الحساب تلقائياً بعد إكمال الملف الشخصي
        $user->status = 'active';
        $user->save();

        return redirect()->route('farmer.dashboard')->with('success', 'تم تحديث الملف الشخصي وتفعيل الحساب بنجاح!');
    }
    
    /**
     * عرض الملف الشخصي العام لمزارع معين
     * 
     * تقوم هذه الدالة بـ:
     * - جلب بيانات المستخدم مع علاقاته (الملف الشخصي والمحاصيل)
     * - التأكد من أن المستخدم هو مزارع
     * - عرض صفحة الملف الشخصي العام
     * 
     * العلاقات: User hasOne FarmerProfile, User hasMany Crop
     * 
     * @param int $id رقم المستخدم (المزارع)
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // جلب المستخدم مع علاقاته
        $user = \App\Models\User::with('farmerProfile', 'crops')->findOrFail($id);
        
        // التأكد من أن المستخدم مزارع
        if ($user->role !== 'farmer') {
            abort(404);
        }

        return view('farmer.profile.show', compact('user'));
    }
}
