@extends('adminlte::page')

@section('title', 'Tambah Dokumen')


@section('content_header')

    <div class="d-flex justify-content-between align-items-center">

        <h1>
            <i class="fas fa-file-upload"></i>
            Tambah Dokumen
        </h1>

    </div>

@stop


@section('content')


{{-- Pesan error validasi --}}

@if($errors->any())

    <div class="alert alert-danger">

        <strong>
            Dokumen belum dapat disimpan.
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div class="card">

    <div class="card-header">

        <strong>
            Data Dokumen
        </strong>

    </div>


    <div class="card-body">

        <form
            action="{{ route('documents.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            {{-- Nomor Dokumen --}}

            <div class="form-group mb-3">

                <label>
                    Nomor Dokumen
                </label>

                <input
                    type="text"
                    name="document_number"
                    class="form-control"
                    value="{{ old('document_number') }}"
                    placeholder="Contoh: AIRNAV/OPS/001/2026"
                    required
                >

                <small class="text-muted">

                    Masukkan nomor dokumen yang unik.

                </small>

            </div>


            {{-- Judul Dokumen --}}

            <div class="form-group mb-3">

                <label>
                    Judul Dokumen
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    placeholder="Masukkan judul dokumen"
                    required
                >

            </div>


            {{-- Kategori --}}

            <div class="form-group mb-3">

                <label>
                    Kategori
                </label>

                <select
                    name="category_id"
                    class="form-control"
                    required
                >

                    <option value="">
                        -- Pilih Kategori --
                    </option>


                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

                <small class="text-muted">

                    Kategori menentukan tempat penyimpanan dokumen
                    di Google Drive.

                </small>

            </div>


            {{-- Versi --}}

            <div class="form-group mb-3">

                <label>
                    Versi Dokumen
                </label>

                <input
                    type="text"
                    name="version"
                    class="form-control"
                    value="{{ old('version', '1.0') }}"
                    placeholder="Contoh: 1.0"
                >

                <small class="text-muted">

                    Jika tidak diubah, versi awal adalah 1.0.

                </small>

            </div>


            {{-- File --}}

            <div class="form-group mb-4">

                <label>
                    File Dokumen
                </label>

                <input
                    type="file"
                    name="file"
                    class="form-control"
                    required
                >

                <small class="text-muted">

                    Pilih file dokumen yang akan disimpan.

                </small>

            </div>


            {{-- Tombol --}}

            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="fas fa-save"></i>

                Simpan Dokumen

            </button>


            <a
                href="{{ route('documents.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>


        </form>

    </div>

</div>

@stop