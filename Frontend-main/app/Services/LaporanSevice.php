<?php
namespace App\Services;

use App\Repositories\LaporanRepository;

class LaporanSevice
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new LaporanRepository();
    }

    public function exportLaporanTransaksiPDF($token, $startDate = null, $endDate = null)
    {
        return $this->repository->exportLaporanTransaksiPDF($token, $startDate, $endDate);
    }
    public function exportLaporanStokExcel($token)
{
    return $this->repository->exportLaporanStokExcel($token);
}

    public function exportLaporanStokPDF($token)
    {
        return $this->repository->exportLaporanStokPDF($token);
    }

    public function exportLaporanTransaksiPDFByType($token, $typeId, $startDate = null, $endDate = null)
    {
        return $this->repository->exportLaporanTransaksiPDFByType($token, $typeId, $startDate, $endDate);
    }

    // Tambahan untuk export Excel
    public function exportLaporanTransaksiExcel($token, $startDate = null, $endDate = null)
    {
        return $this->repository->exportLaporanTransaksiExcel($token, $startDate, $endDate);
    }

    public function exportLaporanTransaksiExcelByType($token, $typeId, $startDate = null, $endDate = null)
    {
        return $this->repository->exportLaporanTransaksiExcelByType($token, $typeId, $startDate, $endDate);
    }
}
