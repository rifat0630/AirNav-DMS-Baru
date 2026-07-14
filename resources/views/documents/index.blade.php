@extends('layouts.app')


@section('title','Documents')


@section('content')


<div class="d-flex justify-content-between mb-3">

    <h3>
        Daftar Dokumen
    </h3>


    <a href="{{ route('documents.create') }}" 
       class="btn btn-primary">

        <i class="fas fa-plus"></i>
        Tambah Dokumen

    </a>

</div>



<table class="table table-bordered table-striped">


<thead class="table-dark">

<tr>

<th>No</th>

<th>Nomor Dokumen</th>

<th>Judul</th>

<th>Kategori</th>

<th>User</th>

<th>Aksi</th>

</tr>

</thead>



<tbody>


@forelse($documents as $document)


<tr>


<td>
{{ $loop->iteration }}
</td>



<td>
{{ $document->document_number }}
</td>



<td>
{{ $document->title }}
</td>



<td>

{{ $document->category->name ?? '-' }}

</td>



<td>

{{ $document->user->name ?? '-' }}

</td>



<td>


<a href="{{ route('documents.show',$document->id) }}"
class="btn btn-info btn-sm">

<i class="fas fa-eye"></i>

</a>



<a href="{{ route('documents.download',$document->id) }}"
class="btn btn-success btn-sm">

<i class="fas fa-download"></i>

</a>



@if(
auth()->user()->role == 'admin' ||
auth()->user()->role == 'teknisi' ||
auth()->user()->role == 'pegawai'
)


<a href="{{ route('documents.edit',$document->id) }}"
class="btn btn-warning btn-sm">

<i class="fas fa-edit"></i>

</a>


@endif



@if(auth()->user()->role == 'admin')

<form action="{{ route('documents.destroy', $document->id) }}" 
      method="POST" 
      style="display:inline">

    @csrf
    @method('DELETE')

    <button type="submit" 
            class="btn btn-danger btn-sm"
            onclick="return confirm('Yakin hapus dokumen ini?')">
        <i class="fas fa-trash"></i>
    </button>

</form>

@endif



</td>


</tr>


@empty


<tr>

<td colspan="6" class="text-center">

Belum ada dokumen

</td>

</tr>


@endforelse


</tbody>


</table>



@endsection