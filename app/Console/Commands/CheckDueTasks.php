<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckDueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for tasks that are due today and create notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i');
        
        // Get all pending tasks that are due today at this specific hour
        $dueTasks = Task::where('status', 'pending')
            ->whereDate('due_date', $now->toDateString())
            ->where('reminder_time', '<=', $currentTime)
            ->with('crop.user')
            ->get();

        $notificationsCreated = 0;

        foreach ($dueTasks as $task) {
            // Check if notification already exists
            $exists = Notification::where('task_id', $task->id)
                ->where('type', 'task_due')
                ->exists();

            if (!$exists && $task->crop && $task->crop->user) {
                Notification::create([
                    'user_id' => $task->crop->user_id,
                    'task_id' => $task->id,
                    'title' => 'مهمة تحتاج انتباهك الآن! ⏰',
                    'message' => "المهمة '{$task->title}' للمحصول '{$task->crop->name}' موعدها اليوم الساعة {$task->reminder_time}.",
                    'type' => 'task_due',
                ]);
                $notificationsCreated++;
            }
        }

        $this->info("Created $notificationsCreated task notifications.");

        // Check for OVERDUE tasks (1+ hours past reminder time, still pending)
        $overdueTasks = Task::where('status', 'pending')
            ->whereDate('due_date', '<=', $now->toDateString())
            ->with('crop.user')
            ->get()
            ->filter(function($task) use ($now) {
                $reminderDateTime = \Carbon\Carbon::parse($task->due_date->format('Y-m-d') . ' ' . $task->reminder_time);
                $hoursPassed = $now->diffInHours($reminderDateTime, false);
                return $hoursPassed >= 1; // At least 1 hour overdue
            });

        $overdueNotificationsCreated = 0;

        foreach ($overdueTasks as $task) {
            // Check if overdue notification already exists
            $exists = Notification::where('task_id', $task->id)
                ->where('type', 'task_overdue')
                ->exists();

            if (!$exists && $task->crop && $task->crop->user) {
                Notification::create([
                    'user_id' => $task->crop->user_id,
                    'task_id' => $task->id,
                    'title' => '⚠️ مهمة متأخرة!',
                    'message' => "أنت متأخر عن المهمة '{$task->title}' للمحصول '{$task->crop->name}'! يرجى التنفيذ فوراً.",
                    'type' => 'task_overdue',
                ]);
                $overdueNotificationsCreated++;
            }
        }

        $this->info("Created $overdueNotificationsCreated overdue notifications.");
        return Command::SUCCESS;
    }
}
