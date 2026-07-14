@extends('layouts.admin')

@section('content')

<div class="row">

    <div class="col-lg-4">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>{{ $totalDocuments }}</h3>

                <p>Total Dokumen</p>

            </div>

            <div class="icon">

                <i class="fas fa-folder"></i>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>{{ $totalCategories }}</h3>

                <p>Total Kategori</p>

            </div>

            <div class="icon">

                <i class="fas fa-list"></i>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>{{ $totalUsers }}</h3>

                <p>Total User</p>

            </div>

            <div class="icon">

                <i class="fas fa-user"></i>

            </div>

        </div>

    </div>

</div>

@endsection