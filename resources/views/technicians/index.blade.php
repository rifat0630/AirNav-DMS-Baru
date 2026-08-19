@extends('adminlte::page')

@section('title', 'Data Teknisi')


@section('content_header')

<div class="d-flex justify-content-between">

    <h1>
        Data Teknisi
    </h1>


    <a
        href="{{ route('technicians.create') }}"
        class="btn btn-primary"
    >

        <i class="fas fa-plus"></i>

        Tambah Teknisi

    </a>

</div>

@stop


@section('content')


@if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">

        {{ session('error') }}

    </div>

@endif


<div class="card">

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>

                <tr>

                    <th width="5%">
                        No
                    </th>

                    <th>
                        Nama Teknisi
                    </th>

                    <th>
                        QR TTD Teknisi
                    </th>

                    <th>
                        Status
                    </th>

                    <th width="20%">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($technicians as $technician)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            {{ $technician->name }}

                        </td>


                        <td class="text-center">

                            @if($technician->qr_token)

                                <img
                                    src="{{ asset(
                                        'qrcodes/technicians/' .
                                        $technician->qr_token .
                                        '.svg'
                                    ) }}"
                                    width="130"
                                    alt="QR TTD {{ $technician->name }}"
                                >

                                <br>

                                <small class="text-muted">

                                    QR TTD Teknisi

                                </small>

                            @else

                                <span class="text-muted">

                                    QR belum tersedia

                                </span>

                            @endif

                        </td>


                        <td>

                            @if($technician->status == 'aktif')

                                <span class="badge badge-success">

                                    Aktif

                                </span>

                            @elseif($technician->status == 'mutasi')

                                <span class="badge badge-warning">

                                    Mutasi

                                </span>

                            @else

                                <span class="badge badge-danger">

                                    Tidak Aktif

                                </span>

                            @endif

                        </td>


                        <td>

                            @if($technician->status == 'aktif')

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'technicians.update',
                                        $technician->id
                                    ) }}"
                                    style="display:inline"
                                >

                                    @csrf

                                    @method('PUT')


                                    <input
                                        type="hidden"
                                        name="status"
                                        value="mutasi"
                                    >


                                    <button
                                        type="submit"
                                        class="btn btn-warning btn-sm"
                                    >

                                        <i class="fas fa-exchange-alt"></i>

                                        Mutasi

                                    </button>

                                </form>

                            @elseif($technician->status == 'mutasi')

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'technicians.update',
                                        $technician->id
                                    ) }}"
                                    style="display:inline"
                                >

                                    @csrf

                                    @method('PUT')


                                    <input
                                        type="hidden"
                                        name="status"
                                        value="aktif"
                                    >


                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm"
                                    >

                                        <i class="fas fa-user-check"></i>

                                        Aktifkan

                                    </button>

                                </form>

                            @endif


                            <form
                                method="POST"
                                action="{{ route(
                                    'technicians.destroy',
                                    $technician->id
                                ) }}"
                                style="display:inline"
                            >

                                @csrf

                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm(
                                        'Hapus teknisi ini?'
                                    )"
                                >

                                    <i class="fas fa-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center"
                        >

                            Belum ada data teknisi

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@stop