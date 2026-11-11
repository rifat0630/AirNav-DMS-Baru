@extends('layouts.app')

@section('title', 'Panel Perintah & Delegasi Manager Teknik')

@section('content')

    <!-- Header Panel Manager Teknik AirNav Indonesia Cabang Banjarmasin -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-xl shadow-md border border-emerald-900/40 p-6 text-white space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider">
            Otoritas Eksekutif Operasional
        </span>
        <h1 class="text-xl font-bold mt-1 flex items-center gap-2">
            <i class="fa-solid fa-tower-observation text-emerald-400"></i> Panel Perintah Manager Teknik AirNav Indonesia Cabang Banjarmasin
        </h1>
        <p class="text-xs text-slate-300">
            Pusat instruksi dinas, delegasi perbaikan peralatan navigasi, dan pengawasan berkas operasional teknisi CNS/A.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- FORM KIRIM INSTRUKSI / PERINTAH KERJA (KIRI) -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2 border-b pb-3">
                <i class="fa-solid fa-paper-plane text-blue-600"></i> Kirim Perintah / Catatan / File ke Teknisi
            </h2>

            <form action="{{ route('manager-teknik.tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Perintah / Judul Tugas <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Kalibrasi Sudut Elevasi GP RWY 10" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Tugaskan Kepada <span class="text-rose-500">*</span></label>
                    <select name="assigned_to" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white font-medium">
                        <option value="">-- Pilih Penerima Tugas --</option>
                        
                        <!-- OPSI SEMUA -->
                        <option value="all" class="font-bold text-emerald-700 bg-emerald-50">
                            Semua
                        </option>
                        
                        <!-- GRUP NAMA TEKNISI -->
                        <optgroup label="Teknisi">
                            @foreach($technicians as $tech)
                                @php
                                    $roleRaw = strtolower($tech->role ?? 'teknisi');
                                    $roleBadge = match($roleRaw) {
                                        'manager_teknik', 'mantek' => 'MANTEK',
                                        'admin' => 'ADMIN',
                                        default => 'TEKNISI',
                                    };
                                @endphp
                                <option value="{{ $tech->id }}">{{ $tech->name }} ({{ $roleBadge }})</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Target Tanggal Selesai <span class="text-rose-500">*</span></label>
                    <input type="date" name="target_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Instruksi Khusus / SOP</label>
                    <textarea name="instruction" rows="3" placeholder="Tuliskan petunjuk teknis spesifik untuk personil pelaksana..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Lampirkan Dokumen / Gambar (Word, PDF, Foto)</label>
                    <input type="file" name="attachment" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" class="w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 cursor-pointer border border-gray-200 rounded-lg p-1 bg-slate-50">
                </div>

                <div class="flex gap-2 pt-2 border-t">
                    <button type="submit" name="action" value="draft" class="w-1/2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition text-center cursor-pointer">
                        Simpan Draf
                    </button>
                    <button type="submit" name="action" value="publish" class="w-1/2 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition text-center cursor-pointer">
                        Terbitkan
                    </button>
                </div>
            </form>
        </div>

        <!-- DAFTAR MONITORING TUGAS DINAS (KANAN) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Kartu Pantauan Instruksi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50/70 flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800">Status Instruksi Dinas Terakhir</h2>
                    <a href="{{ route('instruksi.index') }}" class="text-[11px] text-blue-600 hover:underline font-semibold">Lihat Semua Rekap &rarr;</a>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left text-xs">
                        <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                            <tr>
                                <th class="py-2.5 px-3">Perintah</th>
                                <th class="py-2.5 px-3">Pelaksana</th>
                                <th class="py-2.5 px-3">Target</th>
                                <th class="py-2.5 px-3">Status</th>
                                <th class="py-2.5 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tasks as $t)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $t->title }}</td>
                                <td class="py-2.5 px-3">
                                    @if(empty($t->assigned_to) || $t->assigned_to === 'all')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            Semua
                                        </span>
                                    @else
                                        <span class="text-gray-700 font-medium">
                                            {{ $t->technician->name ?? 'Semua' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-gray-500">{{ $t->target_date ? \Carbon\Carbon::parse($t->target_date)->format('d M Y') : '-' }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $t->status_pengerjaan === 'Selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $t->status_pengerjaan }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <form action="{{ route('manager-teknik.tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus instruksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">Belum ada perintah dinas aktif.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tautan Cepat Ke Memori Serah Terima Jabatan -->
            <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-emerald-900 text-sm">Dossier Memori Serah Terima Jabatan</h3>
                    <p class="text-xs text-emerald-700 mt-0.5">Rangkuman apa yang sudah selesai, sedang berjalan, dan rencana kerja ke depan.</p>
                </div>
                <a href="{{ route('manager-teknik.handover.index') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    Buka Memori Jabatan &rarr;
                </a>
            </div>
        </div>
    </div>

@endsection