<?php

use Illuminate\Support\Facades\Route;

// Import Controller
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AsesmenController;
use App\Http\Controllers\Apl01PdfController;
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\FormulirPendaftaran\DataSertifikasiAsesiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. HALAMAN STATIS (VIEW ONLY)
// ====================================================
Route::get('/', function () { return view('halaman_ambil_skema'); });
Route::get('/tracker', function () { return view('tracker'); });
Route::get('/belum_lulus', function () { return view('belum_lulus'); });

// Group Formulir Pendaftaran
Route::prefix('formulir')->group(function () {
    // Note: URL asli lu gak pake prefix 'formulir', jadi gua balikin ke aslinya
    // biar gak ngerusak link yang udah ada.
});
Route::get('/data_sertifikasi', function () { return view('formulir_pendaftaran/data_sertifikasi'); });
Route::get('/tunggu_upload_dokumen', function () { return view('formulir_pendaftaran/tunggu_upload_dokumen'); });
Route::get('/belum_memenuhi', function () { return view('formulir_pendaftaran/dokumen_belum_memenuhi'); });
Route::get('/bukti_pemohon', function () { return view('formulir_pendaftaran/bukti_pemohon'); });

// Group Pembayaran View
Route::get('/pembayaran', function () { return view('pembayaran/pembayaran'); });
Route::get('/upload_bukti_pembayaran', function () { return view('upload_bukti_pembayaran'); });
Route::get('/tunggu_pembayaran', function () { return view('tunggu_pembayaran'); });

// Group Pra-Asesmen View
Route::get('/praasesmen1', function () { return view('praasesmen1'); });
Route::get('/praasesmen2', function () { return view('praasesmen2'); });
Route::get('/praasesmen3', function () { return view('praasesmen3'); });
Route::get('/praasesmen4', function () { return view('praasesmen4'); });
Route::get('/praasesmen5', function () { return view('praasesmen5'); });
Route::get('/praasesmen6', function () { return view('praasesmen6'); });
Route::get('/praasesmen7', function () { return view('praasesmen7'); });
Route::get('/praasesmen8', function () { return view('praasesmen8'); });

// Group Asesmen Lainnya View
Route::get('/banding', function () { return view('banding'); });
Route::get('/pertanyaan_lisan', function () { return view('pertanyaan_lisan'); });
Route::get('/umpan_balik', function () { return view('umpan_balik'); });
Route::get('/fr_ak01', function () { return view('persetujuan assesmen dan kerahasiaan.fr_ak01'); });
Route::get('/verifikasi_tuk', function () { return view('verifikasi_tuk'); });


// ====================================================
// 2. FEATURE ROUTES (CONTROLLER)
// ====================================================

// --- Tanda Tangan (Web View Only) ---
Route::get('/halaman-tanda-tangan', [TandaTanganController::class, 'showSignaturePage'])
       ->name('show.tandatangan');
Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');

// --- Data Sertifikasi ---
Route::get('/formulir/data-sertifikasi', [DataSertifikasiAsesiController::class, 'showDataSertifikasiAsesiPage'])
    ->name('formulir.data-sertifikasi');

// --- Asesmen ---
Route::get('/asesmen/fr-ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.fr_ak01');

// --- Pembayaran (Action) ---
Route::get('/bayar', [PaymentController::class, 'createTransaction'])->name('payment.create');

// --- PDF ---
Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])->name('apl01.download');
Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])->name('apl01.preview');

// --- Skema (Optional, uncomment kalo butuh) ---
// Route::get('/skema/{id}', [SkemaController::class, 'show'])->name('skema.show');


// ====================================================
// 3. AUTH & DASHBOARD
// ====================================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Load Auth Routes (Login, Register, dll)
require __DIR__.'/auth.php';