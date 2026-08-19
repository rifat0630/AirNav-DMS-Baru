@extends('adminlte::page')

@section('title', 'Detail Barang')


@section('content_header')

    <h1>
        Detail Barang
    </h1>

@stop


@section('content')


{{-- ========================================================= --}}
{{-- PESAN SUKSES --}}
{{-- ========================================================= --}}

@if(session('success'))

    <div class="alert alert-success">

        <i class="fas fa-check-circle"></i>

        {{ session('success') }}

    </div>

@endif


{{-- ========================================================= --}}
{{-- PESAN ERROR --}}
{{-- ========================================================= --}}

@if(session('error'))

    <div class="alert alert-danger">

        <i class="fas fa-exclamation-circle"></i>

        {{ session('error') }}

    </div>

@endif


{{-- ========================================================= --}}
{{-- VALIDATION ERROR --}}
{{-- ========================================================= --}}

@if($errors->any())

    <div class="alert alert-danger">

        <strong>
            Terjadi kesalahan:
        </strong>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif



{{-- ========================================================= --}}
{{-- INFORMASI BARANG --}}
{{-- ========================================================= --}}

<div class="card">


    <div class="card-header">

        <strong>

            <i class="fas fa-box"></i>

            Informasi Barang

        </strong>

    </div>


    <div class="card-body">


        <div class="row">


            {{-- ================================================= --}}
            {{-- FOTO --}}
            {{-- ================================================= --}}

            <div class="col-md-4 text-center">

                @if($inventory->photo)

                    <img
                        src="{{ asset('storage/' . $inventory->photo) }}"
                        alt="{{ $inventory->name }}"
                        class="img-fluid rounded"
                        style="
                            max-height:300px;
                            object-fit:contain;
                        "
                    >

                @else

                    <div
                        class="d-flex align-items-center justify-content-center"
                        style="
                            height:250px;
                            background:#f1f1f1;
                            border-radius:10px;
                        "
                    >

                        <div>

                            <i
                                class="fas fa-image fa-4x text-muted"
                            ></i>

                            <br>

                            <span class="text-muted">
                                Tidak ada foto
                            </span>

                        </div>

                    </div>

                @endif

            </div>



            {{-- ================================================= --}}
            {{-- DATA --}}
            {{-- ================================================= --}}

            <div class="col-md-8">


                <table class="table table-bordered">


                    <tr>

                        <th width="35%">
                            Nama Barang
                        </th>

                        <td>

                            <strong>
                                {{ $inventory->name }}
                            </strong>

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Kode Barang
                        </th>

                        <td>
                            {{ $inventory->code ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Deskripsi
                        </th>

                        <td>
                            {{ $inventory->description ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Stok Saat Ini
                        </th>

                        <td>

                            <strong class="text-primary">

                                {{ rtrim(
                                    rtrim(
                                        number_format(
                                            $inventory->stock,
                                            2,
                                            ',',
                                            '.'
                                        ),
                                        '0'
                                    ),
                                    ','
                                ) }}

                                {{ $inventory->unit }}

                            </strong>

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Status
                        </th>

                        <td>


                            @if($inventory->status === 'tersedia')

                                <span class="badge badge-success">

                                    <i class="fas fa-check"></i>

                                    Tersedia

                                </span>


                            @elseif($inventory->status === 'stok_menipis')

                                <span class="badge badge-warning">

                                    <i class="fas fa-exclamation-triangle"></i>

                                    Stok Menipis

                                </span>


                            @else

                                <span class="badge badge-danger">

                                    <i class="fas fa-times"></i>

                                    Habis

                                </span>

                            @endif


                        </td>

                    </tr>


                    <tr>

                        <th>
                            Ditambahkan Oleh
                        </th>

                        <td>
                            {{ $inventory->user->name ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Ditambahkan
                        </th>

                        <td>

                            {{ $inventory->created_at
                                ? $inventory->created_at->format('d-m-Y H:i')
                                : '-'
                            }}

                        </td>

                    </tr>


                </table>


            </div>

        </div>


    </div>

</div>



{{-- ========================================================= --}}
{{-- AKSI STOK --}}
{{-- ========================================================= --}}

@if(
    Auth::user()->role === 'admin'
    ||
    $inventory->user_id === Auth::id()
)


<div class="row">


    {{-- ===================================================== --}}
    {{-- STOK MASUK --}}
    {{-- ===================================================== --}}

    <div class="col-md-6">


        <div class="card card-success">


            <div class="card-header">

                <strong>

                    <i class="fas fa-plus-circle"></i>

                    Stok Masuk

                </strong>

            </div>


            <div class="card-body">


                <form
                    action="{{ route(
                        'inventory.stock.in',
                        $inventory->id
                    ) }}"
                    method="POST"
                >

                    @csrf


                    <div class="form-group">

                        <label>
                            Jumlah Stok Masuk
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            min="1"
                            step="1"
                            required
                            placeholder="Contoh: 10"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Keterangan
                        </label>

                        <textarea
                            name="note"
                            class="form-control"
                            rows="3"
                            placeholder="Contoh: Pembelian barang baru"
                        ></textarea>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fas fa-plus"></i>

                        Tambah Stok

                    </button>

                </form>


            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- STOK KELUAR --}}
    {{-- ===================================================== --}}

    <div class="col-md-6">


        <div class="card card-danger">


            <div class="card-header">

                <strong>

                    <i class="fas fa-minus-circle"></i>

                    Stok Keluar

                </strong>

            </div>


            <div class="card-body">


                <form
                    action="{{ route(
                        'inventory.stock.out',
                        $inventory->id
                    ) }}"
                    method="POST"
                >

                    @csrf


                    <div class="form-group">

                        <label>
                            Jumlah Stok Keluar
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            min="1"
                            max="{{ floor($inventory->stock) }}"
                            step="1"
                            required
                            placeholder="Contoh: 5"
                        >

                        <small class="text-muted">

                            Stok tersedia:

                            <strong>

                                {{ rtrim(
                                    rtrim(
                                        number_format(
                                            $inventory->stock,
                                            2,
                                            ',',
                                            '.'
                                        ),
                                        '0'
                                    ),
                                    ','
                                ) }}

                                {{ $inventory->unit }}

                            </strong>

                        </small>

                    </div>


                    <div class="form-group">

                        <label>
                            Keterangan
                        </label>

                        <textarea
                            name="note"
                            class="form-control"
                            rows="3"
                            placeholder="Contoh: Dipakai untuk maintenance tower"
                        ></textarea>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        <i class="fas fa-minus"></i>

                        Kurangi Stok

                    </button>

                </form>


            </div>

        </div>

    </div>


</div>

@endif



{{-- ========================================================= --}}
{{-- RIWAYAT STOK --}}
{{-- ========================================================= --}}

<div class="card">


    <div class="card-header">

        <strong>

            <i class="fas fa-history"></i>

            Riwayat Perubahan Stok

        </strong>

    </div>


    <div class="card-body">


        <div class="table-responsive">


            <table
                class="table table-bordered table-striped"
            >


                <thead>

                    <tr>

                        <th width="5%">
                            No
                        </th>

                        <th>
                            Tanggal & Jam
                        </th>

                        <th>
                            Jenis
                        </th>

                        <th>
                            Jumlah
                        </th>

                        <th>
                            Stok Sebelum
                        </th>

                        <th>
                            Stok Sesudah
                        </th>

                        <th>
                            Keterangan
                        </th>

                        <th>
                            Oleh
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse(
                        $inventory->movements->sortByDesc('created_at')
                        as $movement
                    )


                        <tr>


                            <td>

                                {{ $loop->iteration }}

                            </td>


                            <td>

                                {{ $movement->created_at
                                    ? $movement->created_at->format('d-m-Y H:i')
                                    : '-'
                                }}

                            </td>


                            <td>


                                @if($movement->type === 'masuk')

                                    <span class="badge badge-success">

                                        <i class="fas fa-arrow-down"></i>

                                        Stok Masuk

                                    </span>

                                @else

                                    <span class="badge badge-danger">

                                        <i class="fas fa-arrow-up"></i>

                                        Stok Keluar

                                    </span>

                                @endif


                            </td>


                            <td>

                                <strong>

                                    {{ $movement->quantity }}

                                    {{ $inventory->unit }}

                                </strong>

                            </td>


                            <td>

                                {{ $movement->stock_before }}

                                {{ $inventory->unit }}

                            </td>


                            <td>

                                <strong>

                                    {{ $movement->stock_after }}

                                    {{ $inventory->unit }}

                                </strong>

                            </td>


                            <td>

                                {{ $movement->note ?? '-' }}

                            </td>


                            <td>

                                {{ $movement->user->name ?? '-' }}

                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-4"
                            >

                                <i
                                    class="fas fa-history fa-2x mb-2"
                                ></i>

                                <br>

                                Belum ada riwayat perubahan stok.

                            </td>

                        </tr>


                    @endforelse


                </tbody>


            </table>


        </div>


    </div>

</div>



{{-- ========================================================= --}}
{{-- TOMBOL KEMBALI --}}
{{-- ========================================================= --}}

<a
    href="{{ route('inventory.index') }}"
    class="btn btn-secondary"
>

    <i class="fas fa-arrow-left"></i>

    Kembali ke Inventory

</a>


@stop