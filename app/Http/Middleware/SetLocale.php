<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for locale in order of priority:
        // 1. Route parameter '{lang}' (e.g., /api/ar/...)
        // 2. URL parameter 'lang' (manual override e.g., ?lang=ar)
        // 3. Custom 'lang' header (API manual override)
        // 4. Session (Web stored preference)
        // 5. Accept-Language header (Browser/Client auto-detection)
        // 6. Config default
        $locale = $request->route('lang')
               ?? $request->input('lang') 
               ?? $request->header('lang') 
               ?? Session::get('locale') 
               ?? $request->header('Accept-Language') 
               ?? config('app.locale');

        if ($locale) {
            // Clean up locale (e.g., 'en-US' or 'ar,en;q=0.9' -> 'en' or 'ar')
            if (str_contains($locale, ',')) {
                $locale = explode(',', $locale)[0];
            }
            if (str_contains($locale, '-')) {
                $locale = explode('-', $locale)[0];
            }

            if (in_array($locale, ['ar', 'en'])) {
                App::setLocale($locale);
                
                // Remove lang from route parameters so it doesn't interfere with controller arguments
                if ($request->route()) {
                    $request->route()->forgetParameter('lang');
                }
                
                // For web requests, persist the locale if it came from a parameter
                if ($request->has('lang') && ! $request->allowsJson()) {
                    Session::put('locale', $locale);
                }
            }
        }

        return $next($request);
    }
}
