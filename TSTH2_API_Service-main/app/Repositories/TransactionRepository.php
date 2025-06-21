<?php

namespace App\Repositories;

use App\Http\Controllers\NotifikasiController;
use App\Models\{Transaction, TransactionDetail, Barang, BarangGudang, Gudang, User};
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class TransactionRepository
{
    protected $notifcontroller;

    public function __construct(NotifikasiController $notifcontroller)
    {
        $this->notifcontroller = $notifcontroller;
    }
    public function createTransaction($request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $gudangId = $this->getGudangIdByUserId($userId);

        $transaction = Transaction::create([
            'user_id' => $userId,
            'transaction_type_id' => $request->transaction_type_id,
            'transaction_code' => $this->generateTransactionCode($request->transaction_type_id),
            'transaction_date' => now(),
            'description' => $request->description ?? null,
        ]);

        foreach ($request->items as $item) {
            $item['gudang_id'] = $gudangId;
            $this->processTransactionItem($transaction->id, $item, $request->transaction_type_id);
        }


        return $transaction->load([
            'user:id,name',
            'transactionType:id,name',
            'transactionDetails.barang:id,barang_kode,barang_nama',
            'transactionDetails.gudang:id,name',
        ]);
    }

    private function getGudangIdByUserId($userId)
    {
        $gudang = Gudang::where('user_id', $userId)->first();
        if (!$gudang) {
            throw new Exception("Tidak ditemukan gudang yang terdaftar untuk user login.");
        }
        return $gudang->id;
    }

    private function generateTransactionCode($typeId)
    {
        $prefixes = [
            1 => 'MSK',
            2 => 'KLR',
            3 => 'PJM',
            4 => 'KMB',
            5 => 'MTC',
            6 => 'FIX'
        ];

        $prefix = $prefixes[$typeId] ?? 'UNK';

        $lastTransaction = Transaction::where('transaction_type_id', $typeId)->latest('id')->first();
        $number = $lastTransaction ? str_pad($lastTransaction->id + 1, 3, '0', STR_PAD_LEFT) : '001';

        return "TRX-{$prefix}-{$number}";
    }

    private function processTransactionItem($transactionId, $item, $transactionType)
    {
        $barang = Barang::where('barang_kode', $item['barang_kode'])->firstOrFail();

        $barangGudang = BarangGudang::where('barang_id', $barang->id)
            ->where('gudang_id', $item['gudang_id'])
            ->first();

        // Validasi berdasarkan tipe transaksi
        if (in_array($transactionType, [2, 3, 4, 5, 6]) && !$barangGudang) {
            throw new Exception("Barang {$barang->barang_nama} belum tersedia di gudang. Masukkan terlebih dahulu.");
        }



        // Validasi kategori barang vs transaksi
        $this->validateItemTransaction($barang, $barangGudang, $item, $transactionType);

        // Proses transaksi
        match ($transactionType) {
            1 => $this->handleBarangMasuk($barang, $item),
            2 => $this->handleBarangKeluar($barang->id, $item),
            3 => $this->handlePeminjaman($barang->id, $item),
            4 => $this->handlePengembalian($barang->id, $item),
            5 => $this->handleMaintanance($barang->id, $item),
            6 => $this->handleMaintenanceReturn($barang->id, $item),
        };


        // Catat ke transaction_detail
        TransactionDetail::create([
            'transaction_id' => $transactionId,
            'barang_id' => $barang->id,
            'gudang_id' => $item['gudang_id'],
            'quantity' => $item['quantity'],
            'tanggal_kembali' => $transactionType == 4 ? now() : null,
        ]);
    }

    public function validateItemTransaction($barang, $barangGudang, $item, $transactionType)
    {
        // Validasi kategori barang vs transaksi
        $validTransactionType = match (true) {
            $barang->barangcategory_id == 1 => in_array($transactionType, [1, 2]),
            $barang->barangcategory_id == 2 => in_array($transactionType, [1, 3, 4, 5, 6]),
            default => false,
        };

        if (!$validTransactionType) {
            throw new Exception("Jenis transaksi tidak valid untuk barang {$barang->barang_nama}.");
        }

        // Validasi stok
        if (in_array($transactionType, [2, 3]) && (!$barangGudang || $barangGudang->stok_tersedia < $item['quantity'])) {
            throw new Exception("Stok tidak mencukupi untuk barang {$barang->barang_nama}.");
        }

        if ($transactionType == 4 && (!$barangGudang || $barangGudang->stok_dipinjam < $item['quantity'])) {
            throw new Exception("Barang {$barang->barang_nama} dikembalikan lebih banyak dari yang dipinjam.");
        }
    }

    private function getOrCreateBarangGudang($barangId, $gudangId)
    {
        $barangGudang = BarangGudang::firstOrCreate(
            [
                'barang_id' => $barangId,
                'gudang_id' => $gudangId
            ],
            ['stok_tersedia' => 0, 'stok_dipinjam' => 0, 'stok_maintenance' => 0]
        );
        return $barangGudang;
    }


    private function handleBarangMasuk($barang, $item)
    {
        // if (!$barangGudang) {
        //     // Barang belum terdaftar di gudang, buat relasi baru
        //     $barang->gudangs()->attach($item['gudang_id'], [
        //         'stok_tersedia' => $item['quantity'],
        //         'stok_dipinjam' => 0,
        //         'stok_maintenance' => 0,
        //     ]);
        // } else {
        $this->getOrCreateBarangGudang($barang->id, $item['gudang_id']);
        BarangGudang::where('barang_id', $barang->id)
            ->where('gudang_id', $item['gudang_id'])
            ->increment('stok_tersedia', $item['quantity']);
        // }
    }

    private function handleBarangKeluar($barangId, $item)
    {
        DB::transaction(function () use ($barangId, $item) {
            BarangGudang::where('barang_id', $barangId)
                ->where('gudang_id', $item['gudang_id'])
                ->decrement('stok_tersedia', $item['quantity']);

            $barangGudang = BarangGudang::where('barang_id', $barangId)
                ->where('gudang_id', $item['gudang_id'])
                ->first();

            $this->notifcontroller->checkStockAndNotify($barangId, $item['gudang_id'], $barangGudang->stok_tersedia);
        });
    }


    private function handlePeminjaman($barangId, $item)
    {
        DB::transaction(function () use ($barangId, $item) {
            BarangGudang::where('barang_id', $barangId)
                ->where('gudang_id', $item['gudang_id'])
                ->decrement('stok_tersedia', $item['quantity']);

            BarangGudang::where('barang_id', $barangId)
                ->where('gudang_id', $item['gudang_id'])
                ->increment('stok_dipinjam', $item['quantity']);

            $barangGudang = BarangGudang::where('barang_id', $barangId)
                ->where('gudang_id', $item['gudang_id'])
                ->first();

            $this->notifcontroller->checkStockAndNotify($barangId, $item['gudang_id'], $barangGudang->stok_tersedia);
        });
    }

    private function handlePengembalian($barangId, $item)
    {
        BarangGudang::where('barang_id', $barangId)
            ->where('gudang_id', $item['gudang_id'])
            ->increment('stok_tersedia', $item['quantity']);

        BarangGudang::where('barang_id', $barangId)
            ->where('gudang_id', $item['gudang_id'])
            ->decrement('stok_dipinjam', $item['quantity']);
    }

    private function handleMaintanance($barangId, $item)
    {
        BarangGudang::where('barang_id', $barangId)
            ->where('gudang_id', $item['gudang_id'])
            ->increment('stok_maintenance', $item['quantity']);

        BarangGudang::where('barang_id', $barangId)
            ->where('gudang_id', $item['gudang_id'])
            ->decrement('stok_tersedia', $item['quantity']);
    }

    private function handleMaintenanceReturn($barangId, $item)
    {
        BarangGudang::where('barang_id', $barangId)
            ->where('gudang_id', $item['gudang_id'])
            ->increment('stok_tersedia', $item['quantity']);

        BarangGudang::where('barang_id', $barangId)
            ->where('gudang_id', $item['gudang_id'])
            ->decrement('stok_maintenance', $item['quantity']);
    }


    public function findBarangByKode($kode)
    {
        return Barang::where('barang_kode', $kode)->first();
    }

    public function find($id)
    {
        $transaction = Transaction::with([
            'user:id,name',
            'transactionType:id,name',
            'transactionDetails.barang:id,barang_kode,barang_nama',
            'transactionDetails.gudang:id,name'
        ])->find($id);

        if (!$transaction) {
            throw new \Exception('Transaksi tidak ditemukan.');
        }

        return $transaction;
    }

    // public function update($id, array $data)
    // {
    //     $transaction = Transaction::find($id);

    //     if (!$transaction) {
    //         throw new \Exception('Transaksi tidak ditemukan.');
    //     }

    //     $transaction->update($data);

    //     return $transaction;
    // }

    public function updateTransactionWithDetails($id, array $data)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            throw new \Exception('Transaksi tidak ditemukan.');
        }

        $user = User::find($transaction->user_id);
        if (!$user || !$user->gudang_id) {
            throw new \Exception("Gudang tidak ditemukan untuk user ini.");
        }

        $transaction->update([
            'transaction_type_id' => $data['transaction_type_id']
        ]);

        // Hapus semua item sebelumnya
        $transaction->transactionDetails()->delete();

        foreach ($data['items'] as $item) {
            $barang = Barang::where('barang_kode', $item['barang_kode'])->first();

            if (!$barang) {
                throw new \Exception("Barang dengan kode {$item['barang_kode']} tidak ditemukan.");
            }

            $transaction->transactionDetails()->create([
                'barang_id' => $barang->id,
                'quantity' => $item['quantity'],
                'gudang_id' => $user->gudang_id,
                'description' => $item['description'] ?? null,
                'tanggal_kembali' => null,
            ]);
        }

        return $this->find($transaction->id);
    }
}
