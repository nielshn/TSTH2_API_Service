<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $token = session('token');
        if (!$token) {
            Log::info('No token found, redirecting to login');
            return redirect()->route('auth.login')->with('error', 'Unauthorized');
        }

        $userInfo = session('user_info') ?? $this->authService->getUserInfo();
        if (!$userInfo) {
            Log::warning('Failed to get user info, access denied');
            return response()->view('error.403', [], 403);
        }

        $permissions = $userInfo['permissions'] ?? [];
        if (!in_array($permission, $permissions)) {
            Log::warning('Permission denied', ['permission' => $permission, 'user_id' => $userInfo['id'] ?? null]);
            return response()->view('error.403', [], 403);
        }

        $request->merge(['auth_user' => $userInfo]);
        return $next($request);
    }
}