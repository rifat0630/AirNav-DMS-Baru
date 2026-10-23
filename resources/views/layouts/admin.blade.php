<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'AirNav DMS')
    </title>


    {{-- =========================================================
        VITE
    ========================================================== --}}

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- =========================================================
        ADMIN LTE
    ========================================================== --}}

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">


    {{-- =========================================================
        FONT AWESOME
    ========================================================== --}}

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    @yield('css')

</head>


<body class="hold-transition sidebar-mini">


<div class="wrapper">


    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}

    @include('layouts.sidebar')



    {{-- =========================================================
        TOP HEADER
    ========================================================== --}}

    <header class="airnav-header">


        {{-- =====================================================
            LEFT HEADER
        ====================================================== --}}

        <div class="airnav-header-left">


            {{-- SIDEBAR TOGGLE --}}

            <button type="button"

                    class="airnav-header-button"

                    id="airnavSidebarToggle"

                    title="Toggle Sidebar">

                <i class="fas fa-bars"></i>

            </button>


        </div>



        {{-- =====================================================
            RIGHT HEADER
        ====================================================== --}}

        <div class="airnav-header-right">


            {{-- =================================================
                FULLSCREEN
            ================================================== --}}

            <button type="button"

                    class="airnav-header-button"

                    id="airnavFullscreen"

                    title="Fullscreen">

                <i class="fas fa-expand"></i>

            </button>



            {{-- =================================================
                NOTIFICATION
            ================================================== --}}

            <a href="{{ url('/notifications') }}"

               class="airnav-header-button airnav-notification"

               title="Notifications">

                <i class="far fa-bell"></i>


                <span class="airnav-notification-badge">

                    3

                </span>

            </a>



            {{-- =================================================
                HELP
            ================================================== --}}

            <button type="button"

                    class="airnav-header-button"

                    title="Help">

                <i class="far fa-circle-question"></i>

            </button>



            {{-- =================================================
                DIVIDER
            ================================================== --}}

            <div class="airnav-header-divider"></div>



            {{-- =================================================
                USER PROFILE
            ================================================== --}}

            @auth

                @php

                    $roleLabel = match(Auth::user()->role) {

                        'admin' => 'Administrator',

                        'teknisi' => 'Teknisi',

                        'super_admin' => 'Super Admin',

                        'user' => 'User',

                        default => ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                Auth::user()->role
                            )
                        ),

                    };

                    $userName = Auth::user()->name;

                    $userInitial = strtoupper(
                        substr(
                            $userName,
                            0,
                            1
                        )
                    );

                @endphp


                <div class="airnav-user">


                    {{-- =================================================
                        USER INFORMATION
                    ================================================== --}}

                    <div class="airnav-user-info">


                        {{-- USER NAME --}}

                        <div class="airnav-user-name">

                            {{ $userName }}

                        </div>


                        {{-- USER ROLE --}}

                        <div class="airnav-user-role">

                            {{ $roleLabel }}

                            <span>
                                •
                            </span>

                            Perum LPPNPI

                        </div>


                    </div>



                    {{-- =================================================
                        USER AVATAR
                    ================================================== --}}

                    <div class="airnav-user-avatar">

                        {{ $userInitial }}

                    </div>



                    {{-- =================================================
                        DROPDOWN ARROW
                    ================================================== --}}

                    <i class="fas fa-chevron-down airnav-user-arrow"></i>


                </div>

            @else

                {{-- =================================================
                    GUEST
                ================================================== --}}

                <div class="airnav-user">

                    <div class="airnav-user-info">

                        <div class="airnav-user-name">

                            Guest

                        </div>

                        <div class="airnav-user-role">

                            Guest

                        </div>

                    </div>


                    <div class="airnav-user-avatar">

                        <i class="fas fa-user"></i>

                    </div>


                    <i class="fas fa-chevron-down airnav-user-arrow"></i>

                </div>

            @endauth


        </div>

    </header>



    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <div class="content-wrapper">


        <section class="content pt-3">


            <div class="container-fluid">


                @yield('content')


            </div>


        </section>


    </div>


</div>



{{-- =============================================================
    JQUERY
============================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>



{{-- =============================================================
    BOOTSTRAP
============================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>



{{-- =============================================================
    ADMIN LTE
============================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>



<script>


    // =========================================================
    // SIDEBAR TOGGLE
    // =========================================================

    document
        .getElementById('airnavSidebarToggle')
        ?.addEventListener(
            'click',
            function () {

                document.body.classList.toggle(
                    'airnav-sidebar-collapsed'
                );

            }
        );



    // =========================================================
    // FULLSCREEN
    // =========================================================

    document
        .getElementById('airnavFullscreen')
        ?.addEventListener(
            'click',
            function () {


                if (!document.fullscreenElement) {


                    document.documentElement
                        .requestFullscreen();


                } else {


                    document
                        .exitFullscreen();


                }

            }
        );


</script>



@yield('js')


</body>

</html>