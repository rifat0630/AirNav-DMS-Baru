@extends('layouts.admin')

@section('title','Activity Log')

@section('css')
@vite('resources/css/app.css')
@endsection

@section('content_header')
@stop

@section('content')
<div class="airnav-activity-page">
    <div class="airnav-activity-header">
        <h1>Activity Log</h1>
        <p>Audit trail dan riwayat aktivitas pengguna pada sistem AirNav DMS</p>
    </div>

    <div class="airnav-activity-filter">
        <form method="GET">
            <div class="airnav-activity-search">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari aktivitas..." value="{{ request('search') }}">
            </div>
            <div class="airnav-activity-select">
                <select name="module">
                    <option value="">Semua Modul</option>
                    <option value="inventory" {{ request('module') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                    <option value="logbook" {{ request('module') == 'logbook' ? 'selected' : '' }}>Logbook</option>
                    <option value="document" {{ request('module') == 'document' ? 'selected' : '' }}>Document</option>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>
            <button type="submit" class="airnav-activity-search-btn">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <div class="airnav-activity-card">
        <div class="table-responsive">
            <table class="airnav-activity-table">
                <thead>
                    <tr>
                        <th>WAKTU</th>
                        <th>USER</th>
                        <th>MODUL</th>
                        <th>AKTIVITAS</th>
                        <th>DETAIL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="activity-time">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                        <td class="activity-user">{{ $log->user->name ?? '-' }}</td>
                        <td>
                            <span class="activity-module {{ strtolower($log->module) }}">
                                <span class="activity-dot"></span>
                                {{ $log->module }}
                            </span>
                        </td>
                        <td class="activity-name">{{ $log->activity }}</td>
                        <td class="activity-detail">{{ $log->description }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="activity-empty">Belum ada aktivitas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($logs, 'links'))
        <div class="airnav-activity-footer">
            <div class="activity-count">
                Menampilkan {{ $logs->firstItem() ?? 0 }} sampai {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} aktivitas
            </div>
            <div class="activity-pagination">
                {{ $logs->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@stop