<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login(array $credentials): array
    {
        try {
            $response = $this->repository->login($credentials);
            $data = $response->json();

            if ($response->successful() && isset($data['response_code']) && $data['response_code'] == 200) {
                Session::put('token', $data['data']['token']);
                Session::put('user', $data['data']['user']);
                Session::put('permissions', $data['data']['permissions'] ?? []);
                Session::put('roles', $data['data']['roles'] ?? []);

                return ['success' => true, 'user' => $data['data']['user']];
            }

            return ['success' => false, 'message' => $data['message'] ?? 'Login gagal.'];
        } catch (\Exception $e) {
            Log::error('AuthService Login Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan saat menghubungi server.'];
        }
    }

    public function logout(): bool
    {
        $token = Session::get('token');
        if ($token) {
            $response = $this->repository->logout($token);
            if ($response->successful()) {
                Session::flush();
                return true;
            }
        }
        return false;
    }
    public function getUserInfo(): ?array
{
    $token = session('token');
    if (!$token) return null;

    $response = $this->repository->getUserInfo($token);
    if ($response->successful()) {
        return $response->json()['data'];
    }

    return null;
}
}
