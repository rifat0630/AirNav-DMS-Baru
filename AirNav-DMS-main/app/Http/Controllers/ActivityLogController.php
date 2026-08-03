<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar aktivitas.
     */
    public function index()
    {
        $logs = ActivityLog::with(['user', 'document'])
            ->latest()
            ->paginate(20);

        return view('activity_logs.index', compact('logs'));
    }
}