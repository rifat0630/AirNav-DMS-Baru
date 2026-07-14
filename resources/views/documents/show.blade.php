@extends('adminlte::page')

@section('title', 'Detail Dokumen')

@section('content_header')
    <h1>Detail Dokumen</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">{{ $document->title }}</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="25%">Nomor Dokumen</th>
                <td>{{ $document->document_number }}</td>
            </tr>

            <tr>
                <th>Judul</th>
                <td>{{ $document->title }}</td>
            </tr>

            <tr>
                <th>Kategori</th>
                <td>{{ $document->category->name }}</td>
            </tr>

            <tr>
                <th>Versi</th>
                <td>{{ $document->version }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>{{ $document->status }}</td>
            </tr>

            <tr>
                <th>Uploader</th>
                <td>{{ $document->user->name }}</td>
            </tr>

            <tr>
                <th>Nama File</th>
                <td>{{ $document->file_name }}</td>
            </tr>

            <tr>
                <th>Tanggal Upload</th>
                <td>{{ $document->created_at->format('d-m-Y H:i') }}</td>
            </tr>

        </table>

    </div>

    <div class="card-footer">

        <a href="{{ route('documents.index') }}"
           class="btn btn-secondary">

            Kembali

        </a>

        <a href="{{ route('documents.edit',$document->id) }}"
           class="btn btn-warning">

            Edit

        </a>

    </div>

</div>

@stop