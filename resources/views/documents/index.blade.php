@extends('adminlte::page')


@section('title', 'Daftar Dokumen')



@section('content_header')


<div class="d-flex justify-content-between align-items-center">


    <h1>

        <i class="fas fa-folder-open"></i>

        Daftar Dokumen

    </h1>




    <a href="{{ route('documents.create') }}"
       class="btn btn-primary">


        <i class="fas fa-plus"></i>

        Tambah Dokumen


    </a>


</div>


@stop






@section('content')





@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">


    <i class="fas fa-check-circle"></i>

    {{ session('success') }}


    <button type="button"
            class="close"
            data-dismiss="alert">

        <span>
            &times;
        </span>

    </button>


</div>

@endif







@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">


    <i class="fas fa-exclamation-circle"></i>

    {{ session('error') }}



    <button type="button"
            class="close"
            data-dismiss="alert">


        <span>
            &times;
        </span>


    </button>


</div>

@endif







<div class="card">


<div class="card-header">


<h3 class="card-title">

<strong>

Filter Dokumen

</strong>


</h3>


</div>





<div class="card-body">



<form method="GET"
      action="{{ route('documents.index') }}">



<div class="row">






<div class="col-md-6">


<label>

Cari Dokumen

</label>



<input type="text"

       name="search"

       class="form-control"

       value="{{ request('search') }}"

       placeholder="Cari nomor atau judul dokumen">



</div>









<div class="col-md-4">


<label>

Kategori

</label>



<select name="category"

        class="form-control">


<option value="">

-- Semua Kategori --

</option>




@foreach($categories as $category)



<option value="{{ $category->id }}"

{{ request('category') == $category->id ? 'selected':'' }}

>


{{ $category->name }}


</option>



@endforeach




</select>



</div>








<div class="col-md-2 d-flex align-items-end">


<button class="btn btn-secondary w-100">


<i class="fas fa-search"></i>

Cari


</button>



</div>





</div>




</form>



</div>



</div>









<div class="card">


<div class="card-body">



<div class="table-responsive">



<table class="table table-bordered table-striped table-hover">



<thead class="thead-dark">


<tr>


<th width="5%">
No
</th>


<th>
Nomor Dokumen
</th>


<th>
Judul Dokumen
</th>


<th>
Kategori
</th>


<th>
Masa Berlaku
</th>


<th>
Status
</th>


<th>
Upload Oleh
</th>


<th width="220">
Aksi
</th>



</tr>


</thead>





<tbody>





@forelse($documents as $document)



<tr>




<td class="text-center">


{{ $loop->iteration }}


</td>








<td>


{{ $document->document_number }}


</td>








<td>


{{ $document->title }}


</td>








<td>



@if($document->category)


<span class="badge badge-info">


{{ $document->category->name }}


</span>


@else


-


@endif



</td>








<td>


{{ $document->tanggal_berlaku ?? '-' }}


</td>








<td>



@if($document->status == 'aktif')


<span class="badge badge-success">

Aktif

</span>



@else


<span class="badge badge-secondary">

{{ $document->status }}

</span>


@endif



</td>








<td>


{{ $document->user->name ?? '-' }}


</td>









<td class="text-center">





{{-- PREVIEW --}}


<a href="{{ route('documents.preview',$document->id) }}"

   target="_blank"

   class="btn btn-info btn-sm"

   title="Preview">


<i class="fas fa-eye"></i>


</a>







{{-- DOWNLOAD --}}


<a href="{{ route('documents.download',$document->id) }}"

   class="btn btn-success btn-sm"

   title="Download">


<i class="fas fa-download"></i>


</a>







{{-- EDIT --}}


<a href="{{ route('documents.edit',$document->id) }}"

   class="btn btn-warning btn-sm"

   title="Edit">


<i class="fas fa-edit"></i>


</a>







{{-- DELETE --}}



<form action="{{ route('documents.destroy',$document->id) }}"

      method="POST"

      style="display:inline;"



      onsubmit="return confirm('Yakin ingin menghapus dokumen ini? File Google Drive juga akan dihapus.')">


@csrf

@method('DELETE')



<button type="submit"

        class="btn btn-danger btn-sm"

        title="Hapus">


<i class="fas fa-trash"></i>


</button>


</form>







</td>





</tr>





@empty



<tr>


<td colspan="8"

class="text-center">


Belum ada dokumen


</td>


</tr>



@endforelse






</tbody>



</table>




</div>



</div>



</div>





@stop