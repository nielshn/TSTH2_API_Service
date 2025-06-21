<?php

use App\Http\Middleware\RefreshPermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.token' => \App\Http\Middleware\Authenticated::class,
            'auth.session' => \App\Http\Middleware\AuthSession::class,
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            // 'refresh.permissions' => RefreshPermissionMiddleware::class, // Menambahkan middleware refresh-permission
        ]);
        // $middleware->group('web', [
        //     'refresh.permissions', // Menambahkan middleware refresh-permission ke grup web
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
