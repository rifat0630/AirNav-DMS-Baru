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
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('welcome');

});


/*
|--------------------------------------------------------------------------
| GOOGLE DRIVE AUTH
|--------------------------------------------------------------------------
*/

Route::get(
    '/google/auth',
    [GoogleAuthController::class, 'redirect']
)
->name('google.auth');


Route::get(
    '/google/callback',
    [GoogleAuthController::class, 'callback']
)
->name('google.callback');


/*
|--------------------------------------------------------------------------
| TEST GOOGLE DRIVE
|--------------------------------------------------------------------------
*/

Route::get(
    '/google/create-folder',
    function () {

        $drive = app(
            \App\Services\GoogleDriveService::class
        );

        $folderId = $drive->createFolder();

        return 'Folder AirNav DMS berhasil dibuat. ID: '
            . $folderId;

    }
);


/*
|--------------------------------------------------------------------------
| AUTH + VERIFIED
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )
    ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - INDEX
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents',
        [DocumentController::class, 'index']
    )
    ->name('documents.index');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - CREATE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents/create',
        [DocumentController::class, 'create']
    )
    ->name('documents.create');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - STORE
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/documents',
        [DocumentController::class, 'store']
    )
    ->name('documents.store');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - EDIT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents/{document}/edit',
        [DocumentController::class, 'edit']
    )
    ->name('documents.edit');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - UPDATE
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/documents/{document}',
        [DocumentController::class, 'update']
    )
    ->name('documents.update');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - DELETE
    |--------------------------------------------------------------------------
    |
    | PENTING:
    | Route ini TIDAK memakai role:admin.
    | Selama user sudah login + verified,
    | user bisa menghapus dokumen.
    |
    */

    Route::delete(
        '/documents/{document}',
        [DocumentController::class, 'destroy']
    )
    ->name('documents.destroy');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - PREVIEW
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents/{id}/preview',
        [DocumentController::class, 'preview']
    )
    ->name('documents.preview');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - DOWNLOAD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents/{id}/download',
        [DocumentController::class, 'download']
    )
    ->name('documents.download');


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT - SHOW
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/documents/{document}',
        [DocumentController::class, 'show']
    )
    ->name('documents.show');


    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG
    |--------------------------------------------------------------------------
    |
    | Activity Log tetap admin.
    |
    */

    Route::middleware('role:admin')
    ->group(function () {

        Route::get(
            '/activity-logs',
            [ActivityLogController::class, 'index']
        )
        ->name('activity_logs.index');

    });


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )
    ->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )
    ->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )
    ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| AIRNAV DMS MODULE
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | FACILITY LOGBOOK
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/logbook/sign/{token}',
        [
            FacilityLogbookController::class,
            'sign'
        ]
    )
    ->name('logbook.sign');


    Route::post(
        '/logbook/sign/{token}',
        [
            FacilityLogbookController::class,
            'signConfirm'
        ]
    )
    ->name('logbook.sign.confirm');


    Route::resource(
        'logbook',
        FacilityLogbookController::class
    )
    ->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);


    Route::get(
        '/logbook/scan/{id}',
        [
            FacilityLogbookController::class,
            'scan'
        ]
    )
    ->name('logbook.scan');


    /*
    |--------------------------------------------------------------------------
    | INVENTORY
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'inventory',
        InventoryController::class
    )
    ->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ]);


    Route::post(
        '/inventory/{id}/stock-in',
        [
            InventoryController::class,
            'stockIn'
        ]
    )
    ->name('inventory.stock.in');


    Route::post(
        '/inventory/{id}/stock-out',
        [
            InventoryController::class,
            'stockOut'
        ]
    )
    ->name('inventory.stock.out');


    /*
    |--------------------------------------------------------------------------
    | TEKNISI
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