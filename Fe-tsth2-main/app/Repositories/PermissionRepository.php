<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class PermissionRepository
{
    protected $apiBaseUrl;
    protected $token;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
        $this->token = session('token');
    }

    protected function withToken()
    {
        return Http::withToken($this->token);
    }

    public function getAll()
    {
        return $this->withToken()->get("{$this->apiBaseUrl}/permissions");
    }

    public function togglePermission($data)
    {
        return $this->withToken()->post("{$this->apiBaseUrl}/permissions/toggle", $data);
    }

    public function getByRole($role)
    {
        return $this->withToken()->get("{$this->apiBaseUrl}/permissions/{$role}");
    }
}
