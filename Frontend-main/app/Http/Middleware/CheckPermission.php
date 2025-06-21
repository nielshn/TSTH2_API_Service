<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

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
            abort(401, 'Unauthorized');
        }

        $userInfo = $this->authService->getUserInfo($token);

        if (!$userInfo) {
            abort(403, 'Permission denied.');
        }

        $permissions = $userInfo['permissions'] ?? [];

        if (!in_array($permission, $permissions)) {
            return response()->view('error.403', [], 403);
        }

        $request->merge(['auth_user' => $userInfo]);

        return $next($request);
    }
}
