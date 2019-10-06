<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRegistrationsAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!config('auth.misc.users.allow_registration')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
