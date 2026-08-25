@extends('layouts.app')


@section('content')


<div class="container">


<h3>
    Facility Logbook
</h3>



@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif




@if(session('error'))

<div class="alert alert-danger">

    {{ session('error') }}

</div>

@endif







<a href="{{ route('logbook.create') }}"
   class="btn btn-primary mb-3">

    <i class="fas fa-plus"></i>
    Tambah Logbook

</a>








<form method="GET"
      action="{{ route('logbook.index') }}">


<div class="input-group mb-3">


<input type="text"
       name="search"
       class="form-control"
       placeholder="Cari teknisi atau kegiatan..."
       value="{{ request('search') }}">



<button class="btn btn-secondary">

    Cari

</button>


</div>


</form>









<table class="table table-bordered table-striped">


<thead>


<tr>


<th width="5%">
No
</th>


<th>
Tanggal & Jam
</th>



<th>
Teknisi
</th>



<th>
QR Logbook
</th>



<th>
Kegiatan
</th>



<th>
Dibuat Oleh
</th>



<th>
Status
</th>



<th>
Aksi
</th>


</tr>


</thead>








<tbody>



@forelse($logbooks as $log)



<tr>



<td>

{{ $loop->iteration }}

</td>







<td>

{{ 
\Carbon\Carbon::parse(
$log->log_datetime
)
->format('d-m-Y H:i')

}}

</td>








<td>


{{ 
$log->technician->name 
?? 
$log->technicians
?? 
'-'

}}


</td>









<td>



@if($log->signature_file)



<img 
src="{{ asset('qrcodes/'.$log->signature_file) }}"
width="100"
height="100">



<br>


<small>

QR Logbook

</small>





@else



<span class="text-danger">

QR belum dibuat

</span>



@endif



</td>









<td>

{{ $log->action_notes }}

</td>









<td>


{{ 
$log->user->name 
?? 
'-'

}}


</td>









<td>



@if($log->signature_status == 'terverifikasi')



<span class="badge badge-success">

✔ Terverifikasi

</span>



<br>



@if($log->signed_at)


<small>

{{

\Carbon\Carbon::parse(
$log->signed_at
)
->format('d-m-Y H:i')

}}

</small>



@endif






@else



<span class="badge badge-warning">

Belum Verifikasi

</span>



@endif



</td>









<td>




<a href="{{ route('logbook.edit',$log->id) }}"
class="btn btn-primary btn-sm mb-1">


<i class="fas fa-edit"></i>

Edit


</a>









@if($log->signature_status != 'terverifikasi')



<a href="{{ route('logbook.scan',$log->id) }}"
class="btn btn-warning btn-sm mb-1">


<i class="fas fa-qrcode"></i>

Scan


</a>



@endif










<form method="POST"
action="{{ route('logbook.destroy',$log->id) }}"
style="display:inline">


@csrf

@method('DELETE')



<button 
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus logbook?')">


<i class="fas fa-trash"></i>


</button>



</form>






</td>







</tr>





@empty



<tr>


<td colspan="8"
class="text-center">


Belum ada data logbook


</td>


</tr>




@endforelse




</tbody>


</table>






</div>



@endsection