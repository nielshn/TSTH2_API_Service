<?php

namespace App\Http\Controllers;

use App\Services\BarangCategoryService;
use Illuminate\Http\Request;

class BarangCategoryController extends Controller
{
    protected $service;

    public function __construct(BarangCategoryService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $token = session('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $barangCategories = $this->service->all($token);
        return view('frontend.barang-category.index', compact('barangCategories'));
    }

    public function store(Request $request)
    {
        $token = session('token');
        $this->service->create($request->only('name'), $token);
        return redirect()->route('barang-categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // public function edit($id)
    // {
    //     $token = session('token');
    //     $category = $this->service->find($id, $token);
    //     return view('frontend.barang_categories.edit',   compact('category'));
    // }

    public function update(Request $request, $id)
    {
        $token = session('token');
        $this->service->edit($id, $request->only('name'), $token);
        return redirect()->route('barang-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $token = session('token');
        $this->service->destroy($id, $token);
        return redirect()->route('barang-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
