@extends('layouts.app')


@section('content')


<div class="container">


<h3>
Tambah Logbook Facility
</h3>




<form method="POST" 
action="{{ route('logbook.store') }}">


@csrf





<div class="mb-3">


<label>
Tanggal & Jam
</label>


<input 

type="text"

class="form-control"

value="{{ now()->format('d-m-Y H:i') }}"

readonly>


</div>







<div class="mb-3">


<label>
Nama Teknisi
</label>


<select 
name="technician_id"
class="form-control"
required>


<option value="">
-- Pilih Teknisi --
</option>


@foreach($technicians as $tech)

<option value="{{ $tech->id }}">

{{ $tech->name }}

</option>

@endforeach


</select>


</div>







<div class="mb-3">


<label>
Catatan / Tindakan Kegiatan
</label>



<textarea

name="action_notes"

class="form-control"

rows="5"

placeholder="Masukkan kegiatan teknisi..."

required></textarea>


</div>








<button 
type="submit"

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



@endsection