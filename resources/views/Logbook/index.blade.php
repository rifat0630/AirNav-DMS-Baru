@extends('layouts.app')

@section('title', 'Facility Logbook')

@section('content')
<div class="container py-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="font-weight-bold">Facility Log Book</h3>
    </div>

    <!-- Form Input Logbook -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white font-weight-bold">
            + Tambah Catatan Kegiatan Facility
        </div>
        <div class="card-body">
            <form action="{{ route('logbook.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Tanggal & Jam Otomatis Mengikuti Waktu Laptop/HP Pengguna -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Tanggal & Jam</label>
                        <input type="text" id="displayDatetime" class="form-control bg-light" readonly>
                        <input type="hidden" name="log_datetime" id="inputDatetime">
                        <small class="text-muted">* Terisi otomatis sesuai waktu lokal perangkat Anda</small>
                    </div>

                    <!-- Pilih Nama Teknisi (Dropdown) -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Nama Teknisi</label>
                        <select name="technicians" id="selectTeknisi" class="form-control" onchange="updateSignature()" required>
                            <option value="">-- Pilih Teknisi --</option>
                            <option value="Dea" data-ttd="{{ asset('signatures/dea.png') }}">Dea</option>
                            <option value="Dina" data-ttd="{{ asset('signatures/dina.png') }}">Dina</option>
                            <option value="Budi" data-ttd="{{ asset('signatures/budi.png') }}">Budi</option>
                        </select>
                    </div>

                    <!-- Preview Tanda Tangan Otomatis -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Tanda Tangan</label>
                        <div id="ttdPreviewContainer" class="p-2 border rounded text-center bg-light" style="min-height: 60px;">
                            <span id="txtPlaceholder" class="text-muted small">Pilih teknisi untuk melihat TTD</span>
                            <img id="imgTTD" src="" alt="Preview TTD" style="max-height: 50px; display: none; margin: 0 auto;">
                        </div>
                    </div>
                </div>

                <!-- Catatan Kegiatan -->
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Catatan / Tindakan Kegiatan</label>
                    <textarea name="action_notes" class="form-control" rows="3" placeholder="Masukkan catatan kegiatan..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary float-end">
                    <i class="fas fa-save"></i> Simpan Logbook
                </button>
            </form>
        </div>
    </div>

    <!-- Header & Form Pencarian -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="font-weight-bold mb-0">Daftar Catatan Logbook</h5>
        <form action="{{ route('logbook.index') }}" method="GET" class="d-flex" style="max-width: 400px;">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari kegiatan/teknisi..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <!-- Tabel Data Logbook -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tanggal / Jam</th>
                <th width="30%">Catatan / Tindakan</th>
                <th width="15%">Teknisi</th>
                <th width="20%">Tanda Tangan</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logbooks as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->log_datetime)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->action_notes }}</td>
                    <td><strong>{{ $item->technicians }}</strong></td>
                    <td class="text-center">
                        @php
                            $namaFile = strtolower(trim($item->technicians)) . '.png';
                        @endphp
                        <img src="{{ asset('signatures/' . $namaFile) }}" alt="TTD {{ $item->technicians }}" style="max-height: 40px;" onerror="this.style.display='none'; this.after('-');">
                    </td>
                    <td>
                        <form action="{{ route('logbook.destroy', $item->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus catatan logbook ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-3">Belum ada catatan logbook.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
// Fungsi mengambil jam lokal perangkat secara otomatis
function setLocalDatetime() {
    const now = new Date();

    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    // Format Tampilan (dd/mm/yyyy HH:MM)
    document.getElementById('displayDatetime').value = `${day}/${month}/${year} ${hours}:${minutes}`;
    
    // Format Simpan ke Database (yyyy-mm-dd HH:MM:SS)
    document.getElementById('inputDatetime').value = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

// Jalankan fungsi saat halaman dibuka
document.addEventListener('DOMContentLoaded', setLocalDatetime);

// Fungsi Preview TTD
function updateSignature() {
    var select = document.getElementById('selectTeknisi');
    var selectedOption = select.options[select.selectedIndex];
    var ttdUrl = selectedOption.getAttribute('data-ttd');
    var imgElement = document.getElementById('imgTTD');
    var txtPlaceholder = document.getElementById('txtPlaceholder');

    if (ttdUrl && select.value !== "") {
        imgElement.src = ttdUrl;
        imgElement.style.display = 'block';
        txtPlaceholder.style.display = 'none';
    } else {
        imgElement.style.display = 'none';
        txtPlaceholder.style.display = 'inline';
    }
}
</script>
@endsection