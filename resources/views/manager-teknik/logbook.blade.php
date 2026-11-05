@extends('layouts.app')

@section('title', 'Logbook Harian Teknisi')

@section('content')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-book-bookmark text-indigo-600"></i> Logbook Harian Pemeliharaan Fasilitas
            </h1>
            <p class="text-xs text-gray-500 mt-1">
                Catatan operasional shift harian (pagi, siang, malam), checklist preventif, dan penanganan gangguan.
            </p>
        </div>
        <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg">
            <i class="fa-solid fa-clock text-slate-500"></i>
            Monitoring Shift Aktif
        </div>
    </div>

    <!-- Tabel Rekap Logbook -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50/70 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800">Riwayat Catatan Logbook Terkini</h2>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Tanggal & Shift</th>
                        <th class="py-2.5 px-3">Fasilitas / Alat</th>
                        <th class="py-2.5 px-3">Teknisi Jaga</th>
                        <th class="py-2.5 px-3">Uraian Pekerjaan / Pemeriksaan</th>
                        <th class="py-2.5 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logbooks as $log)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-3">
                            <span class="font-semibold text-gray-800 text-[13px] block">
                                {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-[11px] text-gray-500">{{ $log->shift }}</span>
                        </td>
                        <td class="py-3 px-3 font-semibold text-gray-700">
                            {{ $log->fasilitas }}
                        </td>
                        <td class="py-3 px-3 text-gray-600">
                            {{ $log->teknisi }}
                        </td>
                        <td class="py-3 px-3 text-gray-600 max-w-xs">
                            {{ $log->kegiatan }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $log->status === 'Normal' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $log->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">
                            Belum ada entri logbook untuk hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection