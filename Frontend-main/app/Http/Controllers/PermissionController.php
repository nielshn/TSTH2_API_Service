<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $roleService;

    public function __construct(PermissionService $permissionService, RoleService $roleService)
    {
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }

    public function selectRole()
    {
        if (!session('token')) {
            return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
        }

        $roles = $this->roleService->getAllRoles()->json('data') ?? [];

        // Pastikan data role tidak kosong
        if (empty($roles)) {
            return redirect()->back()->withErrors('Tidak ada role ditemukan.');
        }

        return view('frontend.permissions.select_role', compact('roles'));
    }

    public function show(Request $request)
    {
        if (!session('token')) {
            return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
        }

        $role = $request->query('role');

        // Periksa apakah role ada dan valid
        if (empty($role)) {
            return redirect()->back()->withErrors('Role tidak ditemukan.');
        }

        // Panggil service untuk mendapatkan permission
        $permissions = $this->permissionService->getPermissionsByRole($role);
        // dd($permissions); // Debugging: Cek data permissions yang diterima
        // Debugging: Cek apakah data permissions kosong

        // Cek jika permissions kosong
        if (empty($permissions)) {
            return redirect()->back()->withErrors('Tidak ada permission ditemukan untuk role ini.');
        }

        // Log data permissions yang diterima
        Log::info('Permissions Data:', $permissions);

        return view('frontend.permissions.show', compact('permissions', 'role'));
    }

    public function toggle(Request $request)
    {
        if (!session('token')) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $payload = $request->only(['role', 'permission', 'status']);

        // Pastikan status diubah ke boolean
        if (isset($payload['status'])) {
            $payload['status'] = filter_var($payload['status'], FILTER_VALIDATE_BOOLEAN);
        } else {
            return redirect()->back()->withErrors('Status tidak ditemukan.');
        }

        // Proses toggling permission
        $response = $this->permissionService->togglePermission($payload);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Permission berhasil diubah.');
        }

        return redirect()->back()->withErrors('Gagal mengubah permission.');
    }
}
