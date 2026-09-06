@php

    /*
    |--------------------------------------------------------------------------
    | JUMLAH NOTIFIKASI BELUM DIBACA
    |--------------------------------------------------------------------------
    */

    $unreadCount = \App\Models\Notification::where(
        'is_read',
        0
    )->count();


    /*
    |--------------------------------------------------------------------------
    | AMBIL 5 NOTIFIKASI TERBARU
    |--------------------------------------------------------------------------
    */

    $notifications = \App\Models\Notification::latest()
        ->take(5)
        ->get();

@endphp


<li class="nav-item dropdown">


    {{-- =========================================================
         TOMBOL BELL
    ========================================================== --}}

    <a
        class="nav-link"
        data-toggle="dropdown"
        href="#"
        aria-haspopup="true"
        aria-expanded="false"
    >

        <i class="fas fa-bell text-warning"></i>


        @if($unreadCount > 0)

            <span class="badge badge-danger navbar-badge">

                {{ $unreadCount }}

            </span>

        @endif

    </a>



    {{-- =========================================================
         DROPDOWN
    ========================================================== --}}

    <div
        class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
    >


        {{-- HEADER --}}

        <span class="dropdown-header">

            {{ $unreadCount }} Notifikasi Baru

        </span>


        <div class="dropdown-divider"></div>



        {{-- =====================================================
             LIST NOTIFIKASI
        ====================================================== --}}

        @forelse($notifications as $notification)


            @php

                /*
                |--------------------------------------------------
                | ICON BERDASARKAN TYPE
                |--------------------------------------------------
                */

                if ($notification->type === 'danger') {

                    $icon = 'fas fa-times-circle text-danger';

                } elseif ($notification->type === 'warning') {

                    $icon = 'fas fa-exclamation-triangle text-warning';

                } elseif ($notification->type === 'success') {

                    $icon = 'fas fa-check-circle text-success';

                } else {

                    $icon = 'fas fa-info-circle text-info';

                }


                /*
                |--------------------------------------------------
                | URL NOTIFIKASI
                |--------------------------------------------------
                */

                $notificationUrl =
                    $notification->url
                    ?? route(
                        'notifications.read',
                        $notification->id
                    );

            @endphp



            {{-- =================================================
                 ITEM NOTIFIKASI
            ================================================== --}}

            <a
                href="{{ $notificationUrl }}"
                class="dropdown-item notification-item
                {{ $notification->is_read ? '' : 'font-weight-bold' }}"
            >


                {{-- ICON --}}

                <i class="{{ $icon }} mr-2"></i>


                {{-- JUDUL --}}

                <strong>

                    {{ $notification->title }}

                </strong>


                <br>


                {{-- PESAN --}}

                <small class="text-muted">

                    {{ Str::limit(
                        $notification->message,
                        55
                    ) }}

                </small>


                <br>


                {{-- WAKTU --}}

                <small class="text-secondary">

                    <i class="far fa-clock"></i>

                    {{ $notification->created_at->diffForHumans() }}

                </small>


            </a>


            <div class="dropdown-divider"></div>


        @empty


            {{-- =================================================
                 TIDAK ADA NOTIFIKASI
            ================================================== --}}

            <span class="dropdown-item text-center text-muted">

                <i class="fas fa-check-circle text-success mr-1"></i>

                Tidak ada notifikasi

            </span>


        @endforelse



        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <a
            href="{{ route('notifications.index') }}"
            class="dropdown-item dropdown-footer"
        >

            <i class="fas fa-list mr-1"></i>

            Lihat semua notifikasi

        </a>


    </div>

</li>