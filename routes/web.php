<?php

use Illuminate\Support\Facades\Route;

// --- Controllers Import ---
use App\Http\Controllers\TukController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\Api\CountryController;

// --- Controllers Asesi Import ---
use App\Http\Controllers\Asesi\TrackerController;
use App\Http\Controllers\Asesi\Apl01PdfController;
use App\Http\Controllers\Asesi\IA02\Ia02Controller;
use App\Http\Controllers\Asesi\IA03\IA03Controller;
use App\Http\Controllers\Asesi\IA07\Ia07Controller;
use App\Http\Controllers\Asesi\IA11\IA11Controller;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Asesi\Apl02\PraasesmenController;
use App\Http\Controllers\Asesi\umpan_balik\Ak03Controller;
use App\Http\Controllers\Asesi\IA11\PerformaIA11Controller;
use App\Http\Controllers\Asesi\Ak04API\APIBandingController;
use App\Http\Controllers\Asesi\pembayaran\PaymentController;
use App\Http\Controllers\Asesi\asesmen\AsesmenEsaiController;
use App\Http\Controllers\Asesi\IA11\SpesifikasiIA11Controller;
use App\Http\Controllers\Asesi\JadwalTukAPI\JadwalTukAPIController;
use App\Http\Controllers\Asesi\asesmen\AsesmenPilihanGandaController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\TandaTanganAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\DataSertifikasiAsesiController;
use App\Http\Controllers\Asesi\asesmen\AssessmenFRIA04tController;



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

    // Route untuk menampilkan Form Portofolio (Halaman Awal)
Route::get('/portofolio', [PortofolioController::class, 'index'])
    ->name('portofolio.index');

// Route untuk memproses/menyimpan file yang diunggah Asesi
Route::post('/portofolio/store', [PortofolioController::class, 'store'])
    ->name('portofolio.store');

// Route untuk menghapus/membatalkan dokumen (jika Anda ingin mengimplementasikan fungsi hapus)
Route::delete('/portofolio/delete', [PortofolioController::class, 'destroyBukti'])
    ->name('portofolio.destroy');

    // Route untuk menampilkan form FR.IA.04A (GET) - ASESOR
// TIDAK ADA PARAMETER ID
Route::get('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'showIA04A'])->name('fria04a.show');

// Route untuk menangani submit form FR.IA.04A (POST) - ASESOR
Route::post('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'storeIA04A'])->name('fria04a.store');


// --- ASESI VIEW & SUBMIT ---
// Route baru untuk menampilkan form (GET) - ASESI (READ-ONLY + INPUT TANGGAPAN)
// TIDAK ADA PARAMETER ID
Route::get('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'showIA04AAsesi'])->name('fria04a.asesi.show');

// Route baru untuk menyimpan input ASESI (Hanya Tanggapan)
Route::post('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'storeIA04AAsesi'])->name('fria04a.asesi.store');

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

    // --- IA.01 sementara (biar tidak error) ---
    Route::get('/ia01/{id_sertifikasi}', function ($id_sertifikasi) {
        return "HALAMAN IA01 BELUM DIBUAT — ID: " . $id_sertifikasi;
    })->name('ia01.index');

    // --- ROUTE FR.IA.02 (TUGAS PRAKTIK / DEMONSTRASI) ---

    // 1. Menampilkan halaman IA02 (READ-ONLY)
    // id_sertifikasi = ID Data Sertifikasi Asesi
    Route::get('/ia02/{id_sertifikasi}', [Ia02Controller::class, 'index'])
        ->name('ia02.index');

   
    // 2. Tombol "Selanjutnya" → redirect ke IA03
    Route::post('/ia02/{id_sertifikasi}/next', [Ia02Controller::class, 'next'])
        ->name('ia02.next');

    // Halaman utama IA03 (list pertanyaan + identitas lengkap)
    Route::get('/ia03/{id_data_sertifikasi_asesi}', [IA03Controller::class, 'index'])->name('ia03.index');

    // Halaman detail satu pertanyaan (opsional)
    Route::get('/ia03/detail/{id}', [IA03Controller::class, 'show'])->name('ia03.show');



    // --- ROUTE FR.IA.07 (PERTANYAAN LISAN) ---

    // 1. Route untuk Menampilkan Form (GET)
    // Parameter {id_sertifikasi} diperlukan agar Controller tahu data siapa yang ditampilkan
    Route::get('/asesi/ia07/{id_sertifikasi}', [Ia07Controller::class, 'index'])->name('ia07.index');

    // --- ROUTE FR.IA.11 (CEKLIS REVIU PRODUK) ---
    // 1. Route untuk Menampilkan Data (READ)
    Route::get('/ia11/{id_data_sertifikasi_asesi}', [IA11Controller::class, 'show'])->name('ia11.index');

    // 2. Route untuk Menyimpan Data Baru (POST)
    Route::post('/ia11/store', [IA11Controller::class, 'store'])->name('ia11.store');

    // 3. Route untuk Memperbarui Data (PUT/PATCH)
    // Menggunakan ID primary key dari tabel IA11, bukan ID sertifikasi
    Route::put('/ia11/{id}', [IA11Controller::class, 'update'])->name('ia11.update');

    // 4. Route untuk Menghapus Data (DELETE)
    Route::delete('/ia11/{id}', [IA11Controller::class, 'destroy'])->name('ia11.destroy');

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

// ====================================================
// ROUTE ADMIN (MASTER DATA IA.11)
// ====================================================

// Asumsi route ini berada di belakang middleware Admin
Route::prefix('admin/master/ia11')
    ->name('admin.master.ia11.')
    ->group(function () {
        // --- Spesifikasi Produk Master ---
        Route::resource('spesifikasi', SpesifikasiIA11Controller::class)->except(['create', 'edit']) ->middleware('user.type:admin');

        // --- Performa Produk Master ---
        Route::resource('performa', PerformaIA11Controller::class)->except(['create', 'edit'])->middleware('user.type:admin');
    });
