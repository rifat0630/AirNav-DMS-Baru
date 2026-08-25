@extends('adminlte::page')


@section('title','Data User')


@section('content_header')

<div class="d-flex justify-content-between">

    <h1>
        Data User
    </h1>


    <a href="{{ route('users.create') }}"
       class="btn btn-primary">

        + Tambah User

    </a>


</div>

@stop





@section('content')



@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif






<div class="card">

<div class="card-body">


<table class="table table-bordered">


<thead>

<tr>

<th width="50">
No
</th>

<th>
Nama
</th>

<th>
Email
</th>

<th>
Role
</th>

<th>
Teknisi
</th>

<th width="180">
Aksi
</th>


</tr>


</thead>




<tbody>


@foreach($users as $user)


<tr>


<td>
{{ $loop->iteration }}
</td>


<td>
{{ $user->name }}
</td>


<td>
{{ $user->email }}
</td>


<td>

<span class="badge badge-info">

{{ $user->role }}

</span>

</td>



<td>


@if($user->technician)

{{ $user->technician->name }}

@else

-

@endif


</td>



<td>


<a href="{{ route('users.edit',$user->id) }}"
class="btn btn-sm btn-warning">

<i class="fas fa-edit"></i>

Edit

</a>





<form action="{{ route('users.destroy',$user->id) }}"
method="POST"
style="display:inline">


@csrf

@method('DELETE')


<button type="submit"
class="btn btn-sm btn-danger"
onclick="return confirm('Yakin hapus user ini?')">


<i class="fas fa-trash"></i>

Hapus


</button>


</form>



</td>



</tr>


@endforeach


</tbody>


</table>


</div>

</div>


@stop