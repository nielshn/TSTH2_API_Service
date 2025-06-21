<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $service;
    protected $auth_service;


    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->service->login($credentials);

        if ($result['success']) {
            session()->flash('login_success', 'Login berhasil! Selamat datang, ' . $result['user']['name']);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login_error' => $result['message']]);
    }


    public function handleLogout()
    {
        if ($this->service->logout()) {
            return redirect()->route('auth.login')->with('logout_success', 'Anda telah logout.');
        }

        return redirect()->route('auth.login');
    }
}
