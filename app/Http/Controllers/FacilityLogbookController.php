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
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = FacilityLogbook::with([
            'technician',
            'user'
        ]);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action_notes', 'like', "%$search%")
                  ->orWhere('technicians', 'like', "%$search%");
            });
        }

        $logbooks = $query->latest()->get();
        $technicians = Technician::where('status', 'aktif')->orderBy('name')->get();

        return view('logbook.index', compact('logbooks', 'technicians'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $technicians = Technician::where('status', 'aktif')->orderBy('name')->get();
        return view('logbook.create', compact('technicians'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE (TANGGAL & WAKTU OTOMATIS OLEH SISTEM)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'action_notes'  => 'required'
        ]);

        $technician = Technician::findOrFail($request->technician_id);

        // Buat token QR
        $token = (string) Str::uuid();

        // Simpan QR SVG
        $fileName = $token . '.svg';
        $path = public_path('qrcodes/' . $fileName);

        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        QrCode::format('svg')
            ->size(300)
            ->generate(route('logbook.sign', $token), $path);

        $nowTime = now();

        FacilityLogbook::create([
            'technician_id'         => $technician->id,
            'technicians'           => $technician->name,
            'log_datetime'          => $nowTime,
            'tanggal_kejadian'      => $nowTime->format('Y-m-d'),
            'tanggal_dibuat'        => $nowTime->format('Y-m-d'),
            'tanggal_waktu_selesai' => $nowTime->format('Y-m-d H:i:s'),
            'action_notes'          => $request->action_notes,
            'user_id'               => Auth::id(),
            'signature_type'        => 'qr',
            'signature_file'        => $fileName,
            'qr_token'              => $token,
            'signature_status'      => 'belum',
            'status'                => 'Belum Diverifikasi',
        ]);

        return redirect()
            ->route('logbook.index')
            ->with('success', 'Logbook berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN SCAN / SIGN (UNIVERSAL BERDASARKAN ID ATAU QR TOKEN)
    |--------------------------------------------------------------------------
    */
    public function sign($token)
    {
        $logbook = FacilityLogbook::where('qr_token', $token)
            ->orWhere('id', $token)
            ->firstOrFail();

        return view('logbook.sign', compact('logbook'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE SIGN (AJAX FETCH FORMDATA DARI MODAL CANVAS)
    |--------------------------------------------------------------------------
    */
    public function storeSign(Request $request, $id)
    {
        $parafData  = $request->input('paraf_data') ?? $request->input('signature');
        $actionType = strtolower(trim($request->input('action_type') ?? $request->input('role') ?? ''));

        if (empty($parafData)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Goresan tanda tangan tidak boleh kosong!'
            ], 422);
        }

        $logbook = FacilityLogbook::where('id', $id)
            ->orWhere('qr_token', $id)
            ->firstOrFail();

        $userName = Auth::user()->name ?? 'User';
        $now = now(); // Ambil waktu & tanggal saat ini

        if ($actionType === 'teknisi') {
            $logbook->paraf_teknisi = $parafData;
            $logbook->paraf_teknisi_name = $userName;
            $logbook->paraf_teknisi_at = $now; // Simpan waktu paraf teknisi
        } else if ($actionType === 'mantek') {
            $logbook->paraf_mantek = $parafData;
            $logbook->paraf_mantek_name = $userName;
            $logbook->paraf_mantek_at = $now; // Simpan waktu paraf mantek
        }

        // Cek status verifikasi logbook
        if (!empty($logbook->paraf_teknisi) && !empty($logbook->paraf_mantek)) {
            $logbook->status = 'Telah Diverifikasi';
            $logbook->signature_status = 'terverifikasi';
        } else {
            $logbook->status = 'Diverifikasi Sebagian';
            $logbook->signature_status = 'sebagian';
        }

        $logbook->signed_at = $now;
        $logbook->signed_by = Auth::id();
        $logbook->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Paraf berhasil disimpan!'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI TEKNISI MANUAL
    |--------------------------------------------------------------------------
    */
    public function signConfirm($token)
    {
        $logbook = FacilityLogbook::where('qr_token', $token)
            ->orWhere('id', $token)
            ->firstOrFail();

        $user = Auth::user();

        if ($user->role != 'teknisi') {
            return back()->with('error', 'Akun bukan teknisi');
        }

        $logbook->update([
            'signature_status' => 'terverifikasi',
            'status'           => 'Telah Diverifikasi',
            'signed_at'        => now(),
            'signed_by'        => $user->id
        ]);

        return redirect()
            ->route('logbook.sign', $token)
            ->with('success', 'Logbook berhasil diverifikasi');
    }

    /*
    |--------------------------------------------------------------------------
    | SCAN DARI ADMIN
    |--------------------------------------------------------------------------
    */
    public function scan($id)
    {
        $logbook = FacilityLogbook::where('id', $id)
            ->orWhere('qr_token', $id)
            ->firstOrFail();

        return redirect()->route('logbook.sign', $logbook->qr_token ?? $logbook->id);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $logbook = FacilityLogbook::findOrFail($id);
        $technicians = Technician::where('status', 'aktif')->get();

        return view('logbook.edit', compact('logbook', 'technicians'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $logbook = FacilityLogbook::findOrFail($id);

        $request->validate([
            'technician_id' => 'required',
            'action_notes'  => 'required'
        ]);

        $technician = Technician::find($request->technician_id);

        $logbook->update([
            'technician_id'         => $technician->id,
            'technicians'           => $technician->name,
            'tanggal_kejadian'      => $request->tanggal_kejadian ?? $logbook->tanggal_kejadian,
            'tanggal_dibuat'        => $request->tanggal_dibuat ?? $logbook->tanggal_dibuat,
            'tanggal_waktu_selesai' => $request->tanggal_waktu_selesai ?? $logbook->tanggal_waktu_selesai,
            'action_notes'          => $request->action_notes
        ]);

        return redirect()
            ->route('logbook.index')
            ->with('success', 'Logbook diperbarui');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $logbook = FacilityLogbook::findOrFail($id);

        if ($logbook->signature_file) {
            $file = public_path('qrcodes/' . $logbook->signature_file);
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $logbook->delete();

        return back()->with('success', 'Logbook dihapus');
    }
}