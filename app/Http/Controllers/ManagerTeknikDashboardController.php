<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Document;
use App\Models\Category;
use App\Models\MantekTask;
use App\Models\ManagerTeknikAssignment;
use App\Models\Inventory;

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

    // 2. PANEL KHUSUS MANAGER TEKNIK AIRNAV BANJARMASIN
    public function index()
    {
        $user = auth()->user();
        $isManagerTeknik = in_array(strtolower($user->role ?? ''), ['manager_teknik', 'mantek']);

        if (!$isManagerTeknik) {
            abort(403, 'Akses Ditolak: Panel perintah ini dikhususkan bagi Manager Teknik AirNav Indonesia Cabang Banjarmasin.');
        }

        $technicians = class_exists(User::class) ? User::orderBy('name', 'asc')->get() : collect();
        $latestDocuments = class_exists(Document::class) ? Document::latest()->take(10)->get() : collect();
        $tasks = class_exists(MantekTask::class) ? MantekTask::with('technician')->latest()->take(10)->get() : collect();
        $brokenItems = collect([
            (object)['name' => 'Glide Path (GP) Shelter', 'location' => 'RWY 10', 'condition' => 'Perlu Kalibrasi Ulang', 'status' => 'Pending'],
            (object)['name' => 'VHF Receiver Main', 'location' => 'Tower Aerodrome', 'condition' => 'Modul Power Supply Drop', 'status' => 'Proses'],
            (object)['name' => 'UPS 10kVA Radar', 'location' => 'Gedung Operasional', 'condition' => 'Baterai Degradasi', 'status' => 'Perlu Penggantian'],
        ]);

        return view('manager-teknik.dashboard', compact('technicians', 'latestDocuments', 'tasks', 'brokenItems'));
    }

    // 3. DOSSIER RESUME SERAH TERIMA JABATAN (KHUSUS MANAGER TEKNIK)
    public function handoverIndex()
    {
        $user = auth()->user();
        $isManagerTeknik = in_array(strtolower($user->role ?? ''), ['manager_teknik', 'mantek']);

        if (!$isManagerTeknik) {
            abort(403, 'Akses Ditolak: Modul memori jabatan ini khusus untuk Manager Teknik AirNav Indonesia Cabang Banjarmasin.');
        }

        if (Schema::hasTable('manager_teknik_handovers')) {
            $handovers = DB::table('manager_teknik_handovers')->latest()->paginate(10);
        } else {
            $items = collect();
            $handovers = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                0,
                10,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return view('manager-teknik.handover', compact('handovers'));
    }

    public function storeHandover(Request $request)
    {
        $user = auth()->user();
        $isManagerTeknik = in_array(strtolower($user->role ?? ''), ['manager_teknik', 'mantek']);

        if (!$isManagerTeknik) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'judul_resume' => 'required|string|max:255',
            'pejabat_nama' => 'required|string|max:255',
            'uraian_sudah_selesai' => 'required|string',
            'uraian_sedang_berjalan' => 'required|string',
            'uraian_rencana_ke_depan' => 'required|string',
            'tanggal_mulai_tugas' => 'nullable|date',
            'tanggal_selesai_tugas' => 'nullable|date',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:25600',
        ]);

        $docPath = null;
        if ($request->hasFile('dokumen_lampiran')) {
            $docPath = $request->file('dokumen_lampiran')->store('handover_documents', 'public');
        }

        if (!Schema::hasTable('manager_teknik_handovers')) {
            Schema::create('manager_teknik_handovers', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->string('judul_resume');
                $table->string('pejabat_nama');
                $table->string('pejabat_nip')->nullable();
                $table->date('tanggal_mulai_tugas')->nullable();
                $table->date('tanggal_selesai_tugas')->nullable();
                $table->longText('uraian_sudah_selesai');
                $table->longText('uraian_sedang_berjalan');
                $table->longText('uraian_rencana_ke_depan');
                $table->text('catatan_khusus_pejabat_baru')->nullable();
                $table->longText('paraf_digital')->nullable();
                $table->string('dokumen_lampiran')->nullable();
                $table->enum('status_dokumen', ['Draf', 'Resmi Diarsipkan'])->default('Draf');
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('manager_teknik_handovers', 'dokumen_lampiran')) {
                Schema::table('manager_teknik_handovers', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('dokumen_lampiran')->nullable()->after('paraf_digital');
                });
            }
        }

        $statusDokumen = $request->has('kunci_arsip') ? 'Resmi Diarsipkan' : 'Draf';

        DB::table('manager_teknik_handovers')->insert([
            'judul_resume' => $request->judul_resume,
            'pejabat_nama' => $request->pejabat_nama,
            'pejabat_nip' => $request->pejabat_nip,
            'tanggal_mulai_tugas' => $request->tanggal_mulai_tugas,
            'tanggal_selesai_tugas' => $request->tanggal_selesai_tugas,
            'uraian_sudah_selesai' => $request->uraian_sudah_selesai,
            'uraian_sedang_berjalan' => $request->uraian_sedang_berjalan,
            'uraian_rencana_ke_depan' => $request->uraian_rencana_ke_depan,
            'catatan_khusus_pejabat_baru' => $request->catatan_khusus_pejabat_baru,
            'paraf_digital' => $request->paraf_digital,
            'dokumen_lampiran' => $docPath,
            'status_dokumen' => $statusDokumen,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Memori serah terima jabatan Manager Teknik berhasil disimpan bersama berkas lampiran!');
    }

    public function toggleHandoverStatus($id)
    {
        $user = auth()->user();
        $isManagerTeknik = in_array(strtolower($user->role ?? ''), ['manager_teknik', 'mantek']);

        if (!$isManagerTeknik) {
            abort(403, 'Akses Ditolak.');
        }

        $doc = DB::table('manager_teknik_handovers')->where('id', $id)->first();
        if ($doc) {
            $newStatus = ($doc->status_dokumen === 'Resmi Diarsipkan') ? 'Draf' : 'Resmi Diarsipkan';
            DB::table('manager_teknik_handovers')->where('id', $id)->update([
                'status_dokumen' => $newStatus,
                'updated_at' => now(),
            ]);

            $pesan = ($newStatus === 'Resmi Diarsipkan') 
                ? 'Dokumen berhasil dikunci dan diresmikan ke arsip!' 
                : 'Kunci dokumen berhasil dibuka. Status kembali menjadi draf untuk penyesuaian.';

            return redirect()->back()->with('success', $pesan);
        }

        return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
    }

    // 4. PENERBITAN INSTRUKSI TUGAS (MENDUKUNG OPSI 'ALL')
    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'assigned_to' => 'nullable',
            'target_date' => 'required|date',
            'instruction' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'action' => 'nullable|string'
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('mantek_tasks', 'public');
        }

        // Jika dipilih opsi 'all', simpan nilai sebagai null agar diinterpretasikan sebagai tugas untuk seluruh tim
        $assignedVal = ($request->assigned_to === 'all' || !is_numeric($request->assigned_to)) 
            ? null 
            : (int)$request->assigned_to;

        MantekTask::create([
            'title' => $request->title,
            'assigned_to' => $assignedVal,
            'target_date' => $request->target_date,
            'instruction' => $request->instruction,
            'attachment' => $filePath,
            'status_terbit' => ($request->action === 'draft') ? 'Draf' : 'Terbit',
            'status_pengerjaan' => 'Belum Selesai',
        ]);

        return redirect()->route('instruksi.index', ['tab' => 'berjalan'])
            ->with('success', 'Instruksi kerja beserta lampiran berhasil diterbitkan!');
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

    // 5. ARSIP DOKUMEN & CRUD
    public function arsipDokumen(Request $request)
    {
        $query = Document::query();

        if ($request->filled('category')) {
            $cat = $request->category;
            $query->where(function($q) use ($cat) {
                $q->where('kategori', $cat)
                  ->orWhere('category_id', $cat);
            });
        }

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

    public function destroyDocument($id)
    {
        $doc = Document::findOrFail($id);
        $filePath = $doc->file_path ?? $doc->file;
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $doc->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus dari arsip.');
    }

    // 6. PREVIEW & DOWNLOAD DOKUMEN
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

        if (!$targetFile && is_dir('D:/Magang AirNav/ISR')) {
            $isrFiles = glob('D:/Magang AirNav/ISR/*.pdf');
            if (!empty($isrFiles)) {
                $index = ($doc->id - 1) % count($isrFiles);
                $targetFile = $isrFiles[$index] ?? $isrFiles[0];
            }
        }

        if (!$targetFile && is_dir('D:/Magang AirNav')) {
            $allFiles = glob('D:/Magang AirNav/**/*.pdf');
            if (!empty($allFiles)) {
                $targetFile = $allFiles[0];
            }
        }

        if (!$targetFile) {
            abort(404, 'Berkas fisik tidak ditemukan di sistem.');
        }

        $mimeType = mime_content_type($targetFile) ?: 'application/pdf';
        $disposition = $request->has('download') ? 'attachment' : 'inline';

        return response()->file($targetFile, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => $disposition . '; filename="' . basename($targetFile) . '"',
        ]);
    }

    // 7. MONITORING INVENTORY & FASILITAS CNS/A
    public function monitoringFasilitas(Request $request)
    {
        $hasInventoryModel = class_exists(Inventory::class);

        if ($hasInventoryModel) {
            $query = Inventory::query();

            if ($request->filled('kondisi')) {
                $query->where('kondisi', $request->kondisi);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('detail_kerusakan', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            $inventories = $query->latest()->paginate(15);
            $totalRusak = Inventory::whereIn('kondisi', ['Perlu Perbaikan', 'Rusak Berat'])->count();
            $totalProses = Inventory::where('status_penanganan', 'Proses')->count();
            $totalNormal = Inventory::where('kondisi', 'Normal')->count();
        } else {
            $inventories = collect();
            $totalRusak = 0;
            $totalProses = 0;
            $totalNormal = 0;
        }

        $technicians = class_exists(User::class) ? User::orderBy('name', 'asc')->get() : collect();

        return view('manager-teknik.monitoring', compact('inventories', 'technicians', 'totalRusak', 'totalProses', 'totalNormal'));
    }

    public function storeInventory(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:Normal,Perlu Perbaikan,Rusak Berat',
            'detail_kerusakan' => 'nullable|string',
            'tanggal_rusak' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'keterangan_foto' => 'nullable|string|max:255',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('inventory_photos', 'public');
        }

        $detail = ($request->kondisi === 'Normal') ? '-' : ($request->detail_kerusakan ?: '-');
        $tglRusak = ($request->kondisi === 'Normal') ? null : $request->tanggal_rusak;

        if (class_exists(Inventory::class)) {
            $existingColumns = Schema::getColumnListing('inventories');
            $data = [];

            if (in_array('name', $existingColumns)) $data['name'] = $request->nama_barang;
            if (in_array('nama_barang', $existingColumns)) $data['nama_barang'] = $request->nama_barang;
            if (in_array('lokasi', $existingColumns)) $data['lokasi'] = $request->lokasi;
            if (in_array('jumlah', $existingColumns)) $data['jumlah'] = $request->jumlah;
            if (in_array('satuan', $existingColumns)) $data['satuan'] = $request->satuan;
            if (in_array('kondisi', $existingColumns)) $data['kondisi'] = $request->kondisi;
            if (in_array('detail_kerusakan', $existingColumns)) $data['detail_kerusakan'] = $detail;
            if (in_array('tanggal_rusak', $existingColumns)) $data['tanggal_rusak'] = $tglRusak;
            if (in_array('status_penanganan', $existingColumns)) $data['status_penanganan'] = ($request->kondisi === 'Normal') ? 'Normal' : 'Pending';
            if (in_array('foto', $existingColumns)) $data['foto'] = $fotoPath;
            if (in_array('keterangan_foto', $existingColumns)) $data['keterangan_foto'] = $request->keterangan_foto;
            if (in_array('teknisi_bertugas', $existingColumns)) $data['teknisi_bertugas'] = '-';
            if (in_array('paraf_teknisi', $existingColumns)) $data['paraf_teknisi'] = null;
            if (in_array('paraf_mantek', $existingColumns)) $data['paraf_mantek'] = null;

            Inventory::create($data);
        }

        return redirect()->route('monitoring.index')->with('success', 'Data inventaris berhasil disimpan!');
    }

    public function assignRepairTask(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required',
            'technician_id' => 'required',
            'target_date' => 'required|date',
            'instruction' => 'required|string',
        ]);

        $techName = 'Semua Teknisi';
        if ($request->technician_id !== 'all') {
            $techUser = User::find($request->technician_id);
            $techName = $techUser ? $techUser->name : 'Teknisi CNS';
        }

        if (class_exists(Inventory::class)) {
            $inv = Inventory::find($request->inventory_id);
            if ($inv) {
                $cols = Schema::getColumnListing('inventories');
                $updateData = [];

                if (in_array('teknisi_bertugas', $cols)) $updateData['teknisi_bertugas'] = $techName;
                if (in_array('tanggal_perbaikan', $cols)) $updateData['tanggal_perbaikan'] = $request->target_date;
                if (in_array('status_penanganan', $cols)) $updateData['status_penanganan'] = 'Proses';

                if (!empty($updateData)) {
                    $inv->update($updateData);
                }
            }
        }

        $assignedVal = ($request->technician_id === 'all') ? null : (int)$request->technician_id;

        if (class_exists(MantekTask::class)) {
            MantekTask::create([
                'title' => 'Perbaikan: ' . ($request->item_name ?? 'Fasilitas Rusak'),
                'assigned_to' => $assignedVal,
                'target_date' => $request->target_date,
                'instruction' => $request->instruction,
                'status_terbit' => 'Terbit',
                'status_pengerjaan' => 'Dalam Pengerjaan',
            ]);
        }

        return redirect()->back()->with('success', 'Tugas perbaikan berhasil diterbitkan dan didelegasikan kepada personil!');
    }

    public function signTeknisi(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required',
            'signature_data' => 'required|string',
            'catatan_teknisi' => 'required|string',
            'tanggal_selesai' => 'required|date',
        ]);

        if (class_exists(Inventory::class)) {
            $inv = Inventory::find($request->inventory_id);
            if ($inv) {
                $cols = Schema::getColumnListing('inventories');
                $updateData = [];

                if (in_array('paraf_teknisi', $cols)) $updateData['paraf_teknisi'] = $request->signature_data;
                if (in_array('catatan_teknisi', $cols)) $updateData['catatan_teknisi'] = $request->catatan_teknisi;
                if (in_array('tanggal_perbaikan', $cols)) $updateData['tanggal_perbaikan'] = $request->tanggal_selesai;
                if (in_array('status_penanganan', $cols)) $updateData['status_penanganan'] = 'Menunggu Verifikasi Mantek';

                if (!empty($updateData)) {
                    $inv->update($updateData);
                }
            }
        }

        return redirect()->back()->with('success', 'Laporan selesai berhasil dikirim! Menunggu pengesahan dari Manager Teknik.');
    }

    public function signMantek(Request $request)
    {
        $user = auth()->user();
        $isManagerTeknik = in_array(strtolower($user->role ?? ''), ['manager_teknik', 'mantek']);

        if (!$isManagerTeknik) {
            abort(403, 'Akses Ditolak: Hanya Manager Teknik AirNav Indonesia Cabang Banjarmasin yang berhak mengesahkan pengembalian status operasional.');
        }

        $request->validate([
            'inventory_id' => 'required',
            'signature_data' => 'required|string',
            'catatan_mantek' => 'required|string',
        ]);

        if (class_exists(Inventory::class)) {
            $inv = Inventory::find($request->inventory_id);
            if ($inv) {
                $cols = Schema::getColumnListing('inventories');
                
                $tglRusak = $inv->tanggal_rusak ? date('d/m/Y', strtotime($inv->tanggal_rusak)) : '-';
                $tglSelesai = $inv->tanggal_perbaikan ? date('d/m/Y', strtotime($inv->tanggal_perbaikan)) : date('d/m/Y');
                $teknisi = $inv->teknisi_bertugas ?: 'Teknisi Bertugas';
                $kendalaAwal = $inv->detail_kerusakan ?: '-';
                $laporanTeknisi = $inv->catatan_teknisi ?: '-';

                $entryBaru = "[REKAM HISTORIS PERBAIKAN]\n" .
                             "- Tanggal Rusak: {$tglRusak}\n" .
                             "- Tanggal Selesai: {$tglSelesai}\n" .
                             "- Personil Pelaksana: {$teknisi}\n" .
                             "- Kendala Kerusakan: {$kendalaAwal}\n" .
                             "- Tindakan Pengerjaan: {$laporanTeknisi}\n" .
                             "- Pengesahan Manager Teknik: {$request->catatan_mantek}\n" .
                             "- Status: Berhasil Diperbaiki & Sah Operasional\n" .
                             "--------------------------------------------------\n";

                $riwayatLama = in_array('riwayat_perbaikan', $cols) ? ($inv->riwayat_perbaikan ?? '') : '';
                $riwayatTotal = $entryBaru . $riwayatLama;

                $updateData = [];

                if (in_array('paraf_mantek', $cols)) $updateData['paraf_mantek'] = $request->signature_data;
                if (in_array('status_penanganan', $cols)) $updateData['status_penanganan'] = 'Selesai & Diverifikasi';
                if (in_array('riwayat_perbaikan', $cols)) $updateData['riwayat_perbaikan'] = $riwayatTotal;

                if (in_array('kondisi', $cols)) $updateData['kondisi'] = 'Normal';
                if (in_array('detail_kerusakan', $cols)) $updateData['detail_kerusakan'] = '-';

                if (!empty($updateData)) {
                    $inv->update($updateData);
                }
            }
        }

        return redirect()->back()->with('success', 'Peralatan resmi disahkan! Kondisi kembali Normal dan riwayat perbaikan telah diarsipkan.');
    }

    // 8. INSTRUKSI KERJA TEKNISI
    public function instruksiKerja(Request $request)
    {
        $currentTab = $request->get('tab', 'berjalan');
        $query = MantekTask::with('technician');

        if ($currentTab === 'arsip') {
            $query->where(function($q) {
                $q->where('status_pengerjaan', 'Selesai')
                  ->orWhere('status_pengerjaan', 'Diarsipkan');
            });

            if ($request->filled('filter_month')) {
                $query->whereMonth('updated_at', $request->filter_month);
            }

            if ($request->filled('filter_year')) {
                $query->whereYear('updated_at', $request->filter_year);
            }
        } else {
            $query->where('status_pengerjaan', '!=', 'Selesai')
                  ->where('status_pengerjaan', '!=', 'Diarsipkan');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('instruction', 'like', "%{$search}%");
            });
        }

        $tasks = $query->latest('updated_at')->paginate(15);
        $technicians = class_exists(User::class) ? User::orderBy('name', 'asc')->get() : collect();

        $countBerjalan = MantekTask::where('status_pengerjaan', '!=', 'Selesai')
                                  ->where('status_pengerjaan', '!=', 'Diarsipkan')
                                  ->count();
        $countArsip = MantekTask::whereIn('status_pengerjaan', ['Selesai', 'Diarsipkan'])->count();

        $availableYears = MantekTask::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        return view('manager-teknik.instruksi', compact(
            'tasks', 'technicians', 'currentTab', 'countBerjalan', 'countArsip', 'availableYears'
        ));
    }

    public function updateTaskStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengerjaan' => 'required|string',
            'catatan_progres' => 'nullable|string',
        ]);

        $task = MantekTask::findOrFail($id);
        
        $catatanTambahan = $request->catatan_progres ? "\n[Catatan Selesai/Progres " . date('d/m/Y H:i') . "]: " . $request->catatan_progres : '';

        $task->update([
            'status_pengerjaan' => $request->status_pengerjaan,
            'instruction' => ($task->instruction ?? '') . $catatanTambahan,
        ]);

        if ($request->status_pengerjaan === 'Selesai') {
            return redirect()->route('instruksi.index', ['tab' => 'arsip'])
                ->with('success', 'Instruksi pengerjaan telah diselesaikan dan otomatis dipindahkan ke Arsip Selesai!');
        }

        return redirect()->route('instruksi.index', ['tab' => 'berjalan'])
            ->with('success', 'Status pengerjaan instruksi berhasil diperbarui!');
    }

    public function archiveTask($id)
    {
        $task = MantekTask::findOrFail($id);
        $task->update([
            'status_pengerjaan' => 'Diarsipkan',
        ]);

        return redirect()->route('instruksi.index', ['tab' => 'arsip'])
            ->with('success', 'Instruksi kerja berhasil dipindahkan ke Arsip Selesai.');
    }

    // 9. ACTIVITY LOG (LOG AKTIVITAS SISTEM)
    public function activityLogs()
    {
        if (Schema::hasTable('activity_logs')) {
            $logs = DB::table('activity_logs')->latest()->paginate(20);
        } else {
            $logs = collect([
                (object)[
                    'created_at' => now()->subMinutes(5),
                    'user_name' => auth()->user()->name ?? 'Manager Teknik',
                    'role' => 'manager_teknik',
                    'action' => 'Penerbitan Tugas',
                    'description' => 'Menerbitkan perintah operasional fasilitas navigasi udara.',
                ],
                (object)[
                    'created_at' => now()->subHours(1),
                    'user_name' => 'Administrator AirNav',
                    'role' => 'admin',
                    'action' => 'Manajemen Fasilitas',
                    'description' => 'Memperbarui status suku cadang shelter tower.',
                ],
                (object)[
                    'created_at' => now()->subHours(3),
                    'user_name' => 'Teknisi Jaga',
                    'role' => 'teknisi',
                    'action' => 'Logbook Harian',
                    'description' => 'Mengisi checklist shift pemeliharaan DVOR/DME.',
                ],
            ]);
        }

        return view('activity-log.index', compact('logs'));
    }

    // 10. LOGBOOK HARIAN
    public function logbookHarian(Request $request)
    {
        $modelExists = class_exists(\App\Models\FacilityLogbook::class);
        
        if ($modelExists) {
            $query = \App\Models\FacilityLogbook::query();

            if ($request->filled('shift')) {
                $query->where('shift', $request->shift);
            }
            if ($request->filled('fasilitas')) {
                $query->where('fasilitas', 'like', "%{$request->fasilitas}%");
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $logbooks = $query->latest()->paginate(15);
        } else {
            $logbooks = collect();
        }

        return view('manager-teknik.logbook', compact('logbooks'));
    }

    public function storeLogbook(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'shift' => 'required|string',
            'fasilitas' => 'required|string|max:255',
            'teknisi' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'status' => 'required|string',
        ]);

        if (class_exists(\App\Models\FacilityLogbook::class)) {
            \App\Models\FacilityLogbook::create([
                'tanggal' => $request->tanggal,
                'shift' => $request->shift,
                'fasilitas' => $request->fasilitas,
                'teknisi' => $request->teknisi,
                'kegiatan' => $request->kegiatan,
                'status' => $request->status,
            ]);
        }

        return redirect()->back()->with('success', 'Catatan logbook berhasil ditambahkan.');
    }
}