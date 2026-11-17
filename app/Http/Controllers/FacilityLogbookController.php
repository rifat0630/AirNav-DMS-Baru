<?php

namespace App\Http\Controllers;

use App\Models\FacilityLogbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityLogbookController extends Controller
{
    public function index()
    {
        $logbooks = FacilityLogbook::with(['teknisi', 'mantek'])
            ->orderByDesc('tanggal_terjadi')
            ->orderByDesc('created_at')
            ->get();

        $menungguParafCount = $logbooks->where('status', 'Menunggu Paraf Mantek')->count();
        $technicians = User::orderBy('name')->get();

        return view('logbook.index', compact('logbooks', 'menungguParafCount', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fasilitas_alat'   => 'required|string|max:255',
            'shift'            => 'required|in:Pagi,Siang,Malam',
            'uraian_pekerjaan' => 'required|string',
            'tanggal_terjadi'  => 'required|date',
            'tanggal_dibuat'   => 'required|date',
            'waktu_selesai'    => 'nullable|date',
            'paraf_teknisi'    => 'required|string',
        ], [
            'paraf_teknisi.required' => 'Silakan coret Live Paraf Teknisi terlebih dahulu sebelum menyimpan.',
        ]);

        FacilityLogbook::create([
            'user_id'          => $request->input('user_id', Auth::id()),
            'fasilitas_alat'   => $validated['fasilitas_alat'],
            'shift'            => $validated['shift'],
            'uraian_pekerjaan' => $validated['uraian_pekerjaan'],
            'tanggal_terjadi'  => $validated['tanggal_terjadi'],
            'tanggal_dibuat'   => $validated['tanggal_dibuat'],
            'waktu_selesai'    => $validated['waktu_selesai'],
            'paraf_teknisi'    => $validated['paraf_teknisi'],
            'paraf_teknisi_at' => now(),
            'status'           => 'Menunggu Paraf Mantek',
        ]);

        return redirect()->route('logbook.index')
            ->with('success', 'E-Logbook berhasil dicatat beserta Live Paraf Teknisi.');
    }

    public function reviewMantek(Request $request, $id)
    {
        $user = Auth::user();
        $role = strtolower($user->role ?? '');

        if (!in_array($role, ['manager teknik', 'manager_teknik', 'mantek', 'admin'])) {
            abort(403, 'Hanya Manager Teknik yang berwenang memberikan catatan dan paraf.');
        }

        $validated = $request->validate([
            'catatan_mantek' => 'nullable|string',
            'paraf_mantek'   => 'required|string',
        ], [
            'paraf_mantek.required' => 'Silakan coret Live Paraf Manager Teknik pada kotak tanda tangan.',
        ]);

        $logbook = FacilityLogbook::findOrFail($id);

        $logbook->update([
            'mantek_id'       => $user->id,
            'catatan_mantek'  => $validated['catatan_mantek'],
            'paraf_mantek'    => $validated['paraf_mantek'],
            'paraf_mantek_at' => now(),
            'status'          => 'Terverifikasi',
        ]);

        return redirect()->route('logbook.index')
            ->with('success', 'Catatan dan Live Paraf Manager Teknik berhasil disimpan.');
    }

    public function destroy($id)
    {
        $logbook = FacilityLogbook::findOrFail($id);
        $user = Auth::user();
        $role = strtolower($user->role ?? '');
        $isAdminOrMantek = in_array($role, ['manager teknik', 'manager_teknik', 'mantek', 'admin']);

        // Hanya pemilik catatan atau Admin/Mantek yang boleh menghapus
        if ((int) $logbook->user_id !== (int) $user->id && !$isAdminOrMantek) {
            abort(403, 'Anda tidak berhak menghapus catatan E-Logbook milik teknisi lain.');
        }

        $logbook->delete();

        return redirect()->route('logbook.index')
            ->with('success', 'Catatan E-Logbook berhasil dihapus.');
    }
}