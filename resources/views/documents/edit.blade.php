@extends('adminlte::page')


@section('title','Edit Dokumen')



@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <h1>
        <i class="fas fa-edit"></i>
        Edit Dokumen
    </h1>


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
Data belum dapat disimpan.
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



<form action="{{ route('documents.update',$document->id) }}"

      method="POST">



@csrf

@method('PUT')








<div class="form-group">


<label>

Nomor Dokumen

</label>


<input type="text"

name="document_number"

class="form-control"

value="{{ old('document_number',$document->document_number) }}"

required>


</div>











<div class="form-group">


<label>

Judul Dokumen

</label>


<input type="text"

name="title"

class="form-control"

value="{{ old('title',$document->title) }}"

required>


</div>











<div class="form-group">


<label>

Kategori

</label>



<select name="category_id"

class="form-control"

required>


@foreach($categories as $category)



<option value="{{ $category->id }}"

@if($document->category_id == $category->id)

selected

@endif

>


{{ $category->name }}


</option>



@endforeach



</select>



</div>











<div class="form-group">


<label>

Masa Berlaku Dokumen

</label>



<input type="text"

name="tanggal_berlaku"

class="form-control"

value="{{ old('tanggal_berlaku',$document->tanggal_berlaku) }}"

placeholder="Contoh: 27 Februari 2023 s/d 27 Februari 2028"

required>



<small class="text-muted">

Isi periode berlaku dokumen

</small>



</div>












<button type="submit"

class="btn btn-success">


<i class="fas fa-save"></i>

Update Dokumen


</button>






<a href="{{ route('documents.index') }}"

class="btn btn-secondary">


<i class="fas fa-arrow-left"></i>

Kembali


</a>





</form>




</div>


</div>


@stop