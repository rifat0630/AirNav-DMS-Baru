<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Contoh penggunaan:
     * ->middleware('role:admin')
     * ->middleware('role:admin,teknisi')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user diizinkan
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        // Lanjutkan request
        return $next($request);
    }
}