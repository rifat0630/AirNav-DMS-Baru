@extends('layouts.app')

@section('title', 'Arsip Dokumen CNS/A')

@section('content')

    <!-- Header Panel & Toolbar Filter Lengkap -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
        <!-- Baris Atas: Judul Halaman -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
            <div>
                <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-folder-tree text-blue-600"></i> Arsip Dokumen Teknik Navigasi & Komunikasi
                </h1>
                <p class="text-xs text-gray-500 mt-0.5">
                    Kumpulan izin ISR, LOCA, SOP, Manual Alat, Kalibrasi, Wiring, Uji Riksa, dan RAB fasilitas CNS/A.
                </p>
            </div>
        </div>

        <!-- Baris Bawah: Filter Dropdown Kategori + Pencarian + Tombol Upload -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
            <form action="{{ route('arsip.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <!-- Dropdown Kategori -->
                <div class="relative">
                    <select name="category" onchange="this.form.submit()" 
                            class="text-xs font-semibold rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-sm">
                        <option value="">Semua Dokumen (Semua Kategori)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}" {{ request('category') === $cat->name ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Pencarian -->
                <div class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari izin / dokumen..." 
                           class="text-xs rounded-l-lg border border-gray-300 px-3 py-2 outline-none focus:border-blue-500 w-44 md:w-56">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-3 py-2 rounded-r-lg transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                @if(request('category') || request('search'))
                    <a href="{{ route('arsip.index') }}" class="text-xs text-rose-600 hover:underline font-semibold ml-1">
                        Reset Filter
                    </a>
                @endif
            </form>

            <button onclick="document.getElementById('modalUpload').classList.remove('hidden')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-2 shadow-sm transition shrink-0">
                <i class="fa-solid fa-cloud-arrow-up"></i> Unggah Dokumen
            </button>
        </div>
    </div>

    <!-- Tabel Dokumen Tersimpan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50/70 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800">Daftar Dokumen Tersimpan</h2>
            <span class="text-xs text-gray-400">Total: {{ method_exists($documents, 'total') ? $documents->total() : count($documents) }} berkas</span>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Nama / Identitas Dokumen</th>
                        <th class="py-2.5 px-3">No. Izin / Dokumen</th>
                        <th class="py-2.5 px-3">Kategori</th>
                        <th class="py-2.5 px-3">Tanggal Dokumen</th>
                        <th class="py-2.5 px-3 text-center">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($documents as $doc)
                    @php
                        $attrs = is_object($doc) && method_exists($doc, 'getAttributes') ? $doc->getAttributes() : (array) $doc;
                        $filtered = array_filter($attrs, function($val, $key) {
                            return !in_array(strtolower($key), ['id', 'created_at', 'updated_at', 'deleted_at', 'kategori', 'category', 'category_id', 'file', 'file_path', 'nomor_izin', 'no_izin', 'tanggal_dokumen', 'tanggal']) 
                                && is_string($val) 
                                && strlen(trim($val)) > 0;
                        }, ARRAY_FILTER_USE_BOTH);
                        $docTitle = reset($filtered) ?: ($doc->nama ?? 'Dokumen CNS');
                        $catName = $doc->kategori ?? (isset($doc->category) ? $doc->category->name : 'Dokumen');
                        $noIzin = $doc->nomor_izin ?? $doc->no_izin ?? '-';
                        $tglDok = $doc->tanggal_dokumen ?? $doc->tanggal ?? $doc->created_at;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-3 font-medium text-gray-800 flex items-center gap-2.5">
                            <i class="fa-regular fa-file-pdf text-rose-500 text-base"></i>
                            <div>
                                <span class="block font-semibold text-gray-800 text-[13px]">{{ $docTitle }}</span>
                                <span class="text-[11px] text-gray-400">Format: Dokumen Resmi Terverifikasi</span>
                            </div>
                        </td>
                        <td class="py-3 px-3 font-mono text-gray-700">
                            {{ $noIzin }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                {{ $catName }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-slate-500">
                            {{ $tglDok ? \Carbon\Carbon::parse($tglDok)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td class="py-3 px-3 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('arsip.preview', $doc->id) }}" target="_blank" 
                                   class="px-2.5 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold text-[11px] transition flex items-center gap-1" title="Lihat di tab baru">
                                    <i class="fa-solid fa-eye"></i> Preview
                                </a>
                                <a href="{{ route('arsip.preview', $doc->id) }}?download=1" download 
                                   class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 font-semibold text-[11px] transition flex items-center gap-1" title="Download File">
                                    <i class="fa-solid fa-download"></i> Unduh
                                </a>
                                <button onclick="openEditModal({{ $doc->id }}, '{{ addslashes($docTitle) }}', '{{ addslashes($noIzin !== '-' ? $noIzin : '') }}', '{{ $catName }}', '{{ $tglDok ? \Carbon\Carbon::parse($tglDok)->format('Y-m-d') : '' }}')" 
                                        class="p-1.5 text-amber-600 hover:bg-amber-50 rounded transition" title="Edit Dokumen">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('arsip.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition" title="Hapus Dokumen">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada dokumen yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($documents, 'links'))
            <div class="p-4 border-t border-gray-100">{{ $documents->links() }}</div>
        @endif
    </div>

    <!-- MODAL 1: UPLOAD DOKUMEN BARU -->
    <div id="modalUpload" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-lg w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-blue-600"></i> Unggah Dokumen Baru
                </h3>
                <button onclick="document.getElementById('modalUpload').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama / Judul Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: Dokumen Kalibrasi DVOR 2026" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. Izin / No. Dokumen</label>
                        <input type="text" name="nomor_izin" placeholder="Contoh: 500/DU/SDPPI/2026" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Tanggal, Bulan & Tahun <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_dokumen" value="{{ date('Y-m-d') }}" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Kategori Dokumen <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white">
                        <option value="">-- Pilih Kategori Dokumen --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Pilih File (PDF, Word, Excel) <span class="text-rose-500">*</span></label>
                    <input type="file" name="file" required class="w-full text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold cursor-pointer">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalUpload').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Unggah Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT DOKUMEN -->
    <div id="modalEdit" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-lg w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-regular fa-pen-to-square text-amber-600"></i> Edit Informasi Dokumen
                </h3>
                <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <form id="formEdit" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama / Judul Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" id="editNama" name="nama" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. Izin / No. Dokumen</label>
                        <input type="text" id="editNoIzin" name="nomor_izin" placeholder="Contoh: 500/DU/SDPPI/2026" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Tanggal, Bulan & Tahun <span class="text-rose-500">*</span></label>
                        <input type="date" id="editTanggal" name="tanggal_dokumen" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Kategori Dokumen <span class="text-rose-500">*</span></label>
                    <select id="editKategori" name="kategori" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Ganti Berkas File (Opsional)</label>
                    <input type="file" name="file" class="w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 cursor-pointer">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Kontrol Modal Edit -->
    <script>
        function openEditModal(id, nama, noIzin, kategori, tanggal) {
            document.getElementById('editNama').value = nama;
            document.getElementById('editNoIzin').value = noIzin;
            document.getElementById('editKategori').value = kategori;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('formEdit').action = '/arsip-dokumen/' + id;
            document.getElementById('modalEdit').classList.remove('hidden');
        }
    </script>

@endsection