@extends('adminlte::page')

@section('title', 'Tambah Barang')


@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <h1>

        <i class="fas fa-plus-circle"></i>

        Tambah Barang

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


@if($errors->any())

<div class="alert alert-danger">

    <strong>
        Data belum dapat disimpan.
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
            <i class="fas fa-box"></i>
            Data Barang
        </strong>

    </div>


    <form
        action="{{ route('inventory.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="card-body">


            {{-- NAMA --}}

            <div class="form-group">

                <label>
                    Nama Barang
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Printer Label Brady BMP21"
                    required
                >

            </div>



            {{-- KODE --}}

            <div class="form-group">

                <label>
                    Kode Barang
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="code"
                    class="form-control"
                    value="{{ old('code') }}"
                    placeholder="Contoh: RJ001"
                    required
                >

            </div>



            {{-- SERIAL NUMBER --}}

            <div class="form-group">

                <label>
                    Serial Number
                </label>

                <div id="serial-container">

                    <div class="input-group mb-2 serial-row">

                        <input
                            type="text"
                            name="serial_numbers[]"
                            class="form-control"
                            placeholder="Contoh: SN-B21-2508-001"
                        >

                        <div class="input-group-append">

                            <button
                                type="button"
                                class="btn btn-danger remove-serial"
                            >

                                <i class="fas fa-trash"></i>

                            </button>

                        </div>

                    </div>

                </div>


                <button
                    type="button"
                    id="add-serial"
                    class="btn btn-outline-primary btn-sm"
                >

                    <i class="fas fa-plus"></i>

                    Tambah Serial Number

                </button>


                <small class="form-text text-muted">

                    Tambahkan serial number sesuai jumlah barang yang memiliki nomor seri.

                </small>

            </div>



            {{-- STOK --}}

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label>
                            Stok
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            value="{{ old('stock', 0) }}"
                            min="0"
                            required
                        >

                    </div>

                </div>



                {{-- SATUAN --}}

                <div class="col-md-6">

                    <div class="form-group">

                        <label>
                            Satuan
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="unit"
                            class="form-control"
                            value="{{ old('unit', 'pcs') }}"
                            placeholder="pcs"
                            required
                        >

                    </div>

                </div>

            </div>



            {{-- KONDISI --}}

            <div class="form-group">

                <label>

                    Kondisi Barang

                    <span class="text-danger">*</span>

                </label>


                <select
                    name="condition"
                    class="form-control"
                    required
                >

                    <option
                        value="normal"
                        {{ old('condition', 'normal') == 'normal'
                            ? 'selected'
                            : ''
                        }}
                    >

                        Normal

                    </option>


                    <option
                        value="rusak"
                        {{ old('condition') == 'rusak'
                            ? 'selected'
                            : ''
                        }}
                    >

                        Rusak

                    </option>

                </select>

            </div>



            {{-- FOTO --}}

            <div class="form-group">

                <label>

                    Foto Barang

                </label>


                <div class="custom-file">

                    <input
                        type="file"
                        name="photos[]"
                        id="photos"
                        class="custom-file-input"
                        accept="image/*"
                        multiple
                    >

                    <label
                        class="custom-file-label"
                        for="photos"
                    >

                        Pilih foto barang

                    </label>

                </div>


                <small class="form-text text-muted">

                    Maksimal 3 foto. Format JPG, JPEG, PNG atau WEBP. Maksimal 5 MB per foto.

                </small>


                <div
                    id="photo-preview"
                    class="row mt-3"
                ></div>

            </div>


        </div>


        <div class="card-footer">

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

                <i class="fas fa-times"></i>

                Batal

            </a>

        </div>


    </form>

</div>


@stop



@section('js')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | SERIAL NUMBER
        |--------------------------------------------------------------------------
        */

        const serialContainer =
            document.getElementById(
                'serial-container'
            );


        const addSerial =
            document.getElementById(
                'add-serial'
            );


        addSerial.addEventListener(
            'click',
            function () {

                const row =
                    document.createElement(
                        'div'
                    );

                row.className =
                    'input-group mb-2 serial-row';


                row.innerHTML = `

                    <input
                        type="text"
                        name="serial_numbers[]"
                        class="form-control"
                        placeholder="Masukkan Serial Number"
                    >

                    <div class="input-group-append">

                        <button
                            type="button"
                            class="btn btn-danger remove-serial"
                        >

                            <i class="fas fa-trash"></i>

                        </button>

                    </div>

                `;


                serialContainer.appendChild(
                    row
                );

            }
        );


        document.addEventListener(
            'click',
            function (event) {

                if (
                    event.target.closest(
                        '.remove-serial'
                    )
                ) {

                    const rows =
                        document.querySelectorAll(
                            '.serial-row'
                        );


                    if (rows.length > 1) {

                        event.target
                            .closest(
                                '.serial-row'
                            )
                            .remove();

                    }

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | FOTO
        |--------------------------------------------------------------------------
        */

        const photoInput =
            document.getElementById(
                'photos'
            );


        const photoLabel =
            document.querySelector(
                'label[for="photos"]'
            );


        const preview =
            document.getElementById(
                'photo-preview'
            );


        photoInput.addEventListener(
            'change',
            function () {


                preview.innerHTML = '';


                if (
                    this.files.length > 3
                ) {

                    alert(
                        'Maksimal 3 foto.'
                    );

                    this.value = '';

                    photoLabel.textContent =
                        'Pilih foto barang';

                    return;

                }


                photoLabel.textContent =
                    this.files.length +
                    ' foto dipilih';


                Array.from(
                    this.files
                ).forEach(
                    function (file) {


                        const reader =
                            new FileReader();


                        reader.onload =
                            function (event) {

                                const col =
                                    document.createElement(
                                        'div'
                                    );

                                col.className =
                                    'col-md-4 mb-2';


                                col.innerHTML = `

                                    <img
                                        src="${event.target.result}"
                                        class="img-thumbnail"
                                        style="
                                            width: 100%;
                                            height: 160px;
                                            object-fit: cover;
                                        "
                                    >

                                `;


                                preview.appendChild(
                                    col
                                );

                            };


                        reader.readAsDataURL(
                            file
                        );

                    }
                );

            }
        );

    }
);

</script>

@stop