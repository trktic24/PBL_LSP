<?php

use Illuminate\Support\Facades\Route;

// --- Controllers Import ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Api\CountryController;

// --- Controllers Asesi & Asesor Import ---
use App\Http\Controllers\Asesi\TrackerController;
use App\Http\Controllers\Asesi\Apl01PdfController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Asesi\Apl02\PraasesmenController;
use App\Http\Controllers\Asesi\umpan_balik\Ak03Controller;
use App\Http\Controllers\Asesi\Ak04API\APIBandingController;
use App\Http\Controllers\Asesi\pembayaran\PaymentController;
use App\Http\Controllers\Asesi\asesmen\AsesmenEsaiController;
use App\Http\Controllers\Asesi\JadwalTukAPI\JadwalTukAPIController;
use App\Http\Controllers\Asesi\asesmen\AsesmenPilihanGandaController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\DataSertifikasiAsesiController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\TandaTanganAPIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Struktur:
| 1. Public Routes (Landing Page, Info, dll)
| 2. Authentication (Login/Register)
| 3. Authenticated Routes (Harus Login)
|    a. Dashboard & Tracker
|    b. Formulir APL-01 (Pendaftaran)
|    c. Formulir APL-02 (Pra-Asesmen)
|    d. Asesmen & Ujian (IA.05, IA.06)
|    e. Persetujuan & Banding (AK.01, AK.04)
|    f. Pembayaran
|    g. Utilities (PDF, dll)
| 4. API & Utilities
*/

// ====================================================
// 1. PUBLIC ROUTES (GUEST ALLOWED)
// ====================================================

// Landing Page & Skema
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/skema/{id}', 'show')->name('skema.detail');
    Route::get('/detail_skema/{id}', 'show')->name('detail_skema'); // Alias
    Route::get('/detail_jadwal/{id}', 'showJadwalDetail')->name('detail_jadwal');
    Route::get('/jadwal/{id}', 'showJadwalDetail')->name('jadwal.detail'); // Alias
});

// Info Jadwal & Asesor
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

// Info TUK
Route::controller(TukController::class)->group(function () {
    Route::get('/info-tuk', 'index')->name('info.tuk');
    Route::get('/detail-tuk/{id}', 'showDetail')->name('info.tuk.detail');
});

// Halaman Statis (Profil & Info)
Route::prefix('info')
    ->name('info.')
    ->group(function () {
        Route::get('/alur-sertifikasi', fn() => view('landing_page.page_info.alur-sertifikasi'))->name('alur');
    });

Route::prefix('profil')
    ->name('profil.')
    ->group(function () {
        Route::get('/visimisi', fn() => view('landing_page.page_profil.visimisi'))->name('visimisi');
        Route::get('/struktur', fn() => view('landing_page.page_profil.struktur'))->name('struktur');
        Route::get('/mitra', fn() => view('landing_page.page_profil.mitra'))->name('mitra');
    });

// Placeholder Sertifikasi
Route::get('/sertifikasi', fn() => 'Halaman Sertifikasi')->name('sertifikasi');

// ====================================================
// 2. AUTHENTICATION
// ====================================================
require __DIR__ . '/auth.php';

// ====================================================
// 3. AUTHENTICATED ROUTES (USER HARUS LOGIN)
// ====================================================
Route::middleware(['auth'])->group(function () {
    // --- A. Dashboard & Tracker ---
    Route::get('/dashboard', fn() => view('dashboard'))->middleware('verified')->name('dashboard');

    Route::controller(TrackerController::class)->group(function () {
        Route::get('/tracker/{jadwal_id?}', 'index')->name('tracker');
        Route::post('/api/jadwal/daftar', 'daftarJadwal')->name('api.jadwal.daftar');
    });

    // --- B. Formulir APL-01 (Pendaftaran) ---
    // Menggunakan Controller yang baru saja kita rapikan
    Route::get('/data_sertifikasi/{id_sertifikasi}', [DataSertifikasiAsesiController::class, 'showFormulir'])->name('data.sertifikasi');
    Route::get('/bukti_pemohon/{id_sertifikasi}', [BuktiKelengkapanController::class, 'showBuktiPemohon'])->name('bukti.pemohon');
    Route::get('/halaman-tanda-tangan/{id_sertifikasi}', [TandaTanganAPIController::class, 'showTandaTangan'])->name('show.tandatangan');

    Route::get('/formulir-selesai', fn() => 'BERHASIL DISIMPAN! Ini halaman selanjutnya.')->name('form.selesai');

    // --- C. Formulir APL-02 (Pra-Asesmen) ---
    Route::get('/pra-asesmen/{id_sertifikasi}', [PraasesmenController::class, 'index'])->name('apl02.view');

    // --- D. Persetujuan & Jadwal TUK ---
    // Persetujuan Kerahasiaan (FR.AK.01)
    Route::get('/kerahasiaan/fr-ak01/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'show'])->name('kerahasiaan.fr_ak01');
    // Konfirmasi Jadwal TUK
    Route::get('/jadwal-tuk/{id_sertifikasi}', [JadwalTukAPIController::class, 'show'])->name('show.jadwal_tuk');

    // --- E. Asesmen / Ujian (IA.05 & IA.06) ---
    Route::get('/asesmen/ia05/{id_sertifikasi}', [AsesmenPilihanGandaController::class, 'indexPilihanGanda'])->name('asesmen.ia05.view');
    Route::get('/asesmen/ia06/{id_sertifikasi}', [AsesmenEsaiController::class, 'indexEsai'])->name('asesmen.ia06.view');

    // --- F. Pasca Asesmen (Banding & Umpan Balik) ---
    // Umpan Balik (AK.03)
    Route::get('/umpan-balik/{id}', [Ak03Controller::class, 'index'])->name('ak03.index');
    Route::post('/umpan-balik/store/{id}', [Ak03Controller::   class, 'store'])->name('ak03.store');
    // Banding (AK.04)
    Route::get('/banding/fr-ak04/{id_sertifikasi}', [APIBandingController::class, 'show'])->name('banding.fr_ak04');

    // --- G. Pembayaran (Midtrans) ---
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/bayar/{id_sertifikasi}', 'createTransaction')->name('payment.create');
        Route::get('/pembayaran_diproses', 'processed')->name('pembayaran_diproses'); // Callback sukses
        Route::get('/pembayaran_batal', 'paymentCancel')->name('payment.cancel'); // Callback batal
        Route::get('/payment/{id_sertifikasi}/invoice', 'downloadInvoice')->name('payment.invoice');
    });

    // --- H. Utilities (PDF & Cetak) ---
    Route::get('/cetak/apl01/{id_data_sertifikasi}', [Apl01PdfController::class, 'generateApl01'])->name('pdf.apl01');
}); // End of Middleware Auth

// ====================================================
// 4. API & UTILITIES (NON-AUTH / MIXED)
// ====================================================

// Keep Alive Session
Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});

// API Wilayah
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');
