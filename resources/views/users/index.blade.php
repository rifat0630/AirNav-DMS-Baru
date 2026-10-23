@extends('layouts.admin')

@section('title', 'Data User')

@section('css')
    @vite('resources/css/app.css')

    <style>
        .airnav-user-page {
            padding: 10px 4px 30px;
        }

        .airnav-user-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .airnav-user-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .airnav-user-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #f1f6ff;
            color: #2864dc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .airnav-user-title {
            margin: 0;
            color: #172033;
            font-size: 24px;
            font-weight: 700;
        }

        .airnav-user-subtitle {
            margin: 4px 0 0;
            color: #7b8497;
            font-size: 14px;
        }

        .airnav-add-user {
            border: none;
            background: #2864dc;
            color: #fff;
            border-radius: 9px;
            padding: 11px 17px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s ease;
        }

        .airnav-add-user:hover {
            background: #1f55c0;
            color: #fff;
        }

        .airnav-user-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e8edf5;
            box-shadow: 0 4px 16px rgba(23, 32, 51, .05);
            overflow: hidden;
        }

        .airnav-user-table-wrapper {
            overflow-x: auto;
        }

        .airnav-user-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            margin: 0;
        }

        .airnav-user-table thead th {
            background: #f8faff;
            color: #6f7890;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            padding: 15px 18px;
            border-bottom: 1px solid #e8edf5;
            white-space: nowrap;
        }

        .airnav-user-table tbody td {
            padding: 15px 18px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
            color: #263247;
            font-size: 14px;
        }

        .airnav-user-table tbody tr:last-child td {
            border-bottom: none;
        }

        .airnav-user-table tbody tr:hover {
            background: #fbfcff;
        }

        .airnav-user-number {
            width: 60px;
            color: #8a93a6 !important;
            font-weight: 600;
        }

        .airnav-user-name {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 190px;
        }

        .airnav-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #eaf1ff;
            color: #2864dc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .airnav-user-name-text {
            font-weight: 600;
            color: #172033;
        }

        .airnav-username {
            color: #59657a;
            font-family: monospace;
            font-size: 13px;
        }

        .airnav-role-badge,
        .airnav-status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .airnav-role-admin {
            background: #eee9ff;
            color: #6941c6;
        }

        .airnav-role-teknisi {
            background: #e8f3ff;
            color: #1769aa;
        }

        .airnav-status-aktif {
            background: #e8f8ef;
            color: #198754;
        }

        .airnav-status-mutasi {
            background: #fff3df;
            color: #b76e00;
        }

        .airnav-qr-wrapper {
            width: 58px;
            height: 58px;
            border: 1px solid #e4e9f1;
            border-radius: 8px;
            background: #fff;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .airnav-qr-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .airnav-muted {
            color: #a0a8b7;
        }

        .airnav-action-wrapper {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .airnav-action-btn {
            border: none;
            border-radius: 7px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .airnav-btn-edit {
            background: #eef4ff;
            color: #2864dc;
        }

        .airnav-btn-edit:hover {
            background: #dfeaff;
            color: #1f55c0;
        }

        .airnav-btn-mutasi {
            background: #fff3df;
            color: #b76e00;
        }

        .airnav-btn-mutasi:hover {
            background: #ffe8c2;
            color: #9a5d00;
        }

        .airnav-btn-active {
            background: #e8f8ef;
            color: #198754;
        }

        .airnav-btn-active:hover {
            background: #d7f2e3;
            color: #146c43;
        }

        .airnav-btn-delete {
            background: #fff0f0;
            color: #dc3545;
        }

        .airnav-btn-delete:hover {
            background: #ffe1e1;
            color: #bb2d3b;
        }

        .airnav-readonly {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border-radius: 7px;
            background: #f4f6f9;
            color: #7b8497;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .airnav-empty {
            padding: 45px 20px !important;
            text-align: center;
            color: #8b94a7 !important;
        }

        .airnav-empty i {
            display: block;
            font-size: 32px;
            margin-bottom: 10px;
            color: #c3cad7;
        }

        .airnav-modal .modal-content {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(23, 32, 51, .16);
        }

        .airnav-modal .modal-header {
            border-bottom: 1px solid #edf0f5;
            padding: 18px 20px;
        }

        .airnav-modal .modal-title {
            color: #172033;
            font-size: 18px;
            font-weight: 700;
        }

        .airnav-modal .modal-body {
            padding: 20px;
        }

        .airnav-modal .modal-footer {
            border-top: 1px solid #edf0f5;
            padding: 14px 20px;
        }

        .airnav-form-label {
            display: block;
            margin-bottom: 7px;
            color: #344054;
            font-size: 13px;
            font-weight: 600;
        }

        .airnav-form-control {
            width: 100%;
            border: 1px solid #d9dfe9;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            color: #172033;
            outline: none;
            transition: .2s ease;
        }

        .airnav-form-control:focus {
            border-color: #2864dc;
            box-shadow: 0 0 0 3px rgba(40, 100, 220, .10);
        }

        .airnav-form-group {
            margin-bottom: 16px;
        }

        .airnav-modal .form-text {
            color: #8a93a6;
            font-size: 12px;
            margin-top: 5px;
        }

        .airnav-modal .btn-primary {
            background: #2864dc;
            border-color: #2864dc;
        }

        .airnav-modal .btn-primary:hover {
            background: #1f55c0;
            border-color: #1f55c0;
        }

        @media (max-width: 768px) {
            .airnav-user-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .airnav-add-user {
                width: 100%;
                justify-content: center;
            }

            .airnav-user-title {
                font-size: 20px;
            }
        }
    </style>
@endsection

@section('content')

@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

<div class="airnav-user-page">

    <div class="airnav-user-header">

        <div class="airnav-user-heading">

            <div class="airnav-user-icon">
                <i class="fas fa-users"></i>
            </div>

            <div>

                <h1 class="airnav-user-title">
                    Data User
                </h1>

                <p class="airnav-user-subtitle">
                    Kelola akun pengguna dan teknisi pada sistem AirNav DMS
                </p>

            </div>

        </div>


        {{-- TOMBOL TAMBAH HANYA UNTUK ADMIN --}}
        @if($isAdmin)

            <button
                type="button"
                class="airnav-add-user"
                data-toggle="modal"
                data-target="#modalTambahUser"
            >
                <i class="fas fa-plus"></i>
                Tambah User
            </button>

        @endif

    </div>


    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="fas fa-check-circle mr-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>

        </div>

    @endif


    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <i class="fas fa-exclamation-circle mr-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>

        </div>

    @endif


    @if($errors->any())

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <strong>
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Terdapat kesalahan:
            </strong>

            <ul class="mb-0 mt-1">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>

        </div>

    @endif


    <div class="airnav-user-card">

        <div class="airnav-user-table-wrapper">

            <table class="airnav-user-table">

                <thead>

                    <tr>

                        <th>NO</th>
                        <th>NAMA</th>
                        <th>USERNAME</th>
                        <th>ROLE</th>
                        <th>QR TTD</th>
                        <th>STATUS</th>
                        <th>AKSI</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($users as $index => $user)

                        <tr>

                            <td class="airnav-user-number">
                                {{ $index + 1 }}
                            </td>


                            <td>

                                <div class="airnav-user-name">

                                    <div class="airnav-user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div class="airnav-user-name-text">
                                        {{ $user->name }}
                                    </div>

                                </div>

                            </td>


                            <td>

                                <span class="airnav-username">
                                    {{ '@' . $user->username }}
                                </span>

                            </td>


                            <td>

                                @if($user->role === 'admin')

                                    <span class="airnav-role-badge airnav-role-admin">

                                        <i class="fas fa-user-shield mr-1"></i>

                                        Admin

                                    </span>

                                @else

                                    <span class="airnav-role-badge airnav-role-teknisi">

                                        <i class="fas fa-tools mr-1"></i>

                                        Teknisi

                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($user->role === 'teknisi' && $user->technician)

                                    @php

                                        $qrFile =
                                            'qrcodes/technicians/' .
                                            $user->technician->qr_token .
                                            '.svg';

                                        $qrPath = public_path($qrFile);

                                    @endphp


                                    @if(file_exists($qrPath))

                                        <div class="airnav-qr-wrapper">

                                            <img
                                                src="{{ asset($qrFile) }}"
                                                alt="QR TTD {{ $user->name }}"
                                            >

                                        </div>

                                    @else

                                        <span class="airnav-muted">
                                            QR belum tersedia
                                        </span>

                                    @endif

                                @else

                                    <span class="airnav-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($user->role === 'teknisi' && $user->technician)

                                    @if($user->technician->status === 'aktif')

                                        <span
                                            class="airnav-status-badge airnav-status-aktif"
                                        >

                                            <i
                                                class="fas fa-circle mr-1"
                                                style="font-size: 7px;"
                                            ></i>

                                            Aktif

                                        </span>

                                    @else

                                        <span
                                            class="airnav-status-badge airnav-status-mutasi"
                                        >

                                            <i class="fas fa-exchange-alt mr-1"></i>

                                            Mutasi

                                        </span>

                                    @endif

                                @else

                                    <span class="airnav-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($isAdmin)

                                    <div class="airnav-action-wrapper">

                                        {{-- EDIT --}}
                                        <button
                                            type="button"
                                            class="airnav-action-btn airnav-btn-edit"
                                            data-toggle="modal"
                                            data-target="#modalEditUser{{ $user->id }}"
                                            title="Edit User"
                                        >

                                            <i class="fas fa-edit"></i>

                                            Edit

                                        </button>


                                        {{-- MUTASI / AKTIFKAN --}}
                                        @if(
                                            $user->role === 'teknisi'
                                            && $user->technician
                                        )

                                            @if($user->technician->status === 'aktif')

                                                <form
                                                    action="{{ route('users.technician-status', $user->id) }}"
                                                    method="POST"
                                                >

                                                    @csrf

                                                    <input
                                                        type="hidden"
                                                        name="status"
                                                        value="mutasi"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="airnav-action-btn airnav-btn-mutasi"
                                                        onclick="return confirm('Apakah Anda yakin ingin memutasi teknisi ini?')"
                                                    >

                                                        <i class="fas fa-exchange-alt"></i>

                                                        Mutasi

                                                    </button>

                                                </form>

                                            @else

                                                <form
                                                    action="{{ route('users.technician-status', $user->id) }}"
                                                    method="POST"
                                                >

                                                    @csrf

                                                    <input
                                                        type="hidden"
                                                        name="status"
                                                        value="aktif"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="airnav-action-btn airnav-btn-active"
                                                        onclick="return confirm('Aktifkan kembali teknisi ini?')"
                                                    >

                                                        <i class="fas fa-check-circle"></i>

                                                        Aktifkan

                                                    </button>

                                                </form>

                                            @endif

                                        @endif


                                        {{-- HAPUS --}}
                                        <form
                                            action="{{ route('users.destroy', $user->id) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="airnav-action-btn airnav-btn-delete"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?')"
                                            >

                                                <i class="fas fa-trash"></i>

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                @else

                                    {{-- TEKNISI HANYA BOLEH MELIHAT --}}
                                    <span class="airnav-readonly">

                                        <i class="fas fa-eye"></i>

                                        
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="airnav-empty"
                            >

                                <i class="fas fa-users-slash"></i>

                                Belum ada data user.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL TAMBAH USER - ADMIN SAJA --}}
{{-- ========================================================= --}}

@if($isAdmin)

    <div
        class="modal fade airnav-modal"
        id="modalTambahUser"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalTambahUserLabel"
        aria-hidden="true"
    >

        <div
            class="modal-dialog modal-dialog-centered"
            role="document"
        >

            <div class="modal-content">

                <form
                    action="{{ route('users.store') }}"
                    method="POST"
                >

                    @csrf


                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="modalTambahUserLabel"
                        >

                            <i class="fas fa-user-plus mr-2 text-primary"></i>

                            Tambah User

                        </h5>


                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >

                            <span aria-hidden="true">
                                &times;
                            </span>

                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="airnav-form-group">

                            <label class="airnav-form-label">
                                Nama
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="airnav-form-control"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                            >

                        </div>


                        <div class="airnav-form-group">

                            <label class="airnav-form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="airnav-form-control"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username"
                                required
                            >

                            <div class="form-text">
                                Username digunakan untuk login ke sistem.
                            </div>

                        </div>


                        <div class="airnav-form-group">

                            <label class="airnav-form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="airnav-form-control"
                                placeholder="Minimal 6 karakter"
                                required
                            >

                        </div>


                        <div class="airnav-form-group mb-0">

                            <label class="airnav-form-label">
                                Role
                            </label>

                            <select
                                name="role"
                                class="airnav-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Role
                                </option>

                                <option
                                    value="admin"
                                    {{ old('role') === 'admin' ? 'selected' : '' }}
                                >
                                    Admin
                                </option>

                                <option
                                    value="teknisi"
                                    {{ old('role') === 'teknisi' ? 'selected' : '' }}
                                >
                                    Teknisi
                                </option>

                            </select>

                            <div class="form-text">
                                Teknisi akan otomatis mendapatkan QR TTD.
                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-dismiss="modal"
                        >
                            Batal
                        </button>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-save mr-1"></i>

                            Simpan User

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL EDIT USER - ADMIN SAJA --}}
    {{-- ========================================================= --}}

    @foreach($users as $user)

        <div
            class="modal fade airnav-modal"
            id="modalEditUser{{ $user->id }}"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modalEditUserLabel{{ $user->id }}"
            aria-hidden="true"
        >

            <div
                class="modal-dialog modal-dialog-centered"
                role="document"
            >

                <div class="modal-content">

                    <form
                        action="{{ route('users.update', $user->id) }}"
                        method="POST"
                    >

                        @csrf

                        @method('PUT')


                        <div class="modal-header">

                            <h5
                                class="modal-title"
                                id="modalEditUserLabel{{ $user->id }}"
                            >

                                <i class="fas fa-user-edit mr-2 text-primary"></i>

                                Edit User

                            </h5>


                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >

                                <span aria-hidden="true">
                                    &times;
                                </span>

                            </button>

                        </div>


                        <div class="modal-body">

                            <div class="airnav-form-group">

                                <label class="airnav-form-label">
                                    Nama
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="airnav-form-control"
                                    value="{{ $user->name }}"
                                    required
                                >

                            </div>


                            <div class="airnav-form-group">

                                <label class="airnav-form-label">
                                    Username
                                </label>

                                <input
                                    type="text"
                                    name="username"
                                    class="airnav-form-control"
                                    value="{{ $user->username }}"
                                    required
                                >

                            </div>


                            <div class="airnav-form-group">

                                <label class="airnav-form-label">
                                    Password Baru
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="airnav-form-control"
                                    placeholder="Kosongkan jika tidak diubah"
                                >

                            </div>


                            <div class="airnav-form-group mb-0">

                                <label class="airnav-form-label">
                                    Role
                                </label>

                                <select
                                    name="role"
                                    class="airnav-form-control"
                                    required
                                >

                                    <option
                                        value="admin"
                                        {{ $user->role === 'admin' ? 'selected' : '' }}
                                    >
                                        Admin
                                    </option>

                                    <option
                                        value="teknisi"
                                        {{ $user->role === 'teknisi' ? 'selected' : '' }}
                                    >
                                        Teknisi
                                    </option>

                                </select>

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-dismiss="modal"
                            >
                                Batal
                            </button>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fas fa-save mr-1"></i>

                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

@endif

@endsection