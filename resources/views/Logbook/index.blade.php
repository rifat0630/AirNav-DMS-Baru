@extends('layouts.app')

@section('content')
@php
    $userRole = strtolower(auth()->user()->role ?? '');
    $isMantek = in_array($userRole, ['manager teknik', 'manager_teknik', 'mantek', 'admin']);
@endphp

<div class="p-6 space-y-6">
    {{-- Header Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span>📘</span> E-Logbook Harian Pemeliharaan Fasilitas
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Catatan operasional shift (Pagi, Siang, Malam), pencatatan tanggal manual, catatan Manager Teknik, dan Live Paraf digital.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(($menungguParafCount ?? 0) > 0)
                <span class="px-3 py-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold">
                    ⏳ {{ $menungguParafCount }} Menunggu Paraf Mantek
                </span>
            @endif

            <button type="button" onclick="openTambahModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm transition cursor-pointer">
                + Tambah E-Logbook
            </button>
        </div>
    </div>

    {{-- Alert Notifikasi --}}
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

    {{-- Tabel Riwayat E-Logbook --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xs font-bold tracking-wider text-slate-700 uppercase">
                Riwayat Catatan E-Logbook Terkini
            </h2>
            <span class="text-xs text-slate-400">Total: {{ $logbooks->count() }} entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Tanggal & Shift</th>
                        <th class="py-3.5 px-4">Fasilitas & Uraian Teknisi</th>
                        <th class="py-3.5 px-4">Catatan Manager Teknik</th>
                        <th class="py-3.5 px-4 text-center">Live Paraf Teknisi</th>
                        <th class="py-3.5 px-4 text-center">Live Paraf Mantek</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse($logbooks as $item)
                        <tr class="hover:bg-slate-50/80 transition align-top">
                            {{-- Tanggal & Shift --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded bg-blue-50 text-blue-700 mb-1.5">
                                    Shift {{ $item->shift }}
                                </span>
                                <div class="text-xs text-slate-600 space-y-0.5">
                                    <div><span class="font-medium text-slate-700">Terjadi:</span> {{ optional($item->tanggal_terjadi)->format('d/m/Y') ?? '-' }}</div>
                                    <div><span class="font-medium text-slate-700">Dibuat:</span> {{ optional($item->tanggal_dibuat)->format('d/m/Y') ?? '-' }}</div>
                                    <div>
                                        <span class="font-medium text-slate-700">Selesai:</span>
                                        {{ $item->waktu_selesai ? $item->waktu_selesai->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </div>
                            </td>

                            {{-- Fasilitas & Uraian --}}
                            <td class="py-4 px-4 max-w-xs">
                                <div class="font-bold text-slate-800 mb-1">{{ $item->fasilitas_alat }}</div>
                                <p class="text-slate-600 text-xs whitespace-pre-line">{{ $item->uraian_pekerjaan }}</p>
                            </td>

                            {{-- Catatan Manager Teknik --}}
                            <td class="py-4 px-4 max-w-xs">
                                @if($item->catatan_mantek)
                                    <div class="bg-amber-50/70 border border-amber-200 rounded-lg p-2.5 text-xs text-slate-700">
                                        <span class="block font-semibold text-amber-800 mb-0.5">Catatan Mantek:</span>
                                        <p class="whitespace-pre-line">{{ $item->catatan_mantek }}</p>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum ada catatan Mantek</span>
                                @endif
                            </td>

                            {{-- Live Paraf Teknisi --}}
                            <td class="py-4 px-4 text-center">
                                @if($item->paraf_teknisi)
                                    <div class="inline-flex flex-col items-center bg-white border border-slate-200 rounded-lg p-2">
                                        <img src="{{ $item->paraf_teknisi }}" alt="Paraf Teknisi" class="h-14 w-28 object-contain">
                                        <span class="text-[11px] font-semibold text-slate-700 mt-1 border-t border-slate-100 pt-1 w-full">
                                            {{ $item->teknisi->name ?? 'Teknisi' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $item->paraf_teknisi_at ? $item->paraf_teknisi_at->format('d/m H:i') : '' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>

                            {{-- Live Paraf Manager Teknik --}}
                            <td class="py-4 px-4 text-center">
                                @if($item->paraf_mantek)
                                    <div class="inline-flex flex-col items-center bg-emerald-50/40 border border-emerald-200 rounded-lg p-2">
                                        <img src="{{ $item->paraf_mantek }}" alt="Paraf Mantek" class="h-14 w-28 object-contain">
                                        <span class="text-[11px] font-semibold text-emerald-800 mt-1 border-t border-emerald-100 pt-1 w-full">
                                            {{ $item->mantek->name ?? 'Manager Teknik' }}
                                        </span>
                                        <span class="text-[10px] text-emerald-600">
                                            {{ $item->paraf_mantek_at ? $item->paraf_mantek_at->format('d/m H:i') : '' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-block px-2.5 py-1 text-[11px] font-medium rounded-full bg-amber-100 text-amber-800">
                                        ⏳ Menunggu Paraf
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-4 text-center space-y-2">
                                @if($isMantek)
                                    <button type="button"
                                        onclick="openMantekModal({{ $item->id }}, '{{ addslashes($item->fasilitas_alat) }}', '{{ addslashes($item->catatan_mantek ?? '') }}')"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-sm transition cursor-pointer">
                                        ✍️ {{ $item->paraf_mantek ? 'Edit Catatan & Paraf' : 'Catatan & Paraf' }}
                                    </button>
                                @endif

                                <form action="{{ route('logbook.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus catatan logbook ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 underline cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-sm">
                                Belum ada entri E-Logbook. Klik <strong>+ Tambah E-Logbook</strong> untuk membuat catatan baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL 1: TAMBAH E-LOGBOOK + LIVE PARAF TEKNISI --}}
<div id="modalTambahLogbook" class="hidden fixed inset-0 z-50 bg-slate-900/50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden my-8">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Tambah E-Logbook Harian</h3>
            <button type="button" onclick="document.getElementById('modalTambahLogbook').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 text-xl leading-none cursor-pointer">&times;</button>
        </div>

        <form id="formTambahLogbook" action="{{ route('logbook.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Fasilitas / Alat *</label>
                    <input type="text" name="fasilitas_alat" required placeholder="Contoh: Radar / Glide Path"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Shift Jaga *</label>
                    <select name="shift" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="Pagi">Pagi</option>
                        <option value="Siang">Siang</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Teknisi Bertugas *</label>
                <select name="user_id" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @foreach(($technicians ?? collect()) as $tech)
                        <option value="{{ $tech->id }}" {{ auth()->id() == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }} ({{ ucwords(str_replace('_', ' ', $tech->role ?? 'Teknisi')) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Terjadi *</label>
                    <input type="date" name="tanggal_terjadi" value="{{ date('Y-m-d') }}" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Dibuat *</label>
                    <input type="date" name="tanggal_dibuat" value="{{ date('Y-m-d') }}" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal & Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Uraian Pekerjaan / Pemeriksaan *</label>
                <textarea name="uraian_pekerjaan" rows="3" required placeholder="Detail pemeriksaan atau penanganan gangguan..."
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            {{-- Live Paraf Canvas Teknisi --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-slate-700">
                        Live Paraf Teknisi *
                    </label>
                    <button type="button" onclick="padTeknisi.clear()"
                        class="text-xs text-rose-600 hover:underline cursor-pointer">Hapus Coretan</button>
                </div>
                <div class="border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 overflow-hidden">
                    <canvas id="canvasTeknisi" class="w-full h-32 touch-none cursor-crosshair block"></canvas>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Goreskan paraf langsung pada kotak di atas menggunakan mouse atau sentuhan.</p>
                <input type="hidden" name="paraf_teknisi" id="inputParafTeknisi">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalTambahLogbook').classList.add('hidden')"
                    class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm cursor-pointer">
                    Simpan E-Logbook
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: CATATAN & LIVE PARAF MANAGER TEKNIK (MANTEK) --}}
<div id="modalMantek" class="hidden fixed inset-0 z-50 bg-slate-900/50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden my-8">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-emerald-50/50">
            <div>
                <h3 class="font-bold text-slate-800">Catatan & Paraf Manager Teknik</h3>
                <p id="mantekModalSubtitle" class="text-xs text-slate-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="document.getElementById('modalMantek').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 text-xl leading-none cursor-pointer">&times;</button>
        </div>

        <form id="formMantek" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Evaluasi Manager Teknik</label>
                <textarea name="catatan_mantek" id="inputCatatanMantek" rows="3"
                    placeholder="Tuliskan catatan, arahan tindak lanjut, atau evaluasi operasional..."
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
            </div>

            {{-- Live Paraf Canvas Mantek --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-slate-700">
                        Live Paraf Manager Teknik ({{ auth()->user()->name ?? 'Mantek' }}) *
                    </label>
                    <button type="button" onclick="padMantek.clear()"
                        class="text-xs text-rose-600 hover:underline cursor-pointer">Hapus Coretan</button>
                </div>
                <div class="border-2 border-dashed border-emerald-300 rounded-lg bg-emerald-50/30 overflow-hidden">
                    <canvas id="canvasMantek" class="w-full h-32 touch-none cursor-crosshair block"></canvas>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Goreskan paraf pengesahan Manager Teknik pada kotak di atas.</p>
                <input type="hidden" name="paraf_mantek" id="inputParafMantek">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalMantek').classList.add('hidden')"
                    class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-sm cursor-pointer">
                    Simpan Catatan & Sahkan Paraf
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT HTML5 CANVAS LIVE SIGNATURE PAD --}}
<script>
    function initSignaturePad(canvasId) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        let drawing = false;
        let hasDrawn = false;

        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width || 440;
            canvas.height = rect.height || 128;
            ctx.lineWidth = 2.2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a';
        }

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return { x: clientX - rect.left, y: clientY - rect.top };
        }

        function startDraw(e) {
            e.preventDefault();
            drawing = true;
            hasDrawn = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        }

        function draw(e) {
            if (!drawing) return;
            e.preventDefault();
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
        }

        function endDraw() {
            drawing = false;
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', endDraw);

        canvas.addEventListener('touchstart', startDraw, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        window.addEventListener('touchend', endDraw);

        return {
            resize: resizeCanvas,
            clear: function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasDrawn = false;
            },
            isEmpty: function () {
                return !hasDrawn;
            },
            toDataURL: function () {
                return canvas.toDataURL('image/png');
            }
        };
    }

    const padTeknisi = initSignaturePad('canvasTeknisi');
    const padMantek = initSignaturePad('canvasMantek');

    function openTambahModal() {
        document.getElementById('modalTambahLogbook').classList.remove('hidden');
        setTimeout(() => {
            padTeknisi.resize();
            padTeknisi.clear();
        }, 50);
    }

    function openMantekModal(id, fasilitas, catatanLama) {
        const form = document.getElementById('formMantek');
        form.action = `/logbook-harian/${id}/review-mantek`;
        document.getElementById('mantekModalSubtitle').textContent = 'Fasilitas: ' + fasilitas;
        document.getElementById('inputCatatanMantek').value = catatanLama;
        document.getElementById('modalMantek').classList.remove('hidden');

        setTimeout(() => {
            padMantek.resize();
            padMantek.clear();
        }, 50);
    }

    document.getElementById('formTambahLogbook').addEventListener('submit', function (e) {
        if (padTeknisi.isEmpty()) {
            e.preventDefault();
            alert('Silakan goreskan Live Paraf Teknisi terlebih dahulu!');
            return;
        }
        document.getElementById('inputParafTeknisi').value = padTeknisi.toDataURL();
    });

    document.getElementById('formMantek').addEventListener('submit', function (e) {
        if (padMantek.isEmpty()) {
            e.preventDefault();
            alert('Silakan goreskan Live Paraf Manager Teknik terlebih dahulu!');
            return;
        }
        document.getElementById('inputParafMantek').value = padMantek.toDataURL();
    });
</script>
@endsection