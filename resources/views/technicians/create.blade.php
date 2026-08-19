@extends('adminlte::page')

@section('title', 'Tambah Teknisi')

@section('content_header')

<div class="d-flex justify-content-between">

    <h1>
        Tambah Teknisi
    </h1>

</div>

@stop


@section('content')

@if($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

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
            Data Teknisi
        </strong>

    </div>


    <div class="card-body">

        <form
            method="POST"
            action="{{ route('technicians.store') }}"
        >

            @csrf


            <div class="form-group">

                <label>
                    Nama Teknisi
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama teknisi"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="fas fa-save"></i>

                Simpan Teknisi

            </button>


            <a
                href="{{ route('technicians.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>

        </form>

    </div>

</div>

@stop