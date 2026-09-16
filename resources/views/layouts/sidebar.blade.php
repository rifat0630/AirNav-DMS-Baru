<aside class="airnav-sidebar">

    {{-- BRAND --}}
    <div class="airnav-brand">
        <a href="{{ route('dashboard') }}" class="airnav-brand-link">
            <span class="airnav-brand-main">
                AirNav <span>DMS</span>
            </span>

            <span class="airnav-brand-subtitle">
                ENTERPRISE CONTROL
            </span>
        </a>
    </div>


    {{-- SIDEBAR MENU --}}
    <div class="airnav-sidebar-content">

        {{-- MAIN MENU --}}
        <div class="airnav-menu-section">
            <div class="airnav-section-title">
                MAIN MENU
            </div>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="airnav-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-th-large"></i>
                </span>

                <span>Dashboard</span>
            </a>


            {{-- e-Logbook Facility --}}
            <a href="{{ route('logbook.index') }}"
               class="airnav-menu-item {{ request()->routeIs('logbook.*') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-book-open"></i>
                </span>

                <span>e-Logbook Facility</span>
            </a>
        </div>


        {{-- DOKUMEN --}}
        <div class="airnav-menu-section">

            <div class="airnav-section-title">
                DOKUMEN
            </div>


            {{-- Daftar Dokumen --}}
            <a href="{{ route('documents.index') }}"
               class="airnav-menu-item {{ request()->routeIs('documents.index', 'documents.show', 'documents.edit') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="far fa-file-alt"></i>
                </span>

                <span>Daftar Dokumen</span>
            </a>


            {{-- Upload Dokumen --}}
            <a href="{{ route('documents.create') }}"
               class="airnav-menu-item {{ request()->routeIs('documents.create') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </span>

                <span>Upload Dokumen</span>
            </a>

        </div>


        {{-- MASTER --}}
        <div class="airnav-menu-section">

            <div class="airnav-section-title">
                MASTER
            </div>




            {{-- User --}}
            <a href="{{ route('users.index') }}"
               class="airnav-menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-users"></i>
                </span>

                <span>User</span>
            </a>


            {{-- Teknisi --}}
            <a href="{{ route('technicians.index') }}"
               class="airnav-menu-item {{ request()->routeIs('technicians.*') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-user-cog"></i>
                </span>

                <span>Teknisi</span>
            </a>


            {{-- Inventory --}}
            <a href="{{ route('inventory.index') }}"
               class="airnav-menu-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-box"></i>
                </span>

                <span>Inventory Barang</span>
            </a>

        </div>


        {{-- LAPORAN --}}
        <div class="airnav-menu-section">

            <div class="airnav-section-title">
                LAPORAN
            </div>


            {{-- Activity Log --}}
            <a href="{{ route('activity_logs.index') }}"
               class="airnav-menu-item {{ request()->routeIs('activity_logs.*') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-history"></i>
                </span>

                <span>Activity Log</span>
            </a>

        </div>


        {{-- AKUN --}}
        <div class="airnav-menu-section airnav-account-section">

            <div class="airnav-section-title">
                AKUN
            </div>


            {{-- Settings / Profile --}}
            <a href="{{ route('profile.edit') }}"
               class="airnav-menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                <span class="airnav-menu-icon">
                    <i class="fas fa-cog"></i>
                </span>

                <span>Settings</span>
            </a>


            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="airnav-logout-form">
                @csrf

                <button type="submit" class="airnav-menu-item airnav-logout-button">

                    <span class="airnav-menu-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </span>

                    <span>Logout</span>

                </button>
            </form>

        </div>

    </div>

</aside>