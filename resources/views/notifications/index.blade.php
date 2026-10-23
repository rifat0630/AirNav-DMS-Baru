@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('css')
    @vite('resources/css/notifications.css')
@endsection

@section('content')

<div class="airnav-notification-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="airnav-notification-header">

        <div>

            <div class="airnav-notification-heading">

                <div class="airnav-notification-heading-icon">
                    <i class="fas fa-bell"></i>
                </div>

                <div>

                    <h1 class="airnav-notification-title">
                        Notifikasi
                    </h1>

                    <p class="airnav-notification-subtitle">
                        Lihat dan kelola seluruh pemberitahuan pada sistem AirNav DMS.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ALERT SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="airnav-notification-alert success">

            <i class="fas fa-check-circle"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         ALERT ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="airnav-notification-alert error">

            <i class="fas fa-exclamation-circle"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         MAIN PANEL
    ========================================================== --}}

    <div class="airnav-notification-panel">


        {{-- =====================================================
             PANEL HEADER
        ====================================================== --}}

        <div class="airnav-notification-panel-header">

            <div>

                <div class="airnav-notification-panel-title">

                    <i class="fas fa-bell"></i>

                    <strong>
                        Daftar Notifikasi
                    </strong>

                </div>

                <p class="airnav-notification-panel-description">
                    Pemberitahuan terbaru dari sistem AirNav DMS
                </p>

            </div>


            {{-- =================================================
                 MARK ALL
            ================================================== --}}

            @if($notifications->where('is_read', 0)->count() > 0)

                <a
                    href="{{ route('notifications.read.all') }}"
                    class="airnav-notification-mark-all"
                >

                    <i class="fas fa-check-double"></i>

                    <span>
                        Tandai Semua Dibaca
                    </span>

                </a>

            @endif

        </div>


        {{-- =====================================================
             PANEL BODY
        ====================================================== --}}

        <div class="airnav-notification-panel-body">


            {{-- =================================================
                 UNREAD SUMMARY
            ================================================== --}}

            @php

                $unreadCount =
                    $notifications->where('is_read', 0)->count();

            @endphp


            @if($unreadCount > 0)

                <div class="airnav-notification-summary">

                    <div class="airnav-notification-summary-icon">

                        <i class="fas fa-bell"></i>

                    </div>

                    <div>

                        <div class="airnav-notification-summary-number">
                            {{ $unreadCount }}
                        </div>

                        <div class="airnav-notification-summary-label">
                            Notifikasi belum dibaca
                        </div>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 NOTIFICATION LIST
            ================================================== --}}

            <div class="airnav-notification-list">


                @forelse($notifications as $notification)


                    @php

                        $isUnread =
                            $notification->is_read == 0;

                        $type =
                            $notification->type ?? 'info';

                    @endphp


                    {{-- =========================================
                         NOTIFICATION ITEM
                    ========================================== --}}

                    <div
                        class="airnav-notification-item
                        {{ $isUnread ? 'unread' : 'read' }}"
                    >


                        {{-- =====================================
                             ICON
                        ====================================== --}}

                        <div
                            class="airnav-notification-icon {{ $type }}"
                        >

                            @if($type == 'danger')

                                <i class="fas fa-exclamation-circle"></i>

                            @elseif($type == 'warning')

                                <i class="fas fa-exclamation-triangle"></i>

                            @elseif($type == 'success')

                                <i class="fas fa-check-circle"></i>

                            @else

                                <i class="fas fa-info-circle"></i>

                            @endif

                        </div>


                        {{-- =====================================
                             CONTENT
                        ====================================== --}}

                        <div class="airnav-notification-content">


                            {{-- =================================
                                 TOP
                            ================================== --}}

                            <div class="airnav-notification-top">


                                <div class="airnav-notification-title-row">

                                    <h3 class="airnav-notification-item-title">
                                        {{ $notification->title }}
                                    </h3>


                                    @if($isUnread)

                                        <span
                                            class="airnav-notification-badge new"
                                        >
                                            Baru
                                        </span>

                                    @else

                                        <span
                                            class="airnav-notification-badge read"
                                        >
                                            Sudah Dibaca
                                        </span>

                                    @endif

                                </div>


                                {{-- =============================
                                     TIME
                                ============================== --}}

                                <div class="airnav-notification-time">

                                    <i class="far fa-clock"></i>

                                    <span>

                                        {{ $notification->created_at
                                            ? $notification->created_at->format('d-m-Y H:i')
                                            : '-'
                                        }}

                                    </span>

                                </div>


                            </div>


                            {{-- =================================
                                 MESSAGE
                            ================================== --}}

                            <p class="airnav-notification-message">

                                {{ $notification->message }}

                            </p>


                            {{-- =================================
                                 ACTIONS
                            ================================== --}}

                            <div class="airnav-notification-actions">


                                {{-- =============================
                                     LIHAT DOKUMEN
                                ============================== --}}

                                @if(!empty($notification->url))

                                    <a
                                        href="{{ route(
                                            'notifications.read',
                                            $notification->id
                                        ) }}"
                                        class="airnav-notification-action primary"
                                    >

                                        <i class="fas fa-eye"></i>

                                        <span>
                                            Lihat Dokumen
                                        </span>

                                    </a>

                                @endif


                                {{-- =============================
                                     TANDAI DIBACA
                                ============================== --}}

                                @if($isUnread)

                                    <a
                                        href="{{ route(
                                            'notifications.read',
                                            $notification->id
                                        ) }}"
                                        class="airnav-notification-action success"
                                    >

                                        <i class="fas fa-check"></i>

                                        <span>
                                            Tandai Dibaca
                                        </span>

                                    </a>

                                @endif


                            </div>


                        </div>

                    </div>


                @empty


                    {{-- =========================================
                         EMPTY STATE
                    ========================================== --}}

                    <div class="airnav-notification-empty">

                        <div class="airnav-notification-empty-icon">

                            <i class="fas fa-bell-slash"></i>

                        </div>

                        <h3 class="airnav-notification-empty-title">
                            Tidak ada notifikasi
                        </h3>

                        <p class="airnav-notification-empty-text">
                            Saat ini tidak ada notifikasi yang perlu diperiksa.
                        </p>

                    </div>


                @endforelse


            </div>

        </div>

    </div>

</div>

@endsection