@extends('layouts.app')


@section('content')


<div class="container">


<div class="row justify-content-center">


<div class="col-md-8">



<div class="card">


<div class="card-header bg-success text-white">

<h4 class="mb-0">
Verifikasi Logbook Teknisi
</h4>

</div>





<div class="card-body">



<div class="alert alert-info">

<i class="fas fa-user"></i>

Login sebagai:

<strong>
{{ Auth::user()->name }}
</strong>

</div>







<table class="table table-bordered">


<tr>

<th width="35%">
Teknisi
</th>


<td>

{{ $logbook->technician->name ?? '-' }}

</td>

</tr>





<tr>

<th>
Tanggal & Jam
</th>


<td>

{{ 
\Carbon\Carbon::parse(
$logbook->log_datetime
)->format('d-m-Y H:i')
}}

</td>

</tr>






<tr>

<th>
Kegiatan
</th>


<td>

{{ $logbook->action_notes }}

</td>

</tr>







<tr>

<th>
Status
</th>


<td>


@if($logbook->signature_status == 'terverifikasi')


<span class="badge badge-success">

✔ Terverifikasi

</span>


@else


<span class="badge badge-warning">

Belum Verifikasi

</span>


@endif


</td>

</tr>



</table>









@if($logbook->signature_status != 'terverifikasi')



<form method="POST"

action="{{ route(
'logbook.sign.confirm',
$logbook->qr_token
) }}">


@csrf




<button type="submit"

class="btn btn-success btn-lg btn-block"

onclick="return confirm(
'Verifikasi logbook ini?'
)">


<i class="fas fa-check-circle"></i>


Verifikasi Logbook


</button>




</form>





@else



<div class="alert alert-success text-center">


<i class="fas fa-check"></i>


Logbook sudah diverifikasi



<br>


Waktu:


{{ 
\Carbon\Carbon::parse(
$logbook->signed_at
)->format('d-m-Y H:i')
}}



</div>



@endif





</div>


</div>



</div>


</div>


</div>



@endsection