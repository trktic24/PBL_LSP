<?php

use App\Models\Asesi;

// ====================================================
// KUMPULAN SEMUA CONTROLLER & MODEL
// ====================================================
use App\Models\Skema;
use App\Models\Asesor;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TukController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AsesmenController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\Apl01PdfController;
use App\Http\Controllers\SkemaWebController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\FormulirPendaftaran\BuktiKelengkapanController;
use App\Models\DataSertifikasiAsesi; // <-- [PENTING] Saya tambahkan ini
use App\Http\Controllers\FormulirPendaftaran\DataSertifikasiAsesiController;
use App\Http\Controllers\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// GRUP 1: LANDING PAGE & HALAMAN INFO UTAMA
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Halaman Utama
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');
Route::get('/sertifikasi', function () {
    return 'Halaman Sertifikasi'; // Placeholder
})->name('sertifikasi');

// Rute Detail Skema & Jadwal
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema'); // Alias
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail'); // Alias

// Rute Halaman Info Profil & TUK
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

Route::get('/visimisi', function () {
    return view('landing_page.page_profil.visimisi');
})->name('profil.visimisi');
Route::get('/struktur', function () {
    return view('landing_page.page_profil.struktur');
})->name('profil.struktur');
Route::get('/mitra', function () {
    return view('landing_page.page_profil.mitra');
})->name('profil.mitra');

// ====================================================
// GRUP 2: AUTENTIKASI & DASHBOARD
// ====================================================

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route untuk file auth.php
require __DIR__ . '/auth.php';

// ====================================================
// GRUP 3: TRACKER ASESI (WAJIB LOGIN)
// ====================================================

Route::get('/tracker/{jadwal_id?}', [TrackerController::class, 'index'])
    ->middleware('auth')
    ->name('tracker');

// API (AJAX) untuk mendaftar ke jadwal
Route::post('/api/jadwal/daftar', [TrackerController::class, 'daftarJadwal'])
    ->middleware('auth')
    ->name('api.jadwal.daftar');

// ====================================================
// GRUP 4: FORMULIR PENDAFTARAN (APL-01, APL-02, DLL)
// ====================================================

// --- Formulir APL-01: Data Sertifikasi ---
// INI ADALAH ROUTE YANG BENAR (YANG LAMA SUDAH SAYA HAPUS)
Route::get('/data_sertifikasi/{id_sertifikasi}', function ($id_sertifikasi) {
    try {
        // Kita cari data pendaftarannya (sertifikasi), BUKAN asesinya
        $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);

        // Kirim ID pendaftaran dan data asesi (untuk sidebar) ke Blade
        return view('formulir_pendaftaran/data_sertifikasi', [
            'id_sertifikasi_untuk_js' => $sertifikasi->id_data_sertifikasi_asesi,
            'asesi' => $sertifikasi->asesi, // <-- Ini buat sidebar & link "Selanjutnya"
        ]);
    } catch (\Exception $e) {
        // Kalo gak ketemu, balikin ke tracker
        return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
    }
})->name('data.sertifikasi');

Route::get('/bukti_pemohon/{id_sertifikasi}', function ($id_sertifikasi) {
    try {
        // [PERBAIKAN] Ambil data berdasarkan ID Sertifikasi (Pendaftaran), bukan ID Asesi
        // Kita juga load 'asesi'-nya biar sidebar tetep jalan
        $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);

        return view('formulir_pendaftaran/bukti_pemohon', [
            'sertifikasi' => $sertifikasi, // Kirim data pendaftaran lengkap
            'asesi' => $sertifikasi->asesi, // Kirim data orangnya buat sidebar
        ]);
    } catch (\Exception $e) {
        return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
    }
})->name('bukti.pemohon');

Route::get('/halaman-tanda-tangan/{id_sertifikasi}', function ($id_sertifikasi) {
    try {
        // 1. Cari Data Pendaftaran berdasarkan ID Pendaftaran (BUKAN ID Asesi)
        $sertifikasi = DataSertifikasiAsesi::with('asesi.dataPekerjaan')->findOrFail($id_sertifikasi);

        return view('formulir_pendaftaran/tanda_tangan_pemohon', [
            'sertifikasi' => $sertifikasi,
            'asesi' => $sertifikasi->asesi,
        ]);
    } catch (\Exception $e) {
        // 2. INI YANG BIKIN KAMU MENTAL.
        // Kalau ID Sertifikasi salah kirim (misal kirim ID Asesi padahal butuh ID Sertifikasi),
        // findOrFail akan gagal dan masuk ke sini.
        return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
    }
})->name('show.tandatangan');

// --- Halaman Statis Formulir ---
Route::get('/tunggu_upload_dokumen', function () {
    return view('formulir_pendaftaran/tunggu_upload_dokumen');
});
Route::get('/belum_memenuhi', function () {
    return view('formulir_pendaftaran/dokumen_belum_memenuhi');
});
Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');

// ====================================================
// GRUP 5: ASESMEN & PEMBAYARAN
// ====================================================

// Route::get('/praasesmen', [PraasesmenController::class, 'index']);

// OPSI 2: Menggunakan URL spesifik (Direkomendasikan)
// Ini lebih aman dan rapi, biasanya untuk formulir atau fitur tertentu.
// Contoh: Mengakses formulir di domainanda.com/asesmen/pra
// Route::get('/asesmen/pra', [PraAsesmenController::class, 'index']);

// // --- Pra-Asesmen Views ---
Route::get('/praasesmen1', function () {
    return view('pra-assesmen.praasesmen1');
});
Route::get('/praasesmen2', function () {
    return view('pra-assesmen.praasesmen2');
});
Route::get('/praasesmen3', function () {
    return view('pra-assesmen.praasesmen3');
});
Route::get('/praasesmen4', function () {
    return view('pra-assesmen.praasesmen4');
});
Route::get('/praasesmen5', function () {
    return view('pra-assesmen.praasesmen5');
});
Route::get('/praasesmen6', function () {
    return view('pra-assesmen.praasesmen6');
});
Route::get('/praasesmen7', function () {
    return view('pra-assesmen.praasesmen7');
});
Route::get('/praasesmen8', function () {
    return view('pra-assesmen.praasesmen8');
});

// --- Asesmen Lainnya Views ---
// --- PDF Download ---
Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])->name('apl01.download');
Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])->name('apl01.preview');

// --- Halaman Statis Asesmen ---
Route::get('/praasesmen1', function () {
    return view('pra-assesmen.praasesmen1');
});
Route::get('/praasesmen2', function () {
    return view('pra-assesmen.praasesmen2');
});


// ====================================================
// GRUP 6: ROUTE LAIN-LAIN (API & KEEP ALIVE)
// ====================================================

Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');

// 1. Route Klik Bayar
Route::get('/bayar/{id_sertifikasi}', [PaymentController::class, 'createTransaction'])
    ->middleware('auth')
    ->name('payment.create');

// 2. Route Sukses/Finish (Callback Midtrans)
// [PERBAIKAN] Nama route disesuaikan dengan config Midtrans di controller
Route::get('/pembayaran_diproses', [PaymentController::class, 'processed'])->name('pembayaran_diproses');

// 3. Route Batal/Cancel
Route::get('/pembayaran_batal', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

Route::get('/kerahasiaan/fr-ak01/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'show'])
       ->name('kerahasiaan.fr_ak01');