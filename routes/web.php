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

Route::get('/dosen/dashboard', function () {
    return view('dosen.dashboard');
});

Route::get('/dosen/lowongan/create', function () {
    return view('dosen.lowongan.create'); 
});

Route::get('/dosen/timesheet', function () {
    return view('dosen.timesheet.index');
});

Route::get('/dosen/timesheet/detail', function () {
    return view('dosen.timesheet.detail');
});