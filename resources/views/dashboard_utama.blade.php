@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-gauge text-blue-600"></i> Dashboard Utama Operasional CNS/A
            </h1>
            <p class="text-xs text-gray-500 mt-1">
                Ikhtisar sistem manajemen dokumen, status fasilitas navigasi, dan koordinasi tim teknisi.
            </p>
        </div>
        <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg">
            <i class="fa-regular fa-calendar-days text-slate-500"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Total Arsip Dokumen</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalDocs }} Dokumen</p>
                <a href="{{ route('arsip.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">Lihat arsip &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Total Instruksi Kerja</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalTasks }} Tugas</p>
                <a href="{{ route('instruksi.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">Kelola instruksi &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Kondisi Fasilitas</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">Normal Operasional</p>
                <a href="{{ route('monitoring.index') }}" class="text-xs text-emerald-600 hover:underline mt-1 inline-block">Status fasilitas &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-shield-check"></i>
            </div>
        </div>
    </div>

    <!-- Ringkasan Dokumen & Tugas Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Dokumen Baru -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h2 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-file-lines text-blue-500"></i> Dokumen Terakhir Ditambahkan
            </h2>
            <div class="divide-y divide-gray-100 text-xs">
                @forelse($latestDocs as $doc)
                <div class="py-2.5 flex justify-between items-center">
                    <span class="font-medium text-gray-800">{{ $doc->nama ?? $doc->judul ?? 'Dokumen' }}</span>
                    <span class="text-gray-400">{{ isset($doc->created_at) ? $doc->created_at->format('d/m/Y') : '-' }}</span>
                </div>
                @empty
                <p class="text-gray-400 py-3 text-center">Belum ada dokumen.</p>
                @endforelse
            </div>
        </div>

        <!-- Tugas Baru -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h2 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-tasks text-emerald-500"></i> Instruksi Mantek Terbaru
            </h2>
            <div class="divide-y divide-gray-100 text-xs">
                @forelse($recentTasks as $task)
                <div class="py-2.5 flex justify-between items-center">
                    <div>
                        <span class="font-semibold text-gray-800 block">{{ $task->title }}</span>
                        <span class="text-gray-400">{{ $task->technician->name ?? 'Semua Teknisi' }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">{{ $task->status_pengerjaan }}</span>
                </div>
                @empty
                <p class="text-gray-400 py-3 text-center">Belum ada tugas.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection