<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SatuanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SatuanController extends Controller
{
    protected $service;

    public function __construct(SatuanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $token = session('token');
        $satuans = $this->service->all($token);
        return view('frontend.satuan.index', compact('satuans'));
    }

    public function store(Request $request)
    {
        $token = session('token');
        $data = $request->only(['name', 'description']);
        $this->service->create($token, $data);
        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $token = session('token');
        $data = $request->only(['name', 'description']);
        $this->service->update($token, $id, $data);
        return redirect()->back()->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $token = session('token');
        $this->service->delete($token, $id);
        return redirect()->back()->with('success', 'Satuan berhasil dihapus');
    }
}
