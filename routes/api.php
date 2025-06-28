<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WebController;
use App\Http\Controllers\BarangCategoryController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

// ==================== AUTH ====================
Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login')->name('auth.login');
    });
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('refresh-permission', [AuthController::class, 'refreshPermissions']);
        Route::get('user', [AuthController::class, 'userInfo'])->name('auth.user');
    });
});

// ==================== ROUTE GROUP BESAR DENGAN auth:api ====================
Route::middleware(['auth:api'])->group(function () {

    // ===== USER MANAGEMENT =====
    Route::get('/dashboard', fn() => response()->json(['message' => 'Hanya Superadmin bisa akses']));
    Route::prefix('users')->group(function () {
        Route::get('operators', [UserController::class, 'getOperators']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::put('admin-update/{id}', [UserController::class, 'updateUserByAdmin']);
        Route::apiResource('/', UserController::class)->parameters(['' => 'user']);
    });
    Route::put('/user/avatar', [UserController::class, 'updateAvatar']);
    Route::delete('/user/avatar', [UserController::class, 'deleteAvatar']);
    Route::put('/user/update-email', [UserController::class, 'updateEmail']);
    Route::get('/check-roles', [UserController::class, 'checkRoles']);

    // ===== ROLES & PERMISSIONS =====
    Route::apiResource('roles', RoleController::class);

    // ===== MASTER DATA =====
    Route::apiResource('gudangs', GudangController::class);
    Route::apiResource('satuans', SatuanController::class);
    Route::apiResource('barang-categories', BarangCategoryController::class);
    Route::apiResource('transaction-types', TransactionTypeController::class);
    Route::apiResource('jenis-barangs', JenisBarangController::class);
    Route::patch('jenis-barang/{id}/restore', [JenisBarangController::class, 'restore']);
    Route::delete('jenis-barang/{id}/force-delete', [JenisBarangController::class, 'forceDelete']);
    Route::apiResource('barangs', BarangController::class);

    // ===== TRANSACTION =====
    Route::get('transactions/check-barcode/{kode}', [TransactionController::class, 'checkBarcode']);
    Route::apiResource('transactions', TransactionController::class);

    // ===== REPORTS =====
    // --- Laporan Transaksi ---
    Route::prefix('laporan-transaksi')->group(function () {
        Route::get('/', [LaporanController::class, 'laporantransaksi']);
        Route::get('export-pdfs', [LaporanController::class, 'generateTransaksiReportPdf'])->name('transactions.exportPdf');
        Route::get('export-pdf/{typeId}', [LaporanController::class, 'generateTransaksiTypeReportPdf']);
        Route::get('export-excel/{id}', [LaporanController::class, 'generateTransaksiTypeReportexcel']);
        Route::get('export-excel', [LaporanController::class, 'generateAllTransaksiexcel']);
    });
    // --- Laporan Stok ---
    Route::prefix('laporan-stok')->group(function () {
        Route::get('/', [LaporanController::class, 'laporanstok']);
        Route::get('pdf', [LaporanController::class, 'exportStokPdf']);
        Route::get('excel', [LaporanController::class, 'exportStokExcel']);
        Route::get('category/{category_id}', [LaporanController::class, 'laporanstokByCategory']);
        Route::get('category/{category_id}/pdf', [LaporanController::class, 'exportStokPdfByCategory']);
        Route::get('category/{category_id}/excel', [LaporanController::class, 'exportStokExcelByCategory']);
    });

    // ===== OTP =====
    Route::post('/otp/send', [LaporanController::class, 'send']);
    Route::post('/otp/verify', [LaporanController::class, 'verify']);

    // ===== QRCODE =====
    Route::get('/barang/qrcode/save/{id}', [QRCodeController::class, 'generateQRCodeImage']);
    Route::get('/generate-qrcodes', [QRCodeController::class, 'generateAllQRCodesImage']);
    Route::get('/barangs/export-pdf/{id}', [QRCodeController::class, 'generateQRCodePDF']);
    Route::get('/export-pdf', [QRCodeController::class, 'generateAllQRCodesPDF']);

    // ===== WEB SETTINGS =====
    Route::apiResource('webs', WebController::class);

    // ===== NOTIFICATIONS =====
    Route::get('/notifikasis', [NotifikasiController::class, 'index']);
    Route::put('/notifikasis/{id}/read', [NotifikasiController::class, 'markAsRead']);
    Route::put('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead']);
});

// ===== ROLES & PERMISSIONS KHUSUS SUPERADMIN ATAU YANG PUNYA manage_permissions =====
Route::middleware(['auth:api', 'role_or_permission:superadmin|manage_permissions'])->group(function () {
    Route::post('/toggle-permission', [PermissionController::class, 'togglePermission']);
    Route::get('/permission', [PermissionController::class, 'index']);
});

// ===== EMAIL VERIFICATION =====
Route::post('/email/resend', function (Request $request) {
    $user = $request->user();

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email sudah diverifikasi.']);
    }

    $pendingEmail = Cache::get('pending_email_' . $user->id);
    if (!$pendingEmail) {
        return response()->json(['message' => 'Belum ada email baru yang didaftarkan.'], 400);
    }

    $cacheKey = 'email_verification_timestamp_' . $user->id;
    $lastSent = Cache::get($cacheKey);

    $cooldown = 60;
    if ($lastSent) {
        $elapsed = now()->diffInSeconds($lastSent);
        if ($elapsed < $cooldown) {
            return response()->json([
                'message' => 'Coba lagi setelah 1 menit.',
                'countdown' => $cooldown - $elapsed
            ], 400);
        }
    }

    Cache::put($cacheKey, now(), 61);

    // Kirim notifikasi ke email baru, bukan email lama
    $user->notify(new \App\Notifications\CustomVerifyEmail($pendingEmail));

    return response()->json(['message' => 'Link verifikasi telah dikirim ulang ke email baru.']);
})->middleware(['auth:api']);

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);
    $pendingEmail = Cache::get('pending_email_' . $user->id);

    if (!$pendingEmail) {
        Log::warning("Verifikasi gagal: Tidak ada pending email untuk user ID $id.");
        return redirect('http://127.0.0.1:8000/user_profile?status=failed');
    }

    if (!hash_equals((string) $hash, sha1($pendingEmail))) {
        Log::warning("Verifikasi gagal: Hash tidak cocok untuk user ID $id.");
        return redirect('http://127.0.0.1:8000/user_profile?status=invalid');
    }

    Log::info("Memverifikasi user ID $id, email: $pendingEmail");

    $user->email = $pendingEmail;
    $user->email_verified_at = now();
    $user->save();

    Cache::forget('pending_email_' . $user->id);

    return redirect('http://127.0.0.1:8000/user_profile?status=success');
})->middleware(['signed'])->name('verification.verify');
