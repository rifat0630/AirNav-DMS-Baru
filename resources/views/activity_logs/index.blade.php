@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem (Activity Log)')

@section('content')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-500"></i> Log Aktivitas Pengguna & Sistem
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Rekam jejak setiap aksi penting (unggah arsip dokumen, pencatatan inventory, delegasi perintah, dan paraf digital).
                </p>
            </div>
        </div>
    </div>

    <!-- Tabel Rekam Jejak Aktivitas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Waktu Kejadian</th>
                        <th class="py-2.5 px-3">Nama Pengguna</th>
                        <th class="py-2.5 px-3">Role</th>
                        <th class="py-2.5 px-3">Aksi / Modul</th>
                        <th class="py-2.5 px-3">Detail Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-3 text-gray-500 font-mono text-[11px]">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                        </td>
                        <td class="py-3 px-3 font-bold text-gray-800">
                            {{ $log->user_name ?? ($log->user->name ?? 'Administrator') }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 uppercase">
                                {{ $log->role ?? 'Admin' }}
                            </span>
                        </td>
                        <td class="py-3 px-3 font-semibold text-blue-700">
                            {{ $log->action ?? 'Operasional CNS' }}
                        </td>
                        <td class="py-3 px-3 text-gray-600">
                            {{ $log->description ?? $log->kegiatan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Belum ada riwayat aktivitas yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($logs, 'links'))
            <div class="p-4 border-t border-gray-100">{{ $logs->links() }}</div>
        @endif
    </div>

@endsection