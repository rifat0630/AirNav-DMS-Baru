<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerTeknikDashboardController;

// Redirect root URL ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard Utama (Ringkasan Statistik & Aktivitas)
    Route::get('/dashboard', [ManagerTeknikDashboardController::class, 'dashboardUtama'])->name('dashboard');

    // 2. Modul Arsip Dokumen CNS/A (CRUD & Preview)
    Route::get('/arsip-dokumen', [ManagerTeknikDashboardController::class, 'arsipDokumen'])->name('arsip.index');
    Route::post('/arsip-dokumen/upload', [ManagerTeknikDashboardController::class, 'storeDocument'])->name('arsip.store');
    Route::put('/arsip-dokumen/{id}', [ManagerTeknikDashboardController::class, 'updateDocument'])->name('arsip.update');
    Route::delete('/arsip-dokumen/{id}', [ManagerTeknikDashboardController::class, 'destroyDocument'])->name('arsip.destroy');
    Route::get('/arsip-dokumen/preview/{id}', [ManagerTeknikDashboardController::class, 'previewDocument'])->name('arsip.preview');

    // 3. Modul Logbook Harian
    Route::get('/logbook-harian', [ManagerTeknikDashboardController::class, 'logbookHarian'])->name('logbook.index');

    // 4. Modul Khusus Manager Teknik (Protected Prefix)
    Route::prefix('mantek')->group(function () {
        Route::get('/dashboard', [ManagerTeknikDashboardController::class, 'index'])->name('mantek.dashboard');
        Route::post('/dashboard/task', [ManagerTeknikDashboardController::class, 'storeTask'])->name('mantek.tasks.store');
        Route::delete('/dashboard/task/{id}', [ManagerTeknikDashboardController::class, 'destroyTask'])->name('mantek.tasks.destroy');
        Route::get('/monitoring-fasilitas', [ManagerTeknikDashboardController::class, 'monitoringFasilitas'])->name('monitoring.index');
        Route::get('/instruksi-kerja', [ManagerTeknikDashboardController::class, 'instruksiKerja'])->name('instruksi.index');
    });

});

require __DIR__.'/auth.php';