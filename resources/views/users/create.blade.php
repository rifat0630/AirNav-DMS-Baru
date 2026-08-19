@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
<h1>Tambah User</h1>
@stop


@section('content')


<div class="card">

<div class="card-body">


<form method="POST"
action="{{ route('users.store') }}">

@csrf



<div class="form-group">

<label>
Nama
</label>

<input 
type="text"
name="name"
class="form-control"
required>

</div>



<div class="form-group">

<label>
Username
</label>

<input 
type="text"
name="username"
class="form-control"
required>

</div>




<div class="form-group">

<label>
Email
</label>

<input 
type="email"
name="email"
class="form-control"
required>

</div>




<div class="form-group">

<label>
Password
</label>

<input 
type="password"
name="password"
class="form-control"
required>

</div>




<div class="form-group">

<label>
Role
</label>


<select 
name="role"
class="form-control">


<option value="teknisi">
Teknisi
</option>


<option value="pegawai">
Pegawai
</option>


<option value="admin">
Admin
</option>


</select>

</div>





<div class="form-group">

<label>
Hubungkan Teknisi
</label>


<select 
name="technician_id"
class="form-control">


<option value="">
-- Pilih Teknisi --
</option>



@foreach($technicians as $technician)


<option value="{{ $technician->id }}">

{{ $technician->name }}

</option>


@endforeach


</select>


</div>





<button class="btn btn-primary">

Simpan

</button>


</form>


</div>

</div>


@stop