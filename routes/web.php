<?php

use App\Http\Controllers\BelajarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TandaTanganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkemaController;


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

Route::get('/tunggu_upload_dokumen', function () {
    return view('tunggu_upload_dokumen');
});

Route::get('/belum_lulus', function () {
    return view('belum_lulus');
});

Route::get('/bukti_pemohon', function () {
    return view('bukti_pemohon');
});

Route::get('/upload_bukti_pembayaran', function () {
    return view('upload_bukti_pembayaran');
});

Route::get('/bukti_pemohon', function () {
    return view('bukti_pemohon');
});

Route::get('/praasesmen1', function () {
    return view('praasesmen1');
});

Route::get('/praasesmen2', function () {
    return view('praasesmen2');
});

Route::get('/praasesmen3', function () {
    return view('praasesmen3');
});

Route::get('/praasesmen4', function () {
    return view('praasesmen4');
});

Route::get('/praasesmen5', function () {
    return view('praasesmen5');
});

Route::get('/praasesmen6', function () {
    return view('praasesmen6');
});

Route::get('/praasesmen7', function () {
    return view('praasesmen7');
});

Route::get('/tunggu_pembayaran', function () {
    return view('tunggu_pembayaran');
});

Route::get('/praasesmen8', function () {
    return view('praasesmen8');
});

Route::get('/banding', function () {
    return view('banding');
});

Route::get('/pertanyaan_lisan', function () {
    return view('pertanyaan_lisan');
});

Route::get('/umpan_balik', function () {
    return view('umpan_balik');
});

Route::get('/fr_ak01', function () {
    return view('fr_ak01');
});

Route::get('/verifikasi_tuk', function () {
    return view('verifikasi_tuk');
});

// Route::get('/', [SkemaController::class, 'show'])->defaults('id', 1);

// Route::get('/skema/{id}', [SkemaController::class, 'show'])->name('skema.show');



Route::get('/daftar-skema', [BelajarController::class, 'index']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/simpan/tandatangan', [TandaTanganController::class, 'simpanTandaTangan'])
    ->name('simpan.tandatangan'); // <--- INI KUNCI UTAMA
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
