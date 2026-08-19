@extends('adminlte::page')

@section('title', 'Inventory Barang')


@section('content_header')

    <h1>
        Inventory Barang
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
{{-- CARD --}}
{{-- ========================================================= --}}

<div class="card">


    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <strong>

                <i class="fas fa-boxes"></i>

                Data Barang

            </strong>


            <a
                href="{{ route('inventory.create') }}"
                class="btn btn-primary"
            >

                <i class="fas fa-plus"></i>

                Tambah Barang

            </a>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- BODY --}}
    {{-- ===================================================== --}}

    <div class="card-body">


        {{-- ================================================= --}}
        {{-- SEARCH --}}
        {{-- ================================================= --}}

        <form
            method="GET"
            action="{{ route('inventory.index') }}"
            class="mb-3"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama atau kode barang..."
                    value="{{ request('search') }}"
                >


                <button
                    class="btn btn-secondary"
                    type="submit"
                >

                    <i class="fas fa-search"></i>

                    Cari

                </button>


                @if(request('search'))

                    <a
                        href="{{ route('inventory.index') }}"
                        class="btn btn-outline-secondary"
                    >

                        Reset

                    </a>

                @endif

            </div>

        </form>



        {{-- ================================================= --}}
        {{-- TABLE --}}
        {{-- ================================================= --}}

        <div class="table-responsive">

            <table
                class="table table-bordered table-striped table-hover"
            >

                <thead>

                    <tr>

                        <th width="5%">
                            No
                        </th>

                        <th width="12%">
                            Foto
                        </th>

                        <th>
                            Nama Barang
                        </th>

                        <th>
                            Kode
                        </th>

                        <th>
                            Stok
                        </th>

                        <th>
                            Satuan
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Dibuat Oleh
                        </th>

                        <th width="22%">
                            Aksi
                        </th>

                    </tr>

                </thead>



                <tbody>


                    @forelse($inventories as $inventory)


                        <tr>


                            {{-- ================================================= --}}
                            {{-- NOMOR --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $loop->iteration }}

                            </td>



                            {{-- ================================================= --}}
                            {{-- FOTO --}}
                            {{-- ================================================= --}}

                            <td class="text-center">

                                @if($inventory->photo)

                                    <img
                                        src="{{ asset('storage/' . $inventory->photo) }}"
                                        alt="{{ $inventory->name }}"
                                        style="
                                            width:80px;
                                            height:80px;
                                            object-fit:cover;
                                            border-radius:8px;
                                        "
                                    >

                                @else

                                    <div
                                        class="text-muted"
                                        style="
                                            width:80px;
                                            height:80px;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            background:#f1f1f1;
                                            border-radius:8px;
                                            margin:auto;
                                        "
                                    >

                                        <i class="fas fa-image fa-2x"></i>

                                    </div>

                                @endif

                            </td>



                            {{-- ================================================= --}}
                            {{-- NAMA --}}
                            {{-- ================================================= --}}

                            <td>

                                <strong>

                                    {{ $inventory->name }}

                                </strong>


                                @if($inventory->description)

                                    <br>

                                    <small class="text-muted">

                                        {{ $inventory->description }}

                                    </small>

                                @endif

                            </td>



                            {{-- ================================================= --}}
                            {{-- KODE --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $inventory->code ?? '-' }}

                            </td>



                            {{-- ================================================= --}}
                            {{-- STOK --}}
                            {{-- ================================================= --}}

                            <td>

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

                                </strong>

                            </td>



                            {{-- ================================================= --}}
                            {{-- SATUAN --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $inventory->unit }}

                            </td>



                            {{-- ================================================= --}}
                            {{-- STATUS --}}
                            {{-- ================================================= --}}

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



                            {{-- ================================================= --}}
                            {{-- USER --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $inventory->user->name ?? '-' }}

                            </td>



                            {{-- ================================================= --}}
                            {{-- AKSI --}}
                            {{-- ================================================= --}}

                            <td>


                                {{-- DETAIL --}}

                                <a
                                    href="{{ route(
                                        'inventory.show',
                                        $inventory->id
                                    ) }}"
                                    class="btn btn-info btn-sm mb-1"
                                >

                                    <i class="fas fa-eye"></i>

                                    Detail

                                </a>



                                {{-- ================================================= --}}
                                {{-- PEMILIK / ADMIN --}}
                                {{-- ================================================= --}}

                                @if(
                                    Auth::user()->role === 'admin'
                                    ||
                                    $inventory->user_id === Auth::id()
                                )


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'inventory.edit',
                                            $inventory->id
                                        ) }}"
                                        class="btn btn-primary btn-sm mb-1"
                                    >

                                        <i class="fas fa-edit"></i>

                                        Edit

                                    </a>



                                    {{-- HAPUS --}}

                                    <form
                                        action="{{ route(
                                            'inventory.destroy',
                                            $inventory->id
                                        ) }}"
                                        method="POST"
                                        style="display:inline"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm mb-1"
                                            onclick="return confirm(
                                                'Yakin ingin menghapus barang ini?'
                                            )"
                                        >

                                            <i class="fas fa-trash"></i>

                                            Hapus

                                        </button>

                                    </form>


                                @endif


                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="9"
                                class="text-center text-muted py-4"
                            >

                                <i
                                    class="fas fa-box-open fa-3x mb-3"
                                ></i>

                                <br>

                                <strong>
                                    Belum ada data barang
                                </strong>

                                <br>

                                <small>
                                    Silakan tambahkan barang terlebih dahulu.
                                </small>

                            </td>

                        </tr>


                    @endforelse


                </tbody>

            </table>

        </div>


    </div>

</div>


@stop