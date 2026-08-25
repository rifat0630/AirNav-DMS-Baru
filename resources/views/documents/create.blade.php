@extends('adminlte::page')

@section('title', 'Tambah Dokumen')


@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <h1>
        <i class="fas fa-upload"></i>
        Tambah Dokumen
    </h1>


    <a href="{{ route('documents.index') }}"
       class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>
        Kembali

    </a>

</div>

@stop



@section('content')


@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif



@if($errors->any())

<div class="alert alert-danger">

    <strong>
        Data belum dapat disimpan
    </strong>


    <ul>

        @foreach($errors->all() as $error)

            <li>
                {{ $error }}
            </li>

        @endforeach

    </ul>

</div>

@endif





<div class="card">


<div class="card-body">



<form action="{{ route('documents.store') }}"
      method="POST"
      enctype="multipart/form-data">


@csrf






{{-- NOMOR DOKUMEN --}}

<div class="form-group">


<label>

Nomor Dokumen

<span class="text-danger">*</span>

</label>


<input type="text"

name="document_number"

class="form-control"

value="{{ old('document_number') }}"

placeholder="Contoh: AIRNAV/SOP/001/2026"

required>


</div>









{{-- JUDUL --}}

<div class="form-group">


<label>

Judul Dokumen

<span class="text-danger">*</span>

</label>



<input type="text"

name="title"

class="form-control"

value="{{ old('title') }}"

placeholder="Masukkan judul dokumen"

required>


</div>








{{-- KATEGORI --}}

<div class="form-group">


<label>

Kategori

<span class="text-danger">*</span>

</label>



<select name="category_id"

class="form-control"

required>


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

<div class="form-group">


<label>

Masa Berlaku Dokumen

<span class="text-danger">*</span>

</label>




<input type="text"

name="tanggal_berlaku"

class="form-control"

value="{{ old('tanggal_berlaku') }}"

placeholder="Contoh: 27 Februari 2023 s/d 27 Februari 2028"

required>



<small class="text-muted">

Isi periode berlaku dokumen

</small>


</div>









{{-- FILE --}}

<div class="form-group">


<label>

File Dokumen

<span class="text-danger">*</span>

</label>



<input type="file"

name="file"

class="form-control"

required>


</div>






<button type="submit"

class="btn btn-primary">


<i class="fas fa-save"></i>

Simpan Dokumen


</button>



<a href="{{ route('documents.index') }}"

class="btn btn-secondary">


Batal


</a>



</form>



</div>


</div>


@stop