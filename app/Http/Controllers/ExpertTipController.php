<?php

namespace App\Http\Controllers;

use App\Models\ExpertTip;
use Illuminate\Http\Request;

/**
 * كونترولر نصائح الخبراء - Expert Tip Controller
 * 
 * العلاقات:
 * - ExpertTip (النصيحة): belongsTo User (الخبير)
 * 
 * هذا الكونترولر يتيح للخبراء إضافة وتعديل وحذف النصائح الزراعية
 */
class ExpertTipController extends Controller
{
    /**
     * إضافة نصيحة جديدة من الخبير
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن المستخدم الحالي هو خبير (role = expert)
     * - التحقق من صحة البيانات المدخلة (العنوان والمحتوى)
     * - إنشاء نصيحة جديدة مرتبطة بالخبير الحالي
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * العلاقة: ExpertTip belongsTo User (Expert)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من صلاحية الخبير
        if (auth()->user()->role !== 'expert') {
            abort(403);
        }

        // التحقق من البيانات
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // إنشاء النصيحة
        auth()->user()->expertTips()->create($request->all());

        return redirect()->back()->with('success', 'تم إضافة النصيحة بنجاح');
    }

    /**
     * تعديل نصيحة موجودة
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن المستخدم هو خبير وصاحب النصيحة
     * - التحقق من صحة البيانات المدخلة
     * - تحديث النصيحة
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * @param Request $request
     * @param ExpertTip $expertTip النصيحة المراد تعديلها
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ExpertTip $expertTip)
    {
        // التحقق من الصلاحية والملكية
        if (auth()->user()->role !== 'expert' || $expertTip->user_id !== auth()->id()) {
            abort(403);
        }

        // التحقق من البيانات
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // تحديث النصيحة
        $expertTip->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث النصيحة بنجاح');
    }

    /**
     * حذف نصيحة
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن المستخدم هو خبير وصاحب النصيحة
     * - حذف النصيحة من قاعدة البيانات
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * @param ExpertTip $expertTip النصيحة المراد حذفها
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ExpertTip $expertTip)
    {
        // التحقق من الصلاحية والملكية
        if (auth()->user()->role !== 'expert' || $expertTip->user_id !== auth()->id()) {
            abort(403);
        }

        // حذف النصيحة
        $expertTip->delete();

        return redirect()->back()->with('success', 'تم حذف النصيحة بنجاح');
    }
}
