@extends('layouts.admin')

@section('title', 'Upload Document')

@section('css')
    @vite('resources/css/app.css')
@stop

@section('content_header')
    <div class="airnav-upload-page-header">
        <div>
            <h1>Upload Document</h1>
            <p>Securely add new manuals, procedures, or records to the DMS repository.</p>
        </div>
    </div>
@stop

@section('content')

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- VALIDATION ERROR --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Data belum dapat disimpan</strong>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- UPLOAD CARD --}}
    <div class="airnav-upload-card">

        <form action="{{ route('documents.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf


     {{-- =====================================================
     DRAG & DROP FILE
     ===================================================== --}}

<div class="airnav-dropzone" id="dropzone">

    {{-- TAMPILAN AWAL --}}
    <div id="upload-placeholder">

        <div class="airnav-upload-icon">
            <i class="fas fa-cloud-upload-alt"></i>
        </div>

        <h3>Drag &amp; Drop files here</h3>

        <p>
            or
            <label for="document-file" class="airnav-browse">
                browse files
            </label>
            from your computer
        </p>

        <span>
            Supported formats: PDF, DOCX, XLSX (Max size: 50MB)
        </span>

    </div>


    {{-- PREVIEW FILE --}}
    <div id="file-preview" class="airnav-file-preview">

        <div class="airnav-file-preview-icon" id="file-preview-icon">
            <i class="fas fa-file-alt"></i>
        </div>

        <div class="airnav-file-preview-info">

            <h4 id="selected-file-name">
                Nama File
            </h4>

            <p id="selected-file-size">
                0 KB
            </p>

        </div>

        <button
            type="button"
            id="remove-file"
            class="airnav-remove-file"
        >
            <i class="fas fa-times"></i>
        </button>

    </div>


    {{-- INPUT FILE --}}
    <input
        type="file"
        id="document-file"
        name="file"
        accept=".pdf,.doc,.docx,.xls,.xlsx"
        required
    >

</div>


{{-- =====================================================
     DIVIDER
     ===================================================== --}}

<div class="airnav-upload-divider"></div>


{{-- =====================================================
     NOMOR DOKUMEN
     ===================================================== --}}

<div class="airnav-form-group">

    <label for="document_number">
        Nomor Dokumen
        <span>*</span>
    </label>

    <input
        type="text"
        id="document_number"
        name="document_number"
        value="{{ old('document_number') }}"
        placeholder="Contoh: No Izin : 012839/DPMPTSP/ISR/2019"
        required
    >

    <small>
        Masukkan nomor registrasi atau izin resmi dokumen.
    </small>

</div>


{{-- =====================================================
     JUDUL DOKUMEN
     ===================================================== --}}

<div class="airnav-form-group">

    <label for="title">
        Document Title / Judul Dokumen
        <span>*</span>
    </label>

    <input
        type="text"
        id="title"
        name="title"
        value="{{ old('title') }}"
        placeholder="Masukkan judul dokumen"
        required
    >

</div>


{{-- =====================================================
     CATEGORY + MASA BERLAKU
     ===================================================== --}}

<div class="airnav-form-row">

    {{-- CATEGORY --}}
    <div class="airnav-form-group">

        <label for="category_id">
            Category
            <span>*</span>
        </label>

        <div class="airnav-select-wrapper">

            <select
                id="category_id"
                name="category_id"
                required
            >

                <option value="">
                    Pilih Kategori...
                </option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>

            <i class="fas fa-chevron-down"></i>

        </div>

    </div>


    {{-- MASA BERLAKU --}}
    <div class="airnav-form-group">

        <label for="tanggal_berlaku">
            Masa Berlaku Dokumen
            <span>*</span>
        </label>

        <div class="airnav-date-wrapper">

            <input
                type="text"
                id="tanggal_berlaku"
                name="tanggal_berlaku"
                value="{{ old('tanggal_berlaku') }}"
                placeholder="Contoh: 02 Juni 2019 s/d 01 Juni 2024"
                required
            >

            <i class="far fa-calendar-alt"></i>

        </div>

        <small>
            Format periode masa berlaku awal s/d selesai.
        </small>

    </div>

</div>

       

                    <small>
                        Format periode masa berlaku awal s/d selesai.
                    </small>

                </div>

            </div>


            {{-- =====================================================
                 BUTTON
                 ===================================================== --}}

            <div class="airnav-upload-actions">

                <a
                    href="{{ route('documents.index') }}"
                    class="airnav-btn airnav-btn-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="airnav-btn airnav-btn-primary"
                >
                    Simpan Dokumen
                </button>

            </div>

        </form>

    </div>

@stop


@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('document-file');
    const dropzone = document.getElementById('dropzone');
    const placeholder = document.getElementById('upload-placeholder');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('selected-file-name');
    const fileSize = document.getElementById('selected-file-size');
    const fileIcon = document.getElementById('file-preview-icon');
    const removeFile = document.getElementById('remove-file');

    if (!fileInput) return;

    function showFile(file) {

        if (!file) return;

        fileName.textContent = file.name;

        const sizeKB = file.size / 1024;

        if (sizeKB < 1024) {
            fileSize.textContent = sizeKB.toFixed(1) + ' KB';
        } else {
            fileSize.textContent = (sizeKB / 1024).toFixed(2) + ' MB';
        }

        // Icon berdasarkan tipe file
        const extension = file.name
            .split('.')
            .pop()
            .toLowerCase();

        if (extension === 'pdf') {

            fileIcon.innerHTML =
                '<i class="fas fa-file-pdf"></i>';

            fileIcon.className =
                'airnav-file-preview-icon pdf';

        } else if (extension === 'doc' || extension === 'docx') {

            fileIcon.innerHTML =
                '<i class="fas fa-file-word"></i>';

            fileIcon.className =
                'airnav-file-preview-icon word';

        } else if (extension === 'xls' || extension === 'xlsx') {

            fileIcon.innerHTML =
                '<i class="fas fa-file-excel"></i>';

            fileIcon.className =
                'airnav-file-preview-icon excel';

        } else {

            fileIcon.innerHTML =
                '<i class="fas fa-file-alt"></i>';

            fileIcon.className =
                'airnav-file-preview-icon';
        }

        placeholder.style.display = 'none';
        filePreview.classList.add('show');
        dropzone.classList.add('has-file');
    }

    // Pilih file dari komputer
    fileInput.addEventListener('change', function () {

        if (this.files && this.files.length > 0) {
            showFile(this.files[0]);
        }

    });

    // Drag over
    dropzone.addEventListener('dragover', function (event) {

        event.preventDefault();

        dropzone.classList.add('dragover');

    });

    // Drag leave
    dropzone.addEventListener('dragleave', function () {

        dropzone.classList.remove('dragover');

    });

    // Drop file
    dropzone.addEventListener('drop', function (event) {

        event.preventDefault();

        dropzone.classList.remove('dragover');

        const files = event.dataTransfer.files;

        if (files && files.length > 0) {

            fileInput.files = files;

            showFile(files[0]);

        }

    });

    // Hapus file
    removeFile.addEventListener('click', function () {

        fileInput.value = '';

        filePreview.classList.remove('show');
        placeholder.style.display = 'block';
        dropzone.classList.remove('has-file');

    });

});
</script>
@stop