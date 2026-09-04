@extends('adminlte::page')


@section('title','Edit Barang')



@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <h1>
        <i class="fas fa-edit"></i>
        Edit Barang
    </h1>


    <a href="{{ route('inventory.index') }}"
       class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>
        Kembali

    </a>

</div>

@stop





@section('content')


@if($errors->any())

<div class="alert alert-danger">

<strong>
Data belum dapat diperbarui.
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



<form action="{{ route('inventory.update',$inventory->id) }}"
      method="POST"
      enctype="multipart/form-data">


@csrf
@method('PUT')



<div class="card-body">



{{-- NAMA BARANG --}}

<div class="form-group">

<label>
Nama Barang
<span class="text-danger">*</span>
</label>


<input type="text"
       name="name"
       class="form-control"
       value="{{ old('name',$inventory->name) }}"
       required>

</div>





{{-- KODE BARANG --}}

<div class="form-group">

<label>
Kode Barang
<span class="text-danger">*</span>
</label>


<input type="text"
       name="code"
       class="form-control"
       value="{{ old('code',$inventory->code) }}"
       required>

</div>





{{-- SERIAL NUMBER --}}

<div class="form-group">


<label>
Serial Number
</label>



<div id="serial-container">


@php
$serialNumbers = $inventory->serial_numbers ?? [];
@endphp



@if(is_string($serialNumbers))

@php
$serialNumbers = json_decode($serialNumbers,true) ?? [];
@endphp

@endif





@if(count($serialNumbers) > 0)


@foreach($serialNumbers as $serial)


<div class="input-group mb-2 serial-row">


<input type="text"
       name="serial_numbers[]"
       class="form-control"
       value="{{ $serial }}"
       placeholder="Masukkan Serial Number">


<div class="input-group-append">


<button type="button"
        class="btn btn-danger remove-serial">

<i class="fas fa-trash"></i>

</button>


</div>


</div>


@endforeach



@else



<div class="input-group mb-2 serial-row">


<input type="text"
       name="serial_numbers[]"
       class="form-control"
       placeholder="Masukkan Serial Number">


<div class="input-group-append">

<button type="button"
        class="btn btn-danger remove-serial">

<i class="fas fa-trash"></i>

</button>


</div>


</div>



@endif


</div>





<button type="button"
        id="add-serial"
        class="btn btn-outline-primary btn-sm">


<i class="fas fa-plus"></i>

Tambah Serial Number


</button>


</div>







{{-- STOK DAN SATUAN --}}


<div class="row">


<div class="col-md-6">

<div class="form-group">

<label>
Stok
</label>


<input type="number"
       name="stock"
       class="form-control"
       value="{{ old('stock',$inventory->stock) }}"
       min="0"
       required>

</div>

</div>





<div class="col-md-6">


<div class="form-group">

<label>
Satuan
</label>


<input type="text"
       name="unit"
       class="form-control"
       value="{{ old('unit',$inventory->unit) }}"
       required>


</div>


</div>


</div>







{{-- KONDISI --}}


<div class="form-group">


<label>
Kondisi Barang
</label>



<select name="condition"
        class="form-control">


<option value="normal"
{{ $inventory->condition == 'normal' ? 'selected':'' }}>

Normal

</option>


<option value="rusak"
{{ $inventory->condition == 'rusak' ? 'selected':'' }}>

Rusak

</option>


</select>


</div>








{{-- FOTO LAMA --}}


@if(!empty($inventory->photos))


<div class="form-group">


<label>
Foto Saat Ini
</label>


<div class="row">


@foreach($inventory->photos as $photo)


<div class="col-md-4 mb-3">


<img src="{{ asset('storage/'.$photo) }}"
     class="img-thumbnail"
     style="height:180px;width:100%;object-fit:cover;">


</div>


@endforeach


</div>


</div>


@endif








{{-- FOTO BARU --}}


<div class="form-group">


<label>
Ganti Foto
</label>


<input type="file"
       name="photos[]"
       class="form-control"
       multiple
       accept="image/*">


<small class="text-muted">

Maksimal 3 foto

</small>


</div>





</div>




<div class="card-footer">


<button type="submit"
        class="btn btn-success">

<i class="fas fa-save"></i>

Update Barang

</button>


<a href="{{ route('inventory.index') }}"
   class="btn btn-secondary">

Kembali

</a>


</div>



</form>


</div>



@stop






@section('js')


<script>


document.addEventListener(
'DOMContentLoaded',
function(){



// TAMBAH SERIAL

document.getElementById('add-serial')
.addEventListener('click',function(){


let html = `

<div class="input-group mb-2 serial-row">


<input type="text"
name="serial_numbers[]"
class="form-control"
placeholder="Masukkan Serial Number">


<div class="input-group-append">


<button type="button"
class="btn btn-danger remove-serial">


<i class="fas fa-trash"></i>


</button>


</div>


</div>

`;


document
.getElementById('serial-container')
.insertAdjacentHTML(
'beforeend',
html
);



});





// HAPUS SERIAL


document.addEventListener(
'click',
function(e){


if(
e.target.closest('.remove-serial')
){


let rows =
document.querySelectorAll('.serial-row');


if(rows.length > 1){


e.target.closest('.serial-row').remove();


}


}


});


});


</script>


@stop