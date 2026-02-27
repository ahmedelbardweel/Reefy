<?php

namespace App\Providers;

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
    }
}
