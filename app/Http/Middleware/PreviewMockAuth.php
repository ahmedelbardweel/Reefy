<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class PreviewMockAuth
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
        if (!auth()->check()) {
            $dummyUser = new User([
                'id' => 9999,
                'name' => 'Designer Preview',
                'email' => 'designer@preview.com',
                'role' => 'farmer'
            ]);
            $dummyUser->setRelation('crops', collect([]));
            auth()->setUser($dummyUser);
        }

        return $next($request);
    }
}
