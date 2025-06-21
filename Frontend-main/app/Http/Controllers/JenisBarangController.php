<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\JenisBarangService;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    protected $service;

    public function __construct(JenisBarangService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $token = $request->session()->get('token');
        $jenisBarangs = $this->service->all($token);

        return view('frontend.jenis-barangs.index', compact('jenisBarangs'));
    }

    public function store(Request $request)
    {
        $token = session('token');
        $data = $request->only(['name', 'description']);
        $this->service->create($token, $data);
        return redirect()->back()->with('success', 'Jenis Barang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $token = session('token');
        $data = $request->only(['name', 'description']);
        $this->service->update($token, $id, $data);
        return redirect()->back()->with('success', 'Jenis Barang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $token = session('token');
        $this->service->delete($token, $id);
        return redirect()->back()->with('success', 'Jenis Barang berhasil dihapus');
    }
}
