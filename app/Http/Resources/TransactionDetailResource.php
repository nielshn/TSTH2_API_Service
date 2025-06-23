<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'barang' => [
                'kode' => $this->barang->barang_kode,
                'nama' => $this->barang->barang_nama,
                'kategori' => $this->barang->category ? $this->barang->category->name : null,  // Nama kategori
                'satuan' => $this->barang->satuan ? $this->barang->satuan->name : null,  // Nama satuan
                'harga' => $this->barang->barang_harga,
                'stok_tersedia' => $this->barang->gudangs->first()->pivot->stok_tersedia,  // Stok yang tersedia di gudang pertama
            ],
            'gudang' => [
                'nama' => $this->gudang->name,  // Nama gudang
            ],
            'quantity' => $this->quantity,
            'tanggal_kembali' => $this->tanggal_kembali
                ? date('Y-m-d H:i:s', strtotime($this->tanggal_kembali))
                : null,
        ];
    }
}
