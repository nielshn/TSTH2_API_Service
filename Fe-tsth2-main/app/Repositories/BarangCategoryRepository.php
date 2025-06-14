<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class BarangCategoryRepository
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url') . '/barang-categories';
    }

    public function getAll($token)
    {
        return Http::withToken($token)->get($this->baseUrl);
    }

    public function getById($id, $token)
    {
        return Http::withToken($token)->get("{$this->baseUrl}/{$id}");
    }


    public function store(array $data, $token)
    {
        return Http::withToken($token)->post($this->baseUrl, $data);
    }

    public function update($id, array $data, $token)
    {
        return Http::withToken($token)->put("{$this->baseUrl}/{$id}", $data);
    }

    public function delete($id, $token)
    {
        return Http::withToken($token)->delete("{$this->baseUrl}/{$id}");
    }
}
