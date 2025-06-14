<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\WebService;
use Illuminate\Support\Facades\Auth; // Tambahkan jika pakai Auth
use App\Services\AuthService; // Pastikan ini sesuai lokasi service-mu

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Ambil informasi user
            $authService = app(AuthService::class); // Panggil service
            $user = $authService->getUserInfo();     // Ambil user info

            // Pastikan permission ada di dalam array $user, misal $user['permissions']
            $permissions = $user['permissions'] ?? []; // Menggunakan array untuk akses permissions
            // dd($permissions);
            // Daftar permission keys
            $webService = app(WebService::class);
            $token = session('token'); // atau bisa juga $authService->getToken() jika ada
            $web = $webService->getById($token, 1); // panggil berdasarkan ID

            $keys = [
                'manage_permissions',
                'create_user',
                'update_user',
                'view_user',
                'delete_user',
                'create_role',
                'update_role',
                'view_role',
                'delete_role',
                'create_barang',
                'update_barang',
                'view_barang',
                'delete_barang',
                'create_gudang',
                'update_gudang',
                'view_gudang',
                'delete_gudang',
                'create_satuan',
                'update_satuan',
                'view_satuan',
                'delete_satuan',
                'create_jenis_barang',
                'update_jenis_barang',
                'view_jenis_barang',
                'delete_jenis_barang',
                'create_transaction_type',
                'update_transaction_type',
                'view_transaction_type',
                'delete_transaction_type',
                'create_transaction',
                'view_transaction',
                'create_category_barang',
                'update_category_barang',
                'view_category_barang',
                'delete_category_barang',
            ];

            // Generate permission flags berdasarkan permissions yang diambil dari user
            $flags = generatePermissionsFlags($permissions, $keys);
            $view->with(array_merge(['user' => $user, 'web' => $web], $flags));
            // Kirim $user dan $flags ke semua view
            $view->with(array_merge(['user' => $user], $flags));
        });

        Blade::if('can', function ($permission) {
            // Ambil informasi user untuk permission
            $user = app(AuthService::class)->getUserInfo();
            // Periksa apakah user memiliki permission yang diberikan
            return in_array($permission, $user['permissions'] ?? []);
        });
    }
}
