<?php

namespace App\Http\Middleware;

use Closure;

class EmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->verified <> 1) {
            return redirect()->guest(route('verify.email'));
        }

        return $next($request);
    }
}
