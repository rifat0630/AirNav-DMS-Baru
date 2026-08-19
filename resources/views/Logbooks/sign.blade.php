<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tanda Tangan Logbook</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-body">

            <h3 class="text-center mb-4">
                Tanda Tangan Logbook
            </h3>

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <div class="mb-3">

                <label class="fw-bold">
                    Teknisi
                </label>

                <div class="form-control">

                    {{ $logbook->technician->name ?? '-' }}

                </div>

            </div>

            <div class="mb-3">

                <label class="fw-bold">
                    Tanggal & Jam
                </label>

                <div class="form-control">

                    {{ \Carbon\Carbon::parse($logbook->log_datetime)->format('d-m-Y H:i') }}

                </div>

            </div>

            <div class="mb-3">

                <label class="fw-bold">
                    Kegiatan
                </label>

                <div class="form-control"
                     style="height:auto; min-height:100px;">

                    {{ $logbook->action_notes }}

                </div>

            </div>


            @if($logbook->signature_status == 'terverifikasi')

                <div class="alert alert-success text-center">

                    <h5>
                        ✔ Logbook Sudah Ditandatangani
                    </h5>

                    <div>
                        {{ \Carbon\Carbon::parse($logbook->signed_at)->format('d-m-Y H:i') }}
                    </div>

                </div>

            @else

                <div class="alert alert-info text-center">

                    Pastikan data logbook sudah benar sebelum
                    melakukan tanda tangan.

                </div>

                <form
                    method="POST"
                    action="{{ route('logbook.sign.confirm', $logbook->qr_token) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary btn-lg w-100">

                        ✔ TANDA TANGAN LOGBOOK

                    </button>

                </form>

            @endif

        </div>

    </div>

</div>

</body>

</html>