<?php

namespace App\Http\Controllers;

use App\Models\FacilityLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityLogbookController extends Controller
{
    public function index()
    {
        $logbooks = FacilityLogbook::with('user')->latest('log_datetime')->get();
        return view('logbook.index', compact('logbooks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'log_datetime' => 'required|date',
            'action_notes' => 'required|string',
            'technicians'  => 'required|string|max:255',
        ]);

        FacilityLogbook::create([
            'log_datetime' => $request->log_datetime,
            'action_notes' => $request->action_notes,
            'technicians'  => $request->technicians,
            'user_id'      => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Catatan logbook berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $logbook = FacilityLogbook::findOrFail($id);
        $logbook->delete();

        return redirect()->back()->with('success', 'Catatan logbook berhasil dihapus!');
    }
}