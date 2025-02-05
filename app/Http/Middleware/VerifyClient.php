<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard()->check()&&(empty(Auth::guard()->user()->phone_verified_at)))
        {
            abort(403, "Verify Phone number to continue.");
        }
//
//        if (Auth::guard()->check()&&(empty(Auth::guard()->user()->email_verified_at))) {
//            abort(403, "Verify ghana card details to continue.");
//        }

        return $next($request);
    }
}
