@extends('layouts.app')

@section('title', 'Monitoring Inventory & Fasilitas')

@section('content')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-emerald-600"></i> Monitoring Inventory & Peralatan Fasilitas CNS/A
            </h1>
            <p class="text-xs text-gray-500 mt-1">
                Data inventaris barang rusak, kebutuhan perbaikan berkala, dan suku cadang fasilitas navigasi.
            </p>
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-2 shadow-sm transition">
            <i class="fa-solid fa-plus"></i> Catat Peralatan Rusak
        </button>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium">Perlu Perbaikan Segera</p>
                <p class="text-2xl font-bold text-rose-600 mt-1">3 Unit</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium">Dalam Proses Pengerjaan</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">1 Fasilitas</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium">Normal / Beroperasi Baik</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">98.5%</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- Tabel Data Inventory -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50/70 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800">Daftar Fasilitas & Suku Cadang Butuh Tindakan</h2>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Nama Peralatan / Modul</th>
                        <th class="py-2.5 px-3">Lokasi Fasilitas</th>
                        <th class="py-2.5 px-3">Detail Kerusakan / Kendala</th>
                        <th class="py-2.5 px-3">Status Penanganan</th>
                        <th class="py-2.5 px-3 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($brokenItems as $item)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-3 font-semibold text-gray-800">{{ $item->name }}</td>
                        <td class="py-3 px-3 text-gray-600">{{ $item->location }}</td>
                        <td class="py-3 px-3">
                            <span class="text-rose-700 bg-rose-50 px-2 py-0.5 rounded font-medium">{{ $item->condition }}</span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <button class="px-2.5 py-1 text-xs border border-gray-300 rounded hover:bg-gray-100 text-gray-700 font-medium">Tugaskan Perbaikan</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Seluruh fasilitas operasional dalam kondisi prima.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection