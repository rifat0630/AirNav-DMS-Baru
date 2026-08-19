@extends('adminlte::page')

@section('title', 'Edit Logbook')

@section('content_header')
    <h1>Edit Logbook Facility</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header">
        <strong>Data Logbook</strong>
    </div>

    <div class="card-body">

        {{-- Pesan error --}}
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


        {{-- Pesan sukses --}}
        @if (session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif


        <form
            action="{{ route('logbook.update', $logbook->id) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            {{-- ================================================= --}}
            {{-- TANGGAL & JAM --}}
            {{-- ================================================= --}}

            <div class="form-group mb-3">

                <label>Tanggal & Jam</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ \Carbon\Carbon::parse($logbook->log_datetime)->format('d-m-Y H:i') }}"
                    readonly
                >

            </div>


            {{-- ================================================= --}}
            {{-- TEKNISI --}}
            {{-- ================================================= --}}

            <div class="form-group mb-3">

                <label>Nama Teknisi</label>

                <select
                    name="technician_id"
                    class="form-control"
                    required
                >

                    <option value="">
                        -- Pilih Teknisi --
                    </option>


                    @foreach ($technicians as $technician)

                        <option
                            value="{{ $technician->id }}"
                            {{ $logbook->technician_id == $technician->id ? 'selected' : '' }}
                        >

                            {{ $technician->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- ================================================= --}}
            {{-- KEGIATAN --}}
            {{-- ================================================= --}}

            <div class="form-group mb-3">

                <label>Kegiatan</label>

                <textarea
                    name="action_notes"
                    class="form-control"
                    rows="5"
                    required
                >{{ old('action_notes', $logbook->action_notes) }}</textarea>

            </div>


            {{-- ================================================= --}}
            {{-- STATUS TANDA TANGAN --}}
            {{-- ================================================= --}}

            <div class="form-group mb-3">

                <label>Status Tanda Tangan</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $logbook->signature_status === 'terverifikasi' ? 'Sudah Ditandatangani' : 'Belum Ditandatangani' }}"
                    readonly
                >

            </div>


            {{-- ================================================= --}}
            {{-- TOMBOL --}}
            {{-- ================================================= --}}

            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="fas fa-save"></i>

                Simpan Perubahan

            </button>


            <a
                href="{{ route('logbook.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>

        </form>

    </div>

</div>

@stop