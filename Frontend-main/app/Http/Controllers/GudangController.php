<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GudangService;
use Exception;

class GudangController extends Controller
{
    protected $service;

    public function __construct(GudangService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $token = session('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $gudangs = $this->service->all($token);
            $operators = $this->service->getOperators($token);

            return view('frontend.gudangs.index', compact('gudangs', 'operators'));
        } catch (Exception $e) {
            return redirect()->route('gudangs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $token = session('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $data = $request->only('name', 'description', 'user_id');
            $response = $this->service->create($data, $token);

            if ($response['success']) {
                return redirect()->route('gudangs.index')->with('success', $response['message'] ?? 'Gudang berhasil ditambahkan.');
            }

            return redirect()->route('gudangs.index')->with('error', $response['message'] ?? 'Gagal menambahkan gudang.');
        } catch (Exception $e) {
            return redirect()->route('gudangs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $token = session('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $data = $request->only('name', 'description', 'user_id');

            $response = $this->service->edit($id, $data, $token);

            if ($response['success']) {
                return redirect()->route('gudangs.index')->with('success', $response['message'] ?? 'Gudang berhasil diperbarui.');
            }

            return redirect()->route('gudangs.index')->with('error', $response['message'] ?? 'Gagal memperbarui gudang.');
        } catch (Exception $e) {
            return redirect()->route('gudangs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $token = session('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $response = $this->service->destroy($id, $token);

            if ($response['success']) {
                return redirect()->route('gudangs.index')->with('success', $response['message'] ?? 'Gudang berhasil dihapus.');
            }

            return redirect()->route('gudangs.index')->with('error', $response['message'] ?? 'Gagal menghapus gudang.');
        } catch (Exception $e) {
            return redirect()->route('gudangs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
