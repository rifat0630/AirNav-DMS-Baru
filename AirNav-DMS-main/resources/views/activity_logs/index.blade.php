@extends('adminlte::page')

@section('title', 'Activity Log')

@section('content_header')
    <h1>Activity Log</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header">
        <h5>Riwayat Aktivitas Pengguna</h5>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th width="5%">No</th>
                    <th>User</th>
                    <th>Dokumen</th>
                    <th>Aktivitas</th>
                    <th>Tanggal</th>
                </tr>

            </thead>

            <tbody>

                @forelse($logs as $log)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $log->user->name ?? '-' }}</td>

                    <td>{{ $log->document->title ?? '-' }}</td>

                    <td>{{ $log->activity }}</td>

                    <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center">
                        Belum ada aktivitas.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        <div class="mt-3">
            {{ $logs->links() }}
        </div>

    </div>

</div>

@stop