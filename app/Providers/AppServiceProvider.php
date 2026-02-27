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
        if (!file_exists(public_path('storage'))) {
            try {
                $this->app->make('files')->link(storage_path('app/public'), public_path('storage'));
            } catch (\Exception $e) {
                // Ignore errors if link already exists or cannot be created
            }
        }
    }
}
