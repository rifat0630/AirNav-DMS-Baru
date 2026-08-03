<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">
            AirNav DMS
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Dokumen -->
                <li class="nav-item">
                    <a href="{{ route('documents.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Dokumen</p>
                    </a>
                </li>

                <!-- Menu e-Logbook Facility -->
                <li class="nav-item">
                    <a href="{{ route('logbook.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>e-Logbook Facility</p>
                    </a>
                </li>

                <!-- Kategori -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Kategori</p>
                    </a>
                </li>

                <!-- User -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>User</p>
                    </a>
                </li>

            </ul>

        </nav>

    </div>

</aside>