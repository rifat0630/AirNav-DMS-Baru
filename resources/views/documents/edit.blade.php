@extends('adminlte::page')

@section('title', 'Edit Dokumen')

@section('content_header')
<h1>Edit Dokumen</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('documents.update', $document->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nomor Dokumen</label>
                <input type="text"
                       name="document_number"
                       class="form-control"
                       value="{{ $document->document_number }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Judul Dokumen</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ $document->title }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Kategori</label>

                <select name="category_id" class="form-control">

                    @foreach($categories as $category)

                    <option value="{{ $category->id }}"
                        {{ $document->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>

                    @endforeach

                </select>

            </div>

            <button class="btn btn-success">
                Update Dokumen
            </button>

            <a href="{{ route('documents.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </form>

    </div>
</div>

@stop