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

<th>No</th>

<th>Nama</th>

<th>Email</th>

<th>Role</th>

<th>Teknisi</th>


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
{{ $user->role }}
</td>


<td>


@if($user->technician)

{{ $user->technician->name }}

@else

-

@endif


</td>


</tr>


@endforeach


</tbody>


</table>


</div>

</div>


@stop