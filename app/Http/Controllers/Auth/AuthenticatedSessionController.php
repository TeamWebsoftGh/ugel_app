<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Auth\UserDevice;
use App\Models\Common\Email;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
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

        $user = Auth::user();
        //$this->enforceSingleSession($user);
        $this->updateLoginMetadata($user, $request);
        $this->handleNewDeviceDetection($user, $request);

        log_activity('Login Successful with Login ID: ' . $user->username, $user, 'login-successful');

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Enforce single session per user by deleting old session.
     */
    protected function enforceSingleSession($user): void
    {
        if ($user->session_id) {
            DB::table('sessions')->where('id', $user->session_id)->delete();
        }

        $user->session_id = Session::getId();
    }

    /**
     * Update login timestamp and IP address.
     */
    protected function updateLoginMetadata($user, Request $request): void
    {
        $user->timestamps = false;
        $user->last_login_date = Carbon::now()->toDateTimeString();
        $user->last_login_ip = $request->ip();
        $user->save();
    }

    /**
     * Detect new device and log if not previously seen.
     */
    protected function handleNewDeviceDetection($user, Request $request): void
    {
        $ip = $request->ip();
        $agent = $request->userAgent();
        $device = substr($agent, 0, 255);

        $existingDevice = UserDevice::where('user_id', $user->id)
            ->where('device', $device)
            ->where('ip_address', $ip)
            ->first();

        if (!$existingDevice) {
            $location = $this->getLocationFromIp($ip);

            UserDevice::create([
                'user_id' => $user->id,
                'device' => $device,
                'ip_address' => $ip,
                'user_agent' => $agent,
                'location' => $location,
                'is_verified' => true,
            ]);

            $this->sendNewDeviceAlert($user, $device, $ip, $location);
        }
    }

    /**
     * Get geolocation based on IP.
     */
    protected function getLocationFromIp(string $ip): string
    {
        try {
            $geo = Http::timeout(5)->get("http://ip-api.com/json/{$ip}")->json();
            return ($geo['city'] ?? 'Unknown') . ', ' . ($geo['country'] ?? 'Unknown');
        } catch (\Exception $e) {
            return 'Unknown Location';
        }
    }

    /**
     * Create email alert using custom email model.
     */
    protected function sendNewDeviceAlert($user, string $device, string $ip, string $location): void
    {
        Email::create([
            'emailable_type' => get_class($user),
            'emailable_id' => $user->id,
            'to' => $user->email,
            'recipient_name' => $user->fullname ?? $user->name ?? 'User',
            'subject' => 'New Device Login Detected',
            'line_1' => 'We noticed a login from a new device.',
            'line_2' => "Device: {$device}",
            'line_3' => "IP Address: {$ip}",
            'line_4' => "Location: {$location}",
            'line_5' => 'If this was you, no further action is needed. If this wasn’t you, please reset your password immediately.',
            'company_id' => company_id() ?? 1,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            $user->session_id = null;
            $user->save();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Determine login field (email or username).
     */
    public function username(): string
    {
        $field = (filter_var(request()->username, FILTER_VALIDATE_EMAIL) || !request()->username)
            ? 'email' : 'username';
        request()->merge([$field => request()->username]);

        return $field;
    }
}
