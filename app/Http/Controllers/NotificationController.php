<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
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
     * Mark a single notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Get unread notifications count (for bell badge).
     */
    public function getUnreadCount()
    {
        return Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get unread notifications (for AJAX polling).
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
     * Get upcoming tasks for JavaScript scheduling.
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
                    'reminder_timestamp' => $dateTime->timestamp, // Unix timestamp (UTC-independent)
                ];
            });

        return response()->json(['tasks' => $tasks]);
    }
}
