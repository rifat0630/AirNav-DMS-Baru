@extends('adminlte::page')

@section('title', 'Detail Barang')


@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <h1>

        <i class="fas fa-box-open"></i>

        Detail Barang

    </h1>


    <a
        href="{{ route('inventory.index') }}"
        class="btn btn-secondary"
    >

        <i class="fas fa-arrow-left"></i>

        Kembali

    </a>

</div>

@stop



@section('content')


<div class="card">

    <div class="card-header">

        <strong>
            {{ $inventory->name }}
        </strong>

    </div>


    <div class="card-body">


        <div class="row">


            {{-- FOTO --}}

            <div class="col-md-5">

                <h5>
                    Foto Barang
                </h5>


                @if(
                    !empty($inventory->photos)
                )

                    <div class="row">

                        @foreach(
                            $inventory->photos
                            as $photo
                        )

                            <div
                                class="col-md-6 mb-3"
                            >

                                <a
                                    href="{{ asset(
                                        'storage/' . $photo
                                    ) }}"
                                    target="_blank"
                                >

                                    <img
                                        src="{{ asset(
                                            'storage/' . $photo
                                        ) }}"
                                        class="img-thumbnail"
                                        style="
                                            width: 100%;
                                            height: 180px;
                                            object-fit: cover;
                                        "
                                    >

                                </a>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div
                        class="text-muted"
                    >

                        <i
                            class="fas fa-image fa-3x"
                        ></i>

                        <br>

                        Tidak ada foto.

                    </div>

                @endif

            </div>



            {{-- DETAIL --}}

            <div class="col-md-7">


                <table
                    class="table table-bordered"
                >

                    <tr>

                        <th width="180">
                            Nama Barang
                        </th>

                        <td>
                            {{ $inventory->name }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Kode Barang
                        </th>

                        <td>
                            {{ $inventory->code }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Serial Number
                        </th>

                        <td>

                            @if(
                                !empty(
                                    $inventory->serial_numbers
                                )
                            )

                                <ul class="mb-0">

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

                                -

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Stok
                        </th>

                        <td>
                            {{ $inventory->stock }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Satuan
                        </th>

                        <td>
                            {{ $inventory->unit }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Kondisi Barang
                        </th>

                        <td>

                            @if(
                                strtolower(
                                    $inventory->condition
                                ) === 'rusak'
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

                    </tr>


                    <tr>

                        <th>
                            Dibuat Oleh
                        </th>

                        <td>

                            {{ optional(
                                $inventory->user
                            )->name ?? '-' }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Dibuat
                        </th>

                        <td>

                            {{ optional(
                                $inventory->created_at
                            )->format(
                                'd-m-Y H:i'
                            ) }}

                        </td>

                    </tr>

                </table>


                <div class="mt-3">

                    <a
                        href="{{ route(
                            'inventory.edit',
                            $inventory->id
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-edit"></i>

                        Edit Barang

                    </a>

                </div>


            </div>

        </div>


    </div>

</div>


@stop