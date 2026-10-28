<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\Mahasiswa\LowonganController as MahasiswaLowongan;
use App\Http\Controllers\Mahasiswa\PendaftaranController as MahasiswaPendaftaran;
use App\Http\Controllers\Mahasiswa\StatusController as MahasiswaStatus;
use App\Http\Controllers\Mahasiswa\TimesheetController as MahasiswaTimesheet;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;
use App\Http\Controllers\Dosen\LowonganController as DosenLowongan;
use App\Http\Controllers\Dosen\TimesheetController as DosenTimesheet;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Route Mahasiswa
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])
        ->name('dashboard');

    Route::get('/lowongan', [MahasiswaLowongan::class, 'index'])
        ->name('lowongan.index');

    Route::get('/lowongan/{id}', [MahasiswaLowongan::class, 'show'])
        ->name('lowongan.show');

    Route::get('/pendaftaran/{id}', [MahasiswaPendaftaran::class, 'create'])
        ->name('pendaftaran.create');

    Route::post('/pendaftaran', [MahasiswaPendaftaran::class, 'store'])
        ->name('pendaftaran.store');

    Route::get('/status', [MahasiswaStatus::class, 'index'])
        ->name('status');

    Route::get('/timesheet', [MahasiswaTimesheet::class, 'index'])
        ->name('timesheet');

    Route::post('/timesheet', [MahasiswaTimesheet::class, 'store'])
        ->name('timesheet.store');
});

/*
|--------------------------------------------------------------------------
| Route Dosen
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')->name('dosen.')->group(function () {

    Route::get('/dashboard', [DosenDashboard::class, 'index'])
        ->name('dashboard');

    Route::get('/lowongan', [DosenLowongan::class, 'index'])
        ->name('lowongan.index');

    Route::get('/lowongan/create', [DosenLowongan::class, 'create'])
        ->name('lowongan.create');

    Route::post('/lowongan', [DosenLowongan::class, 'store'])
        ->name('lowongan.store');

    Route::get('/lowongan/{id}', [DosenLowongan::class, 'show'])
        ->name('lowongan.show');

    Route::get('/lowongan/{id}/edit', [DosenLowongan::class, 'edit'])
        ->name('lowongan.edit');

    Route::put('/lowongan/{id}', [DosenLowongan::class, 'update'])
        ->name('lowongan.update');

    Route::patch('/lowongan/{id}/status', [DosenLowongan::class, 'toggleStatus'])
        ->name('lowongan.toggle-status');

    Route::delete('/lowongan/{id}', [DosenLowongan::class, 'destroy'])
        ->name('lowongan.destroy');

    Route::get('/timesheet', [DosenTimesheet::class, 'index'])
        ->name('timesheet');

    Route::get('/timesheet/{id}', [DosenTimesheet::class, 'show'])
        ->name('timesheet.show');
});