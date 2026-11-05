@extends('layouts.app')

@section('title', 'Panel Khusus Manager Teknik')

@section('content')

    <!-- Header Panel Halaman -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-screwdriver-wrench text-blue-600"></i> Menu Khusus Manager Teknik
            </h1>
            <p class="text-xs text-gray-500 mt-1">
                Monitoring fasilitas, arsip dokumen teknik, dan koordinasi instruksi operasional teknisi.
            </p>
        </div>
        <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg">
            <i class="fa-regular fa-calendar-days text-slate-500"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- Form Kirim Perintah / Catatan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50/70 px-5 py-3.5 flex items-center justify-between">
            <h2 class="text-xs font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                <i class="fa-solid fa-paper-plane text-blue-600"></i> Kirim Perintah / Catatan / File ke Teknisi
            </h2>
            <span class="text-xs text-gray-400">Catatan draf tidak akan tampil di panel teknisi.</span>
        </div>

        <form action="{{ route('mantek.tasks.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Judul Perintah / Catatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Pengecekan Rutin DME" 
                           class="w-full text-sm rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Tugaskan Kepada</label>
                    <select name="assigned_to" class="w-full text-sm rounded-lg border border-gray-300 px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                        <option value="">-- Semua Teknisi (Pengumuman Bersama) --</option>
                        @isset($technicians)
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Target Tanggal Pengerjaan <span class="text-rose-500">*</span></label>
                    <input type="date" name="target_date" value="{{ date('Y-m-d') }}" required
                           class="w-full text-sm rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Teks Catatan / Instruksi</label>
                <textarea name="instruction" rows="3" placeholder="Tulis instruksi atau catatan teknis di sini..."
                          class="w-full text-sm rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Upload File / Gambar</label>
                <input type="file" name="attachment" 
                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit" name="action" value="draft" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-xs font-semibold transition flex items-center gap-2">
                    <i class="fa-regular fa-bookmark"></i> Simpan Draf (Belum Terlihat Teknisi)
                </button>
                <button type="submit" name="action" value="publish" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Kirim & Publikasikan Langsung
                </button>
            </div>
        </form>
    </div>

    <!-- 2 Kolom: Monitoring & Arsip Dokumen -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Monitoring Barang Rusak -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-amber-50/50 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm"></i>
                    Monitoring Barang Rusak / Perlu Perbaikan
                </h3>
            </div>
            <div class="p-4">
                <table class="w-full text-left text-xs">
                    <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                        <tr>
                            <th class="py-2 px-3">Nama Barang</th>
                            <th class="py-2 px-3">Lokasi</th>
                            <th class="py-2 px-3">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($brokenItems ?? [] as $item)
                        <tr>
                            <td class="py-2 px-3 font-medium">{{ $item->name ?? $item->nama_barang }}</td>
                            <td class="py-2 px-3 text-gray-600">{{ $item->location ?? $item->lokasi }}</td>
                            <td class="py-2 px-3"><span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-semibold">{{ $item->condition ?? $item->kondisi }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-400">Tidak ada barang rusak.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Arsip Dokumen Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-blue-50/50 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-blue-500 text-sm"></i>
                    Arsip Dokumen Terbaru
                </h3>
            </div>
            <div class="p-4">
                <table class="w-full text-left text-xs">
                    <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                        <tr>
                            <th class="py-2 px-3">Nama Dokumen</th>
                            <th class="py-2 px-3">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($latestDocuments ?? [] as $doc)
                        @php
                            $attrs = is_object($doc) && method_exists($doc, 'getAttributes') ? $doc->getAttributes() : (array) $doc;
                            $filtered = array_filter($attrs, function($val, $key) {
                                return !in_array(strtolower($key), ['id', 'created_at', 'updated_at', 'deleted_at', 'kategori', 'category', 'category_id', 'file', 'file_path', 'lampiran']) 
                                    && is_string($val) 
                                    && strlen(trim($val)) > 0;
                            }, ARRAY_FILTER_USE_BOTH);
                            $docTitle = reset($filtered) ?: ($doc->nama ?? $doc->judul ?? 'Dokumen');
                        @endphp
                        <tr>
                            <td class="py-2 px-3 font-medium flex items-center gap-2">
                                <i class="fa-regular fa-file text-slate-400"></i>
                                <span>{{ $docTitle }}</span>
                            </td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">
                                    {{ $doc->kategori ?? (isset($doc->category) ? $doc->category->name : 'ISR') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-6 text-center text-gray-400">Belum ada dokumen terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Rekap Daftar Instruksi Kerja & Catatan Mantek -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-slate-50/60">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-indigo-600"></i>
                Daftar Instruksi Kerja & Catatan Mantek
            </h3>
            <span class="text-[11px] text-gray-400">Total: {{ count($tasks ?? []) }} tugas</span>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Judul / Instruksi</th>
                        <th class="py-2.5 px-3">Penerima</th>
                        <th class="py-2.5 px-3">Target Tanggal</th>
                        <th class="py-2.5 px-3">Lampiran</th>
                        <th class="py-2.5 px-3">Status Terbit</th>
                        <th class="py-2.5 px-3">Status Pengerjaan</th>
                        <th class="py-2.5 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tasks ?? [] as $task)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-2.5 px-3">
                            <div class="font-semibold text-gray-800 text-[13px]">{{ $task->title ?? $task->judul }}</div>
                            @if(!empty($task->instruction ?? $task->instruksi))
                                <div class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{{ $task->instruction ?? $task->instruksi }}</div>
                            @endif
                        </td>
                        <td class="py-2.5 px-3 text-gray-600 font-medium">
                            {{ $task->technician->name ?? $task->assignedUser->name ?? $task->penerima ?? 'Semua Teknisi' }}
                        </td>
                        <td class="py-2.5 px-3 text-slate-500">
                            @if(!empty($task->target_date))
                                {{ \Carbon\Carbon::parse($task->target_date)->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-2.5 px-3">
                            @php
                                $attachmentPath = $task->attachment ?? $task->file_path ?? null;
                            @endphp
                            @if($attachmentPath)
                                <a href="{{ asset('storage/' . $attachmentPath) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold text-[11px] transition">
                                    <i class="fa-solid fa-paperclip"></i> Lihat File
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-3">
                            @if(($task->status_terbit ?? '') === 'Terbit')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terbit
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-200 text-gray-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Draf
                                </span>
                            @endif
                        </td>
                        <td class="py-2.5 px-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ ($task->status_pengerjaan ?? '') === 'Selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ $task->status_pengerjaan ?? 'Belum Selesai' }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <form action="{{ route('mantek.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus instruksi kerja ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded transition" title="Hapus Tugas">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            <i class="fa-regular fa-clipboard text-2xl text-gray-300 mb-1.5 block"></i>
                            Belum ada tugas atau catatan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection