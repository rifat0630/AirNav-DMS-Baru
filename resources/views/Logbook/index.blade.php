@extends('layouts.admin')

@section('title','Facility Logbook')

@section('content')
<div class="airnav-logbook">

    <div class="airnav-logbook-header">
        <div>
            <h1 class="airnav-logbook-title">Facility Logbook</h1>
            <p class="airnav-logbook-subtitle">Kelola dan pantau seluruh aktivitas logbook fasilitas.</p>
        </div>

        <button type="button" class="airnav-logbook-add"
                data-toggle="modal" data-target="#modalTambahLogbook">
            <i class="fas fa-plus"></i>
            <span>Tambah Logbook</span>
        </button>
    </div>

    @if(session('success'))
        <div class="airnav-logbook-alert success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="airnav-logbook-alert error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="airnav-logbook-search-card">
        <form method="GET" action="{{ route('logbook.index') }}" class="airnav-logbook-search-form">
            <div class="airnav-logbook-search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search"
                       placeholder="Cari teknisi atau kegiatan..."
                       value="{{ request('search') }}">
            </div>

            <button type="submit" class="airnav-logbook-search-button">
                <i class="fas fa-search"></i>
                <span>Cari</span>
            </button>
        </form>
    </div>

    <div class="airnav-logbook-table-card">
        <div class="airnav-logbook-table-wrapper">
            <table class="airnav-logbook-table">
                <thead>
                    <tr>
                        <th class="col-no">NO</th>
                        <th>TANGGAL & JAM</th>
                        <th>TEKNISI</th>
                        <th class="col-qr">QR<br>LOGBOOK</th>
                        <th class="col-kegiatan">KEGIATAN</th>
                        <th>DIBUAT<br>OLEH</th>
                        <th>STATUS</th>
                        <th class="col-action">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logbooks as $log)
                        <tr>
                            <td class="col-no">{{ $loop->iteration }}</td>

                            <td class="logbook-date">
                                {{ \Carbon\Carbon::parse($log->log_datetime)->format('d/m/Y, H:i') }}
                            </td>

                            <td class="logbook-technician">
                                {{ $log->technician->name ?? $log->technicians ?? '-' }}
                            </td>

                            <td class="col-qr">
                                @if($log->signature_file)
                                    <div class="airnav-logbook-qr">
                                        <img src="{{ asset('qrcodes/'.$log->signature_file) }}" alt="QR Logbook">
                                        <span>QR LOGBOOK</span>
                                    </div>
                                @else
                                    <span class="airnav-logbook-no-qr">QR belum dibuat</span>
                                @endif
                            </td>

                            <td class="logbook-activity">
                                <div class="logbook-activity-title">
                                    {{ $log->action_notes }}
                                </div>
                            </td>

                            <td class="logbook-created-by">
                                {{ $log->user->name ?? '-' }}
                            </td>

                            <td>
                                @if($log->signature_status == 'terverifikasi')
                                    <div class="airnav-logbook-status verified">
                                        <span>Terverifikasi</span>
                                    </div>
                                @else
                                    <div class="airnav-logbook-status pending">
                                        <span>Belum Verifikasi</span>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="airnav-logbook-actions">

                                    <button type="button"
                                            class="airnav-logbook-action edit"
                                            data-toggle="modal"
                                            data-target="#modalEditLogbook{{ $log->id }}">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>

                                    @if($log->signature_status != 'terverifikasi')
                                        <a href="{{ route('logbook.scan', $log->id) }}"
                                           class="airnav-logbook-action scan">
                                            <i class="fas fa-qrcode"></i>
                                            <span>Scan</span>
                                        </a>
                                    @endif

                                    <form method="POST"
                                          action="{{ route('logbook.destroy', $log->id) }}"
                                          class="airnav-logbook-delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="airnav-logbook-action delete"
                                                onclick="return confirm('Hapus logbook?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="airnav-logbook-empty">
                                <i class="fas fa-book-open"></i>
                                <span>Belum ada data logbook</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="airnav-logbook-footer">
            <div class="airnav-logbook-total">
                Menampilkan
                <strong>{{ $logbooks->count() }}</strong>
                catatan logbook
            </div>
        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- MODAL TAMBAH LOGBOOK --}}
{{-- ========================================================= --}}

<div class="modal fade" id="modalTambahLogbook" tabindex="-1"
     role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <div class="modal-content airnav-logbook-modal">

            <div class="modal-header airnav-logbook-modal-header">
                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-book"></i>
                        Tambah Logbook Facility
                    </h5>
                    <p>Tambahkan aktivitas teknisi ke dalam logbook.</p>
                </div>

                <button type="button" class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('logbook.store') }}" method="POST">
                @csrf

                <div class="modal-body airnav-logbook-modal-body">

                    <div class="form-group">
                        <label>Tanggal & Jam</label>

                        <input type="text"
                               class="form-control"
                               value="{{ date('d-m-Y H:i') }}"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Teknisi</label>

                        <select name="technician_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Teknisi --
                            </option>

                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">
                                    {{ $technician->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Catatan Pekerjaan</label>

                        <textarea name="action_notes"
                                  class="form-control"
                                  rows="5"
                                  placeholder="Masukkan pekerjaan teknisi..."
                                  required></textarea>
                    </div>

                </div>

                <div class="modal-footer airnav-logbook-modal-footer">

                    <button type="button"
                            class="airnav-logbook-modal-cancel"
                            data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="airnav-logbook-modal-save">
                        <i class="fas fa-save"></i>
                        Simpan Logbook
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- MODAL EDIT LOGBOOK --}}
{{-- ========================================================= --}}

@foreach($logbooks as $log)

<div class="modal fade"
     id="modalEditLogbook{{ $log->id }}"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content airnav-logbook-modal">

            <div class="modal-header airnav-logbook-modal-header">

                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i>
                        Edit Logbook Facility
                    </h5>

                    <p>Perbarui informasi aktivitas logbook.</p>
                </div>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <form action="{{ route('logbook.update', $log->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="modal-body airnav-logbook-modal-body">

                    <div class="form-group">

                        <label>Tanggal & Jam</label>

                        <input type="text"
                               class="form-control"
                               value="{{ \Carbon\Carbon::parse($log->log_datetime)->format('d-m-Y H:i') }}"
                               readonly>

                    </div>


                    <div class="form-group">

                        <label>Nama Teknisi</label>

                        <select name="technician_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Teknisi --
                            </option>

                            @foreach($technicians as $technician)

                                <option value="{{ $technician->id }}"
                                    {{ $log->technician_id == $technician->id ? 'selected' : '' }}>

                                    {{ $technician->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Kegiatan</label>

                        <textarea name="action_notes"
                                  class="form-control"
                                  rows="5"
                                  required>{{ old('action_notes', $log->action_notes) }}</textarea>

                    </div>


                    <div class="form-group">

                        <label>Status Tanda Tangan</label>

                        <input type="text"
                               class="form-control"
                               value="{{ $log->signature_status == 'terverifikasi' ? 'Sudah Ditandatangani' : 'Belum Ditandatangani' }}"
                               readonly>

                    </div>

                </div>


                <div class="modal-footer airnav-logbook-modal-footer">

                    <button type="button"
                            class="airnav-logbook-modal-cancel"
                            data-dismiss="modal">

                        Batal

                    </button>


                    <button type="submit"
                            class="airnav-logbook-modal-save">

                        <i class="fas fa-save"></i>
                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach

@endsection