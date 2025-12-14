<?php

use Illuminate\Support\Facades\Route;

// ==========================
// 1. FRONTEND / PUBLIC CONTROLLERS
// ==========================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\V1\CountryController; // Used for helper API
use App\Http\Controllers\Api\V1\MitraController;

// ==========================
// 2. AUTHENTICATION & USER
// ==========================
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;

// ==========================
// 3. ADMIN CONTROLLERS
// ==========================
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\AsesorController; // Master Asesor
use App\Http\Controllers\Admin\AsesiController;
use App\Http\Controllers\Admin\TukAdminController;
use App\Http\Controllers\Admin\CategoryController;

// ==========================
// 4. ASESMEN & FORMULIR CONTROLLERS
// ==========================
use App\Http\Controllers\APL01Controller; // Permohonan
use App\Http\Controllers\Asesi\Apl02\PraasesmenController; // APL-02
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController; // AK-01
use App\Http\Controllers\Asesi\TrackerController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\Asesi\asesmen\AssessmenFRIA04tController;
use App\Http\Controllers\Asesi\asesmen\AssessmenFRIA09Controller;

use App\Http\Controllers\FrMapa01Controller; // MAPA-01
use App\Http\Controllers\Mapa02Controller; // MAPA-02
use App\Http\Controllers\FrAk07Controller; // AK-07
use App\Http\Controllers\Ak02Controller; // AK-02
use App\Http\Controllers\SoalController;

// Instrumen Asesmen
use App\Http\Controllers\IA01Controller;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\Ia06Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\IA08Controller;
use App\Http\Controllers\IA09Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\Ia11Controller;

use App\Http\Controllers\Validator\ValidatorTrackerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================================
// A. RUTE PUBLIK (Landing Page, Info, Jadwal)
// ==========================================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard Redirect (Fallback)
Route::get('/dashboard', fn() => redirect()->route('home'))->name('dashboard');

// Skema Sertifikasi
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema'); // Alias

// Jadwal & Detail
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
Route::get('/jadwal/{id}', [JadwalController::class, 'show'])->name('jadwal.show');
Route::get('/jadwal/{id}/detail', [JadwalController::class, 'detail'])->name('jadwal.detail');
Route::get('/detail-jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');

// Berita
Route::get('/berita/{id}', [HomeController::class, 'showBeritaDetail'])->name('berita.detail');

// Halaman Informasi Statis
Route::get('/alur-sertifikasi', fn() => view('landing_page.page_info.alur-sertifikasi'))->name('info.alur');
Route::get('/visimisi', fn() => view('landing_page.page_profil.visimisi'))->name('profil.visimisi');
Route::get('/struktur', fn() => view('landing_page.page_profil.struktur'))->name('profil.struktur');
Route::get('/mitra', [MitraController::class, 'index'])->name('profil.mitra');

// Informasi TUK
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

// Daftar Asesor
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

// Placeholder Routes (Compatibility)
Route::get('/sertifikasi', fn() => "Halaman Sertifikasi")->name('sertifikasi');


// ==========================================================
// B. AUTHENTICATION
// ==========================================================
require __DIR__ . '/auth.php';

// Register specific route override if needed
Route::post('/register-asesi', [RegisteredUserController::class, 'store'])->name('register.asesi');


// ==========================================================
// C. PROTECTED ROUTES (Middleware: auth)
// ==========================================================
Route::middleware('auth')->group(function () {

    // User Profile
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========================
    // 1. ASESMEN & FORMULIR
    // ========================

    // APL-01 (Permohonan)
    Route::get('/APL_01_1', fn() => view('frontend/APL_01/APL_01_1'))->name('APL_01_1');
    Route::get('/APL_01_2', fn() => view('frontend/APL_01/APL_01_2'))->name('APL_01_2');
    Route::get('/APL_01_3', fn() => view('frontend/APL_01/APL_01_3'))->name('APL_01_3');

    // APL-02 (Asesmen Mandiri)
    Route::get('/APL_02', fn() => view('frontend/APL_02/APL_02'))->name('APL_02');
    // Buka routes/web.php
// Pastikan kamu punya route seperti ini (sesuaikan controller-nya):

Route::post('/asesor/apl02/verifikasi/{id}', [App\Http\Controllers\Asesi\Apl02\PraasesmenController::class, 'verifikasi'])
    ->name('asesor.apl02.verifikasi'); // <--- BAGIAN INI YANG HILANG

    // FR-AK (Ceklis, Banding, dll)
    Route::get('/FR_AK_01', fn() => view('frontend/FR_AK_01'))->name('FR_AK_01');
    // --- TAMBAHKAN BARIS INI (Fix Route AK01) ---
    Route::post('/FR_AK_01/simpan/{id}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])
        ->name('ak01.store');
    Route::get('/FR_AK_02', fn() => view('frontend/AK_02/FR_AK_02'))->name('FR_AK_02');
    Route::get('/FR_AK_03', fn() => view('frontend/AK_03/FR_AK_03'))->name('FR_AK_03');
    Route::get('/FR_AK_04', fn() => view('frontend/FR_AK_04'))->name('FR_AK_04');
    Route::get('/FR_AK_05', fn() => view('frontend/AK_05/FR_AK_05'))->name('FR_AK_05');

    // FR-AK-07
    Route::get('/FR_AK_07/{id}', [FrAk07Controller::class, 'create'])->name('fr-ak-07.create');
    Route::post('/FR_AK_07/{id}', [FrAk07Controller::class, 'store'])->name('fr-ak-07.store');

    // ========================
    // 2. INSTRUMEN ASESMEN (IA)
    // ========================

    Route::middleware(['auth', 'role:superadmin'])->prefix('validator')->group(function () {
        Route::get('/tracker/{id}', [ValidatorTrackerController::class, 'show'])->name('validator.tracker.show');
        Route::post('/tracker/{id}/validasi', [ValidatorTrackerController::class, 'validasi'])->name('validator.tracker.validasi');
    });

    // IA-01
    Route::prefix('ia01/{id_sertifikasi}')->group(function () {
        Route::get('/cover', [IA01Controller::class, 'showCover'])->name('ia01.cover');
        Route::post('/cover', [IA01Controller::class, 'storeCover'])->name('ia01.storeCover');
        Route::get('/step/{urutan}', [IA01Controller::class, 'showStep'])->name('ia01.showStep');
        Route::post('/step/{urutan}', [IA01Controller::class, 'storeStep'])->name('ia01.storeStep');
        Route::get('/finish', [IA01Controller::class, 'showFinish'])->name('ia01.finish');
        Route::post('/finish', [IA01Controller::class, 'storeFinish'])->name('ia01.storeFinish');
        Route::get('/admin', [IA01Controller::class, 'showAdmin'])->name('ia01.admin.show');
    });
    Route::get('/ia01/success', fn() => view('frontend.IA_01.success'))->name('ia01.success_page');

    // IA-02
    Route::get('/ia02/{id_sertifikasi}', [IA02Controller::class, 'show'])->name('ia02.show');
    Route::post('/ia02/{id_sertifikasi}', [IA02Controller::class, 'store'])->name('ia02.store');

    // IA-07
    Route::get('/FR_IA_07', [IA07Controller::class, 'index'])->name('ia07.asesor');
    Route::post('/FR_IA_07/store', [IA07Controller::class, 'store'])->name('ia07.store');

    // IA-08
    Route::get('/ia08/{id_data_sertifikasi_asesi}', [IA08Controller::class, 'show'])
     ->name('ia08.show');
    Route::post('/ia08/store', [IA08Controller::class, 'store'])
        ->name('ia08.store');


    // IA-09
    Route::prefix('IA09')->group(function () {
        Route::get('/asesor', [IA09Controller::class, 'showWawancaraAsesor'])->name('ia09.asesor');
        Route::post('/store', [IA09Controller::class, 'storeWawancara'])->name('ia09.store');
        Route::get('/admin', [IA09Controller::class, 'showWawancaraAdmin'])->name('ia09.admin');
    });

    // IA-11
    Route::get('/FR_IA_11', [Ia11Controller::class, 'create'])->name('ia11.create');
    Route::post('/FR_IA_11/store', [Ia11Controller::class, 'store'])->name('ia11.store');

    // MAPA-02
    Route::get('/mapa02/{id_sertifikasi}', [Mapa02Controller::class, 'show'])->name('mapa02.show');
    Route::post('/mapa02/{id_sertifikasi}', [Mapa02Controller::class, 'store'])->name('mapa02.store');

    // Portofolio
    Route::get('/PORTOFOLIO', [PortofolioController::class, 'index'])->name('PORTOFOLIO');

    //FRIA04_Asesi
    Route::get('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'showIA04AAsesi'])->name('fria04a.asesi.show');
    Route::post('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'storeIA04AAsesi'])->name('fria04a.asesi.store');

    //FRIA04_Asesor
    Route::get('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'showIA04A'])->name('fria04a.show');
    Route::post('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'storeIA04A'])->name('fria04a.store');

    //FRIA09
    Route::get('/asesmen/ia09', [AssessmenFRIA09Controller::class, 'index'])->name('asesmen.ia09.view'); // <-- URL: /asesmen/ia09
    Route::post('/asesmen/ia09/store', [AssessmenFRIA09Controller::class, 'store'])->name('asesmen.ia09.store');

    // ========================
    // 3. CETAK PDF
    // ========================
    Route::prefix('cetak')->group(function () {
        Route::get('/mapa02/{id}', [Mapa02Controller::class, 'cetakPDF'])->name('mapa02.cetak_pdf');
        Route::get('/ia05/{id_asesi}', [IA05Controller::class, 'cetakPDF'])->name('ia05.cetak_pdf');
        Route::get('/ia10/{id_asesi}', [IA10Controller::class, 'cetakPDF'])->name('ia10.cetak_pdf');
        Route::get('/ia02/{id}', [IA02Controller::class, 'cetakPDF'])->name('ia02.cetak_pdf');
        Route::get('/ia06/{id}', [Ia06Controller::class, 'cetakPDF'])->name('ia06.cetak_pdf');
        Route::get('/ia07/{id}', [IA07Controller::class, 'cetakPDF'])->name('ia07.cetak_pdf');
        Route::get('/apl01/{id}', [APL01Controller::class, 'cetakPDF'])->name('apl01.cetak_pdf');
        Route::get('/mapa01/{id}', [FrMapa01Controller::class, 'cetakPDF'])->name('mapa01.cetak_pdf');
        Route::get('/apl02/{id}', [PraasesmenController::class, 'generatePDF'])->name('apl02.cetak_pdf');
        Route::get('/ak01/{id}', [PersetujuanKerahasiaanAPIController::class, 'cetakPDF'])->name('ak01.cetak_pdf');
        Route::get('/ak02/{id}', [Ak02Controller::class, 'cetakPDF'])->name('ak02.cetak_pdf');
    });
    // Legacy mapping (just in case)
    Route::get('/mapa02/cetak/{id}', [Mapa02Controller::class, 'cetakPDF']);
});

// ==========================================================
// D. HELPER APIS (Mixed Use)
// ==========================================================
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');
Route::post('/api/jadwal/daftar', [TrackerController::class, 'daftarJadwal'])->name('api.jadwal.daftar');
Route::get('/tracking', [TrackerController::class, 'index'])->name('tracker');
