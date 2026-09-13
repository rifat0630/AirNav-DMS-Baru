@extends('layouts.admin')

@section('title', 'Inventory Barang')


@section('content')

<div class="airnav-inventory-header">

    <div class="airnav-inventory-title">

        <div class="airnav-inventory-icon">
            <i class="fas fa-box"></i>
        </div>

        <div>
            <h1>Inventory Barang</h1>

            <p>
                Sistem Pemantauan Aset Fasilitas Telekomunikasi, Navigasi & Pengamatan Udara
            </p>
        </div>

    </div>

  <button
    type="button"
    class="airnav-inventory-add"
    id="btnTambahBarang"
>
    <i class="fas fa-plus"></i>
    <span>Tambah Barang</span>
</button>

</div>

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    <i class="fas fa-check-circle"></i>

    {{ session('success') }}

    <button
        type="button"
        class="close"
        data-dismiss="alert"
    >

        <span>&times;</span>

    </button>

</div>

@endif


@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">

    <i class="fas fa-exclamation-circle"></i>

    {{ session('error') }}

    <button
        type="button"
        class="close"
        data-dismiss="alert"
    >

        <span>&times;</span>

    </button>

</div>

@endif



<div class="card airnav-inventory-card">

    <div class="airnav-inventory-card-header">
        <div class="airnav-inventory-card-title">
            <i class="fas fa-database"></i>
            <span>Data Barang</span>
        </div>
    </div>

    <div class="airnav-inventory-search-wrapper">

        <form
            method="GET"
            action="{{ route('inventory.index') }}"
            class="airnav-inventory-search"
        >

            <div class="airnav-inventory-search-input">
                <i class="fas fa-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama barang, kode barang, atau serial number..."
                >
            </div>

            <button
                type="submit"
                class="airnav-inventory-search-button"
            >
                <i class="fas fa-search"></i>
                <span>Cari</span>
            </button>

        </form>

    </div>

        {{-- TABLE --}}

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="thead-light">

                    <tr>

                        <th width="50">
                            No
                        </th>

                        <th width="180">
                            Foto
                        </th>

                        <th>
                            Nama Barang
                        </th>

                        <th width="100">
                            Kode
                        </th>

                        <th width="220">
                            Serial Number
                        </th>

                        <th width="80">
                            Stok
                        </th>

                        <th width="80">
                            Satuan
                        </th>

                        <th width="130">
                            Kondisi Barang
                        </th>

                        <th width="200">
                            Dibuat Oleh
                        </th>

                        <th width="190">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                @forelse($inventories as $inventory)

                    <tr>

                        {{-- NO --}}

                        <td>

                            {{ $inventories->firstItem() + $loop->index }}

                        </td>



                        {{-- FOTO --}}

                        <td>

                            @if(
                                !empty($inventory->photos)
                                &&
                                count($inventory->photos) > 0
                            )

                                <div class="d-flex flex-wrap">

                                    @foreach(
                                        array_slice(
                                            $inventory->photos,
                                            0,
                                            3
                                        )
                                        as $photo
                                    )

                                        <a
                                            href="{{ asset('storage/' . $photo) }}"
                                            target="_blank"
                                        >

                                            <img
                                                src="{{ asset('storage/' . $photo) }}"
                                                class="img-thumbnail mr-1 mb-1"
                                                style="
                                                    width: 52px;
                                                    height: 52px;
                                                    object-fit: cover;
                                                "
                                            >

                                        </a>

                                    @endforeach

                                </div>

                            @else

                                <div
                                    class="text-muted text-center"
                                >

                                    <i
                                        class="fas fa-image fa-2x"
                                    ></i>

                                    <br>

                                    <small>
                                        Tidak ada foto
                                    </small>

                                </div>

                            @endif

                        </td>



                        {{-- NAMA --}}

                        <td>

                            <strong>
                                {{ $inventory->name }}
                            </strong>

                        </td>



                        {{-- KODE --}}

                        <td>

                            {{ $inventory->code }}

                        </td>



                        {{-- SERIAL NUMBER --}}

                        <td>

                            @if(
                                !empty($inventory->serial_numbers)
                            )

                                <ul
                                    class="mb-0 pl-3"
                                >

                                    @foreach(
                                        $inventory->serial_numbers
                                        as $serial
                                    )

                                        <li>
                                            {{ $serial }}
                                        </li>

                                    @endforeach

                                </ul>

                            @else

                                <span
                                    class="text-muted"
                                >
                                    -
                                </span>

                            @endif

                        </td>



                        {{-- STOK --}}

                        <td>

                            <strong>
                                {{ $inventory->stock }}
                            </strong>

                        </td>



                        {{-- SATUAN --}}

                        <td>

                            {{ $inventory->unit }}

                        </td>



                        {{-- KONDISI --}}

                        <td>

                            @if(
                                strtolower(
                                    $inventory->condition
                                )
                                === 'rusak'
                            )

                                <span
                                    class="badge badge-warning"
                                >

                                    <i
                                        class="fas fa-exclamation-triangle"
                                    ></i>

                                    Rusak

                                </span>

                            @else

                                <span
                                    class="badge badge-success"
                                >

                                    <i
                                        class="fas fa-check-circle"
                                    ></i>

                                    Normal

                                </span>

                            @endif

                        </td>



                        {{-- USER --}}

                        <td>

                            @if($inventory->user)

                                {{ $inventory->user->name }}

                            @else

                                <span
                                    class="text-muted"
                                >
                                    -
                                </span>

                            @endif

                        </td>



                        {{-- AKSI --}}

                        <td>

                            <div class="d-flex flex-wrap">


                                {{-- DETAIL --}}

                              <button
    type="button"
    class="btn btn-info btn-sm mr-1 mb-1"
    data-toggle="modal"
    data-target="#modalDetailBarang{{ $inventory->id }}"
>
    <i class="fas fa-eye"></i>
    Detail
</button>



                                {{-- EDIT --}}

                          <button
    type="button"
    class="btn btn-primary btn-sm mr-1 mb-1 airnav-inventory-edit"
    data-toggle="modal"
    data-target="#modalEditBarang{{ $inventory->id }}"
>
    <i class="fas fa-edit"></i>
    Edit
</button>



                                {{-- HAPUS --}}

                                <form
                                    action="{{ route(
                                        'inventory.destroy',
                                        $inventory->id
                                    ) }}"
                                    method="POST"
                                    class="d-inline mb-1"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus barang ini?'
                                    );"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >

                                        <i class="fas fa-trash"></i>
                                        Hapus

                                    </button>

                                </form>


                            </div>

                        </td>


                    </tr>

                    {{-- MODAL DETAIL BARANG --}}
<div class="modal fade" id="modalDetailBarang{{ $inventory->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailBarangLabel{{ $inventory->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content airnav-inventory-modal">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modalDetailBarangLabel{{ $inventory->id }}">
                        <i class="fas fa-box text-primary mr-2"></i>Detail Barang
                    </h5>
                    <small class="text-muted">Informasi lengkap barang inventory</small>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body airnav-inventory-modal-body">
                <div class="form-group">
                    <label><i class="fas fa-images mr-1"></i> Foto Barang</label>
                    @if(!empty($inventory->photos) && count($inventory->photos) > 0)
                        <div class="row">
                            @foreach($inventory->photos as $photo)
                                <div class="col-md-4 col-6 mb-3">
                                    <img src="{{ asset('storage/' . $photo) }}" class="img-thumbnail" style="width:100%;height:180px;object-fit:cover;" alt="Foto {{ $inventory->name }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted"><i class="fas fa-image mr-1"></i>Tidak ada foto barang.</div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <div class="form-control bg-light">{{ $inventory->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <div class="form-control bg-light">{{ $inventory->code }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Stok</label>
                            <div class="form-control bg-light">{{ $inventory->stock }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Satuan</label>
                            <div class="form-control bg-light">{{ $inventory->unit }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Kondisi Barang</label>
                    <div>
                        @if(strtolower($inventory->condition) === 'rusak')
                            <span class="badge badge-warning p-2"><i class="fas fa-exclamation-triangle mr-1"></i>Rusak</span>
                        @else
                            <span class="badge badge-success p-2"><i class="fas fa-check-circle mr-1"></i>Normal</span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-barcode mr-1"></i> Serial Number</label>
                    @if(!empty($inventory->serial_numbers) && count($inventory->serial_numbers) > 0)
                        <div class="border rounded p-3 bg-light">
                            <ul class="mb-0 pl-3">
                                @foreach($inventory->serial_numbers as $serial)
                                    <li>{{ $serial }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-muted">-</div>
                    @endif
                </div>

                <div class="form-group">
                    <label><i class="fas fa-user mr-1"></i> Dibuat Oleh</label>
                    <div class="form-control bg-light">{{ $inventory->user->name ?? '-' }}</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
              {{-- MODAL EDIT BARANG --}}
<div class="modal fade" id="modalEditBarang{{ $inventory->id }}" tabindex="-1" role="dialog"
    aria-labelledby="modalEditBarangLabel{{ $inventory->id }}" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content airnav-inventory-modal">

            {{-- HEADER --}}
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modalEditBarangLabel{{ $inventory->id }}">
                        <i class="fas fa-edit text-primary mr-2"></i>
                        Edit Barang
                    </h5>
                    <small class="text-muted">Perbarui informasi barang inventory</small>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- FORM --}}
            <form action="{{ route('inventory.update', $inventory->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body airnav-inventory-modal-body">

                    {{-- NAMA & KODE --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $inventory->name }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control"
                                    value="{{ $inventory->code }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- SERIAL NUMBER --}}
                    <div class="form-group">
                        <label>Serial Number</label>

                        <div id="edit-serial-container-{{ $inventory->id }}">
                            @if(!empty($inventory->serial_numbers) && count($inventory->serial_numbers) > 0)

                                @foreach($inventory->serial_numbers as $serial)
                                    <div class="input-group mb-2 edit-serial-row">
                                        <input type="text" name="serial_numbers[]" class="form-control"
                                            value="{{ $serial }}" placeholder="Serial Number">

                                        <div class="input-group-append">
                                            <button type="button"
                                                class="btn btn-danger edit-remove-serial">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach

                            @else
                                <div class="input-group mb-2 edit-serial-row">
                                    <input type="text" name="serial_numbers[]" class="form-control"
                                        placeholder="Serial Number">

                                    <div class="input-group-append">
                                        <button type="button"
                                            class="btn btn-danger edit-remove-serial">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm"
                            onclick="addEditSerial({{ $inventory->id }})">
                            <i class="fas fa-plus"></i>
                            Tambah Serial Number
                        </button>

                        <small class="form-text text-muted">
                            Tambahkan serial number sesuai jumlah barang.
                        </small>
                    </div>

                    {{-- STOK & SATUAN --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control"
                                    value="{{ $inventory->stock }}" min="0" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="unit" class="form-control"
                                    value="{{ $inventory->unit }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- KONDISI --}}
                    <div class="form-group">
                        <label>Kondisi Barang <span class="text-danger">*</span></label>

                        <select name="condition" class="form-control" required>
                            <option value="normal"
                                {{ $inventory->condition === 'normal' ? 'selected' : '' }}>
                                Normal
                            </option>

                            <option value="rusak"
                                {{ $inventory->condition === 'rusak' ? 'selected' : '' }}>
                                Rusak
                            </option>
                        </select>
                    </div>

                    {{-- FOTO LAMA --}}
                    <div class="form-group">
                        <label>Foto Saat Ini</label>

                        @if(!empty($inventory->photos) && count($inventory->photos) > 0)

                            <div class="row">
                                @foreach($inventory->photos as $photo)
                                    <div class="col-md-4 col-6 mb-2">
                                        <img src="{{ asset('storage/' . $photo) }}"
                                            class="img-thumbnail"
                                            style="width:100%;height:130px;object-fit:cover;"
                                            alt="Foto {{ $inventory->name }}">
                                    </div>
                                @endforeach
                            </div>

                            <small class="text-muted">
                                Jika memilih foto baru, foto lama akan diganti.
                            </small>

                        @else
                            <div class="text-muted">
                                <i class="fas fa-image mr-1"></i>
                                Belum ada foto.
                            </div>
                        @endif
                    </div>

                    {{-- FOTO BARU --}}
                    <div class="form-group">
                        <label>Ganti Foto Barang</label>

                        <div class="custom-file">
                            <input type="file"
                                name="photos[]"
                                id="edit-photos-{{ $inventory->id }}"
                                class="custom-file-input"
                                accept="image/*"
                                multiple>

                            <label class="custom-file-label"
                                for="edit-photos-{{ $inventory->id }}">
                                Pilih foto baru
                            </label>
                        </div>

                        <small class="form-text text-muted">
                            Maksimal 3 foto. Jika tidak memilih foto baru, foto lama tetap digunakan.
                        </small>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
                @empty

                    <tr>

                        <td
                            colspan="10"
                            class="text-center text-muted py-4"
                        >

                            <i
                                class="fas fa-box-open fa-2x mb-2"
                            ></i>

                            <br>

                            Belum ada data barang.

                        </td>

                    </tr>

                @endforelse


                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        <div class="d-flex justify-content-between align-items-center mt-3">

            <div>

                Menampilkan

                {{ $inventories->firstItem() ?? 0 }}

                sampai

                {{ $inventories->lastItem() ?? 0 }}

                dari

                {{ $inventories->total() }}

                data

            </div>


            <div>

                {{ $inventories->links() }}

            </div>

        </div>


    </div>

</div>
{{-- MODAL TAMBAH BARANG --}}
<div
    class="modal fade"
    id="modalTambahBarang"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalTambahBarangLabel"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg modal-dialog-centered"
        role="document"
    >
        <div class="modal-content airnav-inventory-modal">

            {{-- HEADER MODAL --}}
            <div class="modal-header airnav-inventory-modal-header">

                <div class="airnav-inventory-modal-title">

                    <div class="airnav-inventory-modal-icon">
                        <i class="fas fa-box"></i>
                    </div>

                    <div>
                        <h5 id="modalTambahBarangLabel">
                            Tambah Barang
                        </h5>

                        <p>
                            Tambahkan data barang baru ke inventory
                        </p>
                    </div>

                </div>

                <button
                    type="button"
                    class="close airnav-inventory-modal-close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>


            {{-- ISI FORM --}}
            <div class="modal-body airnav-inventory-modal-body">

                @include('inventory._form')

            </div>

        </div>
    </div>
</div>


@stop

@section('js')
<script>
$(document).ready(function () {

    console.log('INVENTORY JS BERJALAN');


    // ==========================================
    // MODAL TAMBAH BARANG
    // ==========================================
    $('.airnav-inventory-add').on('click', function () {

        console.log('TOMBOL TAMBAH DIKLIK');

        $('#modalTambahBarang').modal('show');

    });


    // ==========================================
    // MODAL EDIT BARANG
    // ==========================================
    $('.airnav-inventory-edit').on('click', function () {

        var modalId = $(this).data('target');

        console.log('TOMBOL EDIT DIKLIK');
        console.log('TARGET MODAL:', modalId);

        if (modalId) {
            $(modalId).modal('show');
        }

    });


    // ==========================================
    // TUTUP MODAL
    // ==========================================
    $('.modal').on('hidden.bs.modal', function () {

        console.log('MODAL DITUTUP');

    });

});
</script>
@endsection