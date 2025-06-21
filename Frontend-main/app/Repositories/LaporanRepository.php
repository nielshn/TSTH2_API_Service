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

    public function exportLaporanStokPDF($token)
    {
        $response = Http::withToken($token)->get($this->baseUrl . '/pdf');

        if ($response->successful()) {
            return [
                'status' => true,
                'pdf_url' => $response->json()['pdf_url'] ?? null,
            ];
        }

        return ['status' => false];
    }

public function exportLaporanStokExcel($token)
{
    $url = config('api.base_url') . '/laporan-stok/excel';
   $response = Http::withToken($token)->get($this->baseUrl . '/excel');

    if ($response->successful()) {
        return [
            'status' => true,
            'excel_url' => $response->json()['excel_url'] ?? null,
        ];
    }

    return ['status' => false];
}
    public function exportLaporanTransaksiPDF($token, $startDate = null, $endDate = null)
    {
        $params = [];
        if ($startDate) $params['transaction_date_start'] = $startDate;
        if ($endDate) $params['transaction_date_end'] = $endDate;

        $response = Http::withToken($token)->get($this->baseUr2 . '/export-pdf', $params);

        if ($response->successful()) {
            return [
                'status' => true,
                'pdf_url' => $response->json()['pdf_url'] ?? null,
            ];
        }

        return ['status' => false];
    }

    public function exportLaporanTransaksiPDFByType($token, $typeId, $startDate = null, $endDate = null)
    {
        $params = [];
        if ($startDate) $params['transaction_date_start'] = $startDate;
        if ($endDate) $params['transaction_date_end'] = $endDate;

        $url = config('api.base_url') . '/laporan-transaksi/export-pdf/' . $typeId;
        $response = Http::withToken($token)->get($url, $params);

        if ($response->successful()) {
            return [
                'status' => true,
                'pdf_url' => $response->json()['pdf_url'] ?? null,
            ];
        }

        return ['status' => false];
    }
    public function exportLaporanTransaksiExcel($token, $startDate = null, $endDate = null)
{
    $params = [];
    if ($startDate) $params['transaction_date_start'] = $startDate;
    if ($endDate) $params['transaction_date_end'] = $endDate;

    $response = Http::withToken($token)->get($this->baseUr2 . '/export-excel', $params);

    if ($response->successful()) {
        return [
            'status' => true,
            'excel_url' => $response->json()['excel_url'] ?? null,
        ];
    }

    return ['status' => false];
}

public function exportLaporanTransaksiExcelByType($token, $typeId, $startDate = null, $endDate = null)
{
    $params = [];
    if ($startDate) $params['transaction_date_start'] = $startDate;
    if ($endDate) $params['transaction_date_end'] = $endDate;

    $url = config('api.base_url') . '/laporan-transaksi/export-excel/' . $typeId;
    $response = Http::withToken($token)->get($url, $params);

    if ($response->successful()) {
        return [
            'status' => true,
            'excel_url' => $response->json()['excel_url'] ?? null,
        ];
    }

    return ['status' => false];
}
}
