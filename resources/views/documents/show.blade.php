@extends('adminlte::page')

@section('title', 'Detail Dokumen')


@section('content_header')

    <h1>
        <i class="fas fa-file-alt"></i>
        Detail Dokumen
    </h1>

@stop


@section('content')

<div class="card">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-file-pdf text-danger mr-2"></i>

            {{ $document->title }}

        </h3>

    </div>


    {{-- =========================================================
         BODY
    ========================================================== --}}

    <div class="card-body">

        <div class="alert alert-info">

            <i class="fas fa-info-circle mr-1"></i>

            Silakan periksa informasi dokumen sebelum melakukan
            <strong>Edit</strong> atau <strong>Hapus</strong>.

        </div>


        <table class="table table-bordered table-hover">

            <tbody>


                {{-- NOMOR DOKUMEN --}}
                <tr>

                    <th width="25%" class="bg-light">

                        <i class="fas fa-hashtag mr-1"></i>

                        Nomor Dokumen

                    </th>

                    <td>

                        {{ $document->document_number ?? '-' }}

                    </td>

                </tr>


                {{-- JUDUL --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-heading mr-1"></i>

                        Judul

                    </th>

                    <td>

                        <strong>

                            {{ $document->title }}

                        </strong>

                    </td>

                </tr>


                {{-- KATEGORI --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-folder mr-1"></i>

                        Kategori

                    </th>

                    <td>

                        {{ optional($document->category)->name ?? '-' }}

                    </td>

                </tr>


                {{-- VERSI --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-code-branch mr-1"></i>

                        Versi

                    </th>

                    <td>

                        {{ $document->version ?? '-' }}

                    </td>

                </tr>


                {{-- STATUS --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-toggle-on mr-1"></i>

                        Status

                    </th>

                    <td>

                        @if($document->status === 'Aktif')

                            <span class="badge badge-success">

                                <i class="fas fa-check-circle"></i>

                                {{ $document->status }}

                            </span>

                        @elseif($document->status === 'Kadaluarsa')

                            <span class="badge badge-danger">

                                <i class="fas fa-times-circle"></i>

                                {{ $document->status }}

                            </span>

                        @else

                            <span class="badge badge-secondary">

                                {{ $document->status ?? '-' }}

                            </span>

                        @endif

                    </td>

                </tr>


                {{-- MASA BERLAKU --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-calendar-alt mr-1"></i>

                        Masa Berlaku

                    </th>

                    <td>

                        {{ $document->tanggal_berlaku ?? '-' }}

                    </td>

                </tr>


                {{-- UPLOADER --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-user mr-1"></i>

                        Uploader

                    </th>

                    <td>

                        {{ optional($document->user)->name ?? '-' }}

                    </td>

                </tr>


                {{-- NAMA FILE --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-file-pdf mr-1"></i>

                        Nama File

                    </th>

                    <td>

                        <span class="badge badge-light border">

                            <i class="fas fa-file-pdf text-danger"></i>

                            {{ $document->file_name ?? '-' }}

                        </span>

                    </td>

                </tr>


                {{-- TIPE FILE --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-file-code mr-1"></i>

                        Tipe File

                    </th>

                    <td>

                        {{ $document->file_type ?? '-' }}

                    </td>

                </tr>


                {{-- UKURAN FILE --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-database mr-1"></i>

                        Ukuran File

                    </th>

                    <td>

                        @if($document->file_size)

                            {{ number_format(
                                $document->file_size / 1024,
                                2
                            ) }}

                            KB

                        @else

                            -

                        @endif

                    </td>

                </tr>


                {{-- TANGGAL UPLOAD --}}
                <tr>

                    <th class="bg-light">

                        <i class="fas fa-clock mr-1"></i>

                        Tanggal Upload

                    </th>

                    <td>

                        {{ $document->created_at
                            ? $document->created_at->format('d-m-Y H:i')
                            : '-'
                        }}

                    </td>

                </tr>


            </tbody>

        </table>

    </div>


    {{-- =========================================================
         ACTION BUTTON
    ========================================================== --}}

    <div class="card-footer">

        <div
            class="d-flex flex-wrap"
            style="gap: 8px;"
        >


            {{-- KEMBALI --}}

            <a
                href="{{ route('documents.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left mr-1"></i>

                Kembali

            </a>



            {{-- LIHAT DOKUMEN --}}

            <a
                href="{{ route(
                    'documents.preview',
                    $document->id
                ) }}"
                target="_blank"
                class="btn btn-primary"
            >

                <i class="fas fa-eye mr-1"></i>

                Lihat Dokumen

            </a>



            {{-- DOWNLOAD --}}

            <a
                href="{{ route(
                    'documents.download',
                    $document->id
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-download mr-1"></i>

                Download

            </a>



            {{-- EDIT --}}

            <a
                href="{{ route(
                    'documents.edit',
                    $document->id
                ) }}"
                class="btn btn-warning"
            >

                <i class="fas fa-edit mr-1"></i>

                Edit

            </a>



            {{-- =================================================
                 DELETE FORM
            ================================================== --}}

            <form
                id="deleteDocumentForm"
                action="{{ route(
                    'documents.destroy',
                    $document->id
                ) }}"
                method="POST"
                style="display:inline-block;"
            >

                @csrf

                @method('DELETE')


                <button
                    type="button"
                    id="deleteDocumentButton"
                    class="btn btn-danger"
                >

                    <i class="fas fa-trash mr-1"></i>

                    Hapus

                </button>

            </form>


        </div>

    </div>

</div>



{{-- =========================================================
     SWEETALERT2
     
     Diletakkan SEBELUM script tombol Hapus
========================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/sweetalert2@11"
></script>



{{-- =========================================================
     DELETE CONFIRMATION
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const deleteButton =
            document.getElementById(
                'deleteDocumentButton'
            );


        const deleteForm =
            document.getElementById(
                'deleteDocumentForm'
            );


        if (!deleteButton || !deleteForm) {

            return;

        }


        deleteButton.addEventListener(
            'click',
            function () {


                Swal.fire({

                    title: 'Hapus Dokumen?',

                    html: `

                        <div
                            style="
                                text-align:left;
                                line-height:1.6;
                            "
                        >

                            <p>
                                Anda akan menghapus
                                dokumen berikut:
                            </p>


                            <div
                                style="
                                    background:#f8f9fa;
                                    border:1px solid #dee2e6;
                                    border-radius:8px;
                                    padding:15px;
                                "
                            >


                                <div
                                    style="
                                        margin-bottom:12px;
                                    "
                                >

                                    <strong>
                                        <i class="fas fa-heading"></i>
                                        Judul
                                    </strong>

                                    <br>

                                    <span>
                                        {{ $document->title }}
                                    </span>

                                </div>



                                <div
                                    style="
                                        margin-bottom:12px;
                                    "
                                >

                                    <strong>
                                        <i class="fas fa-hashtag"></i>
                                        Nomor Dokumen
                                    </strong>

                                    <br>

                                    <span>
                                        {{ $document->document_number ?? '-' }}
                                    </span>

                                </div>



                                <div>

                                    <strong>
                                        <i class="fas fa-file-pdf text-danger"></i>
                                        Nama File
                                    </strong>

                                    <br>

                                    <span>
                                        {{ $document->file_name ?? '-' }}
                                    </span>

                                </div>


                            </div>



                            <div
                                style="
                                    margin-top:15px;
                                    padding:12px;
                                    background:#fff3cd;
                                    border:1px solid #ffe69c;
                                    border-radius:8px;
                                    color:#856404;
                                "
                            >

                                <i
                                    class="fas fa-exclamation-triangle"
                                ></i>

                                <strong>
                                    Perhatian!
                                </strong>

                                <br>

                                Pastikan dokumen yang dipilih
                                sudah benar sebelum dihapus.

                                <br>

                                Tindakan ini
                                <strong>
                                    tidak dapat dibatalkan.
                                </strong>

                            </div>


                        </div>

                    `,

                    icon: 'warning',


                    showCancelButton: true,


                    confirmButtonColor: '#dc3545',


                    cancelButtonColor: '#6c757d',


                    confirmButtonText:
                        '<i class="fas fa-trash"></i> Ya, Hapus',


                    cancelButtonText:
                        '<i class="fas fa-times"></i> Batal',


                    reverseButtons: true,


                    focusCancel: true,


                    allowOutsideClick: false

                })


                .then(
                    function (result) {


                        if (
                            result.isConfirmed
                        ) {


                            deleteForm.submit();


                        }


                    }
                );


            }
        );


    }
);

</script>

@stop