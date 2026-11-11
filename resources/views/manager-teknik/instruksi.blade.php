@extends('layouts.app')

@section('title', 'Rekap Instruksi Kerja Teknisi')

@section('content')

    <!-- Header Panel & Aksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-blue-600"></i> Rekap Semua Instruksi Kerja Teknisi
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Lacak penugasan teknisi, periksa tanggal instruksi & target selesai, unduh lampiran (Word, PDF, Gambar, Excel), serta kelola arsip bulanan & tahunan.
                </p>
            </div>

            <!-- Tombol Tambah Perintah Baru -->
            <button onclick="document.getElementById('modalCreateTask').classList.remove('hidden')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 shadow-sm transition shrink-0 cursor-pointer">
                <i class="fa-solid fa-paper-plane"></i> Buat Perintah Baru
            </button>
        </div>

        <!-- Toolbar Filter Tab (Sedang Berjalan vs Arsip Selesai) -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
            <div class="flex items-center gap-2 text-xs">
                <!-- Tab Sedang Berjalan -->
                <a href="{{ route('instruksi.index', ['tab' => 'berjalan']) }}" 
                   class="px-3.5 py-1.5 rounded-lg font-semibold transition flex items-center gap-1.5 {{ $currentTab === 'berjalan' ? 'bg-amber-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-spinner {{ $currentTab === 'berjalan' ? 'fa-spin' : '' }}"></i> 
                    <span>Sedang Berjalan</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ $currentTab === 'berjalan' ? 'bg-amber-800 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $countBerjalan ?? 0 }}
                    </span>
                </a>

                <!-- Tab Arsip Selesai -->
                <a href="{{ route('instruksi.index', ['tab' => 'arsip']) }}" 
                   class="px-3.5 py-1.5 rounded-lg font-semibold transition flex items-center gap-1.5 {{ $currentTab === 'arsip' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-box-archive"></i> 
                    <span>Arsip Selesai</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ $currentTab === 'arsip' ? 'bg-emerald-800 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $countArsip ?? 0 }}
                    </span>
                </a>
            </div>

            <!-- Toolbar Pencarian & Filter Periode Bulan/Tahun Pada Tab Arsip -->
            <form action="{{ route('instruksi.index') }}" method="GET" class="flex flex-wrap items-center gap-2 text-xs">
                <input type="hidden" name="tab" value="{{ $currentTab }}">

                @if($currentTab === 'arsip')
                    <!-- Filter Bulan -->
                    <select name="filter_month" onchange="this.form.submit()" 
                            class="rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-gray-700 font-semibold outline-none focus:border-emerald-500 shadow-sm cursor-pointer">
                        <option value="">Semua Bulan</option>
                        @foreach([
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ] as $num => $monthName)
                            <option value="{{ $num }}" {{ request('filter_month') == $num ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filter Tahun -->
                    <select name="filter_year" onchange="this.form.submit()" 
                            class="rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-gray-700 font-semibold outline-none focus:border-emerald-500 shadow-sm cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <!-- Pencarian Kata Kunci -->
                <div class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." 
                           class="rounded-l-lg border border-gray-300 px-3 py-1.5 outline-none focus:border-blue-500 w-36 md:w-44">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 rounded-r-lg transition cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                @if(request('filter_month') || request('filter_year') || request('search'))
                    <a href="{{ route('instruksi.index', ['tab' => $currentTab]) }}" class="text-rose-600 hover:underline font-semibold ml-1">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabel Instruksi Kerja -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Judul Instruksi</th>
                        <th class="py-2.5 px-3">Teknisi Bertugas</th>
                        <th class="py-2.5 px-3">Jadwal Penugasan</th>
                        <th class="py-2.5 px-3">Lampiran Berkas</th>
                        <th class="py-2.5 px-3">Status Terbit</th>
                        <th class="py-2.5 px-3">Status Pengerjaan</th>
                        <th class="py-2.5 px-3 text-center">Aksi & Pengaturan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tasks as $t)
                    @php
                        $techName = $t->technician ? $t->technician->name : ($t->assigned_to_name ?? (is_string($t->assigned_to) ? $t->assigned_to : 'Semua'));
                        $isCompleted = in_array($t->status_pengerjaan, ['Selesai', 'Diarsipkan']);
                        
                        $hasAttachment = !empty($t->attachment);
                        $fileUrl = $hasAttachment ? asset('storage/' . ltrim($t->attachment, '/')) : null;
                        $ext = $hasAttachment ? strtolower(pathinfo($t->attachment, PATHINFO_EXTENSION)) : '';

                        $iconClass = 'fa-solid fa-file text-slate-500';
                        $fileBadge = 'File';
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $iconClass = 'fa-solid fa-file-image text-purple-600';
                            $fileBadge = 'Gambar';
                        } elseif (in_array($ext, ['doc', 'docx'])) {
                            $iconClass = 'fa-solid fa-file-word text-blue-600';
                            $fileBadge = 'Word';
                        } elseif ($ext === 'pdf') {
                            $iconClass = 'fa-solid fa-file-pdf text-rose-600';
                            $fileBadge = 'PDF';
                        } elseif (in_array($ext, ['xls', 'xlsx'])) {
                            $iconClass = 'fa-solid fa-file-excel text-emerald-600';
                            $fileBadge = 'Excel';
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition {{ $isCompleted ? 'bg-slate-50/30' : '' }}">
                        <!-- Judul & Ringkasan Perintah -->
                        <td class="py-3 px-3">
                            <span class="font-bold text-gray-800 text-[13px] block">{{ $t->title }}</span>
                            <span class="text-[11px] text-gray-500 block mt-0.5 line-clamp-1">{{ $t->instruction ?: '-' }}</span>
                        </td>

                        <!-- Penerima Tugas -->
                        <td class="py-3 px-3">
                            <span class="font-semibold text-gray-700 bg-slate-100 px-2.5 py-1 rounded text-[11px] inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-user-gear text-slate-400"></i> {{ $techName }}
                            </span>
                        </td>

                        <!-- Jadwal Penugasan -->
                        <td class="py-3 px-3 text-[11px]">
                            <div class="text-gray-500 flex items-center gap-1">
                                <i class="fa-solid fa-calendar-plus text-slate-400"></i>
                                <span>Diinstruksikan:</span> 
                                <strong class="text-gray-700">{{ $t->created_at ? $t->created_at->format('d M Y') : '-' }}</strong>
                            </div>
                            <div class="text-gray-500 flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-calendar-check text-blue-500"></i>
                                <span>Target Selesai:</span> 
                                <strong class="text-blue-700">{{ $t->target_date ? \Carbon\Carbon::parse($t->target_date)->format('d M Y') : '-' }}</strong>
                            </div>
                        </td>

                        <!-- Lampiran Berkas -->
                        <td class="py-3 px-3">
                            @if($hasAttachment)
                                <a href="{{ $fileUrl }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 border border-slate-200 font-semibold text-[11px] transition shadow-xs">
                                    <i class="{{ $iconClass }}"></i>
                                    <span class="text-slate-700">{{ $fileBadge }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-slate-400 ml-0.5"></i>
                                </a>
                            @else
                                <span class="text-gray-400 italic text-[11px] flex items-center gap-1">
                                    <i class="fa-solid fa-minus"></i> Tidak Ada
                                </span>
                            @endif
                        </td>

                        <!-- Status Terbit -->
                        <td class="py-3 px-3">
                            @if(($t->status_terbit ?? 'Terbit') === 'Terbit')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    Terbit
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    Draf
                                </span>
                            @endif
                        </td>

                        <!-- Status Pengerjaan -->
                        <td class="py-3 px-3">
                            @if($t->status_pengerjaan === 'Diarsipkan')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-box-archive"></i> Diarsipkan
                                </span>
                            @elseif($t->status_pengerjaan === 'Selesai')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Selesai
                                </span>
                            @elseif($t->status_pengerjaan === 'Dalam Pengerjaan')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Dalam Pengerjaan
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-clock"></i> Belum Selesai
                                </span>
                            @endif
                        </td>

                        <!-- Aksi Dinamis -->
                        <td class="py-3 px-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if(!$isCompleted)
                                    <!-- TAB BERJALAN: BISA UPDATE DAN BISA HAPUS -->
                                    <button onclick="openUpdateStatusModal({{ $t->id }}, '{{ addslashes($t->title) }}', '{{ $t->status_pengerjaan }}')" 
                                            title="Ubah Status Pengerjaan"
                                            class="px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-md font-semibold text-[11px] border border-blue-200 transition flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square"></i> Update
                                    </button>

                                    <!-- Tombol Hapus Hanya Tersedia Untuk Tugas Yang Masih Berjalan -->
                                    <form action="{{ route('instruksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus catatan instruksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Instruksi" class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- TAB ARSIP SELESAI: HANYA BISA LIHAT CATATAN (TOMBOL DELETE DITIADAKAN AGAR ARSIP AMAN) -->
                                    <button onclick="openViewDetailModal('{{ addslashes($t->title) }}', '{{ addslashes($techName) }}', '{{ $t->created_at ? $t->created_at->format('d M Y') : '-' }}', '{{ $t->target_date ? \Carbon\Carbon::parse($t->target_date)->format('d M Y') : '-' }}', '{{ addslashes($t->instruction) }}', '{{ $fileUrl }}', '{{ $ext }}')"
                                            class="px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-md font-semibold text-[11px] border border-emerald-200 transition flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-eye"></i> Lihat Catatan
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            @if($currentTab === 'arsip')
                                Belum ada instruksi kerja yang berstatus selesai/diarsipkan pada periode ini.
                            @else
                                Tidak ada instruksi kerja yang sedang berjalan saat ini.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($tasks, 'links'))
            <div class="p-4 border-t border-gray-100">{{ $tasks->links() }}</div>
        @endif
    </div>

    <!-- MODAL 1: FORM BUAT PERINTAH BARU -->
    <div id="modalCreateTask" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-lg w-full p-6 space-y-4 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-blue-600"></i> Buat Instruksi / Perintah Baru
                </h3>
                <button onclick="document.getElementById('modalCreateTask').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">&times;</button>
            </div>
            <form action="{{ route('instruksi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Judul Perintah / Tugas <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Meter Reading Pemancar DVOR" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Tugaskan Kepada <span class="text-rose-500">*</span></label>
                        <select name="assigned_to" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white font-medium cursor-pointer">
                            <option value="">-- Pilih Penerima Tugas --</option>
                            <option value="all" class="font-bold text-emerald-700 bg-emerald-50">Semua</option>
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
                        <label class="block font-semibold text-gray-700 mb-1">Target Selesai <span class="text-rose-500">*</span></label>
                        <input type="date" name="target_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Uraian Instruksi / Catatan Teknis</label>
                    <textarea name="instruction" rows="3" placeholder="Tuliskan petunjuk teknis pengerjaan..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        Unggah Lampiran Berkas / Dokumen SOP / Gambar
                    </label>
                    <div class="border border-dashed border-gray-300 rounded-lg p-3 bg-slate-50">
                        <input type="file" name="attachment" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" 
                               class="w-full text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-blue-600 file:text-white file:font-semibold cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-blue-500"></i>
                            <span>Format: <strong>Word (.docx)</strong>, <strong>PDF</strong>, <strong>Gambar (.jpg/.png)</strong>, dan <strong>Excel</strong>.</span>
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalCreateTask').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600 cursor-pointer">Batal</button>
                    <button type="submit" name="action" value="publish" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold cursor-pointer">Terbitkan Instruksi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: FORM UPDATE PROGRES (TUGAS BERJALAN) -->
    <div id="modalUpdateStatus" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-blue-600"></i> Update Progres Instruksi Kerja
                </h3>
                <button onclick="document.getElementById('modalUpdateStatus').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">&times;</button>
            </div>
            <form id="formUpdateStatus" method="POST" action="" class="space-y-3.5 text-xs">
                @csrf
                @method('PUT')

                <div class="bg-slate-50 p-3 rounded-lg border border-slate-200">
                    <span class="text-[11px] text-gray-400 block">Judul Tugas:</span>
                    <span class="font-bold text-gray-800 text-sm" id="modalTaskTitle">-</span>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Ubah Status Pengerjaan <span class="text-rose-500">*</span></label>
                    <select id="modalTaskStatus" name="status_pengerjaan" required class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white font-semibold cursor-pointer">
                        <option value="Dalam Pengerjaan">Dalam Pengerjaan</option>
                        <option value="Selesai">Selesai (Otomatis Masuk Arsip)</option>
                        <option value="Belum Selesai">Belum Selesai</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Catatan Tindakan / Solusi</label>
                    <textarea name="catatan_progres" rows="3" placeholder="Tuliskan modul yang diganti atau hasil pengujian parameter..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalUpdateStatus').classList.add('hidden')" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold cursor-pointer">Simpan Pembaruan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: LIHAT DETAIL CATATAN & PREVIEW LAMPIRAN ARSIP -->
    <div id="modalViewDetail" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-emerald-600"></i> Detail Catatan Arsip Selesai
                </h3>
                <button onclick="document.getElementById('modalViewDetail').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">&times;</button>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-gray-400 block text-[11px]">Judul Tugas:</span>
                    <span class="font-bold text-gray-800 text-sm" id="detailTitle">-</span>
                </div>

                <div class="grid grid-cols-3 gap-2 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                    <div>
                        <span class="text-gray-400 block text-[10px]">Penerima Tugas:</span>
                        <span class="font-semibold text-gray-700" id="detailTech">-</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px]">Diinstruksikan:</span>
                        <span class="font-semibold text-gray-700" id="detailDateCreated">-</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px]">Target Selesai:</span>
                        <span class="font-semibold text-blue-700" id="detailDateTarget">-</span>
                    </div>
                </div>

                <div>
                    <span class="text-gray-400 block text-[11px] mb-1">Uraian / Kronologi Catatan:</span>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-gray-700 whitespace-pre-line leading-relaxed font-mono text-[11px]" id="detailInstruction">-</div>
                </div>

                <!-- Bagian Lampiran File -->
                <div id="detailAttachmentContainer" class="hidden border-t pt-2.5 space-y-2">
                    <span class="text-gray-400 block text-[11px]">Berkas Lampiran SOP / Foto:</span>

                    <div id="detailImgPreviewWrapper" class="hidden">
                        <img id="detailImgPreview" src="" alt="Preview Lampiran" class="max-h-48 w-full object-contain rounded-lg border border-gray-200 bg-black/5 p-1">
                    </div>

                    <a id="detailAttachmentLink" href="#" target="_blank" 
                       class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 font-semibold text-[11px] transition w-full justify-center">
                        <i id="detailFileIcon" class="fa-solid fa-paperclip"></i>
                        <span id="detailFileText">Buka / Unduh Lampiran Berkas</span>
                    </a>
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t">
                <button type="button" onclick="document.getElementById('modalViewDetail').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-semibold text-xs cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script Kontrol Interaktif -->
    <script>
        function openUpdateStatusModal(id, title, status) {
            document.getElementById('modalTaskTitle').innerText = title;
            document.getElementById('modalTaskStatus').value = status || 'Dalam Pengerjaan';
            document.getElementById('formUpdateStatus').action = '/instruksi-kerja/' + id + '/update-status';
            document.getElementById('modalUpdateStatus').classList.remove('hidden');
        }

        function openViewDetailModal(title, tech, dateCreated, dateTarget, instruction, attachmentUrl, ext) {
            document.getElementById('detailTitle').innerText = title;
            document.getElementById('detailTech').innerText = tech;
            document.getElementById('detailDateCreated').innerText = dateCreated;
            document.getElementById('detailDateTarget').innerText = dateTarget;
            document.getElementById('detailInstruction').innerText = instruction || 'Tidak ada catatan uraian.';

            const attachContainer = document.getElementById('detailAttachmentContainer');
            const attachLink = document.getElementById('detailAttachmentLink');
            const imgWrapper = document.getElementById('detailImgPreviewWrapper');
            const imgPreview = document.getElementById('detailImgPreview');
            const fileIcon = document.getElementById('detailFileIcon');
            const fileText = document.getElementById('detailFileText');

            if (attachmentUrl && attachmentUrl !== 'null' && attachmentUrl.length > 5) {
                attachLink.href = attachmentUrl;
                attachContainer.classList.remove('hidden');

                const fileExt = (ext || '').toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'webp'].includes(fileExt);

                if (isImage) {
                    imgPreview.src = attachmentUrl;
                    imgWrapper.classList.remove('hidden');
                    fileIcon.className = 'fa-solid fa-image text-purple-600';
                    fileText.innerText = 'Lihat Gambar Penuh';
                } else {
                    imgWrapper.classList.add('hidden');
                    if (['doc', 'docx'].includes(fileExt)) {
                        fileIcon.className = 'fa-solid fa-file-word text-blue-600';
                        fileText.innerText = 'Buka / Unduh Dokumen Word (.docx)';
                    } else if (fileExt === 'pdf') {
                        fileIcon.className = 'fa-solid fa-file-pdf text-rose-600';
                        fileText.innerText = 'Buka Dokumen PDF (.pdf)';
                    } else if (['xls', 'xlsx'].includes(fileExt)) {
                        fileIcon.className = 'fa-solid fa-file-excel text-emerald-600';
                        fileText.innerText = 'Unduh Berkas Excel (.xlsx)';
                    } else {
                        fileIcon.className = 'fa-solid fa-paperclip text-slate-600';
                        fileText.innerText = 'Buka Berkas Lampiran';
                    }
                }
            } else {
                attachContainer.classList.add('hidden');
                imgWrapper.classList.add('hidden');
            }

            document.getElementById('modalViewDetail').classList.remove('hidden');
        }
    </script>

@endsection