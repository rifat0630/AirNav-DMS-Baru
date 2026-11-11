@extends('layouts.app')

@section('title', 'Monitoring Inventory & Fasilitas CNS/A')

@section('content')

    <!-- Header Panel & Aksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-emerald-600"></i> Monitoring Inventory & Peralatan Fasilitas CNS/A
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Pencatatan inventaris suku cadang, alur penugasan perbaikan, verifikasi bertingkat teknisi & Mantek, serta rekam arsip historis.
                </p>
            </div>
            <!-- Tombol Tambah Peralatan -->
            <button onclick="document.getElementById('modalTambahBarang').classList.remove('hidden')" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 shadow-sm transition shrink-0">
                <i class="fa-solid fa-plus"></i> Tambah Peralatan / Barang
            </button>
        </div>

        <!-- Kartu Statistik Indikator -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <div class="bg-rose-50/70 border border-rose-100 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-rose-700 block">Butuh Tindakan / Rusak</span>
                    <span class="text-2xl font-bold text-rose-800">{{ $totalRusak ?? 0 }} Unit</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>

            <div class="bg-amber-50/70 border border-amber-100 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-amber-700 block">Dalam Proses Pengerjaan</span>
                    <span class="text-2xl font-bold text-amber-800">{{ $totalProses ?? 0 }} Fasilitas</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
            </div>

            <div class="bg-emerald-50/70 border border-emerald-100 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-emerald-700 block">Normal / Siap Operasional</span>
                    <span class="text-2xl font-bold text-emerald-800">{{ $totalNormal ?? 0 }} Fasilitas</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Toolbar Filter Lengkap -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
            <form action="{{ route('monitoring.index') }}" method="GET" class="flex flex-wrap items-center gap-2 text-xs">
                <!-- Filter Kondisi -->
                <select name="kondisi" onchange="this.form.submit()" 
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none focus:border-emerald-500 font-semibold shadow-sm cursor-pointer">
                    <option value="">Semua Kondisi Barang</option>
                    <option value="Normal" {{ request('kondisi') === 'Normal' ? 'selected' : '' }}>Kondisi Normal</option>
                    <option value="Perlu Perbaikan" {{ request('kondisi') === 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    <option value="Rusak Berat" {{ request('kondisi') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>

                <!-- Pencarian -->
                <div class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang / lokasi..." 
                           class="rounded-l-lg border border-gray-300 px-3 py-2 outline-none focus:border-emerald-500 w-44 md:w-56">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-2 rounded-r-lg transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                @if(request('kondisi') || request('search'))
                    <a href="{{ route('monitoring.index') }}" class="text-rose-600 hover:underline font-semibold ml-1">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Inventory & Fasilitas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50/70 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800">Daftar Peralatan & Rekam Kondisi</h2>
            <span class="text-xs text-gray-400">Total: {{ method_exists($inventories, 'total') ? $inventories->total() : count($inventories) }} barang</span>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Nama Alat & Foto</th>
                        <th class="py-2.5 px-3">Lokasi Fasilitas</th>
                        <th class="py-2.5 px-3">Jumlah & Satuan</th>
                        <th class="py-2.5 px-3">Kondisi & Kerusakan</th>
                        <th class="py-2.5 px-3">Jadwal (Rusak / Selesai)</th>
                        <th class="py-2.5 px-3">Teknisi & Paraf Validasi</th>
                        <th class="py-2.5 px-3 text-center">Status & Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $isMantekUser = in_array(auth()->user()->role ?? '', ['manager_teknik', 'mantek']);
                    @endphp

                    @forelse($inventories as $item)
                    @php
                        $namaAlat = $item->name ?? $item->nama_barang ?? $item->nama ?? 'Peralatan CNS';
                        $lokasiAlat = $item->lokasi ?? $item->location ?? '-';
                        $detailKerusakan = $item->detail_kerusakan ?? '-';
                        $isAssigned = !empty($item->teknisi_bertugas) && $item->teknisi_bertugas !== '-';
                        $hasTeknisiSign = !empty($item->paraf_teknisi) && str_starts_with($item->paraf_teknisi, 'data:image');
                        $hasMantekSign = !empty($item->paraf_mantek) && str_starts_with($item->paraf_mantek, 'data:image');
                        $statusPenanganan = $item->status_penanganan ?? 'Pending';
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <!-- Nama Barang & Foto -->
                        <td class="py-3 px-3">
                            <div class="flex items-start gap-2.5">
                                @if(!empty($item->foto))
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Barang" class="w-10 h-10 object-cover rounded-lg border border-gray-200 shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                        <i class="fa-solid fa-camera"></i>
                                    </div>
                                @endif
                                <div>
                                    <span class="font-bold text-gray-800 text-[13px] block">{{ $namaAlat }}</span>
                                    <span class="text-[11px] text-gray-500 italic block">{{ $item->keterangan_foto ?: 'Tidak ada keterangan foto' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Lokasi -->
                        <td class="py-3 px-3">
                            <span class="font-semibold text-gray-700 bg-slate-100 px-2 py-0.5 rounded text-[11px]">
                                <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>{{ $lokasiAlat }}
                            </span>
                        </td>

                        <!-- Jumlah & Satuan -->
                        <td class="py-3 px-3 font-semibold text-gray-800">
                            {{ $item->jumlah ?? $item->quantity ?? 1 }} <span class="text-slate-500 font-normal uppercase text-[11px]">{{ $item->satuan ?? $item->unit ?? 'pcs' }}</span>
                        </td>

                        <!-- Kondisi & Kerusakan -->
                        <td class="py-3 px-3">
                            @if(($item->kondisi ?? '') === 'Normal')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    Normal
                                </span>
                                @if(!empty($item->riwayat_perbaikan))
                                    <span class="block text-[10px] text-blue-600 font-medium mt-1">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Pernah Diperbaiki
                                    </span>
                                @endif
                            @elseif(($item->kondisi ?? '') === 'Perlu Perbaikan')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                    Perlu Perbaikan
                                </span>
                                <span class="block text-[11px] text-rose-600 font-medium mt-1">{{ $detailKerusakan }}</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                    Rusak Berat
                                </span>
                                <span class="block text-[11px] text-rose-600 font-medium mt-1">{{ $detailKerusakan }}</span>
                            @endif
                        </td>

                        <!-- Tanggal Rusak & Perbaikan -->
                        <td class="py-3 px-3 text-[11px] text-gray-600">
                            <div><span class="text-gray-400">Rusak:</span> {{ $item->tanggal_rusak ? \Carbon\Carbon::parse($item->tanggal_rusak)->format('d/m/Y') : '-' }}</div>
                            <div><span class="text-gray-400">Selesai:</span> {{ $item->tanggal_perbaikan ? \Carbon\Carbon::parse($item->tanggal_perbaikan)->format('d/m/Y') : '-' }}</div>
                        </td>

                        <!-- Teknisi & Tampilan Paraf -->
                        <td class="py-3 px-3">
                            <span class="font-semibold text-gray-800 block text-xs">{{ $item->teknisi_bertugas ?: '-' }}</span>
                            
                            <!-- Paraf Teknisi -->
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="text-[10px] text-gray-400 w-12">Teknisi:</span>
                                @if($hasTeknisiSign)
                                    <img src="{{ $item->paraf_teknisi }}" alt="Paraf Teknisi" class="h-6 max-w-[60px] object-contain border border-slate-200 rounded bg-white p-0.5">
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Belum Paraf</span>
                                @endif
                            </div>

                            <!-- Paraf Mantek -->
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="text-[10px] text-gray-400 w-12">Mantek:</span>
                                @if($hasMantekSign)
                                    <img src="{{ $item->paraf_mantek }}" alt="Paraf Mantek" class="h-6 max-w-[60px] object-contain border border-slate-200 rounded bg-white p-0.5">
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Belum Paraf</span>
                                @endif
                            </div>
                        </td>

                        <!-- Status & Tombol Aksi RBAC -->
                        <td class="py-3 px-3 text-center">
                            <div class="flex flex-col gap-1 items-center justify-center min-w-[130px]">
                                @if(($item->kondisi ?? '') !== 'Normal')
                                    @if(!$isAssigned)
                                        <!-- 1. Belum Ditugaskan -->
                                        <button onclick="openAssignModal({{ $item->id }}, '{{ addslashes($namaAlat) }}', '{{ addslashes($lokasiAlat) }}', '{{ addslashes($detailKerusakan) }}')"
                                                class="w-full px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[11px] shadow-sm transition flex items-center justify-center gap-1.5">
                                            <i class="fa-solid fa-user-gear"></i> Tugaskan
                                        </button>
                                    @elseif(!$hasTeknisiSign)
                                        <!-- 2. Sedang Dikerjakan: Teknisi/Admin Melaporkan Selesai & Paraf Teknisi -->
                                        <button onclick="openTeknisiSignModal({{ $item->id }}, '{{ addslashes($namaAlat) }}', '{{ addslashes($item->teknisi_bertugas) }}', '{{ addslashes($detailKerusakan) }}')"
                                                class="w-full px-2.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-[11px] shadow-sm transition flex items-center justify-center gap-1">
                                            <i class="fa-solid fa-pen-clip"></i> Selesai (Paraf Teknisi)
                                        </button>
                                        <span class="text-[10px] text-amber-600 font-semibold flex items-center gap-1">
                                            <i class="fa-solid fa-screwdriver-wrench"></i> Dikerjakan Teknisi
                                        </span>
                                    @else
                                        <!-- 3. Teknisi Sudah Paraf: Menunggu Pengesahan Mantek -->
                                        @if($isMantekUser)
                                            <!-- Mantek yang login melihat tombol Sahkan -->
                                            <button onclick="openMantekSignModal({{ $item->id }}, '{{ addslashes($namaAlat) }}', '{{ addslashes($item->teknisi_bertugas) }}', '{{ addslashes($item->catatan_teknisi ?? $detailKerusakan) }}')"
                                                    class="w-full px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] shadow-sm transition flex items-center justify-center gap-1">
                                                <i class="fa-solid fa-stamp"></i> Sahkan & Paraf Mantek
                                            </button>
                                        @else
                                            <!-- Admin / Teknisi melihat badge status menunggu -->
                                            <span class="w-full px-2 py-1 rounded bg-amber-50 text-amber-700 font-semibold text-[10px] border border-amber-200 block">
                                                <i class="fa-solid fa-clock"></i> Menunggu Paraf Mantek
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    <!-- Jika Kondisi Sudah Normal -->
                                    <span class="text-emerald-600 font-bold text-xs flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> Siap Operasional
                                    </span>
                                    @if(!empty($item->riwayat_perbaikan))
                                        <button onclick="openHistoryModal('{{ addslashes($namaAlat) }}', '{{ addslashes($item->riwayat_perbaikan) }}', '{{ $item->paraf_teknisi }}', '{{ $item->paraf_mantek }}')"
                                                class="text-blue-600 hover:text-blue-800 text-[11px] font-semibold underline mt-0.5 flex items-center gap-1">
                                            <i class="fa-solid fa-clock-rotate-left"></i> Lihat Riwayat
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Belum ada data peralatan inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($inventories, 'links'))
            <div class="p-4 border-t border-gray-100">{{ $inventories->links() }}</div>
        @endif
    </div>

    <!-- MODAL 1: FORM TAMBAH INVENTARIS -->
    <div id="modalTambahBarang" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-xl w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-emerald-600"></i> Catat Inventaris / Peralatan
                </h3>
                <button onclick="document.getElementById('modalTambahBarang').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Peralatan / Modul <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_barang" required placeholder="Contoh: Tang Crimping / VHF Receiver" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Lokasi Fasilitas / Shelter <span class="text-rose-500">*</span></label>
                        <input type="text" name="lokasi" required placeholder="Contoh: Tower / Shelter GP RWY 10" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Jumlah Stok <span class="text-rose-500">*</span></label>
                        <input type="number" step="any" name="jumlah" value="1" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Pilihan Satuan <span class="text-rose-500">*</span></label>
                        <input type="text" name="satuan" list="satuanList" required placeholder="meter / cm / pcs..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                        <datalist id="satuanList">
                            <option value="pcs">
                            <option value="meter">
                            <option value="cm">
                            <option value="unit">
                            <option value="roll">
                            <option value="set">
                            <option value="pack">
                        </datalist>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kondisi Barang <span class="text-rose-500">*</span></label>
                        <select id="selectKondisi" name="kondisi" onchange="toggleKondisiFields()" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white font-semibold">
                            <option value="Normal">Normal (Beroperasi)</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <!-- Bagian Kerusakan -->
                <div id="sectionKerusakan" class="hidden space-y-3 bg-rose-50/50 p-3.5 rounded-lg border border-rose-100">
                    <div>
                        <label class="block font-semibold text-rose-800 mb-1">Detail Kerusakan / Kendala Alat <span class="text-rose-500">*</span></label>
                        <textarea id="detailKerusakanInput" name="detail_kerusakan" rows="2" placeholder="Jelaskan modul yang rusak atau parameter yang drop..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-rose-500 bg-white"></textarea>
                    </div>
                    <div>
                        <label class="block font-semibold text-rose-800 mb-1">Kapan Rusak (Tanggal Kerusakan)</label>
                        <input type="date" name="tanggal_rusak" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-rose-500 bg-white">
                    </div>
                </div>

                <!-- Foto Barang -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Unggah Foto Fisik Barang</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:bg-emerald-50 file:text-emerald-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Keterangan / Deskripsi Foto</label>
                        <input type="text" name="keterangan_foto" placeholder="Contoh: Kondisi fisik rak modul" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTambahBarang').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold">Simpan Inventaris</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: TUGASKAN PERBAIKAN -->
    <div id="modalAssignTask" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-blue-600"></i> Disposisi Perbaikan Fasilitas
                </h3>
                <button onclick="document.getElementById('modalAssignTask').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <form action="{{ route('inventory.assignTask') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <input type="hidden" id="assignInvId" name="inventory_id">
                <input type="hidden" id="assignItemName" name="item_name">

                <div class="bg-slate-50 p-3 rounded-lg border border-slate-200 space-y-1">
                    <div class="font-bold text-gray-800" id="displayItemName">Nama Alat</div>
                    <div class="text-[11px] text-gray-500" id="displayItemLocation">Lokasi: -</div>
                    <div class="text-[11px] text-rose-600 font-semibold" id="displayItemIssue">Kerusakan: -</div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Tugaskan Kepada Teknisi <span class="text-rose-500">*</span></label>
                    <select name="technician_id" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white font-medium">
                        <option value="">-- Pilih Teknisi Pelaksana --</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }} ({{ strtoupper($tech->role ?? 'Teknisi') }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Target Tanggal Selesai <span class="text-rose-500">*</span></label>
                    <input type="date" name="target_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Instruksi Khusus Mantek <span class="text-rose-500">*</span></label>
                    <textarea name="instruction" rows="3" required placeholder="Tuliskan petunjuk teknis perbaikan atau SOP..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalAssignTask').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Kirim Tugas Perbaikan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: LAPORAN SELESAI OLEH TEKNISI (PARAF TEKNISI) -->
    <div id="modalTeknisiSign" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6 space-y-4 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pen-clip text-indigo-600"></i> Laporan Selesai & Paraf Teknisi
                </h3>
                <button onclick="document.getElementById('modalTeknisiSign').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <form action="{{ route('inventory.signTeknisi') }}" method="POST" onsubmit="return submitTeknisiSignature()" class="space-y-3.5 text-xs">
                @csrf
                <input type="hidden" id="tekSignInvId" name="inventory_id">
                <input type="hidden" id="tekSignatureData" name="signature_data">

                <div class="bg-indigo-50/70 border border-indigo-100 p-3 rounded-lg text-indigo-900 space-y-1">
                    <div class="font-bold text-xs" id="tekDisplayItemName">Nama Alat</div>
                    <div class="text-[11px] text-gray-600">Teknisi Bertugas: <span class="font-semibold text-gray-800" id="tekDisplayTech"></span></div>
                    <div class="text-[11px] text-gray-600">Kendala: <span class="text-rose-600 font-semibold" id="tekDisplayIssue"></span></div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Uraian Tindakan Perbaikan yang Dilakukan <span class="text-rose-500">*</span></label>
                    <textarea name="catatan_teknisi" rows="2.5" required placeholder="Jelaskan komponen yang diganti, solderan yang diperbaiki, atau penyesuaian parameter..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-indigo-500"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Tanggal Selesai Dikerjakan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ date('Y-m-d') }}" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-indigo-500">
                </div>

                <p class="text-gray-600 text-[11px]">
                    Bubuhkan paraf digital Teknisi pada kotak di bawah:
                </p>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-1 bg-slate-50 flex flex-col items-center">
                    <canvas id="tekSigCanvas" width="380" height="120" class="bg-white rounded border border-gray-200 cursor-crosshair touch-none"></canvas>
                    <button type="button" onclick="clearTeknisiSignature()" class="text-rose-600 hover:underline text-[11px] font-semibold mt-2">
                        <i class="fa-solid fa-eraser"></i> Hapus / Tulis Ulang
                    </button>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTeknisiSign').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold">
                        Kirim Laporan & Paraf Teknisi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: PENGESAHAN OLEH MANTEK (PARAF MANTEK) -->
    <div id="modalMantekSign" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6 space-y-4 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-stamp text-emerald-600"></i> Pengesahan Hasil Perbaikan (Manager Teknik)
                </h3>
                <button onclick="document.getElementById('modalMantekSign').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>
            <form action="{{ route('inventory.signMantek') }}" method="POST" onsubmit="return submitMantekSignature()" class="space-y-3.5 text-xs">
                @csrf
                <input type="hidden" id="manSignInvId" name="inventory_id">
                <input type="hidden" id="manSignatureData" name="signature_data">

                <div class="bg-emerald-50/70 border border-emerald-100 p-3 rounded-lg text-emerald-900 space-y-1">
                    <div class="font-bold text-xs" id="manDisplayItemName">Nama Alat</div>
                    <div class="text-[11px] text-gray-600">Teknisi: <span class="font-semibold text-gray-800" id="manDisplayTech"></span></div>
                    <div class="text-[11px] text-gray-600">Hasil Pengerjaan: <span class="text-emerald-800 font-semibold" id="manDisplayReport"></span></div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Catatan Validasi & Verifikasi Mantek <span class="text-rose-500">*</span></label>
                    <textarea name="catatan_mantek" rows="2" required placeholder="Contoh: Telah diperiksa, parameter sesuai standar operasional CNS/A. Siap beroperasi." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500"></textarea>
                </div>

                <p class="text-gray-600 text-[11px]">
                    Bubuhkan paraf digital Manager Teknik untuk mengesahkan alat kembali Normal:
                </p>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-1 bg-slate-50 flex flex-col items-center">
                    <canvas id="manSigCanvas" width="380" height="120" class="bg-white rounded border border-gray-200 cursor-crosshair touch-none"></canvas>
                    <button type="button" onclick="clearMantekSignature()" class="text-rose-600 hover:underline text-[11px] font-semibold mt-2">
                        <i class="fa-solid fa-eraser"></i> Hapus / Tulis Ulang
                    </button>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalMantekSign').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold">
                        Sahkan & Kembalikan ke Normal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 5: LIHAT ARSIP RIWAYAT LENGKAP DUA PARAF -->
    <div id="modalHistory" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Arsip Rekam Jejak Pemeliharaan Fasilitas
                </h3>
                <button onclick="document.getElementById('modalHistory').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-gray-400 block text-[11px]">Nama Peralatan:</span>
                    <span class="font-bold text-gray-800 text-sm" id="histItemName">-</span>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 space-y-2">
                    <span class="font-semibold text-gray-700 block text-xs">Log Kronologi & Solusi:</span>
                    <div class="text-gray-600 whitespace-pre-line leading-relaxed font-mono text-[11px]" id="histContent">-</div>
                </div>

                <div class="grid grid-cols-2 gap-3 border-t pt-3">
                    <div id="histTeknisiBox" class="text-center p-2 border rounded-lg bg-gray-50">
                        <span class="text-[10px] text-gray-500 block mb-1">Paraf Teknisi:</span>
                        <img id="histTeknisiImg" src="" alt="Paraf Teknisi" class="h-10 mx-auto object-contain bg-white border p-0.5 rounded">
                    </div>
                    <div id="histMantekBox" class="text-center p-2 border rounded-lg bg-gray-50">
                        <span class="text-[10px] text-gray-500 block mb-1">Paraf Manager Teknik:</span>
                        <img id="histMantekImg" src="" alt="Paraf Mantek" class="h-10 mx-auto object-contain bg-white border p-0.5 rounded">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t">
                <button type="button" onclick="document.getElementById('modalHistory').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-semibold text-xs">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script Kontrol Interaktif & Dua Canvas Tanda Tangan -->
    <script>
        function toggleKondisiFields() {
            const kondisi = document.getElementById('selectKondisi').value;
            const section = document.getElementById('sectionKerusakan');
            const inputDetail = document.getElementById('detailKerusakanInput');

            if (kondisi === 'Normal') {
                section.classList.add('hidden');
                inputDetail.value = '-';
            } else {
                section.classList.remove('hidden');
                if (inputDetail.value === '-') inputDetail.value = '';
            }
        }

        function openAssignModal(id, nama, lokasi, kerusakan) {
            document.getElementById('assignInvId').value = id;
            document.getElementById('assignItemName').value = nama;
            document.getElementById('displayItemName').innerText = nama;
            document.getElementById('displayItemLocation').innerText = 'Lokasi: ' + lokasi;
            document.getElementById('displayItemIssue').innerText = 'Kendala: ' + (kerusakan || '-');
            document.getElementById('modalAssignTask').classList.remove('hidden');
        }

        // --- Logika Canvas Teknisi ---
        const tekCanvas = document.getElementById('tekSigCanvas');
        const tekCtx = tekCanvas.getContext('2d');
        let isDrawingTek = false;
        let hasSignedTek = false;

        function openTeknisiSignModal(id, nama, teknisi, kerusakan) {
            document.getElementById('tekSignInvId').value = id;
            document.getElementById('tekDisplayItemName').innerText = nama;
            document.getElementById('tekDisplayTech').innerText = teknisi || 'Teknisi Bertugas';
            document.getElementById('tekDisplayIssue').innerText = kerusakan || '-';
            clearTeknisiSignature();
            document.getElementById('modalTeknisiSign').classList.remove('hidden');
        }

        function clearTeknisiSignature() {
            tekCtx.clearRect(0, 0, tekCanvas.width, tekCanvas.height);
            hasSignedTek = false;
        }

        function setupCanvasDrawing(canvas, ctx, setFlag) {
            let drawing = false;
            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return { x: clientX - rect.left, y: clientY - rect.top };
            }
            function start(e) {
                drawing = true;
                setFlag(true);
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                e.preventDefault();
            }
            function draw(e) {
                if (!drawing) return;
                const pos = getPos(e);
                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#0f172a';
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                e.preventDefault();
            }
            function stop() { drawing = false; }

            canvas.addEventListener('mousedown', start);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stop);
            canvas.addEventListener('mouseleave', stop);

            canvas.addEventListener('touchstart', start);
            canvas.addEventListener('touchmove', draw);
            canvas.addEventListener('touchend', stop);
        }

        setupCanvasDrawing(tekCanvas, tekCtx, (val) => { hasSignedTek = val; });

        function submitTeknisiSignature() {
            if (!hasSignedTek) {
                alert('Silakan bubuhkan paraf Teknisi pada kotak canvas terlebih dahulu.');
                return false;
            }
            document.getElementById('tekSignatureData').value = tekCanvas.toDataURL('image/png');
            return true;
        }

        // --- Logika Canvas Mantek ---
        const manCanvas = document.getElementById('manSigCanvas');
        const manCtx = manCanvas.getContext('2d');
        let hasSignedMan = false;

        setupCanvasDrawing(manCanvas, manCtx, (val) => { hasSignedMan = val; });

        function openMantekSignModal(id, nama, teknisi, laporan) {
            document.getElementById('manSignInvId').value = id;
            document.getElementById('manDisplayItemName').innerText = nama;
            document.getElementById('manDisplayTech').innerText = teknisi || 'Teknisi Bertugas';
            document.getElementById('manDisplayReport').innerText = laporan || '-';
            clearMantekSignature();
            document.getElementById('modalMantekSign').classList.remove('hidden');
        }

        function clearMantekSignature() {
            manCtx.clearRect(0, 0, manCanvas.width, manCanvas.height);
            hasSignedMan = false;
        }

        function submitMantekSignature() {
            if (!hasSignedMan) {
                alert('Silakan bubuhkan paraf Mantek pada kotak canvas terlebih dahulu.');
                return false;
            }
            document.getElementById('manSignatureData').value = manCanvas.toDataURL('image/png');
            return true;
        }

        // --- Logika Modal Riwayat ---
        function openHistoryModal(nama, historyText, tekSrc, manSrc) {
            document.getElementById('histItemName').innerText = nama;
            document.getElementById('histContent').innerText = historyText || 'Tidak ada catatan riwayat perbaikan.';
            
            const tekImg = document.getElementById('histTeknisiImg');
            const manImg = document.getElementById('histMantekImg');

            if (tekSrc && tekSrc.startsWith('data:image')) {
                tekImg.src = tekSrc;
                document.getElementById('histTeknisiBox').classList.remove('hidden');
            } else {
                document.getElementById('histTeknisiBox').classList.add('hidden');
            }

            if (manSrc && manSrc.startsWith('data:image')) {
                manImg.src = manSrc;
                document.getElementById('histMantekBox').classList.remove('hidden');
            } else {
                document.getElementById('histMantekBox').classList.add('hidden');
            }

            document.getElementById('modalHistory').classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleKondisiFields();
        });
    </script>

@endsection