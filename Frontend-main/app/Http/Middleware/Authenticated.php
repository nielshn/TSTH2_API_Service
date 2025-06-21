<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau user sudah login, jangan izinkan masuk ke halaman login
        if ($request->session()->has('token')) {
            return redirect()->route('dashboard'); // Redirect ke dashboard jika sudah login
        }

        return $next($request);
    }
}
