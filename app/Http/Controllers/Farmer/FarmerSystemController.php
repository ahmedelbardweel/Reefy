<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

/**
 * كونترولر أنظمة المزارع - Farmer System Controller
 * 
 * العلاقات:
 * - Task: belongsTo Crop
 * - Crop: belongsTo User
 * 
 * هذا الكونترولر يدير صفحات الأنظمة المتخصصة للمزارع:
 * - نظام الري (Irrigation)
 * - نظام المعالجة (Treatment) - التسميد ومكافحة الآفات
 * - نظام الحصاد (Harvesting)
 */
class FarmerSystemController extends Controller
{
    /**
     * عرض صفحة نظام الري
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع مهام الري (type = 'water') لمحاصيل المزارع الحالي
     * - تحميل علاقة المحصول لكل مهمة
     * - ترتيب المهام من الأحدث للأقدم
     * - تقسيم النتائج إلى صفحات (10 مهام في كل صفحة)
     * - حساب إجمالي المياه المستخدمة (من المهام المكتملة فقط)
     * - عرض صفحة نظام الري مع المهام والإحصائيات
     * 
     * الإحصائيات:
     * - totalWater: إجمالي كمية المياه المستخدمة (باللتر)
     * 
     * @return \Illuminate\View\View
     */
    public function irrigation()
    {
        $user = auth()->user();
        $cropIds = $user->crops->pluck('id');

        // جلب مهام الري
        $tasks = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'water')
            ->with('crop')
            ->latest()
            ->paginate(10);

        // حساب إجمالي المياه المستخدمة
        $totalWater = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'water')
            ->where('status', 'completed')
            ->sum('water_amount');

        return view('farmer.systems.irrigation', compact('tasks', 'totalWater'));
    }

    /**
     * عرض صفحة نظام المعالجة (التسميد ومكافحة الآفات)
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع مهام المعالجة لمحاصيل المزارع الحالي:
     *   * fertilizer: التسميد
     *   * pest: مكافحة الآفات
     * - تحميل علاقة المحصول لكل مهمة
     * - ترتيب المهام من الأحدث للأقدم
     * - تقسيم النتائج إلى صفحات (10 مهام في كل صفحة)
     * - عرض صفحة نظام المعالجة
     * 
     * @return \Illuminate\View\View
     */
    public function treatment()
    {
        $user = auth()->user();
        $cropIds = $user->crops->pluck('id');

        // جلب مهام التسميد ومكافحة الآفات
        $tasks = Task::whereIn('crop_id', $cropIds)
            ->whereIn('type', ['fertilizer', 'pest'])
            ->with('crop')
            ->latest()
            ->paginate(10);

        return view('farmer.systems.treatment', compact('tasks'));
    }

    /**
     * عرض صفحة نظام الحصاد
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع مهام الحصاد (type = 'harvest') لمحاصيل المزارع الحالي
     * - تحميل علاقة المحصول لكل مهمة
     * - ترتيب المهام من الأحدث للأقدم
     * - تقسيم النتائج إلى صفحات (10 مهام في كل صفحة)
     * - حساب إجمالي الإنتاج (من المهام المكتملة فقط)
     * - عرض صفحة نظام الحصاد مع المهام والإحصائيات
     * 
     * الإحصائيات:
     * - totalYield: إجمالي كمية الحصاد (حسب وحدة القياس المحددة في كل مهمة)
     * 
     * @return \Illuminate\View\View
     */
    public function harvesting()
    {
        $user = auth()->user();
        $cropIds = $user->crops->pluck('id');

        // جلب مهام الحصاد
        $tasks = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'harvest')
            ->with('crop')
            ->latest()
            ->paginate(10);

        // حساب إجمالي الإنتاج
        $totalYield = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'harvest')
            ->where('status', 'completed')
            ->sum('harvest_quantity');

        return view('farmer.systems.harvesting', compact('tasks', 'totalYield'));
    }
    /**
     * تصدير سجلات الحصاد بصيغة CSV
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportHarvesting()
    {
        $user = auth()->user();
        $cropIds = $user->crops->pluck('id');

        $tasks = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'harvest')
            ->where('status', 'completed')
            ->with('crop')
            ->latest()
            ->get();

        $filename = "harvest_report_" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['المحصول', 'تاريخ الحصاد', 'الكمية', 'الوحدة', 'درجة الجودة'];

        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for RTL/Arabic support in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                fputcsv($file, [
                    $task->crop->name,
                    $task->updated_at->format('Y-m-d'),
                    $task->harvest_quantity,
                    $task->harvest_unit,
                    $task->quality_grade ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
