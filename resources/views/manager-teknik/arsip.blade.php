@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Arsip Dokumen Operasional CNS/A</h1>
            <p class="text-sm text-gray-500 mt-1">
                Manajemen sertifikat, lisensi teknisi, ISR, kalibrasi fasilitas, dan dokumen pendukung LASIMI.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ url('/notifications') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-800 text-sm font-medium transition">
                <span>🔔 Cek Reminder Kedaluwarsa</span>
                @php
                    $unreadNotif = class_exists(\App\Models\Notification::class)
                        ? \App\Models\Notification::where('is_read', 0)->count()
                        : 0;
                @endphp
                @if($unreadNotif > 0)
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-rose-600 text-white">
                        {{ $unreadNotif }}
                    </span>
                @endif
            </a>

            <button type="button" onclick="document.getElementById('modalUnggah').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm transition cursor-pointer">
                + Unggah Dokumen
            </button>
        </div>
    </div>

    {{-- Alert Pesan --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter & Pencarian --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('arsip.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari judul atau nomor dokumen..."
                class="md:col-span-2 text-sm border border-gray-200 rounded-lg px-3.5 py-2.5 outline-none focus:border-blue-500">

            <select name="category_id" class="text-sm border border-gray-200 rounded-lg px-3.5 py-2.5 outline-none focus:border-blue-500">
                <option value="">-- Semua Kategori --</option>
                @foreach(($categories ?? collect()) as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name ?? $cat->nama_kategori ?? 'Kategori' }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('arsip.index') }}" class="px-3 py-2.5 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Arsip Dokumen --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Nomor &amp; Judul Dokumen</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Masa Berlaku</th>
                        <th class="py-3.5 px-4">Status Kedaluwarsa</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($documents as $doc)
                        @php
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            $badgeLabel = 'Aktif';

                            if (!empty($doc->tanggal_berlaku) && preg_match('/s\/d\s(.+)/', $doc->tanggal_berlaku, $m)) {
                                try {
                                    $exp = \Carbon\Carbon::parse(trim($m[1]));
                                    $sisaHari = (int) now()->startOfDay()->diffInDays($exp->startOfDay(), false);

                                    if ($sisaHari < 0) {
                                        $badgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
                                        $badgeLabel = 'Kadaluarsa (' . abs($sisaHari) . ' hari lalu)';
                                    } elseif ($sisaHari <= 7) {
                                        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                        $badgeLabel = 'Kritis (H-' . $sisaHari . ')';
                                    } elseif ($sisaHari <= 90) {
                                        $badgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                                        $badgeLabel = 'Segera Berakhir (H-' . $sisaHari . ')';
                                    }
                                } catch (\Exception $e) {}
                            }
                        @endphp

                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-4">
                                <div class="font-bold text-gray-900">{{ $doc->title }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $doc->document_number }}</div>
                            </td>
                            <td class="py-4 px-4 text-xs text-gray-600">
                                {{ $doc->category->name ?? $doc->category->nama_kategori ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-xs font-medium text-gray-700 whitespace-nowrap">
                                {{ $doc->tanggal_berlaku ?? '-' }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badgeClass }}">
                                    {{ $badgeLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap space-x-2">
                                @if(Route::has('documents.show'))
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                        class="inline-block text-xs font-medium text-blue-600 hover:text-blue-800 underline">
                                        Detail
                                    </a>
                                @endif

                                @if(Route::has('arsip.destroy'))
                                    <form action="{{ route('arsip.destroy', $doc->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-800 underline cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400 text-sm">
                                Belum ada dokumen operasional tersimpan. Klik <strong>+ Unggah Dokumen</strong> untuk menambahkan.
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
            <button type="button" onclick="document.getElementById('modalUnggah').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
        </div>

        <form id="formUnggahDokumen" action="{{ Route::has('arsip.store') ? route('arsip.store') : '#' }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
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
                    @foreach(($categories ?? collect()) as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name ?? $cat->nama_kategori ?? 'Kategori' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Masa Berlaku Dokumen (Dari &amp; Sampai Dengan)</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <span class="block text-[11px] text-gray-500 mb-1">Berlaku Dari Tanggal</span>
                        <input type="date" id="tanggal_mulai" required
                            onchange="gabungTanggalBerlaku()"
                            class="w-full text-sm border border-gray-200 rounded-lg p-2.5 outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <span class="block text-[11px] text-gray-500 mb-1">Sampai Dengan Tanggal</span>
                        <input type="date" id="tanggal_akhir" required
                            onchange="gabungTanggalBerlaku()"
                            class="w-full text-sm border border-gray-200 rounded-lg p-2.5 outline-none focus:border-blue-500">
                    </div>
                </div>
                <input type="hidden" name="tanggal_berlaku" id="tanggal_berlaku_gabungan" required>
                <p id="preview_tanggal_berlaku" class="text-[11px] text-blue-600 font-medium mt-1.5"></p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">File Dokumen (PDF)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx" required
                    class="w-full text-sm text-gray-600 border border-gray-200 rounded-lg p-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalUnggah').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm cursor-pointer">
                    Unggah Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function gabungTanggalBerlaku() {
        const mulai = document.getElementById('tanggal_mulai').value;
        const akhir = document.getElementById('tanggal_akhir').value;
        const inputHidden = document.getElementById('tanggal_berlaku_gabungan');
        const preview = document.getElementById('preview_tanggal_berlaku');

        if (mulai && akhir) {
            inputHidden.value = `${mulai} s/d ${akhir}`;
            preview.textContent = `Format tersimpan: ${mulai} s/d ${akhir}`;
        } else {
            inputHidden.value = '';
            preview.textContent = '';
        }
    }

    document.getElementById('formUnggahDokumen').addEventListener('submit', function (e) {
        gabungTanggalBerlaku();
        const mulai = document.getElementById('tanggal_mulai').value;
        const akhir = document.getElementById('tanggal_akhir').value;

        if (!mulai || !akhir) {
            e.preventDefault();
            alert('Silakan pilih tanggal mulai berlaku dan tanggal berakhir dokumen.');
            return;
        }
        if (akhir < mulai) {
            e.preventDefault();
            alert('Tanggal berakhir tidak boleh lebih awal dari tanggal mulai berlaku!');
            return;
        }
    });
</script>
@endsection