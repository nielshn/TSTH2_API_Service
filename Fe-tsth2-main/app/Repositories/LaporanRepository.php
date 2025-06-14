<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class LaporanRepository
{
      protected $baseUrl;
      protected $baseUr2;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url') . '/laporan-stok';
        $this->baseUr2 = config('api.base_url') . '/laporan-transaksi';

    }

    public function exportLaporanTransaksiPDF($token)
    {
        $response = Http::withToken($token)->get($this->baseUr2.'/export-pdf');
// dd($response);
        if ($response->successful()) {
            return [
                'status' => true,
                'pdf_url' => $response->json()['pdf_url'] ?? null,
            ];
        }

        return ['status' => false];
    }

    public function exportLaporanStokPDF($token)
    {
        $response = Http::withToken($token)->get($this->baseUrl.'/pdf');

        if ($response->successful()) {
            return [
                'status' => true,
                'pdf_url' => $response->json()['pdf_url'] ?? null,
            ];
        }

        return ['status' => false];
    }
    public function exportLaporanTransaksiPDFByType($token, $typeId)
    {
        $url = config('api.base_url') . '/laporan-transaksi/export-pdf/' . $typeId;

        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            return [
                'status' => true,
                'pdf_url' => $response->json()['pdf_url'] ?? null,
            ];
        }

        return ['status' => false];
    }

}
