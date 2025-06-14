<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    protected $apiBaseUrl;
    protected $token;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url'); // Pastikan URL API sudah benar
        $this->token = session('token'); // Mendapatkan token dari session
    }

    protected function withToken()
    {
        return Http::withToken($this->token); // Menambahkan token pada header request
    }

    public function getPermissionsByRole($role)
    {
        // Mengambil response dari API
        $response = $this->withToken()->get("{$this->apiBaseUrl}/permission?role={$role}");

        // Debugging: Periksa respons API

        // Cek status kode HTTP respons
        if ($response->failed()) {
            Log::error('Failed to fetch permissions for role: ' . $role);
            return [];
        }

        // Ambil data JSON, pastikan kita mendapatkan 'permissions'
        $permissions = $response->json('permissions'); // Pastikan 'permissions' yang Anda ambil dari response

        // Cek apakah permissions kosong
        if (empty($permissions)) {
            Log::error('No permissions found for role: ' . $role);
        }

        return $permissions ?? [];
    }


    public function togglePermission($payload)
    {
        return $this->withToken()->post("{$this->apiBaseUrl}/toggle-permission", $payload);
    }
}
