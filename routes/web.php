<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
// Controller Web (Cuma buat nampilin halaman)
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\AsesmenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('halaman_ambil_skema'); });
Route::get('/tracker', function () { return view('tracker'); });
Route::get('/data_sertifikasi', function () { return view('formulir pendaftaran/data_sertifikasi'); });
Route::get('/tunggu_upload_dokumen', function () { return view('formulir pendaftaran/tunggu_upload_dokumen'); });
Route::get('/belum_memenuhi', function () { return view('formulir pendaftaran/dokumen_belum_memenuhi'); });
Route::get('/pembayaran', function () { return view('pembayaran/pembayaran'); });
Route::get('/belum_lulus', function () { return view('belum_lulus'); });
Route::get('/bukti_pemohon', function () { return view('formulir pendaftaran/bukti_pemohon'); });
Route::get('/upload_bukti_pembayaran', function () { return view('upload_bukti_pembayaran'); });

// ... (Route praasesmen lu yang banyak itu) ...
Route::get('/praasesmen1', function () { return view('praasesmen1'); });
// ... dst ...

Route::get('/tunggu_pembayaran', function () { return view('tunggu_pembayaran'); });
Route::get('/banding', function () { return view('banding'); });
Route::get('/pertanyaan_lisan', function () { return view('pertanyaan_lisan'); });
Route::get('/umpan_balik', function () { return view('umpan_balik'); });
Route::get('/fr_ak01', function () { return view('persetujuan assesmen dan kerahasiaan.fr_ak01'); });
Route::get('/asesmen/fr-ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.fr_ak01');
Route::get('/verifikasi_tuk', function () { return view('verifikasi_tuk'); });


// ====================================================
// ROUTE TANDA TANGAN (VERSI BERSIH/API)
// ====================================================

// 1. Cuma butuh GET untuk nampilin halamannya aja.
//    Sisanya udah diurus sama api.php dan JavaScript.
Route::get('/halaman-tanda-tangan', [TandaTanganController::class, 'showSignaturePage'])
       ->name('show.tandatangan');

// 2. Halaman "Selesai" (dummy)
Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');

// ====================================================


Route::get('/bayar', [PaymentController::class, 'createTransaction'])->name('payment.create');
Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';