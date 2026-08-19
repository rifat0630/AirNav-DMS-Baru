@extends('layouts.app')

@section('content')

<div class="container">

    <h3 class="mb-3">
        Scan QR Logbook
    </h3>

    <div class="card">

        <div class="card-body text-center">

            <h5 class="mb-3">
                Silakan teknisi scan QR ini menggunakan HP
            </h5>

            @if($logbook->qr_token)

                <img
                    src="{{ asset('qrcodes/'.$logbook->qr_token) }}"
                    alt="QR Logbook"
                    style="width:300px;height:300px;"
                >

                <div class="mt-3">

                    <strong>
                        Teknisi:
                    </strong>

                    {{ $logbook->technician->name ?? '-' }}

                </div>

                <div class="mt-2">

                    <strong>
                        Kegiatan:
                    </strong>

                    {{ $logbook->action_notes }}

                </div>

                <div class="mt-3">

                    @if($logbook->signature_status == 'terverifikasi')

                        <span class="badge badge-success"
                              style="font-size:16px;">

                            ✔ Sudah Ditandatangani

                        </span>

                    @else

                        <span class="badge badge-warning"
                              style="font-size:16px;">

                            Menunggu Tanda Tangan Teknisi

                        </span>

                    @endif

                </div>

            @else

                <div class="alert alert-danger">

                    QR Logbook belum tersedia.

                </div>

            @endif

            <div class="mt-4">

                <a href="{{ route('logbook.index') }}"
                   class="btn btn-secondary">

                    Kembali

                </a>

            </div>

        </div>

    </div>

</div>

@endsection