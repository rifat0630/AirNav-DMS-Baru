@extends('layouts.admin')

@section('content')

<div class="airnav-dashboard">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="airnav-page-header">

        <div>
            <h1 class="airnav-page-title">
                Dashboard Overview
            </h1>

            <p class="airnav-page-subtitle">
                Monitor system health and recent document activity.
            </p>
        </div>

        <a href="{{ route('documents.create') }}"
           class="airnav-new-document">

            <i class="fas fa-plus"></i>

            <span>New Document</span>

        </a>

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="airnav-stat-grid">

        {{-- TOTAL DOCUMENTS --}}

        <div class="airnav-stat-card">

            <div class="airnav-stat-content">

                <div class="airnav-stat-label">
                    Total Documents
                </div>

                <div class="airnav-stat-number">
                    {{ number_format($totalDocuments) }}
                </div>

            </div>

            <div class="airnav-stat-icon blue">

                <i class="far fa-folder"></i>

            </div>

        </div>


        {{-- ACTIVE DOCUMENTS --}}

        <div class="airnav-stat-card">

            <div class="airnav-stat-content">

                <div class="airnav-stat-label">
                    Active Documents
                </div>

                <div class="airnav-stat-number">
                    {{ number_format($activeDocuments) }}
                </div>

            </div>

            <div class="airnav-stat-icon blue">

                <i class="far fa-file-alt"></i>

            </div>

        </div>


        {{-- TOTAL USERS --}}

        <div class="airnav-stat-card">

            <div class="airnav-stat-content">

                <div class="airnav-stat-label">
                    Total Users
                </div>

                <div class="airnav-stat-number">
                    {{ number_format($totalUsers) }}
                </div>

            </div>

            <div class="airnav-stat-icon gray">

                <i class="fas fa-users"></i>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LOWER CONTENT
    ====================================================== --}}

    <div class="airnav-dashboard-grid">


        {{-- =================================================
             QUICK ACTIONS
        ================================================== --}}

        <div class="airnav-quick-section">

            <h2 class="airnav-section-heading">
                Quick Actions
            </h2>


            {{-- UPLOAD DOCUMENT --}}

            <a href="{{ route('documents.create') }}"
               class="airnav-action-card">

                <div class="airnav-action-icon blue">

                    <i class="fas fa-file-upload"></i>

                </div>

                <div class="airnav-action-content">

                    <div class="airnav-action-title">
                        Upload Document
                    </div>

                    <div class="airnav-action-description">
                        Add new files to the system
                    </div>

                </div>

            </a>


            {{-- DOCUMENT LIST --}}

            <a href="{{ route('documents.index') }}"
               class="airnav-action-card">

                <div class="airnav-action-icon gray">

                    <i class="fas fa-list"></i>

                </div>

                <div class="airnav-action-content">

                    <div class="airnav-action-title">
                        Document List
                    </div>

                    <div class="airnav-action-description">
                        Browse all directory files
                    </div>

                </div>

            </a>


            {{-- FACILITY LOGBOOK --}}

            <a href="{{ route('logbook.index') }}"
               class="airnav-action-card">

                <div class="airnav-action-icon gray">

                    <i class="far fa-clipboard"></i>

                </div>

                <div class="airnav-action-content">

                    <div class="airnav-action-title">
                        Facility Logbook
                    </div>

                    <div class="airnav-action-description">
                        Record facility maintenance activities
                    </div>

                </div>

            </a>

        </div>


        {{-- =================================================
             DOCUMENT STATUS
        ================================================== --}}

        <div class="airnav-status-section">

            <h2 class="airnav-section-heading">
                Ringkasan Status Dokumen
            </h2>


            <div class="airnav-status-card">


                {{-- WARNING --}}

                <div class="airnav-status-row">

                    <div class="airnav-status-left">

                        <div class="airnav-status-icon warning">

                            <i class="fas fa-exclamation-triangle"></i>

                        </div>

                        <div class="airnav-status-text">

                            <span>
                                Dokumen yang perlu diperhatikan
                            </span>

                        </div>

                    </div>

                    <div class="airnav-status-right">

                        <span class="airnav-status-badge warning">
                            Perlu Perhatian
                        </span>

                        <a href="{{ route('documents.index') }}"
                           class="airnav-detail-link">

                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>

                        </a>

                    </div>

                </div>


                {{-- EXPIRED --}}

                <div class="airnav-status-row">

                    <div class="airnav-status-left">

                        <div class="airnav-status-icon danger">

                            <i class="far fa-times-circle"></i>

                        </div>

                        <div class="airnav-status-text">

                            <span>
                                Dokumen kedaluwarsa
                            </span>

                        </div>

                    </div>

                    <div class="airnav-status-right">

                        <span class="airnav-status-badge danger">
                            Kedaluwarsa
                        </span>

                        <a href="{{ route('documents.index') }}"
                           class="airnav-detail-link">

                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>

                        </a>

                    </div>

                </div>


                {{-- ACTIVE --}}

                <div class="airnav-status-row">

                    <div class="airnav-status-left">

                        <div class="airnav-status-icon success">

                            <i class="far fa-check-circle"></i>

                        </div>

                        <div class="airnav-status-text">

                            <span>
                                {{ number_format($activeDocuments) }}
                                dokumen berstatus aktif
                            </span>

                        </div>

                    </div>

                    <div class="airnav-status-right">

                        <span class="airnav-status-badge success">
                            Aktif
                        </span>

                        <a href="{{ route('documents.index') }}"
                           class="airnav-detail-link">

                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>

                        </a>

                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

@endsection