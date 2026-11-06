@extends('layouts.admin')

@section('title','Facility Logbook')

@section('content')
<div class="airnav-logbook">

    <div class="airnav-logbook-header">
        <div>
            <h1 class="airnav-logbook-title">Facility Logbook</h1>
            <p class="airnav-logbook-subtitle">Manage and monitor all facility logbook activities.</p>
        </div>

        <button type="button" class="airnav-logbook-add"
                data-toggle="modal" data-target="#modalTambahLogbook">
            <i class="fas fa-plus"></i>
            <span>Add Logbook</span>
        </button>
    </div>

    @if(session('success'))
        <div class="airnav-logbook-alert success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="airnav-logbook-alert error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="airnav-logbook-search-card">
        <form method="GET" action="{{ route('logbook.index') }}" class="airnav-logbook-search-form">
            <div class="airnav-logbook-search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search"
                       placeholder="Search technician or activity..."
                       value="{{ request('search') }}">
            </div>

            <button type="submit" class="airnav-logbook-search-button">
                <i class="fas fa-search"></i>
                <span>Search</span>
            </button>
        </form>
    </div>

    <div class="airnav-logbook-table-card">
        <div class="airnav-logbook-table-wrapper">
            <table class="airnav-logbook-table">
                <thead>
                    <tr>
                        <th class="col-no">NO</th>
                        <th>DATE & TIME</th>
                        <th>TECHNICIAN</th>
                        <th class="col-qr">QR<br>LOGBOOK</th>
                        <th class="col-kegiatan">ACTIVITY</th>
                        <th>CREATED<br>BY</th>
                        <th>STATUS</th>
                        <th class="col-action">ACTION</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logbooks as $log)
                        <tr>
                            <td class="col-no">{{ $loop->iteration }}</td>

                            <td class="logbook-date">
                                {{ \Carbon\Carbon::parse($log->log_datetime ?? $log->created_at)->format('d/m/Y, H:i') }}
                            </td>

                            <td class="logbook-technician">
                                {{ $log->technician->name ?? $log->technicians ?? '-' }}
                            </td>

                            <td class="col-qr">
                                @if($log->signature_file)
                                    <div class="airnav-logbook-qr">
                                        <img src="{{ asset('qrcodes/'.$log->signature_file) }}" alt="QR Logbook">
                                        <span>QR LOGBOOK</span>
                                    </div>
                                @else
                                    <span class="airnav-logbook-no-qr">QR Not Created</span>
                                @endif
                            </td>

                            <td class="logbook-activity">
                                <div class="logbook-activity-title">
                                    {{ $log->action_notes }}
                                </div>
                            </td>

                            <td class="logbook-created-by">
                                {{ $log->user->name ?? '-' }}
                            </td>

                            {{-- DYNAMIC STATUS COLUMN --}}
                            <td>
                                @if(!empty($log->paraf_teknisi) && !empty($log->paraf_mantek) || $log->signature_status == 'terverifikasi' || $log->status == 'Verified')
                                    <div class="airnav-logbook-status verified" style="background-color: #d1e7dd; color: #0f5132; padding: 4px 8px; border-radius: 4px; text-align: center; font-weight: bold;">
                                        <span>Verified</span>
                                    </div>
                                @elseif(!empty($log->paraf_teknisi) || !empty($log->paraf_mantek) || $log->signature_status == 'sebagian')
                                    <div class="airnav-logbook-status partial" style="background-color: #fff3cd; color: #664d03; padding: 4px 8px; border-radius: 4px; text-align: center; font-weight: bold;">
                                        <span>Partially Verified</span>
                                    </div>
                                @else
                                    <div class="airnav-logbook-status pending" style="background-color: #f8d7da; color: #842029; padding: 4px 8px; border-radius: 4px; text-align: center; font-weight: bold;">
                                        <span>Unverified</span>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="airnav-logbook-actions">

                                    <button type="button"
                                            class="airnav-logbook-action edit"
                                            data-toggle="modal"
                                            data-target="#modalEditLogbook{{ $log->id }}">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>

                                    {{-- ACTION BUTTON: VIEW OR SCAN/SIGN --}}
                                    @if((!empty($log->paraf_teknisi) && !empty($log->paraf_mantek)) || $log->signature_status == 'terverifikasi' || $log->status == 'Verified')
                                        <a href="{{ route('logbook.scan', $log->id) }}"
                                           class="airnav-logbook-action view" style="background-color: #0dcaf0; color: #fff;">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </a>
                                    @else
                                        <a href="{{ route('logbook.scan', $log->id) }}"
                                           class="airnav-logbook-action scan" style="background-color: #ffc107; color: #000;">
                                            <i class="fas fa-qrcode"></i>
                                            <span>Scan / Sign</span>
                                        </a>
                                    @endif

                                    <form method="POST"
                                          action="{{ route('logbook.destroy', $log->id) }}"
                                          class="airnav-logbook-delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="airnav-logbook-action delete"
                                                onclick="return confirm('Delete this logbook entry?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="airnav-logbook-empty">
                                <i class="fas fa-book-open"></i>
                                <span>No logbook data available</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="airnav-logbook-footer">
            <div class="airnav-logbook-total">
                Showing
                <strong>{{ $logbooks->count() }}</strong>
                logbook entry(ies)
            </div>
        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- MODAL ADD LOGBOOK --}}
{{-- ========================================================= --}}

<div class="modal fade" id="modalTambahLogbook" tabindex="-1"
     role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content airnav-logbook-modal">

            <div class="modal-header airnav-logbook-modal-header">
                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-book"></i>
                        Add Facility Logbook
                    </h5>
                    <p>Add technician activities to the logbook.</p>
                </div>

                <button type="button" class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('logbook.store') }}" method="POST">
                @csrf

                <div class="modal-body airnav-logbook-modal-body">

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Technician Name</label>

                        <select name="technician_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Select Technician --
                            </option>

                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">
                                    {{ $technician->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Work Notes / Activity</label>

                        <textarea name="action_notes"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Enter technician work description..."
                                  required></textarea>
                    </div>

                </div>

                <div class="modal-footer airnav-logbook-modal-footer">

                    <button type="button"
                            class="airnav-logbook-modal-cancel"
                            data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="airnav-logbook-modal-save">
                        <i class="fas fa-save"></i>
                        Save Logbook
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- MODAL EDIT LOGBOOK --}}
{{-- ========================================================= --}}

@foreach($logbooks as $log)

<div class="modal fade"
     id="modalEditLogbook{{ $log->id }}"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         role="document">

        <div class="modal-content airnav-logbook-modal">

            <div class="modal-header airnav-logbook-modal-header">

                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i>
                        Edit Facility Logbook
                    </h5>

                    <p>Update logbook activity information.</p>
                </div>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <form action="{{ route('logbook.update', $log->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="modal-body airnav-logbook-modal-body">

                    <div class="form-group mb-3">

                        <label class="font-weight-bold">Technician Name</label>

                        <select name="technician_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Select Technician --
                            </option>

                            @foreach($technicians as $technician)

                                <option value="{{ $technician->id }}"
                                    {{ $log->technician_id == $technician->id ? 'selected' : '' }}>

                                    {{ $technician->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="form-group mb-3">

                        <label class="font-weight-bold">Activity Notes</label>

                        <textarea name="action_notes"
                                  class="form-control"
                                  rows="4"
                                  required>{{ old('action_notes', $log->action_notes) }}</textarea>

                    </div>


                    <div class="form-group">

                        <label class="font-weight-bold">Signature Status</label>

                        @php
                            $statusText = 'Unsigned';
                            if (!empty($log->paraf_teknisi) && !empty($log->paraf_mantek)) {
                                $statusText = 'Fully Verified (Technician & Mantek)';
                            } elseif (!empty($log->paraf_teknisi)) {
                                $statusText = 'Partially Verified (Technician Initialed)';
                            } elseif (!empty($log->paraf_mantek)) {
                                $statusText = 'Partially Verified (Mantek Initialed)';
                            }
                        @endphp

                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $statusText }}"
                               readonly>

                    </div>

                </div>


                <div class="modal-footer airnav-logbook-modal-footer">

                    <button type="button"
                            class="airnav-logbook-modal-cancel"
                            data-dismiss="modal">

                        Cancel

                    </button>


                    <button type="submit"
                            class="airnav-logbook-modal-save">

                        <i class="fas fa-save"></i>
                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach

@endsection