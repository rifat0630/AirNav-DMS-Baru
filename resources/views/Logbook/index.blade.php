@extends('layouts.app') {{-- Menyesuaikan layout bawaan AirNav DMS --}}

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="fw-bold m-0">Facility Log Book</h3>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Tambah Logbook -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            + Tambah Catatan Kegiatan Facility
        </div>
        <div class="card-body">
            <form action="{{ route('logbook.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tanggal & Jam</label>
                        <input type="datetime-local" name="log_datetime" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Catatan / Tindakan Kegiatan</label>
                        <textarea name="action_notes" class="form-control" rows="2" placeholder="Masukkan catatan kegiatan..." required></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Teknisi</label>
                        <input type="text" name="technicians" class="form-control" placeholder="Contoh: Ahmad, Hendri" required>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Simpan Logbook</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Logbook -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 5%;">NO</th>
                            <th style="width: 20%;">TANGGAL / JAM</th>
                            <th style="width: 45%;">CATATAN / TINDAKAN</th>
                            <th style="width: 20%;">TEKNISI</th>
                            <th class="text-center" style="width: 10%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logbooks as $index => $log)
                        <tr>
                            <td class="ps-3">{{ $index + 1 }}</td>
                            <td class="fw-bold">
                                {{ \Carbon\Carbon::parse($log->log_datetime)->format('d/m/Y H:i') }}
                            </td>
                            <td>{!! nl2br(e($log->action_notes)) !!}</td>
                            <td><span class="badge bg-secondary">{{ $log->technicians }}</span></td>
                            <td class="text-center">
                                <form action="{{ route('logbook.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada catatan logbook.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection