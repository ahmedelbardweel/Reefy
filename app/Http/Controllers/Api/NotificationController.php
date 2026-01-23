<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;

/**
 * كونترولر الإشعارات API - Notification API Controller
 * 
 * هذا الكونترولر يوفر واجهات API لإدارة إشعارات المستخدم
 */
class NotificationController extends ApiController
{
    /**
     * عرض قائمة الإشعارات
     * 
     * تقوم هذه الدالة بـ:
     * - جلب إشعارات المستخدم الحالي
     * - إرجاع الإشعارات (المقروءة وغير المقروءة)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->with('task.crop')
            ->latest()
            ->paginate(15);
        
        return $this->successResponse($notifications, 'Notifications retrieved successfully.');
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return $this->successResponse([], 'All notifications marked as read.');
    }

    /**
     * تحديد إشعار واحد كمقروء
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return $this->successResponse([], 'Notification marked as read.');
        }

        return $this->errorResponse('Notification not found.');
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        return $this->successResponse(['count' => $count], 'Unread count retrieved.');
    }
}
