<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RoleService
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

    public function count()
    {
        $response = $this->withToken()->get("{$this->apiBaseUrl}/roles");

        if ($response->successful()) {
            $data = $response->json('data') ?? [];
            return count($data);
        }

        return 0;
    }

    public function getAllRoles()
    {
        return $this->withToken()->get("{$this->apiBaseUrl}/roles");
    }

    public function createRole($data)
    {
        return $this->withToken()->post("{$this->apiBaseUrl}/roles", $data);
    }

    public function getRole($id)
    {
        return $this->withToken()->get("{$this->apiBaseUrl}/roles/{$id}");
    }

    public function updateRole($id, $data)
    {
        return $this->withToken()->put("{$this->apiBaseUrl}/roles/{$id}", $data);
    }

    public function deleteRole($id)
    {
        return $this->withToken()->delete("{$this->apiBaseUrl}/roles/{$id}");
    }
}
