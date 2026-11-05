@extends('layouts.app')

@section('title', 'Rekap Instruksi Kerja Teknisi')

@section('content')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-blue-600"></i> Rekap Semua Instruksi Kerja Teknisi
            </h1>
            <p class="text-xs text-gray-500 mt-1">
                Lacak seluruh perintah, penugasan teknisi, status penyelesaian, serta unduh lampiran terkait.
            </p>
        </div>
        <a href="{{ route('mantek.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-2 shadow-sm transition">
            <i class="fa-solid fa-paper-plane"></i> Buat Perintah Baru
        </a>
    </div>

    <!-- Tabel Data Rekap -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Judul Instruksi</th>
                        <th class="py-2.5 px-3">Teknisi Bertugas</th>
                        <th class="py-2.5 px-3">Target Selesai</th>
                        <th class="py-2.5 px-3">Lampiran</th>
                        <th class="py-2.5 px-3">Status Terbit</th>
                        <th class="py-2.5 px-3">Status Pengerjaan</th>
                        <th class="py-2.5 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-3">
                            <span class="font-semibold text-gray-800 text-[13px] block">{{ $task->title }}</span>
                            <span class="text-[11px] text-gray-500">{{ $task->instruction ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-3 font-medium text-gray-600">{{ $task->technician->name ?? 'Semua Teknisi' }}</td>
                        <td class="py-3 px-3 text-slate-500">{{ \Carbon\Carbon::parse($task->target_date)->translatedFormat('d M Y') }}</td>
                        <td class="py-3 px-3">
                            @if($task->attachment)
                                <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-1 font-semibold">
                                    <i class="fa-solid fa-paperclip"></i> Unduh
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $task->status_terbit === 'Terbit' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $task->status_terbit }}
                            </span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium {{ $task->status_pengerjaan === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                                {{ $task->status_pengerjaan }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <form action="{{ route('mantek.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 p-1">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Belum ada catatan instruksi kerja.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection