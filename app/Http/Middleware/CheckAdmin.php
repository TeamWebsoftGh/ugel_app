<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
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
        $company_id = company_id();
        if (is_admin())
        {
            return $next($request);
        } // if employee
        elseif (user()->role_users_id == 2)
        {
            return route('employee.dashboard', ['company_id' => $company_id]);
        } //if client
    }
}
