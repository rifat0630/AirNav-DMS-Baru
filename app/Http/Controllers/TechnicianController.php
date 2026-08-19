<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TechnicianController extends Controller
{
    /**
     * Daftar Teknisi
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
     * Form Tambah Teknisi
     */
    public function create()
    {
        return view(
            'technicians.create'
        );
    }


    /**
     * Simpan Teknisi
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Buat QR TTD unik untuk teknisi
        |--------------------------------------------------------------------------
        */

        $qrToken = Str::uuid()->toString();


        /*
        |--------------------------------------------------------------------------
        | Simpan teknisi
        |--------------------------------------------------------------------------
        */

        $technician = Technician::create([

            'name' => $request->name,

            'qr_token' => $qrToken,

            'status' => 'aktif',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Folder QR Teknisi
        |--------------------------------------------------------------------------
        */

        if (!file_exists(public_path('qrcodes/technicians'))) {

            mkdir(
                public_path('qrcodes/technicians'),
                0755,
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Nama file QR
        |--------------------------------------------------------------------------
        */

        $qrName = $qrToken . '.svg';


        /*
        |--------------------------------------------------------------------------
        | Isi QR
        |
        | QR ini adalah identitas TTD teknisi.
        |--------------------------------------------------------------------------
        */

        QrCode::format('svg')
            ->size(300)
            ->generate(
                'TECHNICIAN-TTD:' . $qrToken,
                public_path(
                    'qrcodes/technicians/' . $qrName
                )
            );


        return redirect()
            ->route('technicians.index')
            ->with(
                'success',
                'Teknisi berhasil ditambahkan dan QR TTD berhasil dibuat.'
            );
    }


    /**
     * Update status teknisi
     */
    public function update(
        Request $request,
        Technician $technician
    ) {
        $request->validate([
            'status' => 'required|in:aktif,mutasi,tidak_aktif',
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
     * Hapus teknisi
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
}