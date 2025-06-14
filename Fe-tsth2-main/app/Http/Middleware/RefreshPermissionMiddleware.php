<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RefreshPermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //    // Cek apakah token ada di session
        // if (Session::has('token')) {
        //     try {
        //         $token = Session::get('token');
        //         dd($token); // Debugging token

        //         // Jika belum ada permissions di session atau mau selalu refresh
        //         if (!Session::has('permissions')) {
        //             $response = Http::withToken($token)
        //                 ->get('http://127.0.0.1:8090/api/auth/refresh-permission');

        //             if ($response->successful()) {
        //                 $permissions = $response->json('data.permissions', []);
        //                 $roles = $response->json('data.roles', []);

        //                 Session::put('permissions', $permissions);
        //                 Session::put('roles', $roles);
        //             }
        //         }

        //     } catch (\Exception $e) {
        //         Log::error('Gagal refresh permission: ' . $e->getMessage());
        //     }
        // }

        return $next($request);

    }
}
