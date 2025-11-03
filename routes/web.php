<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// --------------------
// Halaman Umum (static page)
// --------------------
Route::get('/navbar', function () {
    return view('navbar-fix');
})->name('navbar');

Route::get('/daftar_asesi', function () {
    return view('frontend/daftar_asesi');
})->name('daftar_asesi');

Route::get('/tracker', function () {
    return view('frontend/tracker');
})->name('tracker');

// --------------------
// Halaman Home & Detail Skema
// --------------------
// Bisa ubah '/home' jadi '/' biar jadi halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// --------------------
// Halaman Tambahan dari Controller
// --------------------
Route::get('/jadwal', [HomeController::class, 'jadwal'])->name('jadwal');
Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');

// --------------------
// Dashboard (hanya untuk user login)
// --------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');
});

require __DIR__.'/auth.php';
