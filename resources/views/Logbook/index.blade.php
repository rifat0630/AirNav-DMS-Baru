@extends('layouts.admin')

@section('content')

<div class="airnav-logbook">

    {{-- =====================================================
         PAGE HEADER
         ===================================================== --}}
    <div class="airnav-logbook-header">

        <div>
            <h1 class="airnav-logbook-title">
                Facility Logbook
            </h1>

            <p class="airnav-logbook-subtitle">
                Kelola dan pantau seluruh aktivitas logbook fasilitas.
            </p>
        </div>

        <a href="{{ route('logbook.create') }}"
           class="airnav-logbook-add">

            <i class="fas fa-plus"></i>

            <span>Tambah Logbook</span>

        </a>

    </div>


    {{-- =====================================================
         ALERT
         ===================================================== --}}

    @if(session('success'))

        <div class="airnav-logbook-alert success">

            <i class="fas fa-check-circle"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="airnav-logbook-alert error">

            <i class="fas fa-exclamation-circle"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         SEARCH
         ===================================================== --}}

    <div class="airnav-logbook-search-card">

        <form method="GET"
              action="{{ route('logbook.index') }}"
              class="airnav-logbook-search-form">

            <div class="airnav-logbook-search-input">

                <i class="fas fa-search"></i>

                <input
                    type="text"
                    name="search"
                    placeholder="Cari teknisi atau kegiatan..."
                    value="{{ request('search') }}"
                >

            </div>

            <button type="submit"
                    class="airnav-logbook-search-button">

                <i class="fas fa-search"></i>

                <span>Cari</span>

            </button>

        </form>

    </div>


    {{-- =====================================================
         TABLE
         ===================================================== --}}

    <div class="airnav-logbook-table-card">

        <div class="airnav-logbook-table-wrapper">

            <table class="airnav-logbook-table">

                <thead>

                    <tr>

                        <th class="col-no">
                            NO
                        </th>

                        <th>
                            TANGGAL & JAM
                        </th>

                        <th>
                            TEKNISI
                        </th>

                        <th class="col-qr">
                            QR<br>LOGBOOK
                        </th>

                        <th class="col-kegiatan">
                            KEGIATAN
                        </th>

                        <th>
                            DIBUAT<br>OLEH
                        </th>

                        <th>
                            STATUS
                        </th>

                        <th class="col-action">
                            AKSI
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($logbooks as $log)

                        <tr>

                            {{-- NO --}}
                            <td class="col-no">

                                {{ $loop->iteration }}

                            </td>


                            {{-- TANGGAL --}}
                            <td class="logbook-date">

                                {{ 
                                    \Carbon\Carbon::parse(
                                        $log->log_datetime
                                    )->format('d/m/Y, H:i')
                                }}

                            </td>


                            {{-- TEKNISI --}}
                            <td class="logbook-technician">

                                {{ 
                                    $log->technician->name 
                                    ?? 
                                    $log->technicians
                                    ?? 
                                    '-'
                                }}

                            </td>


                            {{-- QR --}}
                            <td class="col-qr">

                                @if($log->signature_file)

                                    <div class="airnav-logbook-qr">

                                        <img
                                            src="{{ asset('qrcodes/'.$log->signature_file) }}"
                                            alt="QR Logbook"
                                        >

                                        <span>QR LOGBOOK</span>

                                    </div>

                                @else

                                    <span class="airnav-logbook-no-qr">
                                        QR belum dibuat
                                    </span>

                                @endif

                            </td>


                            {{-- KEGIATAN --}}
                            <td class="logbook-activity">

                                <div class="logbook-activity-title">

                                    {{ $log->action_notes }}

                                </div>

                            </td>


                            {{-- DIBUAT OLEH --}}
                            <td class="logbook-created-by">

                                {{ 
                                    $log->user->name 
                                    ?? 
                                    '-'
                                }}

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($log->signature_status == 'terverifikasi')

                                    <div class="airnav-logbook-status verified">

                                        <span>
                                            Terverifikasi
                                        </span>

                                    </div>

                                @else

                                    <div class="airnav-logbook-status pending">

                                        <span>
                                            Belum Verifikasi
                                        </span>

                                    </div>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="airnav-logbook-actions">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('logbook.edit', $log->id) }}"
                                        class="airnav-logbook-action edit"
                                    >

                                        <i class="fas fa-edit"></i>

                                        <span>Edit</span>

                                    </a>


                                    {{-- SCAN --}}
                                    @if($log->signature_status != 'terverifikasi')

                                        <a
                                            href="{{ route('logbook.scan', $log->id) }}"
                                            class="airnav-logbook-action scan"
                                        >

                                            <i class="fas fa-qrcode"></i>

                                            <span>Scan</span>

                                        </a>

                                    @endif


                                    {{-- DELETE --}}
                                    <form
                                        method="POST"
                                        action="{{ route('logbook.destroy', $log->id) }}"
                                        class="airnav-logbook-delete-form"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="airnav-logbook-action delete"
                                            onclick="return confirm('Hapus logbook?')"
                                        >

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="airnav-logbook-empty"
                            >

                                <i class="fas fa-book-open"></i>

                                <span>
                                    Belum ada data logbook
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =================================================
             TABLE FOOTER
             ================================================= --}}

        <div class="airnav-logbook-footer">

            <div class="airnav-logbook-total">

                Menampilkan

                <strong>
                    {{ $logbooks->count() }}
                </strong>

                catatan logbook

            </div>

        </div>

    </div>

</div>

@endsection