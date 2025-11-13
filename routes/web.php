<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkemaController;

// Import Controller
use App\Http\Controllers\AsesmenController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Apl01PdfController;
use App\Models\Asesor; // Pastikan use Model ini
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\Kerahasiaan\PersetujuanKerahasiaanController;
use App\Models\Asesi; // <-- [PENTING] Tambahin ini buat route /tracker
use App\Http\Controllers\FormulirPendaftaran\DataSertifikasiAsesiController;
use App\Http\Controllers\FormulirPendaftaran\BuktiKelengkapanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. HALAMAN STATIS (VIEW ONLY)
// ====================================================
Route::get('/', function () { return view('halaman_ambil_skema'); });

// --- Formulir Pendaftaran Views ---
Route::get('/data_sertifikasi/{id_asesi}', function ($id_asesi) {
    try {
        // Kita ambil data asesi-nya pake ID dari URL
        $asesi = Asesi::findOrFail($id_asesi); 

        // Sekarang kita kirim data $asesi itu ke view
        return view('formulir_pendaftaran/data_sertifikasi', [
            'asesi' => $asesi
        ]);

    } catch (\Exception $e) {
        // Kalo Asesi ID-nya gak ada, mentalin ke home
        return redirect('/')->with('error', 'Data Asesi tidak ditemukan.');
    }
})->name('data.sertifikasi'); // Kita kasih nama route-nya

Route::get('/tunggu_upload_dokumen', function () { return view('formulir_pendaftaran/tunggu_upload_dokumen'); });
Route::get('/belum_memenuhi', function () { return view('formulir_pendaftaran/dokumen_belum_memenuhi'); });
Route::get('/bukti_pemohon/{id_asesi}', function ($id_asesi) {
    try {
        // Kita ambil data asesi-nya pake ID dari URL
        $asesi = Asesi::findOrFail($id_asesi); 

        // Sekarang kita kirim data $asesi itu ke view
        return view('formulir_pendaftaran/bukti_pemohon', [
            'asesi' => $asesi
        ]);

    } catch (\Exception $e) {
        // Kalo Asesi ID-nya gak ada, mentalin ke home
        return redirect('/')->with('error', 'Data Asesi tidak ditemukan.');
    }
})->name('bukti.pemohon'); // Kita kasih nama route-nya

// --- Pembayaran Views ---
Route::get('/pembayaran', function () { return view('pembayaran/pembayaran'); });
Route::get('/upload_bukti_pembayaran', function () { return view('upload_bukti_pembayaran'); });
Route::get('/tunggu_pembayaran', function () { return view('tunggu_pembayaran'); });

// --- Pra-Asesmen Views ---
Route::get('/praasesmen1', function () { return view('pra-assesmen.praasesmen1'); });
Route::get('/praasesmen2', function () { return view('pra-assesmen.praasesmen2'); });
Route::get('/praasesmen3', function () { return view('pra-assesmen.praasesmen3'); });
Route::get('/praasesmen4', function () { return view('pra-assesmen.praasesmen4'); });
Route::get('/praasesmen5', function () { return view('pra-assesmen.praasesmen5'); });
Route::get('/praasesmen6', function () { return view('pra-assesmen.praasesmen6'); });
Route::get('/praasesmen7', function () { return view('pra-assesmen.praasesmen7'); });
Route::get('/praasesmen8', function () { return view('pra-assesmen.praasesmen8'); });

// --- Asesmen Lainnya Views ---
Route::get('/belum_lulus', function () { return view('belum_lulus'); });
Route::get('/banding', function () { return view('banding'); });
Route::get('/pertanyaan_lisan', function () { return view('pertanyaan_lisan'); });
Route::get('/umpan_balik', function () { return view('umpan_balik'); });
//Route::get('/fr_ak01', function () { return view('persetujuan_assesmen_dan_kerahasiaan/fr_ak01'); });
Route::get('/fr_ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.show_view'); // Ini hanya menampilkan kerangka HTML View
Route::get('/verifikasi_tuk', function () { return view('verifikasi_tuk'); });


// ====================================================
// 2. FEATURE ROUTES (CONTROLLER)
// ====================================================

// --- Tracker (FIXED) ---
// PERHATIAN: URL-nya sekarang jadi /tracker/{id_asesi}
Route::get('/tracker/{id_asesi}', function ($id_asesi) {
    try {
        // Ambil data asesi + relasi 'skema'
        $asesi = Asesi::with('skema')->findOrFail($id_asesi); 

        // Kirim data asesi ke view tracker
        return view('tracker', [
            'asesi' => $asesi
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Jika Asesi dengan ID tsb tidak ada, kembali ke home
        return redirect('/')->with('error', 'Data Asesi tidak ditemukan.');
    }
})->name('tracker');

// --- Tanda Tangan (Web View Only) ---
Route::get('/halaman-tanda-tangan/{id_asesi}', [TandaTanganController::class, 'showSignaturePage'])
       ->name('show.tandatangan');
Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');

// --- Data Sertifikasi ---
Route::get('/formulir/data-sertifikasi', [DataSertifikasiAsesiController::class, 'showDataSertifikasiAsesiPage'])
    ->name('formulir.data-sertifikasi');

<<<<<<< HEAD
<<<<<<< HEAD
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
=======
=======
//bukti kelengkapan
Route::get('/formulir/bukti-pemohon', [BuktiKelengkapanController::class, 'showBuktiKelengkapanPage'])
    ->name('web.bukti_pemohon.show');

>>>>>>> d48454bbf8f4c112e7aadf187f8ed953d9876bdd
// --- Kerahasiaan ---
//Route::get('/asesmen/fr_ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.fr_ak01');
Route::get('/kerahasiaan/fr-ak01/{id_asesi}', [PersetujuanKerahasiaanController::class, 'showFrAk01'])
       ->name('kerahasiaan.fr_ak01');

// --- Pembayaran (Action) ---
>>>>>>> 2422a1906c9cb609afbd5e5a2081f311c507acc0
Route::get('/bayar', [PaymentController::class, 'createTransaction'])->name('payment.create');

// --- PDF ---
Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])->name('apl01.download');
Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])->name('apl01.preview');


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