@extends('layouts.app')

@section('content')
<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card">
<div class="card-header bg-success text-white">
<h4 class="mb-0">Verifikasi Logbook Facility</h4>
</div>

<div class="card-body">

<div class="alert alert-info">
<i class="fas fa-user"></i> Login sebagai: <strong>{{ Auth::user()->name }}</strong>
</div>

<table class="table table-bordered">
<tr>
<th width="35%">Teknisi</th>
<td>{{ $logbook->technician->name ?? '-' }}</td>
</tr>
<tr>
<th>Tanggal Terjadi</th>
<td>{{ $logbook->tanggal_terjadi ? \Carbon\Carbon::parse($logbook->tanggal_terjadi)->format('d-m-Y') : '-' }}</td>
</tr>
<tr>
<th>Tanggal Dibuat</th>
<td>{{ $logbook->tanggal_dibuat ? \Carbon\Carbon::parse($logbook->tanggal_dibuat)->format('d-m-Y') : '-' }}</td>
</tr>
<tr>
<th>Waktu Selesai</th>
<td>{{ $logbook->waktu_selesai ? \Carbon\Carbon::parse($logbook->waktu_selesai)->format('d-m-Y H:i') : '-' }}</td>
</tr>
<tr>
<th>Kegiatan</th>
<td>{{ $logbook->action_notes }}</td>
</tr>

<!-- Status Paraf Teknisi -->
<tr>
<th>Paraf Teknisi</th>
<td>
@if($logbook->paraf_teknisi)
<span class="badge badge-success">✔ Paraf Teknisi ({{ $logbook->paraf_teknisi }})</span>
@else
<span class="badge badge-warning">Belum Paraf Teknisi</span>
@endif
</td>
</tr>

<!-- Status Paraf Mantek -->
<tr>
<th>Paraf Mantek</th>
<td>
@if($logbook->paraf_mantek)
<span class="badge badge-success">✔ Paraf Mantek ({{ $logbook->paraf_mantek }})</span>
@else
<span class="badge badge-warning">Belum Paraf Mantek</span>
@endif
</td>
</tr>
</table>

<!-- Tombol Konfirmasi Verifikasi / Paraf -->
<form method="POST" action="{{ route('logbook.sign.confirm', $logbook->qr_token ?? 1) }}">
@csrf
<div class="form-row">
    <div class="col">
        <button type="submit" name="action_type" value="teknisi" class="btn btn-primary btn-block" onclick="return confirm('Berikan Paraf Teknisi?')">
            <i class="fas.fa-signature"></i> Paraf Sebagai Teknisi
        </button>
    </div>
    <div class="col">
        <button type="submit" name="action_type" value="mantek" class="btn btn-success btn-block" onclick="return confirm('Berikan Paraf Mantek?')">
            <i class="fas.fa-stamp"></i> Paraf Sebagai Mantek
        </button>
    </div>
</div>
</form>

</div>
</div>

</div>
</div>
</div>
@endsection