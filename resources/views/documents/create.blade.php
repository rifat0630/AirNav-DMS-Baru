@extends('adminlte::page')

@section('title', 'Tambah Dokumen')

@section('content_header')
    <h1>Tambah Dokumen</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="form-group mb-3">
                <label>Nomor Dokumen</label>
                <input type="text" name="document_number" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Judul Dokumen</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Kategori</label>

                <select name="category_id" class="form-control" required>

                    <option value="">-- Pilih Kategori --</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="form-group mb-3">
                <label>Upload File</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <button class="btn btn-primary">
                Simpan Dokumen
            </button>

            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>
</div>

@stop