<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\FacilityLogbookController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Google Drive Authentication
|--------------------------------------------------------------------------
*/

Route::get(
    '/google/auth',
    [GoogleAuthController::class, 'redirect']
)->name('google.auth');


Route::get(
    '/google/callback',
    [GoogleAuthController::class, 'callback']
)->name('google.callback');


/*
|--------------------------------------------------------------------------
| TEST GOOGLE DRIVE FOLDER
|--------------------------------------------------------------------------
*/

Route::get('/google/create-folder', function () {

    $drive = app(
        \App\Services\GoogleDriveService::class
    );

    $folderId = $drive->createFolder();

    return "Folder AirNav DMS berhasil dibuat. ID: " . $folderId;

});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified'
])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents',
        [DocumentController::class, 'index']
    )->name('documents.index');


    /*
    |--------------------------------------------------------------------------
    | Admin, Teknisi, Pegawai
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,teknisi,pegawai'
    )->group(function () {


        Route::get(
            '/documents/create',
            [DocumentController::class, 'create']
        )->name('documents.create');


        Route::post(
            '/documents',
            [DocumentController::class, 'store']
        )->name('documents.store');


        Route::get(
            '/documents/{document}/edit',
            [DocumentController::class, 'edit']
        )->name('documents.edit');


        Route::put(
            '/documents/{document}',
            [DocumentController::class, 'update']
        )->name('documents.update');

    });


    /*
    |--------------------------------------------------------------------------
    | Preview & Download Documents
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents/{id}/preview',
        [DocumentController::class, 'preview']
    )->name('documents.preview');


    Route::get(
        '/documents/{id}/download',
        [DocumentController::class, 'download']
    )->name('documents.download');


    /*
    |--------------------------------------------------------------------------
    | Show Document
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents/{document}',
        [DocumentController::class, 'show']
    )->name('documents.show');


    /*
    |--------------------------------------------------------------------------
    | Admin Only
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin'
    )->group(function () {


        Route::delete(
            '/documents/{document}',
            [DocumentController::class, 'destroy']
        )->name('documents.destroy');


        Route::get(
            '/activity-logs',
            [ActivityLogController::class, 'index']
        )->name('activity_logs.index');

    });


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| TANDA TANGAN LOGBOOK DARI HP
|--------------------------------------------------------------------------
|
| Route ini berada DI LUAR middleware auth.
|
| HP teknisi cukup scan QR lalu langsung membuka
| halaman tanda tangan tanpa harus login.
|
|--------------------------------------------------------------------------
*/

Route::get(
    '/logbook/sign/{token}',
    [FacilityLogbookController::class, 'sign']
)->name('logbook.sign');


Route::post(
    '/logbook/sign/{token}',
    [FacilityLogbookController::class, 'signConfirm']
)->name('logbook.sign.confirm');


/*
|--------------------------------------------------------------------------
| AIRNAV DMS RESOURCES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | FACILITY LOGBOOK
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'logbook',
        FacilityLogbookController::class
    )->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN QR LOGBOOK
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/logbook/scan/{id}',
        [
            FacilityLogbookController::class,
            'scan'
        ]
    )->name('logbook.scan');


    /*
    |--------------------------------------------------------------------------
    | VERIFY QR LAMA
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logbook/verify/{id}',
        [
            FacilityLogbookController::class,
            'verify'
        ]
    )->name('logbook.verify');


    /*
    |--------------------------------------------------------------------------
    | INVENTORY BARANG
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'inventory',
        InventoryController::class
    )->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ]);


    /*
    |--------------------------------------------------------------------------
    | STOK MASUK
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/inventory/{id}/stock-in',
        [
            InventoryController::class,
            'stockIn'
        ]
    )->name('inventory.stock.in');


    /*
    |--------------------------------------------------------------------------
    | STOK KELUAR
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/inventory/{id}/stock-out',
        [
            InventoryController::class,
            'stockOut'
        ]
    )->name('inventory.stock.out');


    /*
    |--------------------------------------------------------------------------
    | MASTER TEKNISI
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'technicians',
        TechnicianController::class
    );


    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'users',
        UserController::class
    );

});