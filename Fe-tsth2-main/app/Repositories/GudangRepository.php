<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class GudangRepository
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url') . '/gudangs';
    }

    public function getAll($token)
    {
        return Http::withToken($token)->get($this->baseUrl);
    }

    public function getById($id, $token)
    {
        return Http::withToken($token)->get("{$this->baseUrl}/{$id}");
    }

    public function getOperators($token)
    {
        $url = config('api.base_url') . '/user/operators';
        return Http::withToken($token)->get($url);
    }


    public function store(array $data, $token)
    {
        $response = Http::withToken($token)->post($this->baseUrl, $data);
        // Periksa apakah response API sukses
        if ($response->successful()) {
            return $response->json();
        } else {
            throw new \Exception('Error creating Gudang: ' . $response->body());
        }
    }

    public function update($id, array $data, $token)
    {
        $response = Http::withToken($token)->put("{$this->baseUrl}/{$id}", $data);

        // Periksa apakah response API sukses
        if ($response->successful()) {
            return $response->json();
        } else {
            // Tangani jika request gagal
            throw new \Exception('Error updating Gudang: ' . $response->body());
        }
    }

    public function delete($id, $token)
    {
        return Http::withToken($token)->delete("{$this->baseUrl}/{$id}");
    }
}
