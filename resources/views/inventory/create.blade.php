@extends('adminlte::page')

@section('title', 'Tambah Barang')

@section('content_header')

    <h1>Tambah Barang</h1>

@stop


@section('content')

<div class="card">

    <div class="card-header">
        <strong>Tambah Data Barang</strong>
    </div>

    <div class="card-body">

        {{-- Error validasi --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>Terjadi kesalahan:</strong>

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('inventory.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            {{-- NAMA BARANG --}}
            <div class="form-group mb-3">

                <label>
                    Nama Barang
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Contoh: RJ45 Connector"
                    required
                >

            </div>


            {{-- KODE BARANG --}}
            <div class="form-group mb-3">

                <label>
                    Kode Barang
                </label>

                <input
                    type="text"
                    name="code"
                    class="form-control"
                    value="{{ old('code') }}"
                    placeholder="Contoh: RJ45-001"
                >

                <small class="text-muted">
                    Kode digunakan untuk mempermudah pencarian barang.
                </small>

            </div>


            {{-- STOK --}}
            <div class="form-group mb-3">

                <label>
                    Jumlah Stok
                </label>

                <input
                    type="number"
                    name="stock"
                    class="form-control"
                    value="{{ old('stock', 0) }}"
                    min="0"
                    step="0.01"
                    required
                >

            </div>


            {{-- SATUAN --}}
            <div class="form-group mb-3">

                <label>
                    Satuan
                </label>

                <select
                    name="unit"
                    class="form-control"
                    required
                >

                    <option value="">
                        -- Pilih Satuan --
                    </option>

                    <option value="pcs"
                        {{ old('unit') == 'pcs' ? 'selected' : '' }}>
                        Pcs
                    </option>

                    <option value="meter"
                        {{ old('unit') == 'meter' ? 'selected' : '' }}>
                        Meter
                    </option>

                    <option value="roll"
                        {{ old('unit') == 'roll' ? 'selected' : '' }}>
                        Roll
                    </option>

                    <option value="box"
                        {{ old('unit') == 'box' ? 'selected' : '' }}>
                        Box
                    </option>

                    <option value="unit"
                        {{ old('unit') == 'unit' ? 'selected' : '' }}>
                        Unit
                    </option>

                    <option value="buah"
                        {{ old('unit') == 'buah' ? 'selected' : '' }}>
                        Buah
                    </option>

                    <option value="set"
                        {{ old('unit') == 'set' ? 'selected' : '' }}>
                        Set
                    </option>

                </select>

            </div>


            {{-- FOTO --}}
            <div class="form-group mb-3">

                <label>
                    Foto Barang
                </label>

                <input
                    type="file"
                    name="photo"
                    class="form-control"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                >

                <small class="text-muted">
                    Format JPG, JPEG, PNG atau WEBP. Maksimal 2 MB.
                </small>

            </div>


            {{-- DESKRIPSI --}}
            <div class="form-group mb-3">

                <label>
                    Keterangan / Deskripsi
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Contoh: Connector RJ45 untuk kabel LAN..."
                >{{ old('description') }}</textarea>

            </div>


            {{-- TOMBOL --}}
            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="fas fa-save"></i>

                Simpan Barang

            </button>


            <a
                href="{{ route('inventory.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>


        </form>

    </div>

</div>

@stop