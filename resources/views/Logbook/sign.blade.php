@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white fw-bold py-3">
                    <h5 class="m-0"><i class="fas fa-check-circle me-2"></i>Verifikasi Logbook Facility</h5>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info py-2">
                        <i class="fas fa-user me-1"></i> Login sebagai: <strong>{{ Auth::user()->name ?? 'User' }}</strong>
                    </div>

                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th style="width: 30%;" class="bg-light">Teknisi</th>
                                <td>{{ $logbook->technician->name ?? $logbook->technicians ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Tanggal Terjadi</th>
                                <td>{{ $logbook->tanggal_kejadian ? \Carbon\Carbon::parse($logbook->tanggal_kejadian)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Kegiatan</th>
                                <td>{{ $logbook->action_notes ?? '-' }}</td>
                            </tr>
                            
                            <!-- Status Paraf Teknisi -->
                            <tr>
                                <th class="bg-light">Paraf Teknisi</th>
                                <td>
                                    @if(!empty($logbook->paraf_teknisi))
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge bg-success py-2 px-3 fs-6">
                                                <i class="fas fa-check-circle me-1"></i> Telah Diparaf
                                            </span>
                                            <small class="text-muted mt-1">
                                                <i class="fas fa-user me-1"></i>{{ $logbook->paraf_teknisi_name ?? 'Teknisi' }} 
                                                <i class="fas fa-clock ms-2 me-1"></i>{{ $logbook->paraf_teknisi_at ? \Carbon\Carbon::parse($logbook->paraf_teknisi_at)->format('d/m/Y H:i:s') : ($logbook->signed_at ? \Carbon\Carbon::parse($logbook->signed_at)->format('d/m/Y H:i:s') : '-') }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary py-2 px-3 fs-6">Belum diparaf</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Status Paraf Mantek -->
                            <tr>
                                <th class="bg-light">Paraf Mantek</th>
                                <td>
                                    @if(!empty($logbook->paraf_mantek))
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge bg-success py-2 px-3 fs-6">
                                                <i class="fas fa-check-circle me-1"></i> Telah Diparaf
                                            </span>
                                            <small class="text-muted mt-1">
                                                <i class="fas fa-user me-1"></i>{{ $logbook->paraf_mantek_name ?? 'Mantek' }} 
                                                <i class="fas fa-clock ms-2 me-1"></i>{{ $logbook->paraf_mantek_at ? \Carbon\Carbon::parse($logbook->paraf_mantek_at)->format('d/m/Y H:i:s') : ($logbook->signed_at ? \Carbon\Carbon::parse($logbook->signed_at)->format('d/m/Y H:i:s') : '-') }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary py-2 px-3 fs-6">Belum diparaf</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Tombol Aksi Paraf -->
                    <div class="d-flex gap-2 mt-3">
                        @if(empty($logbook->paraf_teknisi))
                            <button type="button" class="btn btn-primary" onclick="bukaModalTtd('teknisi')">
                                <i class="fas fa-signature me-1"></i>Paraf Sebagai Teknisi
                            </button>
                        @endif

                        @if(empty($logbook->paraf_mantek))
                            <button type="button" class="btn btn-success" onclick="bukaModalTtd('mantek')">
                                <i class="fas fa-signature me-1"></i>Paraf Sebagai Mantek
                            </button>
                        @endif

                        <a href="{{ route('logbook.index') }}" class="btn btn-outline-secondary ms-auto">
                            Kembali ke Logbook
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL CANVAS TANDA TANGAN -->
<div class="modal fade" id="modalTtd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTtdTitle">Area Tanda Tangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body text-center">
                <p class="text-muted small mb-2">Goreskan tanda tangan/paraf Anda pada kotak di bawah ini:</p>
                
                <div class="border rounded p-1 bg-light d-inline-block w-100">
                    <canvas id="canvasModal" width="400" height="180" style="border: 1px dashed #ccc; background: #fff; cursor: crosshair; width: 100%;"></canvas>
                </div>

                <div class="mt-2 text-start">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clearModalCanvas">
                        <i class="fas fa-eraser me-1"></i>Hapus Paraf
                    </button>
                </div>

                <input type="hidden" id="actionTypeInput">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpan" onclick="simpanParafAjax()">
                    <i class="fas fa-save me-1"></i>Simpan & Verifikasi
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePadModal;
    let modalElement;
    const logbookIdentifier = "{{ $logbook->qr_token ?? $logbook->id }}";
    const csrfToken = "{{ csrf_token() }}";

    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('canvasModal');
        signaturePadModal = new SignaturePad(canvas);
        
        document.getElementById('clearModalCanvas').addEventListener('click', function() {
            signaturePadModal.clear();
        });

        modalElement = new bootstrap.Modal(document.getElementById('modalTtd'));
    });

    function bukaModalTtd(role) {
        document.getElementById('actionTypeInput').value = role;
        
        if (role === 'teknisi') {
            document.getElementById('modalTtdTitle').innerText = 'Tanda Tangan - Paraf Teknisi';
        } else {
            document.getElementById('modalTtdTitle').innerText = 'Tanda Tangan - Paraf Mantek';
        }

        signaturePadModal.clear();
        modalElement.show();

        setTimeout(() => {
            const canvas = document.getElementById('canvasModal');
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePadModal.clear();
        }, 300);
    }

    function simpanParafAjax() {
        if (signaturePadModal.isEmpty()) {
            alert('Silakan goreskan tanda tangan pada area canvas terlebih dahulu!');
            return;
        }

        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

        const parafData = signaturePadModal.toDataURL('image/png');
        const actionType = document.getElementById('actionTypeInput').value;

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('paraf_data', parafData);
        formData.append('action_type', actionType);

        fetch('/logbook/store-sign/' + logbookIdentifier, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.reload();
        });
    }
</script>
@endsection