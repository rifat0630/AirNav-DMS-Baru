@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard AirNav DMS</h1>

    <a href="{{ route('documents.create') }}" class="btn btn-primary">
        <i class="fas fa-upload"></i> Upload Dokumen
    </a>
</div>
@stop

@section('content')

<div class="row">

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalDocuments }}</h3>
                <p>Total Dokumen</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalCategories }}</h3>
                <p>Kategori</p>
            </div>
            <div class="icon">
                <i class="fas fa-folder"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $activeDocuments }}</h3>
                <p>Dokumen Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>User</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

</div>

<div class="row">

<div class="col-md-12">

<div class="card card-primary">

<div class="card-header">
<h3 class="card-title">
Quick Access
</h3>
</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-3">
<a href="{{ route('documents.create') }}" class="btn btn-primary btn-lg btn-block">
<i class="fas fa-upload fa-2x"></i><br><br>
Upload Dokumen
</a>
</div>

<div class="col-md-3 mb-3">
<a href="{{ route('documents.index') }}" class="btn btn-success btn-lg btn-block">
<i class="fas fa-folder-open fa-2x"></i><br><br>
Daftar Dokumen
</a>
</div>

<div class="col-md-3 mb-3">
<a href="#" class="btn btn-warning btn-lg btn-block">
<i class="fas fa-folder fa-2x"></i><br><br>
Kategori
</a>
</div>

<div class="col-md-3 mb-3">
<a href="{{ route('activity_logs.index') }}" class="btn btn-danger btn-lg btn-block">
<i class="fas fa-history fa-2x"></i><br><br>
Activity Log
</a>
</div>

</div>

</div>

</div>

</div>

</div>

<div class="card">

<div class="card-header bg-primary">

<h3 class="card-title">
Tentang Sistem
</h3>

</div>

<div class="card-body">

<h5>AirNav Document Management System</h5>

<p>
Sistem ini digunakan untuk mengelola arsip digital AirNav Indonesia secara terpusat.
</p>

</div>

</div>

@stop