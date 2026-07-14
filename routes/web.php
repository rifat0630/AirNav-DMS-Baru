<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\GoogleAuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
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

Route::get('/google/auth', [GoogleAuthController::class, 'redirect'])
    ->name('google.auth');


Route::get('/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');



/*
|--------------------------------------------------------------------------
| TEST GOOGLE DRIVE FOLDER
|--------------------------------------------------------------------------
*/

Route::get('/google/create-folder', function () {

    $drive = app(\App\Services\GoogleDriveService::class);

    $folderId = $drive->createFolder();

    return "Folder AirNav DMS berhasil dibuat. ID: " . $folderId;

});



/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {



    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');




    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */


    Route::get('/documents', [DocumentController::class, 'index'])
        ->name('documents.index');




    /*
    |--------------------------------------------------------------------------
    | Admin, Teknisi, Pegawai
    |--------------------------------------------------------------------------
    */


    Route::middleware('role:admin,teknisi,pegawai')->group(function () {


        Route::get('/documents/create', [DocumentController::class, 'create'])
            ->name('documents.create');


        Route::post('/documents', [DocumentController::class, 'store'])
            ->name('documents.store');


        Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])
            ->name('documents.edit');


        Route::put('/documents/{document}', [DocumentController::class, 'update'])
            ->name('documents.update');


    });



    /*
    |--------------------------------------------------------------------------
    | Preview & Download
    |--------------------------------------------------------------------------
    */


    Route::get('/documents/{id}/preview', [DocumentController::class, 'preview'])
        ->name('documents.preview');


    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])
        ->name('documents.download');



    /*
    |--------------------------------------------------------------------------
    | Show Document
    |--------------------------------------------------------------------------
    */

    Route::get('/documents/{document}', [DocumentController::class, 'show'])
        ->name('documents.show');




    /*
    |--------------------------------------------------------------------------
    | Admin Only
    |--------------------------------------------------------------------------
    */


    Route::middleware('role:admin')->group(function () {


        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])
            ->name('documents.destroy');


        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity_logs.index');


    });




    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');


    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');



});


require __DIR__.'/auth.php';