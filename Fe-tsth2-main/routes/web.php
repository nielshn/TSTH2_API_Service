<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    BarangCategoryController,
    BarangController,
    DashboardController,
    GudangController,
    JenisBarangController,
    LaporanController,
    NotifikasiController,
    PermissionController,
    ProfileController,
    RoleController,
    SatuanController,
    TransactionController,
    TransactionTypeController,
    UserController,
    WebController
};

// Routes untuk halaman login (guest only)
Route::middleware('auth.token')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'handleLogin'])->name('post.login')->middleware('guest');
});

// Routes untuk user yang sudah login
Route::middleware('auth.session')->group(function () {

    // Auth & Dashboard
    Route::post('/logout', [AuthController::class, 'handleLogout'])->name('auth.logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Scan Result
    Route::get('/scan-result', fn () => view('scan-result', ['data' => request()->query('data')]));

    Route::get('/middleware-test', fn () => 'Middleware OK')->middleware('refresh.permissions');

    // Profile
    Route::get('/user_profile', [ProfileController::class, 'index'])->name('profile.user_profile');
    Route::get('/user_profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.ganti-password');
    Route::put('profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
    Route::put('/profile/update-user', [ProfileController::class, 'updateUser'])->name('profile.update-user');


    // Laporan
    Route::prefix('laporan-transaksi')->group(function () {
        Route::get('/', [LaporanController::class, 'laporanTrans'])->name('laporan.transaksi');
        Route::get('/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.transaksi.exportPDF');
        Route::get('/export-pdf/{id}', [LaporanController::class, 'exportLaporanTransaksiPDFByType'])->name('transactions.exportPdfByType');
        Route::get('/export-excel', [LaporanController::class, 'generateAllTransaksiexcel']);
        Route::get('/export-excel/{id}', [LaporanController::class, 'generateTransaksiTypeReportexcel']);
    });

    Route::prefix('laporan-stok')->group(function () {
        Route::get('/', [LaporanController::class, 'laporanStok'])->name('laporan.stok');
        Route::get('/pdf', [LaporanController::class, 'exportStokPdf'])->name('laporan.stok.exportPDF');
        Route::get('/excel', [LaporanController::class, 'exportStokExcel']);
    });

    // Barang
    Route::resource('barangs', BarangController::class);
    Route::get('/export-barang-pdf', [BarangController::class, 'exportPDFALL'])->name('barangs.exportPDFALL');
    Route::get('/barangs/export-pdf/{id}', [BarangController::class, 'exportPDF'])->name('barangs.exportPDF');
    Route::get('/barang/refresh-qrcodes', [BarangController::class, 'refreshQRCodes'])->name('barang.refresh-qrcodes');

    // Transaction
    Route::resource('transactions', TransactionController::class);
    Route::get('/search-barang', [TransactionController::class, 'searchBarang'])->name('search.barang');
    Route::post('/kode-barang/check', [TransactionController::class, 'check'])->name('kode_barang.check');
    Route::get('/kode-barang/reset', [TransactionController::class, 'reset'])->name('kode_barang.reset');
    Route::post('/kode-barang/remove', [TransactionController::class, 'remove'])->name('kode_barang.remove');

    // Resources lainnya dengan permission middleware
    Route::resource('satuans', SatuanController::class)->middleware('check.permission:view_satuan');
    Route::resource('gudangs', GudangController::class)->middleware('check.permission:view_gudang');
    Route::resource('jenis-barangs', JenisBarangController::class)->middleware('check.permission:view_jenis_barang');
    Route::resource('barang-categories', BarangCategoryController::class)->middleware('check.permission:view_category_barang');
    Route::resource('transaction-types', TransactionTypeController::class);
    Route::resource('roles', RoleController::class)->middleware('check.permission:view_role');
    Route::resource('users', UserController::class)->middleware('check.permission:view_user');
    Route::resource('webs', WebController::class);

    // Permission Management
    Route::get('/select-role', [PermissionController::class, 'selectRole'])->name('permissions.index');
    Route::get('select-role/permissions', [PermissionController::class, 'show'])->name('permissions.show');
    Route::post('/permissions/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');

    // Notifikasi
    Route::get('notifikasi', [NotifikasiController::class, 'getUnreadNotifications'])->name('getnotifikasi');
});

// Route halaman error
Route::get('/error', fn () => view('error.error'))->name('error');
