<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AirNav DMS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')


    {{-- TOP HEADER --}}
    <header class="airnav-header">

        {{-- LEFT --}}
        <div class="airnav-header-left">

            <button type="button"
                    class="airnav-header-button"
                    id="airnavSidebarToggle"
                    title="Toggle Sidebar">

                <i class="fas fa-bars"></i>

            </button>

        </div>


        {{-- RIGHT --}}
        <div class="airnav-header-right">

            {{-- FULLSCREEN --}}
            <button type="button"
                    class="airnav-header-button"
                    id="airnavFullscreen"
                    title="Fullscreen">

                <i class="fas fa-expand"></i>

            </button>


            {{-- NOTIFICATION --}}
            <a href="{{ url('/notifications') }}"
               class="airnav-header-button airnav-notification"
               title="Notifications">

                <i class="far fa-bell"></i>

                <span class="airnav-notification-badge">
                    3
                </span>

            </a>


            {{-- HELP --}}
            <button type="button"
                    class="airnav-header-button"
                    title="Help">

                <i class="far fa-circle-question"></i>

            </button>


            {{-- DIVIDER --}}
            <div class="airnav-header-divider"></div>


            {{-- USER --}}
            <div class="airnav-user">

                <div class="airnav-user-info">

                    <div class="airnav-user-name">
                        {{ Auth::user()->name ?? 'Administrator AirNav Cabang Banjarmasin' }}
                    </div>

                    <div class="airnav-user-role">
                        {{ Auth::user()->role ?? 'Super Admin' }} · Perum LPPNPI
                    </div>

                </div>


                {{-- USER ICON --}}
                <div class="airnav-user-avatar">

                    <i class="fas fa-user"></i>

                </div>


                {{-- DROPDOWN ARROW --}}
                <i class="fas fa-chevron-down airnav-user-arrow"></i>

            </div>

        </div>

    </header>


    {{-- MAIN CONTENT --}}
    <div class="content-wrapper">

        <section class="content pt-3">

            <div class="container-fluid">

                @yield('content')

            </div>

        </section>

    </div>

</div>


{{-- SCRIPTS --}}

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>


<script>

    // ==========================================
    // SIDEBAR TOGGLE
    // ==========================================

    document.getElementById('airnavSidebarToggle')
        ?.addEventListener('click', function () {

            document.body.classList.toggle('airnav-sidebar-collapsed');

        });


    // ==========================================
    // FULLSCREEN
    // ==========================================

    document.getElementById('airnavFullscreen')
        ?.addEventListener('click', function () {

            if (!document.fullscreenElement) {

                document.documentElement.requestFullscreen();

            } else {

                document.exitFullscreen();

            }

        });

</script>
@yield('js')
</body>
</html>