<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // التأكد من وجود رابط التخزين (Fix for Render images)
        if (app()->environment('production')) {
            $storageLink = public_path('storage');
            if (file_exists($storageLink) && !is_link($storageLink)) {
                // If it's a folder but not a link, remove it to allow link creation
                $this->app->make('files')->deleteDirectory($storageLink);
            }

            if (!file_exists($storageLink)) {
                try {
                    $this->app->make('files')->link(storage_path('app/public'), $storageLink);
                } catch (\Exception $e) {
                    // Fail silently
                }
            }
        }


View::composer('*', function ($view) {
    if(auth()->check()){
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // أو get() حسب ما تحتاج
        $unreadCount = $notifications->where('is_read', false)->count();
    } else {
        $notifications = collect(); // مجموعة فارغة
        $unreadCount = 0;
    }

    $view->with(compact('notifications', 'unreadCount'));
});
    }
     }

