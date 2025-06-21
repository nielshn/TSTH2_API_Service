<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionRepository
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url') . '/transactions';
    }

    public function createTransaction(array $payload, $token): array
    {
        try {
            $response = Http::withToken($token)->post($this->baseUrl, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Terjadi kesalahan saat menyimpan transaksi.',
            ];
        } catch (\Exception $e) {
            Log::error('API CreateTransaction Error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return [
                'success' => false,
                'message' => 'Gagal terhubung ke API transaksi.',
            ];
        }
    }

    public function getAll($token)
    {
        return Http::withToken($token)->get($this->baseUrl);
    }

    public function checkAndParseBarang($token, string $kode)
    {
        $response = Http::withToken($token)->get("{$this->baseUrl}/check-barcode/{$kode}");

        if ($response->successful() && $response->json('success')) {
            return [
                'success' => true,
                'data' => $response->json('data'),
            ];
        }

        return [
            'success' => false,
            'message' => $response->json('message') ?? 'Barang tidak ditemukan.',
        ];
    }
}
