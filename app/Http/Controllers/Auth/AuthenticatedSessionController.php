<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        //saving login timestamps and ip after login
        $user = Auth::user();
        $user->timestamps = false;
        $user->last_login_date = Carbon::now()->toDateTimeString();
        $user->last_login_ip = $request->ip();
        $user->save();

        //Audit Trail
        $auditMessage = 'Login Successful with Login ID: '.$user->username;
        $logAction = 'login-successful';
        log_activity($auditMessage, $user, $logAction);

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function username()
    {
        $field = (filter_var(request()->username, FILTER_VALIDATE_EMAIL) || !request()->username) ? 'email' : 'username';
        request()->merge([$field => request()->username]);
        return $field;
    }
}
