<?php

namespace App\Http\Controllers;

use App\Models\FacilityLogbook;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FacilityLogbookController extends Controller
{
    /**
     * Tampilkan daftar logbook
     */
    public function index(Request $request)
    {
        $query = FacilityLogbook::with([
            'technician',
            'user'
        ])
        ->latest('log_datetime');

        if ($request->search) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'action_notes',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'technician',
                    function ($tech) use ($search) {

                        $tech->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            });
        }

        $logbooks = $query->get();

        return view(
            'logbooks.index',
            compact('logbooks')
        );
    }


    /**
     * Form tambah logbook
     */
    public function create()
    {
        $technicians = Technician::where(
            'status',
            'aktif'
        )
        ->orderBy('name')
        ->get();

        return view(
            'logbooks.create',
            compact('technicians')
        );
    }

    /**
 * Form Edit Logbook
 */
public function edit($id)
{
    $logbook = FacilityLogbook::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Admin boleh edit semua
    |--------------------------------------------------------------------------
    */

    if (Auth::user()->role !== 'admin') {

        /*
        |--------------------------------------------------------------------------
        | User biasa hanya boleh edit logbook miliknya
        |--------------------------------------------------------------------------
        */

        if ($logbook->user_id !== Auth::id()) {

            abort(
                403,
                'Anda tidak memiliki izin untuk mengedit logbook ini.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil teknisi aktif
    |--------------------------------------------------------------------------
    */

    $technicians = Technician::where(
        'status',
        'aktif'
    )
    ->orderBy('name')
    ->get();

    return view(
        'logbooks.edit',
        compact(
            'logbook',
            'technicians'
        )
    );
}


/**
 * Update Logbook
 */
public function update(
    Request $request,
    $id
) {

    $logbook = FacilityLogbook::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Cek hak akses
    |--------------------------------------------------------------------------
    */

    if (Auth::user()->role !== 'admin') {

        if ($logbook->user_id !== Auth::id()) {

            abort(
                403,
                'Anda tidak memiliki izin untuk mengubah logbook ini.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'technician_id'
            => 'required|exists:technicians,id',

        'action_notes'
            => 'required|string',

    ]);

    /*
    |--------------------------------------------------------------------------
    | Ambil teknisi
    |--------------------------------------------------------------------------
    */

    $technician = Technician::findOrFail(
        $request->technician_id
    );

    /*
    |--------------------------------------------------------------------------
    | Update DATA LOGBOOK
    |
    | QR TOKEN TIDAK DIUBAH
    | STATUS TTD TIDAK DIUBAH
    |--------------------------------------------------------------------------
    */

    $logbook->update([

        'technician_id'
            => $technician->id,

        'technicians'
            => $technician->name,

        'action_notes'
            => $request->action_notes,

    ]);

    return redirect()
        ->route('logbook.index')
        ->with(
            'success',
            'Logbook berhasil diperbarui'
        );
}

    /**
     * Simpan logbook
     *
     * QR berisi URL halaman tanda tangan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'action_notes'  => 'required|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Ambil teknisi
        |--------------------------------------------------------------------------
        */

        $technician = Technician::findOrFail(
            $request->technician_id
        );


        /*
        |--------------------------------------------------------------------------
        | Token unik QR
        |--------------------------------------------------------------------------
        */

        $qrToken = Str::uuid()->toString();


        /*
        |--------------------------------------------------------------------------
        | Simpan logbook
        |--------------------------------------------------------------------------
        */

        $logbook = FacilityLogbook::create([

            'log_datetime' => now(),

            'technician_id' => $technician->id,

            'technicians' => $technician->name,

            'action_notes' => $request->action_notes,

            'qr_token' => $qrToken,

            'signature_status' => 'belum',

            'user_id' => Auth::id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Folder QR
        |--------------------------------------------------------------------------
        */

        if (!file_exists(public_path('qrcodes'))) {

            mkdir(
                public_path('qrcodes'),
                0755,
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | URL untuk HP
        |--------------------------------------------------------------------------
        |
        | Menggunakan APP_URL dari .env
        |
        */

        $signUrl =
            rtrim(config('app.url'), '/')
            . '/logbook/sign/'
            . $qrToken;


        /*
        |--------------------------------------------------------------------------
        | Nama file QR
        |--------------------------------------------------------------------------
        */

        $qrName = $qrToken . '.svg';


        /*
        |--------------------------------------------------------------------------
        | Generate QR
        |--------------------------------------------------------------------------
        */

        QrCode::format('svg')
            ->size(300)
            ->generate(
                $signUrl,
                public_path(
                    'qrcodes/' . $qrName
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Simpan nama file QR
        |--------------------------------------------------------------------------
        |
        | Database menyimpan UUID.svg
        |
        */

        $logbook->update([
            'qr_token' => $qrName,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke logbook
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('logbook.index')
            ->with(
                'success',
                'Logbook berhasil dibuat'
            );
    }


    /**
     * Halaman Scan QR
     *
     * Komputer hanya menampilkan QR besar.
     */
    public function scan($id)
    {
        $logbook = FacilityLogbook::findOrFail($id);

        return view(
            'logbooks.scan',
            compact('logbook')
        );
    }


    /**
     * Halaman tanda tangan dari HP
     *
     * QR mengirim UUID tanpa .svg.
     * Database menyimpan UUID.svg.
     *
     * Karena itu sistem mencari keduanya.
     */
    public function sign($token)
    {
        /*
        |--------------------------------------------------------------------------
        | Hilangkan .svg jika ada
        |--------------------------------------------------------------------------
        */

        $qrToken = pathinfo(
            $token,
            PATHINFO_FILENAME
        );


        /*
        |--------------------------------------------------------------------------
        | Cari logbook
        |--------------------------------------------------------------------------
        */

        $logbook = FacilityLogbook::where(
            'qr_token',
            $token
        )
        ->orWhere(
            'qr_token',
            $qrToken . '.svg'
        )
        ->with('technician')
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Tampilkan halaman tanda tangan
        |--------------------------------------------------------------------------
        */

        return view(
            'logbooks.sign',
            compact('logbook')
        );
    }


    /**
     * Konfirmasi tanda tangan dari HP
     */
    public function signConfirm($token)
    {
        /*
        |--------------------------------------------------------------------------
        | Hilangkan .svg jika ada
        |--------------------------------------------------------------------------
        */

        $qrToken = pathinfo(
            $token,
            PATHINFO_FILENAME
        );


        /*
        |--------------------------------------------------------------------------
        | Cari logbook
        |--------------------------------------------------------------------------
        */

        $logbook = FacilityLogbook::where(
            'qr_token',
            $token
        )
        ->orWhere(
            'qr_token',
            $qrToken . '.svg'
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Cek apakah sudah ditandatangani
        |--------------------------------------------------------------------------
        */

        if (
            $logbook->signature_status ===
            'terverifikasi'
        ) {

            return redirect()
                ->route(
                    'logbook.sign',
                    $token
                )
                ->with(
                    'success',
                    'Logbook ini sudah ditandatangani.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Tandai sebagai terverifikasi
        |--------------------------------------------------------------------------
        */

        $logbook->update([

            'signature_status' =>
                'terverifikasi',

            'signed_at' =>
                now(),

            /*
            | Tanda tangan dilakukan melalui QR
            | tanpa login akun.
            */

            'signed_by' =>
                null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke halaman HP
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'logbook.sign',
                $token
            )
            ->with(
                'success',
                'Tanda tangan berhasil. Logbook telah terverifikasi.'
            );
    }


    /**
     * Verifikasi QR lama
     *
     * Dipertahankan supaya fitur lama
     * tidak rusak.
     */
    public function verify(
        Request $request,
        $id
    ) {
        $logbook =
            FacilityLogbook::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Cek QR
        |--------------------------------------------------------------------------
        */

        $requestToken = $request->qr_token;

        $databaseToken = $logbook->qr_token;


        /*
        | Bandingkan dengan token database
        | baik dengan maupun tanpa .svg.
        */

        if (
            $requestToken !== $databaseToken
            &&
            $requestToken . '.svg' !== $databaseToken
        ) {

            return response()->json([

                'success' => false,

                'message' =>
                    'QR tidak sesuai'

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Verifikasi
        |--------------------------------------------------------------------------
        */

        $logbook->update([

            'signature_status' =>
                'terverifikasi',

            'signed_at' =>
                now(),

            'signed_by' =>
                Auth::id()

        ]);


        return response()->json([

            'success' => true,

            'message' =>
                'QR berhasil diverifikasi'

        ]);
    }


    /**
     * Hapus logbook
     */
    public function destroy($id)
{
    $logbook = FacilityLogbook::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Admin boleh menghapus semua logbook
    |--------------------------------------------------------------------------
    */

    if (Auth::user()->role === 'admin') {

        $logbook->delete();

        return back()
            ->with(
                'success',
                'Logbook berhasil dihapus'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | User biasa hanya boleh menghapus
    | logbook yang dibuat oleh dirinya sendiri
    |--------------------------------------------------------------------------
    */

    if ($logbook->user_id !== Auth::id()) {

        abort(
            403,
            'Anda tidak memiliki izin untuk menghapus logbook ini.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Hapus logbook milik sendiri
    |--------------------------------------------------------------------------
    */

    $logbook->delete();


    return back()
        ->with(
            'success',
            'Logbook berhasil dihapus'
        );
}
}