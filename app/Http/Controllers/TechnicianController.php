<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Menampilkan daftar teknisi.
     *
     * Sementara masih dipertahankan agar halaman lama
     * tidak langsung error.
     */
    public function index()
    {
        $technicians = Technician::orderBy('name')->get();

        return view(
            'technicians.index',
            compact('technicians')
        );
    }


    /**
     * Form tambah teknisi.
     *
     * Sementara masih dipertahankan.
     * Pembuatan teknisi baru nantinya dilakukan
     * melalui Data User.
     */
    public function create()
    {
        return view('technicians.create');
    }


    /**
     * Menyimpan teknisi baru.
     *
     * Sementara masih dipertahankan untuk kompatibilitas
     * dengan route lama.
     *
     * Untuk alur utama, teknisi baru dibuat melalui
     * UserController.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $technician = Technician::create([
            'name' => $request->name,
            'qr_token' => \Illuminate\Support\Str::uuid()->toString(),
            'status' => 'aktif',
        ]);

        $this->generateQrCode($technician);

        return redirect()
            ->route('technicians.index')
            ->with(
                'success',
                'Teknisi berhasil ditambahkan dan QR TTD berhasil dibuat.'
            );
    }


    /**
     * Update status teknisi.
     *
     * Status yang diperbolehkan hanya:
     * - aktif
     * - mutasi
     */
    public function update(
        Request $request,
        Technician $technician
    ) {
        $request->validate([
            'status' => [
                'required',
                'in:aktif,mutasi',
            ],
        ]);

        $technician->update([
            'status' => $request->status,
        ]);

        return back()
            ->with(
                'success',
                'Status teknisi berhasil diperbarui.'
            );
    }


    /**
     * Hapus teknisi.
     *
     * Untuk sementara tetap dipertahankan karena
     * route lama masih tersedia.
     */
    public function destroy(
        Technician $technician
    ) {
        $technician->delete();

        return back()
            ->with(
                'success',
                'Teknisi berhasil dihapus.'
            );
    }


    /**
     * Membuat QR TTD teknisi.
     */
    private function generateQrCode(
        Technician $technician
    ): void {
        $qrDirectory = public_path(
            'qrcodes/technicians'
        );

        /*
         * Buat folder jika belum tersedia.
         */
        if (!file_exists($qrDirectory)) {
            mkdir(
                $qrDirectory,
                0755,
                true
            );
        }

        /*
         * Nama file QR.
         */
        $qrFile = $qrDirectory .
            '/' .
            $technician->qr_token .
            '.svg';

        /*
         * Generate QR TTD.
         */
        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(300)
            ->generate(
                'TECHNICIAN-TTD:' . $technician->qr_token,
                $qrFile
            );
    }
}