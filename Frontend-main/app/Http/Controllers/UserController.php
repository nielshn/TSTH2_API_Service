<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\RoleService;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $usersResponse = $this->userService->getAllUsers();
            $rolesResponse = $this->roleService->getAllRoles();

            if ($usersResponse->successful() && $rolesResponse->successful()) {
                $users = $usersResponse->json('data') ?? [];
                $roles = $rolesResponse->json('data') ?? [];

                return view('frontend.user.index', compact('users', 'roles'));
            }

            return back()->withErrors(['message' => 'Gagal mengambil data pengguna atau peran.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->userService->createUser($request->only([
                'name', 'email', 'password', 'password_confirmation', 'roles'
            ]));

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
            }

        $responseBody = $response->json();
        return back()->withErrors([
            'message' => $responseBody['error'] ?? 'Gagal menyimpan user.'
        ]);
    } catch (\Exception $e) {
        return back()->withErrors(['message' => $e->getMessage()]);
    }
}

public function update(Request $request, $id)
    {
        try {
            // Mendapatkan data yang diterima dari form, termasuk roles yang dikirim sebagai array
            $data = $request->only([
                'name',
                'password',
                'password_confirmation',
                'roles'
            ]);

            // Proses update user menggunakan service
            $response = $this->userService->updateUser($data, $id);

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
            }

            $responseBody = $response->json();
            return back()->withErrors([
                'message' => $responseBody['error'] ?? 'Gagal memperbarui user.'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $response = $this->userService->deleteUser($id);

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
            }

            return back()->withErrors(['message' => 'Gagal menghapus user.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
