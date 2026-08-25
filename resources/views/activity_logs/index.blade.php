@extends('adminlte::page')


@section('title','Activity Log')


@section('content_header')

<h1>
Activity Log
</h1>

@stop




@section('content')


<div class="card">

<div class="card-body">


<form method="GET">


<div class="row">


<div class="col-md-5">

<input

type="text"

name="search"

class="form-control"

placeholder="Cari aktivitas..."

value="{{request('search')}}"

>

</div>




<div class="col-md-3">

<select

name="module"

class="form-control"

>


<option value="">
Semua Modul
</option>


<option value="inventory">
Inventory
</option>


<option value="logbook">
Logbook
</option>


<option value="document">
Document
</option>


</select>


</div>




<div class="col-md-2">

<button class="btn btn-primary">

Cari

</button>

</div>


</div>


</form>



<hr>



<table class="table table-bordered table-striped">


<thead>

<tr>


<th>
Waktu
</th>


<th>
User
</th>


<th>
Modul
</th>


<th>
Aktivitas
</th>


<th>
Detail
</th>


</tr>

</thead>




<tbody>


@forelse($logs as $log)


<tr>


<td>

{{$log->created_at->format('d-m-Y H:i')}}

</td>



<td>

{{$log->user->name ?? '-'}}

</td>




<td>

<span class="badge badge-info">

{{$log->module}}

</span>

</td>




<td>

{{$log->activity}}

</td>




<td>

{{$log->description}}

</td>


</tr>



@empty


<tr>

<td colspan="5" class="text-center">

Belum ada aktivitas

</td>

</tr>


@endforelse



</tbody>


</table>



</div>

</div>



@stop