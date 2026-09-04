@extends('adminlte::page')

@section('title', 'Inventory Barang')


@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <h1>
        <i class="fas fa-boxes"></i>
        Inventory Barang
    </h1>

    <a
        href="{{ route('inventory.create') }}"
        class="btn btn-primary"
    >
        <i class="fas fa-plus"></i>
        Tambah Barang
    </a>

</div>

@stop


@section('content')


@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    <i class="fas fa-check-circle"></i>

    {{ session('success') }}

    <button
        type="button"
        class="close"
        data-dismiss="alert"
    >

        <span>&times;</span>

    </button>

</div>

@endif


@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">

    <i class="fas fa-exclamation-circle"></i>

    {{ session('error') }}

    <button
        type="button"
        class="close"
        data-dismiss="alert"
    >

        <span>&times;</span>

    </button>

</div>

@endif



<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <strong>
                <i class="fas fa-boxes"></i>
                Data Barang
            </strong>

        </h3>

    </div>


    <div class="card-body">


        {{-- SEARCH --}}

        <form
            method="GET"
            action="{{ route('inventory.index') }}"
            class="mb-4"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Cari nama barang, kode barang, atau serial number..."
                >

                <div class="input-group-append">

                    <button
                        class="btn btn-secondary"
                        type="submit"
                    >

                        <i class="fas fa-search"></i>
                        Cari

                    </button>

                </div>

            </div>

        </form>



        {{-- TABLE --}}

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="thead-light">

                    <tr>

                        <th width="50">
                            No
                        </th>

                        <th width="180">
                            Foto
                        </th>

                        <th>
                            Nama Barang
                        </th>

                        <th width="100">
                            Kode
                        </th>

                        <th width="220">
                            Serial Number
                        </th>

                        <th width="80">
                            Stok
                        </th>

                        <th width="80">
                            Satuan
                        </th>

                        <th width="130">
                            Kondisi Barang
                        </th>

                        <th width="200">
                            Dibuat Oleh
                        </th>

                        <th width="190">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                @forelse($inventories as $inventory)

                    <tr>

                        {{-- NO --}}

                        <td>

                            {{ $inventories->firstItem() + $loop->index }}

                        </td>



                        {{-- FOTO --}}

                        <td>

                            @if(
                                !empty($inventory->photos)
                                &&
                                count($inventory->photos) > 0
                            )

                                <div class="d-flex flex-wrap">

                                    @foreach(
                                        array_slice(
                                            $inventory->photos,
                                            0,
                                            3
                                        )
                                        as $photo
                                    )

                                        <a
                                            href="{{ asset('storage/' . $photo) }}"
                                            target="_blank"
                                        >

                                            <img
                                                src="{{ asset('storage/' . $photo) }}"
                                                class="img-thumbnail mr-1 mb-1"
                                                style="
                                                    width: 52px;
                                                    height: 52px;
                                                    object-fit: cover;
                                                "
                                            >

                                        </a>

                                    @endforeach

                                </div>

                            @else

                                <div
                                    class="text-muted text-center"
                                >

                                    <i
                                        class="fas fa-image fa-2x"
                                    ></i>

                                    <br>

                                    <small>
                                        Tidak ada foto
                                    </small>

                                </div>

                            @endif

                        </td>



                        {{-- NAMA --}}

                        <td>

                            <strong>
                                {{ $inventory->name }}
                            </strong>

                        </td>



                        {{-- KODE --}}

                        <td>

                            {{ $inventory->code }}

                        </td>



                        {{-- SERIAL NUMBER --}}

                        <td>

                            @if(
                                !empty($inventory->serial_numbers)
                            )

                                <ul
                                    class="mb-0 pl-3"
                                >

                                    @foreach(
                                        $inventory->serial_numbers
                                        as $serial
                                    )

                                        <li>
                                            {{ $serial }}
                                        </li>

                                    @endforeach

                                </ul>

                            @else

                                <span
                                    class="text-muted"
                                >
                                    -
                                </span>

                            @endif

                        </td>



                        {{-- STOK --}}

                        <td>

                            <strong>
                                {{ $inventory->stock }}
                            </strong>

                        </td>



                        {{-- SATUAN --}}

                        <td>

                            {{ $inventory->unit }}

                        </td>



                        {{-- KONDISI --}}

                        <td>

                            @if(
                                strtolower(
                                    $inventory->condition
                                )
                                === 'rusak'
                            )

                                <span
                                    class="badge badge-warning"
                                >

                                    <i
                                        class="fas fa-exclamation-triangle"
                                    ></i>

                                    Rusak

                                </span>

                            @else

                                <span
                                    class="badge badge-success"
                                >

                                    <i
                                        class="fas fa-check-circle"
                                    ></i>

                                    Normal

                                </span>

                            @endif

                        </td>



                        {{-- USER --}}

                        <td>

                            @if($inventory->user)

                                {{ $inventory->user->name }}

                            @else

                                <span
                                    class="text-muted"
                                >
                                    -
                                </span>

                            @endif

                        </td>



                        {{-- AKSI --}}

                        <td>

                            <div class="d-flex flex-wrap">


                                {{-- DETAIL --}}

                                <a
                                    href="{{ route(
                                        'inventory.show',
                                        $inventory->id
                                    ) }}"
                                    class="btn btn-info btn-sm mr-1 mb-1"
                                >

                                    <i class="fas fa-eye"></i>
                                    Detail

                                </a>



                                {{-- EDIT --}}

                                <a
                                    href="{{ route(
                                        'inventory.edit',
                                        $inventory->id
                                    ) }}"
                                    class="btn btn-primary btn-sm mr-1 mb-1"
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
                                    class="d-inline mb-1"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus barang ini?'
                                    );"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >

                                        <i class="fas fa-trash"></i>
                                        Hapus

                                    </button>

                                </form>


                            </div>

                        </td>


                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="10"
                            class="text-center text-muted py-4"
                        >

                            <i
                                class="fas fa-box-open fa-2x mb-2"
                            ></i>

                            <br>

                            Belum ada data barang.

                        </td>

                    </tr>

                @endforelse


                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        <div class="d-flex justify-content-between align-items-center mt-3">

            <div>

                Menampilkan

                {{ $inventories->firstItem() ?? 0 }}

                sampai

                {{ $inventories->lastItem() ?? 0 }}

                dari

                {{ $inventories->total() }}

                data

            </div>


            <div>

                {{ $inventories->links() }}

            </div>

        </div>


    </div>

</div>


@stop