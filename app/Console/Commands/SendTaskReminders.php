<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Notification;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for tasks that are due soon or overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for tasks that need reminders...');

        // المهام التي موعدها خلال 24 ساعة القادمة (لم يتم إكمالها)
        $tasksDueSoon = Task::where('status', 'pending')
            ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDay()])
            ->get();

        foreach ($tasksDueSoon as $task) {
            // تحقق من عدم وجود إشعار سابق من نفس النوع لهذه المهمة اليوم
            $existingNotification = Notification::where('task_id', $task->id)
                ->where('type', 'task_due')
                ->whereDate('created_at', Carbon::today())
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $task->crop->user_id,
                    'task_id' => $task->id,
                    'type' => 'task_due',
                    'title' => 'مهمة قريبة',
                    'message' => 'تذكير: مهمة "' . $task->title . '" موعدها خلال 24 ساعة',
                ]);

                $this->info("Reminder created for task: {$task->title}");
            }
        }

        // المهام المتأخرة (لم يتم إكمالها وتجاوز موعدها)
        $tasksOverdue = Task::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->get();

        foreach ($tasksOverdue as $task) {
            // تحقق من عدم وجود إشعار متأخر سابق لهذه المهمة اليوم
            $existingNotification = Notification::where('task_id', $task->id)
                ->where('type', 'task_overdue')
                ->whereDate('created_at', Carbon::today())
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $task->crop->user_id,
                    'task_id' => $task->id,
                    'type' => 'task_overdue',
                    'title' => 'مهمة متأخرة!',
                    'message' => 'تنبيه: مهمة "' . $task->title . '" متأخرة عن موعدها',
                ]);

                $this->info("Overdue reminder created for task: {$task->title}");
            }
        }

        $this->info('Task reminders check completed!');
        return 0;
    }
}
