<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class AuthRepository
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    public function login(array $credentials)
    {
        return Http::post("{$this->apiBaseUrl}/auth/login", $credentials);

    }
    public function getUserInfo(string $token)
{
    return Http::withToken($token)->get("{$this->apiBaseUrl}/auth/user");
}
    public function logout(string $token)
    {
        return Http::withToken($token)->post("{$this->apiBaseUrl}/auth/logout");
    }
}
