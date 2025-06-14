<?php

namespace App\Http\Controllers;

use App\Http\Resources\JenisBarangResource;
use App\Http\Resources\SatuanResource;
use App\Services\BarangCategoryService;
use App\Services\BarangService;
use App\Services\JenisBarangService;
use App\Services\LaporanSevice;
use App\Services\SatuanService;
use App\Services\TransactionService;
use App\Services\TransactionTypeService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    protected $service;
    protected $barang_service;
    protected $transactionService;
    protected $jenis_barang_service;
    protected $kategori_barang_service;
    protected $satuan_service;
    protected $laporanService;


    public function __construct(BarangService $barang_service, TransactionTypeService $service, TransactionService $transactionService, JenisBarangService $jenis_barang_service, BarangCategoryService $barang_category_service, SatuanService $satuan_service, LaporanSevice $laporanService)
    {
        $this->transactionService = $transactionService;
        $this->service = $service;
        $this->barang_service = $barang_service;
        $this->jenis_barang_service = $jenis_barang_service;
        $this->kategori_barang_service = $barang_category_service;
        $this->satuan_service = $satuan_service;
        $this->laporanService = $laporanService;
        $this->service = $service;

    }

    public function laporanTrans(Request $request)
    {
        $token = $request->session()->get('token');
        $transactions = $this->transactionService->getAllTransactions($token);

        // Filter transactions by transaction_type_id if selected
        if ($request->filled('transaction_type_id')) {
            $transactions = $transactions->where('transaction_type.id', $request->transaction_type_id);
        }

        $transactionTypes = $this->service->all($token);

        return view('frontend.Laporan.Laporantransaksi', compact('transactions', 'transactionTypes'));
    }

    public function laporanStok()
    {
        try {
            $token = session('token');

            $barangs = $this->barang_service->getAllBarang();
            // dd($barangs);
            logger()->info('Barang Index Fetch:', $barangs);
            $jenis_barangs = JenisBarangResource::collection(collect($this->jenis_barang_service->all($token)));
            $satuans = SatuanResource::collection(collect($this->satuan_service->all($token)));
            $kategori_barangs = $this->kategori_barang_service->all($token);

            return view('frontend.Laporan.LaporanStok', compact('barangs', 'jenis_barangs', 'kategori_barangs', 'satuans'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal memuat data barang: ' . $th->getMessage());
        }
    }

    public function generateTransaksiReportPdf(Request $request)
    {
        try {
            $token = $request->session()->get('token');
            $response = $this->laporanService->exportLaporanTransaksiPDF($token);

            if ($response['status'] === true && isset($response['pdf_url'])) {
                return redirect()->away($response['pdf_url']);
            }

            return back()->with('error', 'Gagal mengekspor laporan transaksi.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function exportStokPdf(Request $request)
    {
        try {
            $token = $request->session()->get('token');
            $response = $this->laporanService->exportLaporanStokPDF($token);

            if ($response['status'] === true && isset($response['pdf_url'])) {
                return redirect()->away($response['pdf_url']);
            }

            return back()->with('error', 'Gagal mengekspor laporan stok.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
    public function exportLaporanTransaksiPDFByType(Request $request, $id)
    {
        $token = $request->session()->get('token');

        // Pastikan ini sesuai dengan URL yang diinginkan
        $response = $this->laporanService->exportLaporanTransaksiPDFByType($token, $id);

        if ($response['status'] === true && isset($response['pdf_url'])) {
            return redirect()->away($response['pdf_url']);
        }

        return back()->with('error', 'Gagal mengekspor laporan transaksi berdasarkan jenis.');
    }

}
