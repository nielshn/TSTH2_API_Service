<?php

namespace App\Services;

use App\Repositories\BarangRepository;

class BarangService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BarangRepository();
    }

    // In app/Services/BarangService.php
    public function getAllBarang()
    {
        $token = session('token');
        $barangs = $this->repository->getAll($token);
        $qrCodeBaseUrl = rtrim(config('api.qr_code'), '/') . '/qr_code/';

        foreach ($barangs as &$barang) {
            $barang['qr_code_url'] = $qrCodeBaseUrl . $barang['barang_kode'] . '.png'; // Default to png
        }

        return $barangs;
    }
    public function countbarang()
    {
        $token = session('token');
        return count($this->repository->getAll($token));
    }
    public function getBarangById($id)
    {
        $token = session('token');
        return $this->repository->getById($id, $token);
    }

    public function createBarang(array $data)
    {
        $token = session('token');
        return $this->repository->create($data, $token);
    }
    public function updateBarang($id, array $data)
    {
        $token = session('token');
        return $this->repository->update($id, $data, $token);
    }

    public function deleteBarang($id)
    {
        $token = session('token');
        return $this->repository->delete($id, $token);
    }
    public function regenerateQRCodeAll()
    {
        $token = session('token');
        return $this->repository->regenerateQRCodeAll($token);
    }

    public function exportQRCodePDF($id, $jumlah, $token)
    {
        return $this->repository->exportQRCodePDF($id, $jumlah, $token);
    }

    public function exportQRCodePDFAll($token)
    {
        return $this->repository->exportQRCodePDFAll($token);
    }
    public function getByKode($kode, $token)
    {
        $all = $this->repository->getAll($token);
        return collect($all)->firstWhere('barang_kode', $kode); // Mengambil barang berdasarkan kode
    }
}
