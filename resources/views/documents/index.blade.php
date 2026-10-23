@extends('layouts.admin')

@section('title', 'Daftar Dokumen')

@section('css')
    @vite('resources/css/documents.css')
@endsection

@section('content')

<div class="documents-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="documents-page-header">

        <div class="documents-page-heading">

            <div class="documents-page-icon">
                <i class="fas fa-folder-open"></i>
            </div>

            <div class="documents-page-title-wrapper">

                <h1 class="documents-page-title">
                    Daftar Dokumen
                </h1>

                <p class="documents-page-subtitle">
                    Sistem Repositori & Pemantauan Perizinan Fasilitas CNS
                    (Komunikasi, Navigasi, Pengamatan)
                </p>

            </div>

        </div>


        <a href="{{ route('documents.create') }}"
           class="documents-add-button">

            <i class="fas fa-plus"></i>

            <span>
                Tambah Dokumen
            </span>

        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="documents-alert documents-alert-success">

            <div class="documents-alert-content">

                <i class="fas fa-check-circle"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

            <button type="button"
                    class="documents-alert-close"
                    data-dismiss="alert">

                &times;

            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="documents-alert documents-alert-danger">

            <div class="documents-alert-content">

                <i class="fas fa-exclamation-circle"></i>

                <span>
                    {{ session('error') }}
                </span>

            </div>

            <button type="button"
                    class="documents-alert-close"
                    data-dismiss="alert">

                &times;

            </button>

        </div>

    @endif


    {{-- =========================================================
        MAIN DOCUMENT CARD
    ========================================================== --}}
    <div class="documents-card">


        {{-- =====================================================
            CARD HEADER
        ====================================================== --}}
        <div class="documents-card-header">

            <div>

                <h2 class="documents-card-title">
                    Data Dokumen 
                </h2>


            </div>

        </div>


        {{-- =====================================================
            FILTER
        ====================================================== --}}
        <div class="documents-filter">

            <form method="GET"
                  action="{{ route('documents.index') }}"
                  class="documents-filter-form">


                {{-- SEARCH --}}
                <div class="documents-search-wrapper">

                    <i class="fas fa-search documents-search-icon"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="documents-search-input"
                        placeholder="Cari nomor dokumen, judul, atau instansi..."
                    >

                    <button type="submit"
                            class="documents-search-button">

                        <i class="fas fa-search"></i>

                        <span>
                            Cari
                        </span>

                    </button>

                </div>


                {{-- CATEGORY --}}
                <div class="documents-category-wrapper">

                    <label class="documents-category-label">
                        Kategori:
                    </label>

                    <select
                        name="category"
                        class="documents-category-select"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            Semua Kategori
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </form>

        </div>


        {{-- =====================================================
            TABLE
        ====================================================== --}}
        <div class="documents-table-wrapper">

            <table class="documents-table">

                <thead>

                    <tr>

                        <th class="documents-col-no">
                            NO
                        </th>

                        <th class="documents-col-number">
                            NOMOR<br>DOKUMEN
                        </th>

                        <th>
                            JUDUL DOKUMEN
                        </th>

                        <th class="documents-col-category">
                            KATEGORI
                        </th>

                        <th class="documents-col-validity">
                            MASA BERLAKU
                        </th>

                        <th class="documents-col-upload">
                            UPLOAD<br>OLEH
                        </th>

                        <th class="documents-col-action">
                            AKSI
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($documents as $document)

                        <tr>

                            {{-- =================================================
                                NO
                            ================================================== --}}
                            <td class="documents-number-cell">

                                {{ $loop->iteration }}

                            </td>


                            {{-- =================================================
                                NOMOR DOKUMEN
                            ================================================== --}}
                            <td>

                                <a
                                    href="{{ route('documents.preview', $document->id) }}"
                                    target="_blank"
                                    class="documents-number-link"
                                >

                                    {{ $document->document_number }}

                                </a>

                            </td>


                            {{-- =================================================
                                JUDUL
                            ================================================== --}}
                            <td>

                                <div class="documents-title-cell">

                                    {{ $document->title }}

                                </div>

                            </td>


                            {{-- =================================================
                                KATEGORI
                            ================================================== --}}
                            <td>

                                @if($document->category)

                                    <span class="documents-category-badge">

                                        {{ $document->category->name }}

                                    </span>

                                @else

                                    <span class="documents-empty">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                MASA BERLAKU
                            ================================================== --}}
                            <td>

                                <span class="documents-validity">

                                    {{ $document->tanggal_berlaku ?? '-' }}

                                </span>

                            </td>


                            {{-- =================================================
                                UPLOAD OLEH
                            ================================================== --}}
                            <td>

                                @if($document->user)

                                    <div class="documents-user">

                                        {{ $document->user->name }}

                                    </div>

                                @else

                                    <span class="documents-empty">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                AKSI
                            ================================================== --}}
                            <td>

                                <div class="documents-actions">


                                    {{-- PREVIEW --}}
                                    <a
                                        href="{{ route('documents.preview', $document->id) }}"
                                        target="_blank"
                                        class="documents-action documents-action-preview"
                                        title="Preview"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- DOWNLOAD --}}
                                    <a
                                        href="{{ route('documents.download', $document->id) }}"
                                        class="documents-action documents-action-download"
                                        title="Download"
                                    >

                                        <i class="fas fa-download"></i>

                                    </a>


                                    {{-- =================================================
                                        EDIT
                                        Sekarang menggunakan MODAL
                                    ================================================== --}}
                                    <button
                                        type="button"
                                        class="documents-action documents-action-edit"
                                        title="Edit"
                                        data-toggle="modal"
                                        data-target="#editDocumentModal"
                                        data-id="{{ $document->id }}"
                                        data-number="{{ $document->document_number }}"
                                        data-title="{{ $document->title }}"
                                        data-category="{{ $document->category_id }}"
                                        data-validity="{{ $document->tanggal_berlaku }}"
                                    >

                                        <i class="fas fa-edit"></i>

                                    </button>


                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('documents.destroy', $document->id) }}"
                                        method="POST"
                                        class="documents-delete-form"
                                        onsubmit="return confirm('Yakin ingin menghapus dokumen ini? File Google Drive juga akan dihapus.')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="documents-action documents-action-delete"
                                            title="Hapus"
                                        >

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="documents-empty-row"
                            >

                                <div class="documents-empty-content">

                                    <i class="fas fa-folder-open"></i>

                                    <span>
                                        Belum ada dokumen
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}
        <div class="documents-card-footer">

            <div class="documents-total">

                Menampilkan
                <strong>{{ $documents->count() }}</strong>
                dokumen

            </div>


            @if(method_exists($documents, 'links'))

                <div class="documents-pagination">

                    {{ $documents->links() }}

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
    EDIT DOCUMENT MODAL
============================================================= --}}
<div
    class="modal fade"
    id="editDocumentModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="editDocumentModalLabel"
    aria-hidden="true"
>

    <div
        class="modal-dialog modal-dialog-centered documents-edit-modal"
        role="document"
    >

        <div class="modal-content">


            {{-- =================================================
                MODAL HEADER
            ================================================== --}}
            <div class="modal-header">

                <div class="documents-modal-heading">

                    <div class="documents-modal-icon">

                        <i class="fas fa-edit"></i>

                    </div>

                    <div>

                        <h5
                            class="modal-title"
                            id="editDocumentModalLabel"
                        >
                            Edit Dokumen
                        </h5>

                        <p>
                            Perbarui informasi dokumen
                        </p>

                    </div>

                </div>


                <button
                    type="button"
                    class="close documents-modal-close"
                    data-dismiss="modal"
                    aria-label="Close"
                >

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            {{-- =================================================
                MODAL BODY
            ================================================== --}}
            <div class="modal-body">

                <form
                    id="editDocumentForm"
                    method="POST"
                >

                    @csrf

                    @method('PUT')


                    {{-- NOMOR DOKUMEN --}}
                    <div class="documents-modal-field">

                        <label for="edit_document_number">
                            Nomor Dokumen
                        </label>

                        <input
                            type="text"
                            id="edit_document_number"
                            name="document_number"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- JUDUL --}}
                    <div class="documents-modal-field">

                        <label for="edit_title">
                            Judul Dokumen
                        </label>

                        <input
                            type="text"
                            id="edit_title"
                            name="title"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- KATEGORI --}}
                    <div class="documents-modal-field">

                        <label for="edit_category">
                            Kategori
                        </label>

                        <select
                            id="edit_category"
                            name="category_id"
                            class="form-control"
                            required
                        >

                            <option value="">
                                -- Pilih Kategori --
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}">

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- MASA BERLAKU --}}
                    <div class="documents-modal-field">

                        <label for="edit_tanggal_berlaku">
                            Masa Berlaku Dokumen
                        </label>

                        <input
                            type="text"
                            id="edit_tanggal_berlaku"
                            name="tanggal_berlaku"
                            class="form-control"
                            placeholder="Contoh: 27 feb 23 - 33 feb 23"
                        >

                        <small>
                            Isi periode berlaku dokumen.
                        </small>

                    </div>


                    {{-- BUTTON --}}
                    <div class="documents-modal-actions">

                        <button
                            type="button"
                            class="documents-modal-cancel"
                            data-dismiss="modal"
                        >

                            <i class="fas fa-times"></i>

                            Kembali

                        </button>


                        <button
                            type="submit"
                            class="documents-modal-save"
                        >

                            <i class="fas fa-save"></i>

                            Update Dokumen

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


@endsection


{{-- =============================================================
    JAVASCRIPT EDIT MODAL
============================================================= --}}
@push('js')

<script>

    $(document).ready(function () {

        $('#editDocumentModal').on('show.bs.modal', function (event) {

            const button = $(event.relatedTarget);

            const id = button.data('id');
            const number = button.data('number');
            const title = button.data('title');
            const category = button.data('category');
            const validity = button.data('validity');


            /*
             * Isi data ke form
             */

            $('#edit_document_number').val(number);

            $('#edit_title').val(title);

            $('#edit_category').val(category);

            $('#edit_tanggal_berlaku').val(validity);


            /*
             * Set action form
             *
             * Hasil:
             * /documents/71
             */

            $('#editDocumentForm').attr(
                'action',
                '/documents/' + id
            );

        });


        /*
         * Reset form ketika modal ditutup
         */

        $('#editDocumentModal').on('hidden.bs.modal', function () {

            $('#editDocumentForm')[0].reset();

        });

    });

</script>

@endpush