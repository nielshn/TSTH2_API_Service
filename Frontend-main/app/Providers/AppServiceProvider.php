<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\WebService;
use App\Services\AuthService;

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
            $authService = app(AuthService::class);
            $webService = app(WebService::class);

            $token = session('token');

            // Gunakan hash agar key cache pendek
            $userCacheKey = 'user_info_' . md5($token);

            $user = [];
            if ($token) {
                $user = cache()->remember($userCacheKey, 60, function () use ($authService, $token) {
                    return $authService->getUserInfo($token);
                });
            }
            $permissions = $user['permissions'] ?? [];

            $webCacheKey = 'web_info_' . md5($token);
            $web = null;
            if ($token) {
                $web = cache()->remember($webCacheKey, 300, function () use ($webService, $token) {
                    return $webService->getById($token, 1);
                });
            }

            $keys = [
                'manage_permissions',
                'create_user', 'update_user', 'view_user', 'delete_user',
                'create_role', 'update_role', 'view_role', 'delete_role',
                'create_barang', 'update_barang', 'view_barang', 'delete_barang',
                'create_gudang', 'update_gudang', 'view_gudang', 'delete_gudang',
                'create_satuan', 'update_satuan', 'view_satuan', 'delete_satuan',
                'create_jenis_barang', 'update_jenis_barang', 'view_jenis_barang', 'delete_jenis_barang',
                'create_transaction_type', 'update_transaction_type', 'view_transaction_type', 'delete_transaction_type',
                'create_transaction', 'view_transaction',
                'create_category_barang', 'update_category_barang', 'view_category_barang', 'delete_category_barang',
            ];

            $flags = generatePermissionsFlags($permissions, $keys);

            $view->with(array_merge([
                'user' => $user,
                'web' => $web,
                'permissions' => $permissions
            ], $flags));
        });

Blade::if('can', function ($permission) {
    $token = session('token');
    $userCacheKey = 'user_info_' . md5($token);
    $user = [];
    if ($token) {
        $user = cache()->remember($userCacheKey, 300, function () use ($token) {
            return app(AuthService::class)->getUserInfo($token);
        });
    }
    return in_array($permission, $user['permissions'] ?? []);
});
    }
}
