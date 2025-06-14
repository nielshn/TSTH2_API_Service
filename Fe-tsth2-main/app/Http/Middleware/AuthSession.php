<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

class AuthSession
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada token di sesi
        $token = Session::get('token');
        if (!$token) {
            Log::info('No session token found, redirecting to login', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);
            Session::forget(['token', 'user_info']);
            return redirect()->route('auth.login')->with('error', 'Please log in to continue.');
        }

        // Validasi token dengan BE (dengan caching)
        $userInfo = Session::get('user_info');
        if (!$userInfo) {
            $userInfo = $this->authService->getUserInfo();
            if (!$userInfo) {
                Log::warning('Invalid or expired token, redirecting to login', [
                    'token' => substr($token, 0, 10) . '...',
                    'url' => $request->fullUrl(),
                ]);
                Session::forget(['token', 'user_info']);
                return redirect()->route('auth.login')->with('error', 'Session expired. Please log in again.');
            }
            Session::put('user_info', $userInfo);
            Log::info('User info fetched and cached', ['user_id' => $userInfo['id'] ?? null]);
        }

        // Tambahkan user info ke request untuk digunakan oleh middleware lain
        $request->merge(['auth_user' => $userInfo]);

        return $next($request);
    }
}