<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerTeknikDashboardController;
use App\Http\Middleware\EnsureMantekOrApprovedAdmin;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard Utama (Bisa Diakses Semua Role Termasuk Manager Teknik)
    Route::get('/dashboard', [ManagerTeknikDashboardController::class, 'dashboardUtama'])->name('dashboard');

    // 2. Modul Arsip Dokumen CNS/A (Semua Role)
    Route::get('/arsip-dokumen', [ManagerTeknikDashboardController::class, 'arsipDokumen'])->name('arsip.index');
    Route::post('/arsip-dokumen/upload', [ManagerTeknikDashboardController::class, 'storeDocument'])->name('arsip.store');
    Route::put('/arsip-dokumen/{id}', [ManagerTeknikDashboardController::class, 'updateDocument'])->name('arsip.update');
    Route::delete('/arsip-dokumen/{id}', [ManagerTeknikDashboardController::class, 'destroyDocument'])->name('arsip.destroy');
    Route::get('/arsip-dokumen/preview/{id}', [ManagerTeknikDashboardController::class, 'previewDocument'])->name('arsip.preview');

    // 3. Modul Logbook Harian (Semua Role)
    Route::get('/logbook-harian', [ManagerTeknikDashboardController::class, 'logbookHarian'])->name('logbook.index');
    Route::post('/logbook-harian', [ManagerTeknikDashboardController::class, 'storeLogbook'])->name('logbook.store');

    // 4. Modul Monitoring Inventory & Fasilitas (Semua Role)
    Route::get('/monitoring-fasilitas', [ManagerTeknikDashboardController::class, 'monitoringFasilitas'])->name('monitoring.index');
    Route::post('/monitoring-fasilitas/store', [ManagerTeknikDashboardController::class, 'storeInventory'])->name('inventory.store');
    Route::post('/monitoring-fasilitas/assign-task', [ManagerTeknikDashboardController::class, 'assignRepairTask'])->name('inventory.assignTask');
    Route::post('/monitoring-fasilitas/sign-teknisi', [ManagerTeknikDashboardController::class, 'signTeknisi'])->name('inventory.signTeknisi');
    Route::post('/monitoring-fasilitas/sign-mantek', [ManagerTeknikDashboardController::class, 'signMantek'])->name('inventory.signMantek');

    // 5. Modul Instruksi Kerja Teknisi, Update Status, & Arsip (Semua Role)
    Route::get('/instruksi-kerja', [ManagerTeknikDashboardController::class, 'instruksiKerja'])->name('instruksi.index');
    Route::post('/instruksi-kerja/store', [ManagerTeknikDashboardController::class, 'storeTask'])->name('instruksi.store');
    Route::put('/instruksi-kerja/{id}/update-status', [ManagerTeknikDashboardController::class, 'updateTaskStatus'])->name('instruksi.updateStatus');
    Route::post('/instruksi-kerja/{id}/archive', [ManagerTeknikDashboardController::class, 'archiveTask'])->name('instruksi.archive');
    Route::delete('/instruksi-kerja/{id}', [ManagerTeknikDashboardController::class, 'destroyTask'])->name('instruksi.destroy');

    // 6. Modul Activity Log (Manager Teknik & Admin)
    Route::get('/activity-log', [ManagerTeknikDashboardController::class, 'activityLogs'])->name('activity-log.index');

    // 7. WEWENANG KHUSUS: MANAGER TEKNIK AIRNAV INDONESIA CABANG BANJARMASIN
    Route::prefix('manager-teknik')->middleware([EnsureMantekOrApprovedAdmin::class])->group(function () {
        Route::get('/dashboard', [ManagerTeknikDashboardController::class, 'index'])->name('manager-teknik.dashboard');
        Route::post('/dashboard/task', [ManagerTeknikDashboardController::class, 'storeTask'])->name('manager-teknik.tasks.store');
        Route::delete('/dashboard/task/{id}', [ManagerTeknikDashboardController::class, 'destroyTask'])->name('manager-teknik.tasks.destroy');

        // Memori Serah Terima Jabatan & Kontinuitas Operasional
        Route::get('/handover', [ManagerTeknikDashboardController::class, 'handoverIndex'])->name('manager-teknik.handover.index');
        Route::post('/handover/store', [ManagerTeknikDashboardController::class, 'storeHandover'])->name('manager-teknik.handover.store');
        Route::patch('/handover/{id}/toggle-status', [ManagerTeknikDashboardController::class, 'toggleHandoverStatus'])->name('manager-teknik.handover.toggleStatus');
    });

    // 8. RUTE ALIAS LAMA (Agar tidak terjadi error pada cache template lama)
    Route::post('/manager-teknik/dashboard/task-alias', [ManagerTeknikDashboardController::class, 'storeTask'])->name('mantek.tasks.store');
    Route::delete('/manager-teknik/dashboard/task-alias/{id}', [ManagerTeknikDashboardController::class, 'destroyTask'])->name('mantek.tasks.destroy');
    Route::get('/mantek/dashboard', function() {
        return redirect()->route('manager-teknik.dashboard');
    })->name('mantek.dashboard');

});

require __DIR__.'/auth.php';