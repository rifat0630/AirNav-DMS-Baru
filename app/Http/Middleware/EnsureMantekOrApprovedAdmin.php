<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EnsureMantekOrApprovedAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Jika role adalah Manager Teknik asli, izinkan masuk langsung
        if (in_array($user->role, ['manager_teknik', 'mantek'])) {
            return $next($request);
        }

        // 2. Jika bukan Mantek, periksa apakah tabel izin ada dan memiliki izin aktif
        $hasApprovedDelegation = false;
        try {
            if (Schema::hasTable('manager_teknik_assignments')) {
                $cols = Schema::getColumnListing('manager_teknik_assignments');
                $userCol = in_array('user_id', $cols) ? 'user_id' : (in_array('assigned_to', $cols) ? 'assigned_to' : null);

                if ($userCol) {
                    $hasApprovedDelegation = DB::table('manager_teknik_assignments')
                        ->where($userCol, $user->id)
                        ->when(in_array('status', $cols), fn($q) => $q->where('status', 'approved'))
                        ->exists();
                }
            }
        } catch (\Throwable $e) {
            $hasApprovedDelegation = false;
        }

        if ($hasApprovedDelegation) {
            return $next($request);
        }

        // 3. Tolak jika bukan Mantek dan tidak ada izin resmi
        abort(403, 'Akses Ditolak: Panel instruksi dan perintah kerja ini khusus untuk Manager Teknik atau pengguna dengan izin delegasi resmi.');
    }
}