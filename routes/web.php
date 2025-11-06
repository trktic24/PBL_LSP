<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JadwalController; 
use Illuminate\Support\Facades\Route;

Route::get('/navbar', function () {
    return view('navbar-fix.blade.php');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/home', function () {
    return view('frontend/home');
})->name('home');

Route::get('/laporan', function () {
    return view('frontend/laporan');
})->name('laporan');
Route::get('/profil', function () {
    return view('frontend/profil');
})->name('profil');

Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

Route::get('/detail-jadwal/{id}', [JadwalController::class, 'detail'])->name('detail.jadwal');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
