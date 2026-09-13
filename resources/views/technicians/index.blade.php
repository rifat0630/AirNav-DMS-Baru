@extends('layouts.admin')

@section('title', 'Data Teknisi')

@section('content')

<div class="airnav-technician-header">
    <div class="airnav-technician-heading">
        <div>
            <h1>Data Teknisi</h1>
            <p>
                Kelola data teknisi navigasi dan verifikasi tanda tangan digital (QR TTD) AirNav
            </p>
        </div>
    </div>

    <a
        href="{{ route('technicians.create') }}"
        class="airnav-technician-add"
    >
        <i class="fas fa-plus"></i>
        <span>Tambah Teknisi</span>
    </a>
</div>


{{-- Alert Success --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}

        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


{{-- Alert Error --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}

        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


{{-- Data Teknisi --}}
<div class="card airnav-technician-card">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table airnav-technician-table mb-0">

                <thead>
                    <tr>
                        <th width="70">NO</th>
                        <th>NAMA TEKNISI</th>
                        <th width="220">QR TTD TEKNISI</th>
                        <th width="130">STATUS</th>
                        <th width="180">AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($technicians as $index => $technician)

                        @php
                            $initials = collect(
                                preg_split('/\s+/', trim($technician->name))
                            )
                            ->filter()
                            ->take(2)
                            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                            ->implode('');
                        @endphp

                        <tr>

                            {{-- Nomor --}}
                            <td>
                                <span class="airnav-technician-number">
                                    {{ $index + 1 }}
                                </span>
                            </td>


                            {{-- Nama Teknisi --}}
                            <td>

                                <div class="airnav-technician-profile">

                                    <div class="airnav-technician-avatar">
                                        {{ $initials ?: 'T' }}
                                    </div>

                                    <div class="airnav-technician-info">

                                        <div class="airnav-technician-name">
                                            {{ $technician->name }}
                                        </div>

                                        <div class="airnav-technician-role">
                                            Teknisi Navigasi
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- QR TTD --}}
                            <td>

                                <div class="airnav-technician-qr">

                                    <div class="airnav-technician-qr-box">

                                        <img
                                            src="{{ asset('qrcodes/technicians/' . $technician->qr_token . '.svg') }}"
                                            alt="QR TTD {{ $technician->name }}"
                                        >

                                    </div>

                                    <div class="airnav-technician-qr-label">
                                        <i class="fas fa-qrcode"></i>
                                        <span>QR TTD Teknisi</span>
                                    </div>

                                </div>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($technician->status === 'aktif')

                                    <span class="airnav-status-badge active">
                                        <span class="airnav-status-dot"></span>
                                        Aktif
                                    </span>

                                @elseif($technician->status === 'mutasi')

                                    <span class="airnav-status-badge mutation">
                                        <span class="airnav-status-dot"></span>
                                        Mutasi
                                    </span>

                                @else

                                    <span class="airnav-status-badge inactive">
                                        <span class="airnav-status-dot"></span>
                                        Tidak Aktif
                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}
                            <td>

                                <div class="airnav-technician-actions">

                                    {{-- Mutasi --}}
                                    @if($technician->status === 'aktif')

                                        <form
                                            action="{{ route('technicians.update', $technician) }}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="mutasi"
                                            >

                                            <button
                                                type="submit"
                                                class="airnav-action-mutation"
                                                title="Mutasikan Teknisi"
                                            >
                                                <i class="fas fa-exchange-alt"></i>
                                                <span>Mutasi</span>
                                            </button>

                                        </form>

                                    {{-- Aktifkan --}}
                                    @elseif($technician->status === 'mutasi')

                                        <form
                                            action="{{ route('technicians.update', $technician) }}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="aktif"
                                            >

                                            <button
                                                type="submit"
                                                class="airnav-action-activate"
                                                title="Aktifkan Teknisi"
                                            >
                                                <i class="fas fa-check"></i>
                                                <span>Aktifkan</span>
                                            </button>

                                        </form>

                                    @endif


                                    {{-- Hapus --}}
                                    <form
                                        action="{{ route('technicians.destroy', $technician) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus teknisi ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="airnav-action-delete"
                                            title="Hapus Teknisi"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">

                                <div class="airnav-technician-empty">

                                    <div class="airnav-technician-empty-icon">
                                        <i class="fas fa-users-cog"></i>
                                    </div>

                                    <h3>Belum Ada Data Teknisi</h3>

                                    <p>
                                        Belum ada teknisi yang terdaftar di dalam sistem.
                                    </p>

                                    <a
                                        href="{{ route('technicians.create') }}"
                                        class="airnav-technician-add"
                                    >
                                        <i class="fas fa-plus"></i>
                                        <span>Tambah Teknisi</span>
                                    </a>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection