<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TransactionTypeService;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    protected $service;

    public function __construct(TransactionTypeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $token = $request->session()->get('token');
        $transactionTypes = $this->service->all($token);

        return view('frontend.transaction-types.index', compact('transactionTypes'));
    }

    public function store(Request $request)
    {
        $token = session('token');
        $data = $request->only(['name']);
        $this->service->create($token, $data);
        return redirect()->back()->with('success', 'Jenis Transaksi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $token = session('token');
        $data = $request->only(['name']);
        $this->service->update($token, $id, $data);
        return redirect()->back()->with('success', 'Jenis Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $token = session('token');
        $this->service->delete($token, $id);
        return redirect()->back()->with('success', 'Jenis Transaksi berhasil dihapus');
    }
}
