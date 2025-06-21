<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $auth_service;

    public function __construct(AuthService $auth_service)
    {
        $this->auth_service = $auth_service;
    }

    public function index()
    {
        $token = session('token');

        if (!$token) return null;

        $user = $this->auth_service->getUserInfo();
        // dd($user);
        return view('frontend.profile.user_profile', compact('user'));
    }

    public function changePassword()
    {
        $token = session('token');

        if (!$token) return null;

        $user = $this->auth_service->getUserInfo();

        return view('frontend.profile.ganti_password', compact('user'));
    }

    public function updateEmail(Request $request)
{
    // Ambil email baru dari input form
    $newEmail = $request->input('new_email');

    // Kirim request ke API untuk update email
    $response = Http::withToken(session('token'))->put(config('api.base_url') . '/user/update-email', [
        'email' => $newEmail,
    ]);

    // Handle success response (200)
    if ($response->successful()) {
        // Ambil message dari response API
        $responseData = $response->json();
        $successMessage = $responseData['message'] ?? 'Link verifikasi telah dikirim ke email baru. Harap cek inbox Anda.';

        // Redirect back dengan success message
        return back()->with('success', $successMessage);
    }

    // Handle error response dari API
    $errorData = $response->json();
    $errorMessage = $errorData['message'] ?? 'Gagal memperbarui email.';

    // Jika ada error spesifik dari backend berdasarkan status code
    if ($response->status() === 429) {
        // Cooldown error - Too Many Requests
        return back()->withErrors(['new_email' => $errorMessage])->withInput();
    } elseif ($response->status() === 400) {
        // Bad Request - Email sama atau sudah digunakan
        return back()->withErrors(['new_email' => $errorMessage])->withInput();
    } elseif ($response->status() === 422) {
        // Unprocessable Entity - Validation error
        $errors = $errorData['errors'] ?? ['new_email' => $errorMessage];
        return back()->withErrors($errors)->withInput();
    } elseif ($response->status() === 500) {
        // Internal Server Error - Gagal kirim email
        return back()->withErrors(['new_email' => $errorMessage])->withInput();
    } else {
        // General error untuk status code lainnya
        return back()->withErrors(['new_email' => $errorMessage])->withInput();
    }
}
public function updateUser(Request $request)
{
    $response = Http::withToken(session('token'))->put(config('api.base_url') . '/users/update', [
        'name' => $request->input('name'),
        'phone_number' => $request->input('phone'),
    ]);

    if ($response->successful()) {
        $message = $response->json('message') ?? 'Profil berhasil diperbarui.';
        return back()->with('success', $message);
    }

    // Tangani error dari validasi backend
    $errorMessage = $response->json('message') ?? 'Gagal memperbarui profil';

    // Jika ada error validasi terperinci (dari ValidationException)
    if (isset($response->json()['errors'])) {
        $errors = collect($response->json()['errors'])->flatten()->implode(', ');
        $errorMessage = $errors;
    }

    return back()->withErrors(['message' => $errorMessage]);
}

}
