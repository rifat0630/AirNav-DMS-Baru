@extends('layouts.app')


@section('content')


<div class="container">


<h3>
Scan QR Logbook
</h3>


<div class="card">


<div class="card-body text-center">


<h5>
{{ $logbook->technicians }}
</h5>


<p>
{{ $logbook->action_notes }}
</p>



<img 
src="{{ asset('qrcodes/'.$logbook->signature_file) }}"
width="250">



<br><br>



<a href="{{ route('logbook.sign',$logbook->qr_token) }}"
class="btn btn-success">


<i class="fas fa-signature"></i>

Buka Tanda Tangan


</a>



</div>


</div>


</div>


@endsection