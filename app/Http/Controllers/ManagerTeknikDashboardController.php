<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Document;
use App\Models\Category;
use App\Models\MantekTask;

class ManagerTeknikDashboardController extends Controller
{
    // 1. DASHBOARD UTAMA
    public function dashboardUtama()
    {
        $totalDocs = class_exists(Document::class) ? Document::count() : 0;
        $totalTasks = class_exists(MantekTask::class) ? MantekTask::count() : 0;
        $totalTechs = class_exists(User::class) ? User::where('role', 'teknisi')->count() : 0;

        $latestDocs = class_exists(Document::class) ? Document::latest()->take(5)->get() : collect();
        $recentTasks = class_exists(MantekTask::class) ? MantekTask::with('technician')->latest()->take(5)->get() : collect();

        return view('dashboard_utama', compact('totalDocs', 'totalTasks', 'totalTechs', 'latestDocs', 'recentTasks'));
    }

    // 2. PANEL KHUSUS MANTEK
    public function index()
    {
        $technicians = class_exists(User::class) ? User::where('role', 'teknisi')->get() : collect();
        $latestDocuments = class_exists(Document::class) ? Document::latest()->take(10)->get() : collect();
        $tasks = class_exists(MantekTask::class) ? MantekTask::with('technician')->latest()->take(10)->get() : collect();
        $brokenItems = collect([
            (object)['name' => 'Glide Path (GP) Shelter', 'location' => 'RWY 10', 'condition' => 'Perlu Kalibrasi Ulang', 'status' => 'Pending'],
            (object)['name' => 'VHF Receiver Main', 'location' => 'Tower Aerodrome', 'condition' => 'Modul Power Supply Drop', 'status' => 'Proses'],
        ]);

        return view('manager-teknik.dashboard', compact('technicians', 'latestDocuments', 'tasks', 'brokenItems'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'assigned_to' => 'nullable',
            'target_date' => 'required|date',
            'instruction' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
            'action' => 'required|in:draft,publish'
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('mantek_tasks', 'public');
        }

        MantekTask::create([
            'title' => $request->title,
            'assigned_to' => $request->assigned_to ?: null,
            'target_date' => $request->target_date,
            'instruction' => $request->instruction,
            'attachment' => $filePath,
            'status_terbit' => $request->action === 'publish' ? 'Terbit' : 'Draf',
            'status_pengerjaan' => 'Belum Selesai',
        ]);

        return redirect()->back()->with('success', 'Instruksi kerja berhasil disimpan!');
    }

    public function destroyTask($id)
    {
        $task = MantekTask::findOrFail($id);
        if ($task->attachment && Storage::disk('public')->exists($task->attachment)) {
            Storage::disk('public')->delete($task->attachment);
        }
        $task->delete();
        return redirect()->back()->with('success', 'Instruksi kerja berhasil dihapus.');
    }

    // 3. ARSIP DOKUMEN & CRUD
    public function arsipDokumen(Request $request)
    {
        $query = Document::query();

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $cat = $request->category;
            $query->where(function($q) use ($cat) {
                $q->where('kategori', $cat)
                  ->orWhere('category_id', $cat);
            });
        }

        // Pencarian dokumen jika ada
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('peralatan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->paginate(15);
        $categories = class_exists(Category::class) 
            ? Category::orderBy('name', 'asc')->get() 
            : collect();

        return view('manager-teknik.arsip', compact('documents', 'categories'));
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_izin' => 'nullable|string|max:255',
            'tanggal_dokumen' => 'required|date',
            'kategori' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:20480',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        Document::create([
            'nama' => $request->nama,
            'judul' => $request->nama,
            'nomor_izin' => $request->nomor_izin,
            'no_izin' => $request->nomor_izin,
            'tanggal_dokumen' => $request->tanggal_dokumen,
            'tanggal' => $request->tanggal_dokumen,
            'kategori' => $request->kategori,
            'file_path' => $filePath,
            'file' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Dokumen baru berhasil diunggah!');
    }

    public function updateDocument(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_izin' => 'nullable|string|max:255',
            'tanggal_dokumen' => 'required|date',
            'kategori' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:20480',
        ]);

        $doc = Document::findOrFail($id);
        $data = [
            'nama' => $request->nama,
            'judul' => $request->nama,
            'nomor_izin' => $request->nomor_izin,
            'no_izin' => $request->nomor_izin,
            'tanggal_dokumen' => $request->tanggal_dokumen,
            'tanggal' => $request->tanggal_dokumen,
            'kategori' => $request->kategori,
        ];

        if ($request->hasFile('file')) {
            $oldPath = $doc->file_path ?? $doc->file;
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $data['file_path'] = $request->file('file')->store('documents', 'public');
            $data['file'] = $data['file_path'];
        }

        $doc->update($data);

        return redirect()->back()->with('success', 'Data dokumen berhasil diperbarui!');
    }

    // 4. PREVIEW & DOWNLOAD DOKUMEN (Anti 403 & 404 dengan Fallback Otomatis)
    public function previewDocument(Request $request, $id)
    {
        $doc = Document::findOrFail($id);

        $filePath = null;
        foreach ($doc->getAttributes() as $val) {
            if (is_string($val) && preg_match('/\.(pdf|docx?|xlsx?|png|jpe?g)$/i', $val)) {
                $filePath = $val;
                break;
            }
        }

        if (!$filePath) {
            $filePath = $doc->file_path ?? $doc->file ?? $doc->berkas ?? $doc->path ?? null;
        }

        $fileName = $filePath ? basename($filePath) : '';
        $cleanPath = $filePath ? ltrim(str_replace(['public/', 'storage/'], '', $filePath), '/') : '';

        $targetFile = null;

        // 1. Cek kecocokan nama langsung di berbagai path lokal/proyek
        if ($fileName) {
            $checkLocations = [
                'D:/Magang AirNav/ISR/' . $fileName,
                'D:/Magang AirNav/' . $fileName,
                storage_path('app/' . $cleanPath),
                storage_path('app/public/' . $cleanPath),
                public_path('storage/' . $cleanPath),
                public_path($cleanPath),
                storage_path('app/public/documents/' . $fileName),
                storage_path('app/documents/' . $fileName),
                public_path('storage/documents/' . $fileName),
            ];

            foreach ($checkLocations as $loc) {
                if (file_exists($loc) && is_file($loc)) {
                    $targetFile = $loc;
                    break;
                }
            }
        }

        // 2. Fallback cerdas: Ambil berkas PDF dari D:\Magang AirNav\ISR jika file seeder lama belum sinkron
        if (!$targetFile && is_dir('D:/Magang AirNav/ISR')) {
            $isrFiles = glob('D:/Magang AirNav/ISR/*.pdf');
            if (!empty($isrFiles)) {
                $index = ($doc->id - 1) % count($isrFiles);
                $targetFile = $isrFiles[$index] ?? $isrFiles[0];
            }
        }

        // 3. Fallback rekursif di folder Magang AirNav
        if (!$targetFile && is_dir('D:/Magang AirNav')) {
            $allFiles = glob('D:/Magang AirNav/**/*.pdf');
            if (!empty($allFiles)) {
                $targetFile = $allFiles[0];
            }
        }

        if (!$targetFile) {
            abort(404, 'Berkas PDF fisik tidak ditemukan di sistem.');
        }

        $mimeType = mime_content_type($targetFile) ?: 'application/pdf';
        $disposition = $request->has('download') ? 'attachment' : 'inline';

        return response()->file($targetFile, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => $disposition . '; filename="' . basename($targetFile) . '"',
        ]);
    }

    // 5. MONITORING FASILITAS
    public function monitoringFasilitas()
    {
        $brokenItems = collect([
            (object)['name' => 'Glide Path (GP) Shelter', 'location' => 'RWY 10', 'condition' => 'Perlu Kalibrasi Ulang', 'status' => 'Pending'],
            (object)['name' => 'VHF Receiver Main', 'location' => 'Tower Aerodrome', 'condition' => 'Modul Power Supply Drop', 'status' => 'Proses'],
            (object)['name' => 'UPS 10kVA Radar', 'location' => 'Gedung Operasional', 'condition' => 'Baterai Degradasi', 'status' => 'Perlu Penggantian'],
        ]);
        return view('manager-teknik.monitoring', compact('brokenItems'));
    }

    // 6. INSTRUKSI KERJA
    public function instruksiKerja(Request $request)
    {
        $query = MantekTask::with('technician');
        $tasks = $query->latest()->paginate(15);
        $technicians = class_exists(User::class) ? User::where('role', 'teknisi')->get() : collect();
        return view('manager-teknik.instruksi', compact('tasks', 'technicians'));
    }

    // 7. LOGBOOK HARIAN
    public function logbookHarian()
    {
        $logbooks = collect([
            (object)['tanggal' => now()->format('Y-m-d'), 'shift' => 'Pagi (07:00 - 13:00)', 'fasilitas' => 'DVOR / DME', 'teknisi' => 'Rifky & Tim', 'kegiatan' => 'Pengecekan parameter rutin', 'status' => 'Normal'],
        ]);
        return view('manager-teknik.logbook', compact('logbooks'));
    }
}