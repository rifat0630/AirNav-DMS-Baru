@extends('layouts.admin')

@section('title', 'Data User')

@section('css')
    @vite('resources/css/app.css')
@endsection

@section('content')
<div class="airnav-user-page">

    <div class="airnav-user-header">
        <div>
            <h1>Data User</h1>
            <p>Kelola akun pengguna dan teknisi pada sistem AirNav DMS</p>
        </div>

        <button type="button"
                class="airnav-user-add"
                data-toggle="modal"
                data-target="#modalTambahUser">
            <i class="fas fa-plus"></i>
            <span>Tambah User</span>
        </button>
    </div>

    @if(session('success'))
        <div class="airnav-user-alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>

            <button type="button"
                    class="airnav-user-alert-close"
                    onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="airnav-user-card">

        <div class="airnav-user-card-header">
            <div>
                <h2>Daftar Pengguna</h2>
                <p>Daftar akun yang terdaftar pada sistem</p>
            </div>

            <div class="airnav-user-total">
                <i class="fas fa-users"></i>
                <span>{{ $users->count() }} User</span>
            </div>
        </div>

        <div class="table-responsive">

            <table class="airnav-user-table">

                <thead>
                    <tr>
                        <th class="user-no">NO</th>
                        <th>NAMA</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>TEKNISI</th>
                        <th class="user-action">AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td class="user-no">
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <div class="user-name">

                                    <div class="user-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <span>
                                        {{ $user->name }}
                                    </span>

                                </div>

                            </td>

                            <td class="user-email">
                                {{ $user->email }}
                            </td>

                            <td>

                                <span class="user-role {{ strtolower($user->role) }}">

                                    <span class="user-role-dot"></span>

                                    {{ $user->role }}

                                </span>

                            </td>

                            <td class="user-technician">

                                @if($user->technician)

                                    <span>
                                        <i class="fas fa-user-cog"></i>
                                        {{ $user->technician->name }}
                                    </span>

                                @else

                                    <span class="user-empty">
                                        -
                                    </span>

                                @endif

                            </td>

                            <td class="user-action">

                                <div class="user-actions">

                                 <button type="button"
        class="user-btn-edit"
        data-toggle="modal"
        data-target="#modalEditUser{{ $user->id }}">
    <i class="fas fa-edit"></i>
    Edit
</button>

                                    <form action="{{ route('users.destroy', $user->id) }}"
                                          method="POST"
                                          style="display:inline;">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="user-btn-delete"
                                                onclick="return confirm('Yakin hapus user ini?')">

                                            <i class="fas fa-trash"></i>
                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="user-empty-row">

                                <i class="fas fa-users"></i>

                                <span>
                                    Belum ada data user
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- MODAL TAMBAH USER --}}
<div class="modal fade"
     id="modalTambahUser"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">
<div class="modal-dialog airnav-user-modal-dialog modal-dialog-centered"
     role="document">

        <div class="modal-content airnav-user-modal">

            <div class="modal-header airnav-user-modal-header">

                <div>

                    <h5 class="modal-title">
                        <i class="fas fa-user-plus"></i>
                        Tambah User
                    </h5>

                    <p>
                        Buat akun pengguna baru untuk sistem AirNav DMS
                    </p>

                </div>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <form method="POST"
                  action="{{ route('users.store') }}">

                @csrf

                <div class="modal-body airnav-user-modal-body">

                    <div class="airnav-user-form-grid">

                        <div class="form-group">

                            <label>
                                Nama
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                placeholder="Masukkan username"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Masukkan email"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Minimal 6 karakter"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Role
                            </label>

                            <select name="role"
                                    class="form-control">

                                <option value="teknisi">
                                    Teknisi
                                </option>

                                <option value="pegawai">
                                    Pegawai
                                </option>

                                <option value="admin">
                                    Admin
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Hubungkan Teknisi
                            </label>

                            <select name="technician_id"
                                    class="form-control">

                                <option value="">
                                    -- Pilih Teknisi --
                                </option>

                                @foreach($technicians as $technician)

                                    <option value="{{ $technician->id }}">
                                        {{ $technician->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                <div class="modal-footer airnav-user-modal-footer">

                    <button type="button"
                            class="user-modal-cancel"
                            data-dismiss="modal">

                        Batal

                    </button>


                    <button type="submit"
                            class="user-modal-save">

                        <i class="fas fa-save"></i>

                        Simpan User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
{{-- MODAL EDIT USER --}}
@foreach($users as $editUser)

<div class="modal fade"
     id="modalEditUser{{ $editUser->id }}"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         role="document">

        <div class="modal-content airnav-user-modal">

            <div class="modal-header airnav-user-modal-header">

                <div>

                    <h5 class="modal-title">
                        <i class="fas fa-user-edit"></i>
                        Edit User
                    </h5>

                    <p>
                        Perbarui informasi akun pengguna AirNav DMS
                    </p>

                </div>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <form action="{{ route('users.update', $editUser->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="modal-body airnav-user-modal-body">

                    <div class="airnav-user-form-grid">

                        <div class="form-group">

                            <label>
                                Nama
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ $editUser->name }}"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                value="{{ $editUser->username }}"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ $editUser->email }}"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Password Baru
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Kosongkan jika tidak mengganti">

                        </div>


                        <div class="form-group">

                            <label>
                                Role
                            </label>

                            <select name="role"
                                    class="form-control"
                                    required>

                                <option value="admin"
                                    {{ $editUser->role == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                                <option value="teknisi"
                                    {{ $editUser->role == 'teknisi' ? 'selected' : '' }}>
                                    Teknisi
                                </option>

                                <option value="pegawai"
                                    {{ $editUser->role == 'pegawai' ? 'selected' : '' }}>
                                    Pegawai
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Teknisi
                            </label>

                            <select name="technician_id"
                                    class="form-control">

                                <option value="">
                                    -- Tidak Ada Teknisi --
                                </option>

                                @foreach($technicians as $technician)

                                    <option value="{{ $technician->id }}"
                                        {{ $editUser->technician_id == $technician->id ? 'selected' : '' }}>

                                        {{ $technician->name }}

                                    </option>

                                @endforeach

                            </select>

                            @if($technicians->count() == 0)

                                <small class="text-danger">
                                    Belum ada teknisi aktif.
                                </small>

                            @endif

                        </div>

                    </div>

                </div>


                <div class="modal-footer airnav-user-modal-footer">

                    <button type="button"
                            class="user-modal-cancel"
                            data-dismiss="modal">

                        Batal

                    </button>


                    <button type="submit"
                            class="user-modal-save">

                        <i class="fas fa-save"></i>
                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach
@endsection