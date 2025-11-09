<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AsesmenController;
use App\Http\Controllers\Apl01PdfController;
<<<<<<< HEAD
use App\Models\Asesi; // <-- Pastikan ini ada

Route::get('/', function () {
    return view('halaman_ambil_skema');
});

/*
|--------------------------------------------------------------------------
| INI ADALAH ROUTE YANG DIPERBAIKI
|--------------------------------------------------------------------------
|
| 1. URL diubah menjadi '/tracker/{id_asesi}'
| 2. Fungsi 'function()' diubah menjadi 'function($id_asesi)'
| 3. Kita mencari Asesi berdasarkan ID dari URL
| 4. Middleware auth dihilangkan
|
*/
Route::get('/tracker/{id_asesi}', function ($id_asesi) {
    try {
        // Ambil data asesi berdasarkan ID dari URL
        // Kita gunakan with() (eager loading) agar lebih efisien
        $asesi = Asesi::with('skema')->findOrFail($id_asesi); 

        // Kirim data asesi ke view tracker
        return view('tracker', [
            'asesi' => $asesi
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Jika Asesi dengan ID tsb tidak ada, kembali ke home
        return redirect('/')->with('error', 'Data Asesi tidak ditemukan.');
    }
})->name('tracker'); // Kita beri nama 'tracker'

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
=======
// Controller Web Khusus Tanda Tangan
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\FormulirPendaftaran\DataSertifikasiAsesiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === HALAMAN STATIS / VIEW ===
Route::get('/', function () { return view('halaman_ambil_skema'); });
Route::get('/tracker', function () { return view('tracker'); });
Route::get('/data_sertifikasi', function () { return view('formulir_pendaftaran/data_sertifikasi'); });
Route::get('/tunggu_upload_dokumen', function () { return view('formulir_pendaftaran/tunggu_upload_dokumen'); });
Route::get('/belum_memenuhi', function () { return view('formulir_pendaftaran/dokumen_belum_memenuhi'); });

// Hati-hati, tadi ada 2 route '/pembayaran'. Gua pilih yang view dulu.
// Kalo '/pembayaran' itu harusnya langsung transaksi, pakai yang PaymentController.
Route::get('/pembayaran', function () { return view('pembayaran/pembayaran'); });

Route::get('/belum_lulus', function () { return view('belum_lulus'); });
Route::get('/bukti_pemohon', function () { return view('formulir_pendaftaran/bukti_pemohon'); });
Route::get('/upload_bukti_pembayaran', function () { return view('upload_bukti_pembayaran'); });

// Route Pra-asesmen
Route::get('/praasesmen1', function () { return view('praasesmen1'); });
Route::get('/praasesmen2', function () { return view('praasesmen2'); });
Route::get('/praasesmen3', function () { return view('praasesmen3'); });
Route::get('/praasesmen4', function () { return view('praasesmen4'); });
Route::get('/praasesmen5', function () { return view('praasesmen5'); });
Route::get('/praasesmen6', function () { return view('praasesmen6'); });
Route::get('/praasesmen7', function () { return view('praasesmen7'); });
Route::get('/praasesmen8', function () { return view('praasesmen8'); });

Route::get('/tunggu_pembayaran', function () { return view('tunggu_pembayaran'); });
Route::get('/banding', function () { return view('banding'); });
Route::get('/pertanyaan_lisan', function () { return view('pertanyaan_lisan'); });
Route::get('/umpan_balik', function () { return view('umpan_balik'); });
Route::get('/fr_ak01', function () { return view('persetujuan_assesmen_dan_kerahasiaan.fr_ak01'); });
Route::get('/verifikasi_tuk', function () { return view('verifikasi_tuk'); });


// === ROUTE CONTROLLER ===
>>>>>>> 8f4d691803abb5b9928cbe9d78a15339bd791522

// Asesmen
Route::get('/asesmen/fr-ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.fr_ak01');

// Payment
// Tadi ada 2 route '/pembayaran', gua ganti URL ini jadi '/proses-bayar' biar gak bentrok
Route::get('/bayar', [PaymentController::class, 'createTransaction'])->name('payment.create');
Route::get('/proses-bayar', [PaymentController::class, 'createTransaction'])->name('payment.process');

// PDF APL-01
Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])->name('apl01.download');
Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])->name('apl01.preview');


<<<<<<< HEAD
// Route::get('/', [SkemaController::class, 'show'])->defaults('id', 1);

// Route::get('/skema/{id}', [SkemaController::class, 'show'])->name('skema.show');

// === ROUTE UNTUK HALAMAN TANDA TANGAN ===

// 2. Route GET: Buat nampilin halaman tanda tangan
// URL: /halaman-tanda-angan
// Controller: TandaTanganController, method: showSignaturePage
=======
// === FITUR TANDA TANGAN (SUDAH BENAR) ===
>>>>>>> 8f4d691803abb5b9928cbe9d78a15339bd791522
Route::get('/halaman-tanda-tangan', [TandaTanganController::class, 'showSignaturePage'])
       ->name('show.tandatangan');

Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');
// ========================================


//route buat data sertifikasi
Route::get('/formulir/data-sertifikasi', [DataSertifikasiAsesiController::class, 'showDataSertifikasiAsesiPage'])
    ->name('formulir.data-sertifikasi');

// === AUTH & DASHBOARD ===
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

<<<<<<< HEAD
require __DIR__.'/auth.php';


// Route untuk download PDF
Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])
    ->name('apl01.download');

// Route untuk preview PDF (optional)
Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])
    ->name('apl01.preview');

// Route /tracker yang duplikat dan error di bagian bawah file sudah dihapus.
=======
require __DIR__.'/auth.php';
>>>>>>> 8f4d691803abb5b9928cbe9d78a15339bd791522
