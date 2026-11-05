<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ManagerTeknikAccessRequest;

class EnsureMantekOrApprovedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Cek apakah user sudah terautentikasi
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. Role Mantek memiliki hak akses penuh langsung ke seluruh rute Mantek
        if ($user->role === 'mantek') {
            return $next($request);
        }

        // 3. Pengecualian route: Izinkan Admin & Teknisi mengakses halaman dan submit pengajuan izin
        if ($request->routeIs('mantek.access.request') || $request->routeIs('mantek.access.submit')) {
            return $next($request);
        }

        // 4. Role Admin dan Teknisi wajib memiliki persetujuan izin aktif dari Mantek
        if (in_array($user->role, ['admin', 'teknisi'])) {
            $hasApproval = ManagerTeknikAccessRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                })
                ->exists();

            if ($hasApproval) {
                return $next($request);
            }

            // Jika belum ada izin yang aktif/disetujui, arahkan ke form permohonan izin
            return redirect()->route('mantek.access.request');
        }

        // 5. Role selain mantek, admin, dan teknisi ditolak
        abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka menu ini.');
    }
}