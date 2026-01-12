<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * كونترولر لوحة التحكم الرئيسية - Dashboard Controller
 * 
 * هذا الكونترولر مسؤول عن توجيه المستخدمين إلى لوحة التحكم المناسبة حسب دورهم
 * 
 * الأدوار (Roles):
 * - admin: المدير
 * - expert: الخبير الزراعي
 * - farmer: المزارع (الدور الافتراضي)
 */
class DashboardController extends Controller
{
    /**
     * توجيه المستخدم إلى لوحة التحكم المناسبة حسب دوره
     * 
     * تقوم هذه الدالة بـ:
     * - فحص دور (role) المستخدم الحالي
     * - إعادة التوجيه إلى لوحة التحكم المناسبة:
     *   * admin -> لوحة تحكم المدير
     *   * expert -> لوحة تحكم الخبير
     *   * farmer (أو أي دور آخر) -> لوحة تحكم المزارع
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = auth()->user();

        // توجيه المدير
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        // توجيه الخبير
        elseif ($user->role === 'expert') {
            return redirect()->route('expert.dashboard');
        }

        // التوجيه الافتراضي للمزارعين
        return redirect()->route('farmer.dashboard');
    }
}
