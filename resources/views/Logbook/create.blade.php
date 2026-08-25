@extends('adminlte::page')


@section('title','Tambah Logbook Facility')


@section('content_header')

<h1>
    Tambah Logbook Facility
</h1>

@stop





@section('content')


<div class="card">

<div class="card-body">


<form action="{{ route('logbook.store') }}"
      method="POST">


@csrf




<div class="form-group">

<label>
Tanggal & Jam
</label>


<input type="text"
       class="form-control"
       value="{{ date('d-m-Y H:i') }}"
       readonly>


</div>







<div class="form-group">

<label>
Nama Teknisi
</label>


<select name="technician_id"
        class="form-control"
        required>


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







<div class="form-group">

<label>
Catatan Pekerjaan
</label>


<textarea name="action_notes"
          class="form-control"
          rows="4"
          placeholder="Masukkan pekerjaan teknisi..."
          required></textarea>


</div>







<button type="submit"
        class="btn btn-primary">

<i class="fas fa-save"></i>

Simpan Logbook

</button>



<a href="{{ route('logbook.index') }}"
   class="btn btn-secondary">

Kembali

</a>




</form>



</div>

</div>


@stop