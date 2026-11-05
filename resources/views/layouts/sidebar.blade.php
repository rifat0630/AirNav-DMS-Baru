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

            <a href="{{ route('mantek.dashboard') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition duration-150 {{ request()->routeIs('mantek.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
    </svg>
    <span>Manager Teknik</span>
</a>

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