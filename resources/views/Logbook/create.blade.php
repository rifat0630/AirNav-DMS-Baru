@extends('adminlte::page')

@section('title','Tambah Logbook Facility')

@section('content_header')
<h1>
    Tambah Logbook Facility
</h1>
@stop

@section('content')
<div class="card">
<div class="card-body">

<form action="{{ route('logbook.store') }}" method="POST">
@csrf

<div class="row">
    <!-- Tanggal Terjadi -->
    <div class="col-md-4 form-group">
        <label>Tanggal Terjadi</label>
        <input type="date" name="tanggal_terjadi" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>

    <!-- Tanggal Dibuat -->
    <div class="col-md-4 form-group">
        <label>Tanggal Dibuat</label>
        <input type="date" name="tanggal_dibuat" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>

    <!-- Waktu Selesai -->
    <div class="col-md-4 form-group">
        <label>Waktu Selesai</label>
        <input type="datetime-local" name="waktu_selesai" class="form-control" required>
    </div>
</div>

<div class="form-group">
    <label>Nama Teknisi</label>
    <select name="technician_id" class="form-control" required>
        <option value="">-- Pilih Teknisi --</option>
        @foreach($technicians as $technician)
            <option value="{{ $technician->id }}">{{ $technician->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Catatan Pekerjaan</label>
    <textarea name="action_notes" class="form-control" rows="4" placeholder="Masukkan pekerjaan teknisi..." required></textarea>
</div>

<button type="submit" class="btn btn-primary">
    <i class="fas fa-save"></i> Simpan Logbook
</button>

<a href="{{ route('logbook.index') }}" class="btn btn-secondary">
    Kembali
</a>

</form>

</div>
</div>
@stop