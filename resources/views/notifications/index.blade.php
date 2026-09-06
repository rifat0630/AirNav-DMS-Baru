@extends('adminlte::page')

@section('title', 'Notifikasi')


@section('content_header')

    <div class="d-flex justify-content-between align-items-center">

        <h1>
            <i class="fas fa-bell"></i>
            Semua Notifikasi
        </h1>

    </div>

@stop


@section('content')


<div class="card">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <strong>
                Daftar Notifikasi
            </strong>


            @if(
                $notifications->where('is_read', 0)->count() > 0
            )

                <a
                    href="{{ route('notifications.read.all') }}"
                    class="btn btn-success btn-sm"
                >

                    <i class="fas fa-check-double"></i>

                    Tandai Semua Dibaca

                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
         BODY
    ========================================================== --}}

    <div class="card-body">


        @forelse($notifications as $notification)


            @php

                $isUnread =
                    $notification->is_read == 0;

            @endphp


            {{-- =================================================
                 NOTIFICATION CARD
            ================================================== --}}

            <div
                class="card
                mb-3
                border-left-{{ $notification->type == 'danger'
                    ? 'danger'
                    : ($notification->type == 'warning'
                        ? 'warning'
                        : ($notification->type == 'success'
                            ? 'success'
                            : 'info')) }}"
                style="
                    border-left-width: 5px;
                    {{ $isUnread
                        ? 'background-color:#fffdf5;'
                        : ''
                    }}
                "
            >


                <div class="card-body">


                    {{-- =================================================
                         TITLE
                    ================================================== --}}

                    <div
                        class="d-flex justify-content-between align-items-start"
                    >

                        <h5 class="mb-2">


                            @if($notification->type == 'danger')

                                <i
                                    class="fas fa-exclamation-circle text-danger"
                                ></i>

                            @elseif($notification->type == 'warning')

                                <i
                                    class="fas fa-exclamation-triangle text-warning"
                                ></i>

                            @elseif($notification->type == 'success')

                                <i
                                    class="fas fa-check-circle text-success"
                                ></i>

                            @else

                                <i
                                    class="fas fa-info-circle text-info"
                                ></i>

                            @endif


                            <strong>
                                {{ $notification->title }}
                            </strong>


                            @if($isUnread)

                                <span
                                    class="badge badge-danger ml-2"
                                >
                                    BARU
                                </span>

                            @else

                                <span
                                    class="badge badge-secondary ml-2"
                                >
                                    Sudah Dibaca
                                </span>

                            @endif


                        </h5>


                        <small class="text-muted">

                            <i class="far fa-clock"></i>

                            {{ $notification->created_at
                                ? $notification->created_at->format('d-m-Y H:i')
                                : '-'
                            }}

                        </small>


                    </div>



                    {{-- =================================================
                         MESSAGE
                    ================================================== --}}

                    <p class="mb-3">

                        {{ $notification->message }}

                    </p>



                    {{-- =================================================
                         ACTION BUTTONS
                    ================================================== --}}

                    <div class="d-flex flex-wrap gap-2">


                        {{-- =============================================
                             LIHAT OBJEK TERKAIT
                        ============================================== --}}

                        @if(!empty($notification->url))


                            <a
                                href="{{ route(
                                    'notifications.read',
                                    $notification->id
                                ) }}"
                                class="btn btn-primary btn-sm"
                            >

                                <i class="fas fa-eye"></i>

                                Lihat Dokumen

                            </a>


                        @endif



                        {{-- =============================================
                             TANDAI DIBACA
                        ============================================== --}}

                        @if($isUnread)


                            <a
                                href="{{ route(
                                    'notifications.read',
                                    $notification->id
                                ) }}"
                                class="btn btn-success btn-sm"
                            >

                                <i class="fas fa-check"></i>

                                Tandai Dibaca

                            </a>


                        @endif


                    </div>


                </div>

            </div>


        @empty


            <div class="text-center py-5">


                <i
                    class="fas fa-bell-slash fa-3x text-muted mb-3"
                ></i>


                <h5>
                    Tidak ada notifikasi
                </h5>


                <p class="text-muted">
                    Saat ini tidak ada notifikasi yang perlu diperiksa.
                </p>


            </div>


        @endforelse


    </div>

</div>


@stop