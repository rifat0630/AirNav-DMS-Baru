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
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'action_notes'  => 'required'
        ]);

        $technician = Technician::findOrFail($request->technician_id);

        // Generate QR Token
        $token = (string) Str::uuid();

        // Save QR SVG
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
            'status'                => 'Unverified',
        ]);

        return redirect()
            ->route('logbook.index')
            ->with('success', 'Logbook entry created successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | SIGN / SCAN PAGE
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
    | STORE SIGN VIA AJAX
    |--------------------------------------------------------------------------
    */
    public function storeSign(Request $request, $id)
    {
        $parafData  = $request->input('paraf_data') ?? $request->input('signature');
        $actionType = strtolower(trim($request->input('action_type') ?? $request->input('role') ?? ''));

        if (empty($parafData)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Signature canvas cannot be empty!'
            ], 422);
        }

        $logbook = FacilityLogbook::where('id', $id)
            ->orWhere('qr_token', $id)
            ->firstOrFail();

        $userName = Auth::user()->name ?? 'User';
        $now = now();

        if ($actionType === 'teknisi') {
            $logbook->paraf_teknisi = $parafData;
            $logbook->paraf_teknisi_name = $userName;
            $logbook->paraf_teknisi_at = $now;
        } else if ($actionType === 'mantek') {
            $logbook->paraf_mantek = $parafData;
            $logbook->paraf_mantek_name = $userName;
            $logbook->paraf_mantek_at = $now;
        }

        // Check verification status
        if (!empty($logbook->paraf_teknisi) && !empty($logbook->paraf_mantek)) {
            $logbook->status = 'Verified';
            $logbook->signature_status = 'terverifikasi';
        } else {
            $logbook->status = 'Partially Verified';
            $logbook->signature_status = 'sebagian';
        }

        $logbook->signed_at = $now;
        $logbook->signed_by = Auth::id();
        $logbook->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Initial saved successfully!'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MANUAL CONFIRMATION
    |--------------------------------------------------------------------------
    */
    public function signConfirm($token)
    {
        $logbook = FacilityLogbook::where('qr_token', $token)
            ->orWhere('id', $token)
            ->firstOrFail();

        $user = Auth::user();

        if ($user->role != 'teknisi') {
            return back()->with('error', 'Account is not a technician');
        }

        $logbook->update([
            'signature_status' => 'terverifikasi',
            'status'           => 'Verified',
            'signed_at'        => now(),
            'signed_by'        => $user->id
        ]);

        return redirect()
            ->route('logbook.sign', $token)
            ->with('success', 'Logbook verified successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | SCAN FROM ADMIN
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
            ->with('success', 'Logbook updated successfully');
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

        return back()->with('success', 'Logbook deleted successfully');
    }
}