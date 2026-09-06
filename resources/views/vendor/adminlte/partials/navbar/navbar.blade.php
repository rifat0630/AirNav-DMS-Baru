@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- =========================================================
         NAVBAR LEFT
    ========================================================== --}}

    <ul class="navbar-nav">

        {{-- Left sidebar toggler --}}
        @include(
            'adminlte::partials.navbar.menu-item-left-sidebar-toggler'
        )

        {{-- Configured left links --}}
        @each(
            'adminlte::partials.navbar.menu-item',
            $adminlte->menu('navbar-left'),
            'item'
        )

        {{-- Custom left links --}}
        @yield('content_top_nav_left')

    </ul>


    {{-- =========================================================
         NAVBAR RIGHT
    ========================================================== --}}

    <ul class="navbar-nav ml-auto">

        {{-- =====================================================
             NOTIFICATION
        ====================================================== --}}

        @include(
            'vendor.adminlte.partials.navbar.notifications'
        )


        {{-- Custom right links --}}
        @yield('content_top_nav_right')


        {{-- Configured right links --}}
        @each(
            'adminlte::partials.navbar.menu-item',
            $adminlte->menu('navbar-right'),
            'item'
        )


        {{-- =====================================================
             USER MENU
        ====================================================== --}}

        @if(Auth::user())

            @if(config('adminlte.usermenu_enabled'))

                @include(
                    'adminlte::partials.navbar.menu-item-dropdown-user-menu'
                )

            @else

                @include(
                    'adminlte::partials.navbar.menu-item-logout-link'
                )

            @endif

        @endif


        {{-- =====================================================
             RIGHT SIDEBAR
        ====================================================== --}}

        @if($layoutHelper->isRightSidebarEnabled())

            @include(
                'adminlte::partials.navbar.menu-item-right-sidebar-toggler'
            )

        @endif

    </ul>

</nav>


{{-- =============================================================
     SWEETALERT2
     Popup notifikasi
============================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
</script>


{{-- =============================================================
     AUDIO NOTIFICATION
============================================================= --}}

<audio
    id="notificationSound"
    preload="auto"
>

    <source
        src="{{ asset('sounds/notification.mp3') }}"
        type="audio/mpeg"
    >

</audio>


{{-- =============================================================
     NOTIFICATION JAVASCRIPT
============================================================= --}}

<script
    src="{{ asset('js/notifications.js') }}">
</script>