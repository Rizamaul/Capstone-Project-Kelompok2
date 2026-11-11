<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// === RUTE DOSEN SPRINT 2 ===
Route::get('/dosen/dashboard', function () {
    return view('dosen.dashboard');
})->name('dosen.dashboard');

Route::get('/dosen/lowongan/create', function () {
    return view('dosen.lowongan.create'); 
})->name('dosen.lowongan.create');

Route::get('/dosen/lowongan/{id}', function ($id) {
    return view('dosen.lowongan.show');
})->name('dosen.lowongan.show');

Route::get('/dosen/lowongan/{id}/edit', function ($id) {
    return view('dosen.lowongan.edit');
})->name('dosen.lowongan.edit');

Route::get('/dosen/timesheet', function () {
    return view('dosen.timesheet.index');
})->name('dosen.timesheet.index');

Route::get('/dosen/timesheet/{id}', function ($id) {
    return view('dosen.timesheet.show');
})->name('dosen.timesheet.show');

Route::get('/dosen/notifikasi', function () {
    return view('dosen.notifikasi.index');
})->name('dosen.notifikasi');

Route::get('/dosen/pendaftaran/{id}', function ($id) {
    return view('dosen.pendaftaran.show');
})->name('dosen.pendaftaran.show');