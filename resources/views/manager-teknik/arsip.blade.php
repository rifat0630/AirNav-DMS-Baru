@extends('layouts.app')

@section('title', 'Arsip Dokumen CNS/A')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-blue-600 font-semibold text-sm mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Portal Monitoring & Perintah Operasional CNS/A Banjarmasin</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Arsip Dokumen Teknik Navigasi & Komunikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Kumpulan izin ISR, LOCA, SOP, Manual Alat, Kalibrasi, Wiring, Uji Riksa, dan RAB fasilitas CNS/A.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('modalUnggah').classList.remove('hidden')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Unggah Dokumen
            </button>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('arsip.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="w-full md:w-72">
                <select name="category" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg px-3 py-2.5 outline-none focus:border-blue-500">
                    <option value="">Semua Dokumen (Semua Kategori)</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari izin / dokumen..." class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg pl-3 pr-10 py-2.5 outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Dokumen -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Daftar Dokumen Tersimpan</h2>
            <span class="text-xs text-gray-400">Total: {{ $documents->total() }} berkas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Nama / Identitas Dokumen</th>
                        <th class="py-3.5 px-4 text-center">No. Izin / Dokumen</th>
                        <th class="py-3.5 px-4 text-center">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Tanggal Dokumen</th>
                        <th class="py-3.5 px-4 text-center">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($documents as $doc)
                        @php
                            $docTitle = $doc->title ?? $doc->nama_dokumen ?? $doc->nama ?? 'Dokumen ISR Fasilitas CNS/A';
                            $noIzin = $doc->document_number ?? $doc->nomor_izin ?? $doc->no_izin ?? '-';
                            $categoryName = $doc->category->name ?? ($doc->kategori ?? 'Izin Stasiun Radio (ISR)');
                            $tanggalDok = $doc->tanggal_berlaku ?? $doc->created_at;
                            $tanggalFormatted = $tanggalDok ? \Carbon\Carbon::parse($tanggalDok)->translatedFormat('d F Y') : '-';
                            $fileUrl = $doc->file_path ? asset('storage/' . $doc->file_path) : '#';
                        @endphp

                        <tr class="border-b border-gray-100 hover:bg-slate-50/80 transition duration-150">
                            <!-- NAMA / IDENTITAS DOKUMEN -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 text-rose-500 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block font-semibold text-gray-800 text-[13px] leading-snug">{{ $docTitle }}</span>
                                        <span class="text-[11px] text-gray-400">Format: Dokumen Resmi Terverifikasi</span>
                                    </div>
                                </div>
                            </td>

                            <!-- NO. IZIN / DOKUMEN -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded bg-slate-100 text-slate-700 text-xs font-mono font-medium">
                                    {{ $noIzin }}
                                </span>
                            </td>

                            <!-- KATEGORI -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-semibold border border-blue-200/50">
                                    {{ $categoryName }}
                                </span>
                            </td>

                            <!-- TANGGAL DOKUMEN -->
                            <td class="py-3.5 px-4 text-center text-xs text-gray-500 font-medium">
                                {{ $tanggalFormatted }}
                            </td>

                            <!-- AKSI DOKUMEN -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       class="px-2.5 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold text-[11px] transition flex items-center gap-1 shadow-sm"
                                       title="Lihat di tab baru">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview
                                    </a>

                                    <a href="{{ $fileUrl }}" download
                                       class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 font-semibold text-[11px] transition flex items-center gap-1 shadow-sm"
                                       title="Download File">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Unduh
                                    </a>

                                    @if(Route::has('arsip.destroy'))
                                    <form action="{{ route('arsip.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition" title="Hapus Dokumen">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm">Tidak ada dokumen yang ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($documents->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Unggah Dokumen -->
<div id="modalUnggah" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-100">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-slate-50">
            <h3 class="font-bold text-gray-900 text-base">Unggah Dokumen Operasional CNS/A</h3>
            <button type="button" onclick="document.getElementById('modalUnggah').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        <form action="{{ Route::has('arsip.store') ? route('arsip.store') : '#' }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nama / Identitas Dokumen</label>
                <input type="text" name="title" required placeholder="Contoh: Izin Stasiun Radio VHF-A/G Main" class="w-full text-sm border border-gray-200 rounded-lg p-2.5 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nomor Izin / Dokumen</label>
                <input type="text" name="document_number" required placeholder="Contoh: ISR/CNS-BJM/042/2026" class="w-full text-sm border border-gray-200 rounded-lg p-2.5 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kategori Dokumen</label>
                <select name="category_id" required class="w-full text-sm border border-gray-200 rounded-lg p-2.5 outline-none focus:border-blue-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tanggal Dokumen / Berlaku</label>
                <input type="date" name="tanggal_berlaku" value="{{ date('Y-m-d') }}" class="w-full text-sm border border-gray-200 rounded-lg p-2.5 outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">File Dokumen (PDF)</label>
                <input type="file" name="file" accept=".pdf" required class="w-full text-sm border border-gray-200 rounded-lg p-2 outline-none focus:border-blue-500 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modalUnggah').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium shadow-sm">Unggah Sekarang</button>
            </div>
        </form>
    </div>
</div>
@endsection