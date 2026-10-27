<?php

namespace App\Http\Controllers;

use App\Models\FacilityLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityLogbookController extends Controller
{
    public function index()
    {
        $logbooks = FacilityLogbook::with('user')->latest('created_at')->get();
        return view('logbook.index', compact('logbooks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_terjadi' => 'required|date',
            'tanggal_dibuat'  => 'required|date',
            'waktu_selesai'   => 'required|date',
            'technician_id'   => 'required|exists:users,id', // atau disesuaikan dengan relasi teknisi kamu
            'action_notes'    => 'required|string',
        ]);

        FacilityLogbook::create([
            'tanggal_terjadi' => $request->tanggal_terjadi,
            'tanggal_dibuat'  => $request->tanggal_dibuat,
            'waktu_selesai'   => $request->waktu_selesai,
            'technician_id'   => $request->technician_id,
            'action_notes'    => $request->action_notes,
            'user_id'         => Auth::id(),
        ]);

        return redirect()->route('logbook.index')->with('success', 'Catatan logbook berhasil ditambahkan!');
    }

    // Fungsi tambahan untuk menangani paraf Teknisi atau Mantek
    public function confirmSign(Request $request, $id)
    {
        $logbook = FacilityLogbook::findOrFail($id);
        $userName = Auth::user()->name;

        if ($request->action_type === 'teknisi') {
            $logbook->update([
                'paraf_teknisi' => $userName
            ]);
            $pesan = 'Paraf teknisi berhasil disimpan!';
        } elseif ($request->action_type === 'mantek') {
            $logbook->update([
                'paraf_mantek' => $userName
            ]);
            $pesan = 'Paraf mantek berhasil disimpan!';
        } else {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function destroy($id)
    {
        $logbook = FacilityLogbook::findOrFail($id);
        $logbook->delete();

        return redirect()->back()->with('success', 'Catatan logbook berhasil dihapus!');
    }
}