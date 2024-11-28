<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }
        else{
            return redirect('welcome');
        }

        abort(403, 'Unauthorized');
    }
}

