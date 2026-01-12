<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;

/**
 * كونترولر الإشعارات - Notification Controller
 * 
 * العلاقات:
 * - Notification (الإشعار): belongsTo User (المستخدم المستلم)
 * - Notification: belongsTo Task (المهمة المرتبطة - اختياري)
 * 
 * أنواع الإشعارات (types):
 * - task_due: إشعار باستحقاق مهمة
 * - advice: نصيحة من خبير
 * - system: إشعار نظام
 */
class NotificationController extends Controller
{
    /**
     * عرض قائمة جميع إشعارات المستخدم الحالي
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع إشعارات المستخدم الحالي
     * - تحميل علاقة المهمة والمحصول المرتبط بالإشعار
     * - ترتيب الإشعارات من الأحدث للأقدم
     * - تقسيم النتائج إلى صفحات (20 إشعار في كل صفحة)
     * - عرض صفحة قائمة الإشعارات
     * 
     * العلاقة: Notification belongsTo User, belongsTo Task
     *          Task belongsTo Crop
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->with('task.crop')
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * تحديد إشعار واحد كمقروء
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن الإشعار يخص المستخدم الحالي
     * - تحديث حالة الإشعار إلى مقروء (is_read = true)
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * @param Notification $notification الإشعار المراد تحديده كمقروء
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Notification $notification)
    {
        // التحقق من الصلاحية
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        // تحديد كمقروء
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * تحديد جميع إشعارات المستخدم كمقروءة
     * 
     * تقوم هذه الدالة بـ:
     * - تحديث حالة جميع الإشعارات غير المقروءة للمستخدم الحالي
     * - تغيير is_read من false إلى true
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة
     * 
     * تقوم هذه الدالة بـ:
     * - حساب عدد الإشعارات غير المقروءة للمستخدم الحالي
     * - إرجاع العدد (يستخدم لعرض الشارة على أيقونة الجرس)
     * 
     * @return int عدد الإشعارات غير المقروءة
     */
    public function getUnreadCount()
    {
        return Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    /**
     * الحصول على الإشعارات غير المقروءة (لطلبات AJAX)
     * 
     * تقوم هذه الدالة بـ:
     * - جلب آخر 5 إشعارات غير مقروءة للمستخدم الحالي
     * - تحميل علاقة المهمة والمحصول
     * - إرجاع JSON response يحتوي على:
     *   * count: عدد الإشعارات غير المقروءة
     *   * notifications: قائمة الإشعارات
     * 
     * تُستخدم للتحديث الآلي للإشعارات (AJAX polling)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnread()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->with('task.crop')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * الحصول على المهام القادمة لجدولة التذكيرات في JavaScript
     * 
     * تقوم هذه الدالة بـ:
     * - جلب المهام:
     *   * التي تخص المستخدم الحالي (عبر المحاصيل)
     *   * ذات الحالة 'pending' (قيد الانتظار)
     *   * المستحقة خلال اليومين القادمين
     * - تحميل علاقة المحصول
     * - تحويل البيانات إلى تنسيق مناسب للـ JavaScript:
     *   * id: رقم المهمة
     *   * title: عنوان المهمة
     *   * crop_name: اسم المحصول
     *   * due_date: تاريخ الاستحقاق
     *   * reminder_time: وقت التذكير
     *   * reminder_datetime: التاريخ والوقت الكامل
     *   * reminder_timestamp: الطابع الزمني (لا يعتمد على المنطقة الزمنية)
     * - إرجاع JSON response
     * 
     * تُستخدم لإنشاء تذكيرات تلقائية في المتصفح
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingTasks()
    {
        $tasks = \App\Models\Task::whereHas('crop', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', now()->toDateString())
            ->whereDate('due_date', '<=', now()->addDays(2)->toDateString())
            ->with('crop')
            ->get()
            ->map(function($task) {
                $dateTime = \Carbon\Carbon::parse($task->due_date->format('Y-m-d') . ' ' . $task->reminder_time);
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'crop_name' => $task->crop->name,
                    'due_date' => $task->due_date->format('Y-m-d'),
                    'reminder_time' => $task->reminder_time,
                    'reminder_datetime' => $dateTime->format('Y-m-d H:i:s'),
                    'reminder_timestamp' => $dateTime->timestamp, // الطابع الزمني Unix
                ];
            });

        return response()->json(['tasks' => $tasks]);
    }
}
