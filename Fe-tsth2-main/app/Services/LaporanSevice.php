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

    public function exportLaporanTransaksiPDF($token)
    {
        return $this->repository->exportLaporanTransaksiPDF($token);
    }

    public function exportLaporanStokPDF($token)
    {
        return $this->repository->exportLaporanStokPDF($token);
    }

    public function exportLaporanTransaksiPDFByType($token, $typeId)
{
    return $this->repository->exportLaporanTransaksiPDFByType($token, $typeId);
}

}
