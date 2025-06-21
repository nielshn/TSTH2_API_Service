<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class SatuanRepository
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url') . '/satuans';
    }


    public function getAll($token)
    {
        return Http::withToken($token)->get($this->baseUrl)->json('data');
    }

    public function store($token, $data)
    {
        return Http::withToken($token)->post($this->baseUrl, $data)->json();
    }

    public function update($token, $id, $data)
    {
        return Http::withToken($token)->put("{$this->baseUrl}/{$id}", $data)->json();
    }

    public function delete($token, $id)
    {
        return Http::withToken($token)->delete("{$this->baseUrl}/{$id}")->json();
    }
}
