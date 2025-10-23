<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('halaman_ambil_skema');
});

Route::get('/tracker', function () {
    return view('tracker');
});

Route::get('/data_sertifikasi', function () {
    return view('data_sertifikasi');
});

Route::get('/tanda_tangan_pemohon', function () {
    return view('tanda_tangan_pemohon');
});

Route::get('/upload_bukti_pembayaran', function () {
    return view('upload_bukti_pembayaran');
});

Route::get('/bukti_pemohon', function () {
    return view('bukti_pemohon');
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
