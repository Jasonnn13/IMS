<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Level
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user has a level less than 2, they should not access the /level2 route
        if (Auth::check() && Auth::user()->level < 2 && $request->route()->named('level2')) {
            return redirect()->route('level');
        }

        // If the user has a level of 2 or greater, they should be redirected to /level2
        if (Auth::check() && Auth::user()->level >= 2 && !$request->route()->named('level2')) {
            return redirect()->route('level2');
        }

        return $next($request);
    }
}
