<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\AsesmenController;
use App\Http\Controllers\Apl01PdfController;


Route::get('/', function () {
    return view('halaman_ambil_skema');
});

Route::get('/tracker', function () {
    return view('tracker');
});

Route::get('/data_sertifikasi', function () {
    return view('formulir_pendaftaran/data_sertifikasi');
});;

Route::get('/tunggu_upload_dokumen', function () {
    return view('formulir_pendaftaran/tunggu_upload_dokumen');
});

Route::get('/belum_memenuhi', function () {
    return view('formulir_pendaftaran/dokumen_belum_memenuhi');
});

Route::get('/pembayaran', function () {
    return view('pembayaran/pembayaran');
});

Route::get('/belum_lulus', function () {
    return view('belum_lulus');
});

Route::get('/bukti_pemohon', function () {
    return view('formulir_pendaftaran/bukti_pemohon');
});

Route::get('/upload_bukti_pembayaran', function () {
    return view('upload_bukti_pembayaran');
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
    // Ubah dari 'fr_ak01' menjadi 'persetujuan assesmen dan kerahasiaan.fr_ak01'
    return view('persetujuan assesmen dan kerahasiaan.fr_ak01'); 
});

Route::get('/asesmen/fr-ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.fr_ak01');

Route::get('/fr_ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.fr_ak01');

Route::get('/verifikasi_tuk', function () {
    return view('verifikasi_tuk');
});

Route::post('/simpan-tandatangan', [TandaTanganController::class, 'store'])
    ->name('simpan.tandatangan');

// Route::get('/', [SkemaController::class, 'show'])->defaults('id', 1);

// Route::get('/skema/{id}', [SkemaController::class, 'show'])->name('skema.show');

// === ROUTE UNTUK HALAMAN TANDA TANGAN ===

// 2. Route GET: Buat nampilin halaman tanda tangan
// URL: /halaman-tanda-tangan
// Controller: TandaTanganController, method: showSignaturePage
Route::get('/halaman-tanda-tangan', [TandaTanganController::class, 'showSignaturePage'])
       ->name('show.tandatangan');

// 3. Route POST: Buat nerima data form pas di-submit
// URL: /simpan-tanda-tangan
// Controller: TandaTanganController, method: store
Route::post('/simpan-tanda-tangan', [TandaTanganController::class, 'store'])
       ->name('simpan.tandatangan');

// 4. Route GET: Halaman tujuan setelah sukses submit
// (Ini halaman dummy, sesuai redirect di controller)
Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');

// !!! TAMBAHKAN ROUTE INI !!!
Route::post('/ajax-simpan-tandatangan', [TandaTanganController::class, 'storeAjax'])
       ->name('simpan.tandatangan.ajax');

// === AKHIR ROUTE TANDA TANGAN ===

Route::get('/pembayaran', [PaymentController::class, 'createTransaction'])->name('payment.create');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Route untuk download PDF
Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])
    ->name('apl01.download');

// Route untuk preview PDF (optional)
Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])
    ->name('apl01.preview');