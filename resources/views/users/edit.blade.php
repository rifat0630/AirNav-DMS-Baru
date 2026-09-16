@extends('adminlte::page')


@section('title','Edit User')



@section('content_header')

<h1>
    Edit User
</h1>

@stop
@section('content')
@if($errors->any())

<div class="alert alert-danger">

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

<form action="{{ route('users.update',$user->id) }}"
      method="POST">


@csrf

@method('PUT')

<div class="form-group">

<label>
Nama
</label>


<input type="text"
       name="name"
       class="form-control"
       value="{{ old('name',$user->name) }}"
       required>


</div>

<div class="form-group">

<label>
Username
</label>


<input type="text"
       name="username"
       class="form-control"
       value="{{ old('username',$user->username) }}"
       required>


</div>

<div class="form-group">

<label>
Email
</label>


<input type="email"
       name="email"
       class="form-control"
       value="{{ old('email',$user->email) }}"
       required>

</div>
<div class="form-group">

<label>
Password Baru
</label>


<input type="password"
       name="password"
       class="form-control"
       placeholder="Kosongkan jika tidak mengganti password">


</div>

<div class="form-group">

<label>
Role
</label>


<select name="role"
        class="form-control"
        required>


<option value="admin"
{{ $user->role == 'admin' ? 'selected' : '' }}>
Admin
</option>

<option value="teknisi"
{{ $user->role == 'teknisi' ? 'selected' : '' }}>
Teknisi
</option>

<option value="pegawai"
{{ $user->role == 'pegawai' ? 'selected' : '' }}>
Pegawai
</option>

</select>

</div>

<div class="form-group">
<label>
Teknisi
</label>
<select name="technician_id"
        class="form-control">
<option value="">
-- Tidak Ada Teknisi --
</option>
@foreach($technicians as $technician)
<option value="{{ $technician->id }}"
{{ $user->technician_id == $technician->id ? 'selected' : '' }}
>
{{ $technician->name }}
</option>
@endforeach
</select>
@if($technicians->count() == 0)
<small class="text-danger">
Belum ada teknisi aktif.
Silakan tambah teknisi terlebih dahulu.
</small>
@endif
</div>
<button type="submit"
        class="btn btn-primary">

<i class="fas fa-save"></i>
Simpan Perubahan
</button>
<a href="{{ route('users.index') }}"
   class="btn btn-secondary">
Kembali
</a>
</form>



</div>

</div>


@stop