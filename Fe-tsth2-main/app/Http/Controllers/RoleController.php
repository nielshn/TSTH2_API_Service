<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleService;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $response = $this->roleService->getAllRoles();

            $roles = $response->json('data') ?? [];

            return view('frontend.role.index', compact('roles'));
        } catch (\Exception $e) {
            return view('error.error', ['error' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $response = $this->roleService->createRole($request->only('name'));

            if ($response->successful()) {
                return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan!');
            }

            return back()->withErrors(['message' => 'Gagal menyimpan role.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $response = $this->roleService->getRole($id);

            if ($response->successful()) {
                $role = $response->json('data');
                return view('frontend.role.edit', compact('role'));
            }

            return redirect()->route('roles.index')->withErrors('Data tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $response = $this->roleService->updateRole($id, $request->only('name'));

            if ($response->successful()) {
                return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
            }

            return back()->withErrors(['message' => 'Gagal memperbarui role.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $response = $this->roleService->deleteRole($id);

            if ($response->successful()) {
                return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
            }

            return back()->withErrors(['message' => 'Gagal menghapus role.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            if (!session('token')) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $response = $this->roleService->getRole($id);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['message' => 'Data tidak ditemukan'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
