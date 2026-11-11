@extends('layouts.app')

@section('title', 'Memori Jabatan & Kontinuitas Operasional')

@section('content')

    <!-- Header Panel Eksklusif Manager Teknik -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-xl shadow-md border border-emerald-900/40 p-6 text-white space-y-3">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider">
                    Eksklusif: Manager Teknik AirNav Indonesia Cabang Banjarmasin
                </span>
                <h1 class="text-xl font-bold mt-2 flex items-center gap-2.5">
                    <i class="fa-solid fa-file-signature text-emerald-400"></i> Memori Jabatan & Kontinuitas Operasional CNS/A
                </h1>
                <p class="text-xs text-slate-300 mt-1 max-w-2xl leading-relaxed">
                    Dokumen resmi perangkum capaian tugas yang telah diselesaikan, pekerjaan yang sedang berjalan di fasilitas navigasi, berkas lampiran legal, serta agenda kerja strategis berikutnya untuk menjamin kelancaran transisi pejabat baru.
                </p>
            </div>
            
            <button type="button" onclick="openCreateResumeModal()" 
                    class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 shadow-sm transition shrink-0 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Susun Resume Jabatan Baru
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-gray-500 block">Total Dossier Terdaftar</span>
                <span class="text-2xl font-bold text-gray-800">{{ $handovers->total() }} Berkas</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-box-archive"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-emerald-600 block">Resmi Diserahterimakan (Terkunci)</span>
                <span class="text-2xl font-bold text-emerald-800">
                    {{ $handovers->where('status_dokumen', 'Resmi Diarsipkan')->count() }} Dokumen
                </span>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-stamp"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-amber-600 block">Draf Aktif / Dapat Diubah</span>
                <span class="text-2xl font-bold text-amber-800">
                    {{ $handovers->where('status_dokumen', 'Draf')->count() }} Draf
                </span>
            </div>
            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-pen-ruler"></i>
            </div>
        </div>
    </div>

    <!-- Tabel Berkas Resume -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50/70 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800">Daftar Dokumen Serah Terima Jabatan</h2>
            <span class="text-xs text-gray-400">Total: {{ $handovers->total() }} dokumen</span>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs">
                <thead class="uppercase bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="py-2.5 px-3">Judul Memori Jabatan</th>
                        <th class="py-2.5 px-3">Pejabat Pembuat</th>
                        <th class="py-2.5 px-3">Periode Menjabat</th>
                        <th class="py-2.5 px-3">Lampiran Berkas</th>
                        <th class="py-2.5 px-3">Status Dokumen</th>
                        <th class="py-2.5 px-3">Paraf Pengesahan</th>
                        <th class="py-2.5 px-3 text-center">Aksi & Buka Arsip</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($handovers as $h)
                    @php
                        $hasDoc = !empty($h->dokumen_lampiran);
                        $docUrl = $hasDoc ? asset('storage/' . ltrim($h->dokumen_lampiran, '/')) : null;
                        $isLocked = ($h->status_dokumen === 'Resmi Diarsipkan');
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition {{ $isLocked ? 'bg-slate-50/40' : '' }}">
                        <td class="py-3 px-3">
                            <span class="font-bold text-gray-800 text-[13px] block">{{ $h->judul_resume }}</span>
                            <span class="text-[11px] text-gray-400">Dicatat: {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="font-semibold text-gray-800 block">{{ $h->pejabat_nama ?: '-' }}</span>
                            <span class="text-[11px] text-gray-400">NIP: {{ $h->pejabat_nip ?: '-' }}</span>
                        </td>
                        <td class="py-3 px-3 text-[11px] text-gray-600">
                            {{ $h->tanggal_mulai_tugas ? \Carbon\Carbon::parse($h->tanggal_mulai_tugas)->format('d/m/Y') : '-' }}
                            s.d.
                            {{ $h->tanggal_selesai_tugas ? \Carbon\Carbon::parse($h->tanggal_selesai_tugas)->format('d/m/Y') : 'Sekarang' }}
                        </td>
                        <td class="py-3 px-3">
                            @if($hasDoc)
                                <a href="{{ $docUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded border border-emerald-200 font-semibold text-[11px]">
                                    <i class="fa-solid fa-file-arrow-down"></i> Berkas Lampiran
                                </a>
                            @else
                                <span class="text-gray-400 italic text-[11px]">Tidak Ada Berkas</span>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            @if($isLocked)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-lock"></i> Terkunci & Diarsipkan
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-pen"></i> Draf Terbuka
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            @if(!empty($h->paraf_digital) && str_starts_with($h->paraf_digital, 'data:image'))
                                <img src="{{ $h->paraf_digital }}" alt="Paraf Pengesahan" class="h-8 max-w-[80px] object-contain border border-slate-200 rounded bg-white p-0.5">
                            @else
                                <span class="text-gray-400 italic text-[11px]">Belum Ada Paraf</span>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" onclick="openDetailResumeModal({{ $h->id }})" 
                                        class="px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-md font-semibold text-[11px] border border-blue-200 transition inline-flex items-center gap-1 cursor-pointer">
                                    <i class="fa-solid fa-book-open-reader"></i> Buka Dossier
                                </button>

                                <form action="{{ route('manager-teknik.handover.toggleStatus', $h->id) }}" method="POST" onsubmit="return confirm('{{ $isLocked ? 'Buka kunci status arsip ini agar dapat diedit kembali?' : 'Kunci dan sahkan dokumen serah terima jabatan ini?' }}')">
                                    @csrf
                                    @method('PATCH')
                                    @if($isLocked)
                                        <button type="submit" title="Buka Gembok (Reopen Draf)" class="px-2 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-md font-semibold text-[11px] border border-amber-200 transition cursor-pointer">
                                            <i class="fa-solid fa-lock-open"></i> Buka Kunci
                                        </button>
                                    @else
                                        <button type="submit" title="Kunci Resmi Arsip" class="px-2 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-md font-semibold text-[11px] border border-emerald-200 transition cursor-pointer">
                                            <i class="fa-solid fa-lock"></i> Kunci
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            Belum ada arsip memori serah terima jabatan. Klik tombol "+ Susun Resume Jabatan Baru" untuk menyusun laporan pertama.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">{{ $handovers->links() }}</div>
    </div>

    <!-- MODAL 1: FORM PENYUSUNAN MEMORI JABATAN BARU -->
    <div id="modalTambahResume" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-2xl border border-gray-200 max-w-2xl w-full p-6 space-y-4 max-h-[92vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-emerald-600"></i> Penyusunan Memori Jabatan Manager Teknik
                </h3>
                <button type="button" onclick="closeCreateResumeModal()" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('manager-teknik.handover.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return submitHandoverSign()" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" id="handoverSigData" name="paraf_digital">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Judul Dokumen Memori Jabatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul_resume" required placeholder="Contoh: Memori Jabatan Periode 2024-2026" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Pejabat Manager Teknik <span class="text-rose-500">*</span></label>
                        <input type="text" name="pejabat_nama" required placeholder="Masukkan Nama Lengkap & Gelar Pejabat..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">NIP Pejabat</label>
                        <input type="text" name="pejabat_nip" placeholder="Nomor Induk Pegawai..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Awal Masa Menjabat</label>
                        <input type="date" name="tanggal_mulai_tugas" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Akhir Masa Menjabat</label>
                        <input type="date" name="tanggal_selesai_tugas" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500">
                    </div>
                </div>

                <!-- 1. APA YANG SUDAH DILAKUKAN -->
                <div class="bg-emerald-50/50 p-3.5 rounded-lg border border-emerald-100">
                    <label class="block font-bold text-emerald-900 mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> 1. Apa Saja yang SUDAH Dilakukan & Tuntas Diselesaikan? <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="uraian_sudah_selesai" rows="3" required placeholder="Contoh: Kalibrasi rutin tahunan fasilitas DVOR/DME telah tuntas dilaksanakan, dokumen ISR perpanjangan frekuensi seluruhnya selesai diproses, rekondisi modul power supply VHF tower selesai dan status kembali normal..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500 bg-white"></textarea>
                </div>

                <!-- 2. APA YANG SEDANG DIKERJAKAN -->
                <div class="bg-amber-50/50 p-3.5 rounded-lg border border-amber-100">
                    <label class="block font-bold text-amber-900 mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-spinner fa-spin text-amber-600"></i> 2. Apa Saja yang SEDANG Dikerjakan di Lapangan? <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="uraian_sedang_berjalan" rows="3" required placeholder="Contoh: Penggantian kabel coaxial shelter Glide Path RWY 10 dalam tahap penarikan kabel oleh teknisi shift, monitoring kestabilan tegangan catu daya UPS 10kVA radar..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-amber-500 bg-white"></textarea>
                </div>

                <!-- 3. APA YANG AKAN DILAKUKAN -->
                <div class="bg-blue-50/50 p-3.5 rounded-lg border border-blue-100">
                    <label class="block font-bold text-blue-900 mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-days text-blue-600"></i> 3. Apa Saja RENCANA KE DEPAN yang Perlu Dilanjutkan Pejabat Baru? <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="uraian_rencana_ke_depan" rows="3" required placeholder="Contoh: Pelaksanaan flight calibration triwulan berikutnya, usulan pengadaan modul sparepart receiver cadangan, pemutakhiran jadwal kalibrasi alat ukur navigasi..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-blue-500 bg-white"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Catatan Strategis / Arahan Khusus Pejabat Baru</label>
                    <textarea name="catatan_khusus_pejabat_baru" rows="2" placeholder="Catatan hubungan kelembagaan atau perhatian khusus di lingkungan fasilitas bandara..." class="w-full rounded-lg border border-gray-300 p-2.5 outline-none focus:border-emerald-500"></textarea>
                </div>

                <!-- UPLOAD DOKUMEN RESMI LAMPIRAN FISIK -->
                <div class="bg-slate-50 p-3 rounded-lg border border-gray-200">
                    <label class="block font-semibold text-gray-700 mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-paperclip text-blue-600"></i> Unggah Dokumen Berita Acara / Lampiran Resmi (PDF / Word / Excel / ZIP)
                    </label>
                    <input type="file" name="dokumen_lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" 
                           class="w-full text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-emerald-600 file:text-white file:font-semibold cursor-pointer border border-gray-200 rounded-lg p-1 bg-white">
                    <p class="text-[10px] text-gray-400 mt-1">
                        Dapat mengunggah berkas scan Berita Acara, daftar inventaris pendukung, atau Surat Tugas resmi hingga 25 MB.
                    </p>
                </div>

                <!-- WADAH PARAF DIGITAL DENGAN KANVAS RESPONSIF -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Bubuhkan Paraf Digital Pengesahan</label>
                    <div id="canvasWrapper" class="w-full border-2 border-dashed border-gray-300 rounded-lg p-2 bg-slate-50 flex flex-col items-center">
                        <canvas id="handoverCanvas" class="w-full bg-white rounded border border-gray-200 cursor-crosshair" style="touch-action: none; height: 130px;"></canvas>
                        <button type="button" onclick="clearHandoverSign()" class="text-rose-600 hover:underline text-[11px] font-semibold mt-2 cursor-pointer inline-flex items-center gap-1">
                            <i class="fa-solid fa-eraser"></i> Hapus / Tulis Ulang Paraf
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="kunci_arsip" value="1" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4 cursor-pointer">
                        <span class="font-semibold text-gray-700 text-xs">Langsung Kunci & Arsipkan Resmi (Dapat dibuka kembali sewaktu-waktu)</span>
                    </label>

                    <div class="flex gap-2">
                        <button type="button" onclick="closeCreateResumeModal()" class="px-3.5 py-2 border rounded-lg hover:bg-gray-50 text-gray-600 cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold cursor-pointer">
                            Simpan Memori Jabatan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: TAMPILAN RESUME UNTUK MANAGER TEKNIK BARU -->
    <div id="modalDetailResume" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl shadow-2xl border border-gray-200 max-w-2xl w-full p-6 space-y-4 max-h-[92vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h3 class="font-bold text-gray-800 text-base flex items-center gap-2" id="detailJudul">
                        <i class="fa-solid fa-file-contract text-emerald-600"></i> Judul Resume
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5" id="detailPejabat">Pejabat Pembuat</p>
                </div>
                <button type="button" onclick="document.getElementById('modalDetailResume').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">&times;</button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- 1. Capaian Selesai -->
                <div class="bg-emerald-50/70 border border-emerald-200 rounded-lg p-3.5 space-y-1">
                    <span class="font-bold text-emerald-900 flex items-center gap-1.5 text-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> 1. CAPAIAN YANG TELAH SELESAI DISERAHKAN:
                    </span>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed font-sans mt-1" id="detailSelesai">-</p>
                </div>

                <!-- 2. Sedang Berjalan -->
                <div class="bg-amber-50/70 border border-amber-200 rounded-lg p-3.5 space-y-1">
                    <span class="font-bold text-amber-900 flex items-center gap-1.5 text-xs">
                        <i class="fa-solid fa-spinner text-amber-600"></i> 2. PEKERJAAN OPERASIONAL YANG SEDANG BERJALAN:
                    </span>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed font-sans mt-1" id="detailBerjalan">-</p>
                </div>

                <!-- 3. Rencana Ke Depan -->
                <div class="bg-blue-50/70 border border-blue-200 rounded-lg p-3.5 space-y-1">
                    <span class="font-bold text-blue-900 flex items-center gap-1.5 text-xs">
                        <i class="fa-solid fa-calendar-days text-blue-600"></i> 3. AGENDA & RENCANA KERJA YANG HARUS DILANJUTKAN:
                    </span>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed font-sans mt-1" id="detailRencana">-</p>
                </div>

                <!-- Catatan Khusus -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3.5 space-y-1">
                    <span class="font-bold text-slate-800 flex items-center gap-1.5 text-xs">
                        <i class="fa-solid fa-comment-dots text-slate-500"></i> Catatan Khusus untuk Pejabat Baru:
                    </span>
                    <p class="text-gray-600 italic whitespace-pre-line leading-relaxed mt-1" id="detailCatatan">-</p>
                </div>

                <!-- Dokumen Lampiran Berkas Legal Fisik -->
                <div id="detailFileWrapper" class="hidden border-t pt-2.5">
                    <span class="text-gray-700 block text-[11px] mb-1.5 font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-paperclip text-emerald-600"></i> Berkas Lampiran Dokumen Legal Terlampir:
                    </span>
                    <a id="detailFileLink" href="#" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-300 font-semibold text-xs transition shadow-xs">
                        <i class="fa-solid fa-file-arrow-down text-emerald-600 text-sm"></i> 
                        <span id="detailFileNameText">Unduh / Buka Berkas Lampiran</span>
                    </a>
                </div>

                <!-- Pengesahan Paraf -->
                <div class="border-t pt-3 flex items-center justify-between">
                    <div>
                        <span class="text-gray-400 block text-[10px]">Otoritas Verifikasi:</span>
                        <span class="font-bold text-emerald-700 text-xs">Disahkan oleh Manager Teknik AirNav Cabang Banjarmasin</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-gray-400 block mb-1">Paraf Resmi Digital</span>
                        <img id="detailParafImg" src="" alt="Paraf Pengesahan" class="h-10 mx-auto object-contain border p-0.5 rounded bg-white">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t">
                <button type="button" onclick="document.getElementById('modalDetailResume').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-semibold text-xs cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script Kontrol Interaktif & Canvas Paraf Digital Akurat -->
    <script>
        const handoverList = @json($handovers->items());

        const hCanvas = document.getElementById('handoverCanvas');
        const hCtx = hCanvas.getContext('2d');
        let hDrawing = false;
        let hHasSigned = false;

        function initCanvasResolution() {
            const wrapper = document.getElementById('canvasWrapper');
            if (!wrapper) return;

            const width = wrapper.clientWidth - 20;
            const height = 130;

            hCanvas.width = width > 200 ? width : 450;
            hCanvas.height = height;

            hCtx.lineCap = 'round';
            hCtx.lineJoin = 'round';
            hCtx.lineWidth = 2.5;
            hCtx.strokeStyle = '#064e3b';
        }

        function openCreateResumeModal() {
            document.getElementById('modalTambahResume').classList.remove('hidden');
            setTimeout(() => {
                initCanvasResolution();
                clearHandoverSign();
            }, 60);
        }

        function closeCreateResumeModal() {
            document.getElementById('modalTambahResume').classList.add('hidden');
        }

        function getCanvasCoordinates(e) {
            const rect = hCanvas.getBoundingClientRect();
            let clientX, clientY;

            if (e.touches && e.touches.length > 0) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }

            const scaleX = hCanvas.width / rect.width;
            const scaleY = hCanvas.height / rect.height;

            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function startDrawing(e) {
            e.preventDefault();
            hDrawing = true;
            hHasSigned = true;
            const pos = getCanvasCoordinates(e);
            hCtx.beginPath();
            hCtx.moveTo(pos.x, pos.y);
        }

        function drawLine(e) {
            if (!hDrawing) return;
            e.preventDefault();
            const pos = getCanvasCoordinates(e);
            hCtx.lineTo(pos.x, pos.y);
            hCtx.stroke();
        }

        function stopDrawing(e) {
            if (hDrawing) {
                hDrawing = false;
            }
        }

        hCanvas.addEventListener('mousedown', startDrawing);
        hCanvas.addEventListener('mousemove', drawLine);
        window.addEventListener('mouseup', stopDrawing);

        hCanvas.addEventListener('touchstart', startDrawing, { passive: false });
        hCanvas.addEventListener('touchmove', drawLine, { passive: false });
        window.addEventListener('touchend', stopDrawing);

        function clearHandoverSign() {
            hCtx.clearRect(0, 0, hCanvas.width, hCanvas.height);
            hHasSigned = false;
            document.getElementById('handoverSigData').value = '';
        }

        function submitHandoverSign() {
            if (hHasSigned) {
                document.getElementById('handoverSigData').value = hCanvas.toDataURL('image/png');
            }
            return true;
        }

        // Buka Modal Detail Resume & Tampilkan Link Download Berkas Asli
        function openDetailResumeModal(id) {
            const item = handoverList.find(x => x.id === id);
            if (!item) return;

            document.getElementById('detailJudul').innerHTML = '<i class="fa-solid fa-file-contract text-emerald-600"></i> ' + item.judul_resume;
            document.getElementById('detailPejabat').innerText = 'Disusun oleh: ' + (item.pejabat_nama || '-') + (item.pejabat_nip ? ' (NIP: ' + item.pejabat_nip + ')' : '');
            document.getElementById('detailSelesai').innerText = item.uraian_sudah_selesai || '-';
            document.getElementById('detailBerjalan').innerText = item.uraian_sedang_berjalan || '-';
            document.getElementById('detailRencana').innerText = item.uraian_rencana_ke_depan || '-';
            document.getElementById('detailCatatan').innerText = item.catatan_khusus_pejabat_baru || 'Tidak ada catatan khusus.';

            // Tampilkan File Lampiran
            const fileWrapper = document.getElementById('detailFileWrapper');
            const fileLink = document.getElementById('detailFileLink');
            const fileNameText = document.getElementById('detailFileNameText');

            if (item.dokumen_lampiran && item.dokumen_lampiran.trim() !== '') {
                const cleanFilePath = item.dokumen_lampiran.replace(/^\/+/, '');
                const fileNameOnly = cleanFilePath.split('/').pop();
                
                fileLink.href = '{{ asset("storage") }}/' + cleanFilePath;
                fileNameText.innerText = 'Buka Berkas: ' + fileNameOnly;
                fileWrapper.classList.remove('hidden');
            } else {
                fileWrapper.classList.add('hidden');
            }

            // Tampilkan Gambar Paraf
            const parafImg = document.getElementById('detailParafImg');
            if (item.paraf_digital && item.paraf_digital.startsWith('data:image')) {
                parafImg.src = item.paraf_digital;
                parafImg.classList.remove('hidden');
            } else {
                parafImg.classList.add('hidden');
            }

            document.getElementById('modalDetailResume').classList.remove('hidden');
        }

        window.addEventListener('resize', () => {
            const modal = document.getElementById('modalTambahResume');
            if (modal && !modal.classList.contains('hidden')) {
                initCanvasResolution();
            }
        });
    </script>

@endsection