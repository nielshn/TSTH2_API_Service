<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class TransactionTypeRepository
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url') . '/transaction-types';
    }

    public function getAll($token)
    {
        return Http::withToken($token)->get($this->baseUrl);
    }

    public function getById($id, $token)
    {
        return Http::withToken($token)->get("{$this->baseUrl}/{$id}");
    }

    public function store($token, array $data)
    {
        return Http::withToken($token)->post($this->baseUrl, $data);
    }

    public function update($token, $id, array $data)
    {
        return Http::withToken($token)->put("{$this->baseUrl}/{$id}", $data);
    }

    public function delete($token, $id)
    {
        return Http::withToken($token)->delete("{$this->baseUrl}/{$id}");
    }
}
