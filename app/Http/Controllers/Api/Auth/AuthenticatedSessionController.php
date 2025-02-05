<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\MobileController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends MobileController
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->authenticate();

        $token = auth()->user()->createToken('ApiToken')->plainTextToken;
        $data = [
            "token" => $token,
            "user" => new UserResource(auth()->user())
        ];

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

        return $this->sendResponse("000", "Login Successful.", $data);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        // Revoke all tokens...
        auth()->user()->tokens()->delete();

        return $this->sendResponse("000", "Logout Successful.", null);
    }

    public function username()
    {
        $field = (filter_var(request()->username, FILTER_VALIDATE_EMAIL) || !request()->username) ? 'email' : 'username';
        request()->merge([$field => request()->username]);
        return $field;
    }
}
