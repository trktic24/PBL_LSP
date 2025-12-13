<?php

use Illuminate\Support\Facades\Route;

// Frontend / Public
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\V1\CountryController;

// Auth
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;

// Admin
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\Admin\AsesiController;
use App\Http\Controllers\Admin\TukAdminController;
use App\Http\Controllers\Admin\CategoryController;

// Asesor / Proses Sertifikasi
use App\Http\Controllers\APL01Controller;
use App\Http\Controllers\Asesi\Apl02\PraasesmenController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\FrMapa01Controller;
use App\Http\Controllers\Mapa02Controller;
use App\Http\Controllers\FrAk07Controller;
use App\Http\Controllers\IA01Controller;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\Ia06Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\IA09Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\Ia11Controller;


// ==========================================================
// RUTE DASAR UNTUK MENGATASI ERROR (DASHBOARD & ROOT)
// ==========================================================

// Rute Root
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Dashboard (Fallback jika dibutuhkan)
Route::get('/dashboard', function () {
    return redirect()->route('home'); // Redirect ke home saja
})->name('dashboard');


// ==========================================================
// HALAMAN PUBLIK (Info, Jadwal, TUK)
// ==========================================================

// Detail Skema
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// Detail Jadwal
Route::get('/detail-jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');

// Detail Berita
Route::get('/berita/{id}', [HomeController::class, 'showBeritaDetail'])->name('berita.detail');

// Info Halaman Statis
Route::get('/alur-sertifikasi', fn() => view('landing_page.page_info.alur-sertifikasi'))->name('info.alur');
Route::get('/visimisi', fn() => view('landing_page.page_profil.visimisi'))->name('profil.visimisi');
Route::get('/struktur', fn() => view('landing_page.page_profil.struktur'))->name('profil.struktur');
Route::get('/mitra', fn() => view('landing_page.page_profil.mitra'))->name('profil.mitra');

// Info TUK
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

// Daftar Asesor (Public View)
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

// ==========================================================
// JADWAL (CONTROLLER)
// ==========================================================
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
Route::get('/jadwal/{id}', [JadwalController::class, 'show'])->name('jadwal.show');
Route::get('/jadwal/{id}/detail', [JadwalController::class, 'detail'])->name('jadwal.detail');


// ==========================================================
// AUTHENTICATION
// ==========================================================
require __DIR__ . '/auth.php';

// Register User Baru (Override or Addition)
Route::post('/register-asesi', [RegisteredUserController::class, 'store'])->name('register.asesi'); // Assuming store handles registration


// ==========================================================
// DILINDUNGI AUTH (USER LOGIN)
// ==========================================================
Route::middleware('auth')->group(function () {
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================================
    // ASESMEN ROUTES
    // ==========================================================
    
    // APL 01 (Permohonan)
    Route::get('/APL_01_1', fn() => view('frontend/APL_01/APL_01_1'))->name('APL_01_1');
    Route::get('/APL_01_2', fn() => view('frontend/APL_01/APL_01_2'))->name('APL_01_2');
    Route::get('/APL_01_3', fn() => view('frontend/APL_01/APL_01_3'))->name('APL_01_3');
    
    // APL 02 (Asesmen Mandiri)
    Route::get('/APL_02', fn() => view('frontend/APL_02/APL_02'))->name('APL_02');

    // FR AK (Asesmen)
    Route::get('/FR_AK_01', fn() => view('frontend/FR_AK_01'))->name('FR_AK_01');
    Route::get('/FR_AK_02', fn() => view('frontend/AK_02/FR_AK_02'))->name('FR_AK_02');
    Route::get('/FR_AK_03', fn() => view('frontend/FR_AK_03'))->name('FR_AK_03');
    Route::get('/FR_AK_04', fn() => view('frontend/FR_AK_04'))->name('FR_AK_04');
    Route::get('/FR_AK_05', fn() => view('frontend/FR_AK_05'))->name('FR_AK_05');

    // FR AK 07
    Route::get('/FR_AK_07/{id}', [FrAk07Controller::class, 'create'])->name('fr-ak-07.create');
    Route::post('/FR_AK_07/{id}', [FrAk07Controller::class, 'store'])->name('fr-ak-07.store');

    // IA Routes (Instrumen Asesmen)
    
    // IA 01 (Ceklis Observasi)
    Route::get('/ia01/{id_sertifikasi}/cover', [IA01Controller::class, 'showCover'])->name('ia01.cover');
    Route::post('/ia01/{id_sertifikasi}/cover', [IA01Controller::class, 'storeCover'])->name('ia01.storeCover');
    Route::get('/ia01/{id_sertifikasi}/step/{urutan}', [IA01Controller::class, 'showStep'])->name('ia01.showStep');
    Route::post('/ia01/{id_sertifikasi}/step/{urutan}', [IA01Controller::class, 'storeStep'])->name('ia01.storeStep');
    Route::get('/ia01/{id_sertifikasi}/finish', [IA01Controller::class, 'showFinish'])->name('ia01.finish');
    Route::post('/ia01/{id_sertifikasi}/finish', [IA01Controller::class, 'storeFinish'])->name('ia01.storeFinish');
    Route::get('/ia01/{id_sertifikasi}/admin', [IA01Controller::class, 'showAdmin'])->name('ia01.admin.show');
    Route::get('/ia01/success', fn() => view('frontend.IA_01.success'))->name('ia01.success_page');

    // IA 02
    Route::get('/ia02/{id_sertifikasi}', [IA02Controller::class, 'show'])->name('ia02.show');
    Route::post('/ia02/{id_sertifikasi}', [IA02Controller::class, 'store'])->name('ia02.store');
    
    // IA 07
    Route::get('/FR_IA_07', [IA07Controller::class, 'index'])->name('ia07.asesor');
    Route::post('/FR_IA_07/store', [IA07Controller::class, 'store'])->name('ia07.store');

    // IA 08
    Route::get('/IA_08', fn() => view('frontend/IA_08/IA_08'))->name('IA08');

    // IA 09
    Route::prefix('IA09')->group(function () {
        Route::get('/asesor', [IA09Controller::class, 'showWawancaraAsesor'])->name('ia09.asesor');
        Route::post('/store', [IA09Controller::class, 'storeWawancara'])->name('ia09.store');
        Route::get('/admin', [IA09Controller::class, 'showWawancaraAdmin'])->name('ia09.admin');
    });

    // IA 11
    Route::get('/FR_IA_11', [Ia11Controller::class, 'create'])->name('ia11.create');
    Route::post('/FR_IA_11/store', [Ia11Controller::class, 'store'])->name('ia11.store');

    // MAPA 02
    Route::get('/mapa02/{id_sertifikasi}', [Mapa02Controller::class, 'show'])->name('mapa02.show');
    Route::post('/mapa02/{id_sertifikasi}', [Mapa02Controller::class, 'store'])->name('mapa02.store');

    // Portofolio
    Route::get('/PORTOFOLIO', [PortofolioController::class, 'index'])->name('PORTOFOLIO');
});

// ==========================================================
// ROUTES PDF (Cetak Dokumen)
// ==========================================================
Route::middleware('auth')->group(function() {
    Route::get('/mapa02/cetak/{id}', [Mapa02Controller::class, 'cetakPDF'])->name('mapa02.cetak_pdf');
    Route::get('/ia05/cetak/{id_asesi}', [IA05Controller::class, 'cetakPDF'])->name('ia05.cetak_pdf');
    Route::get('/ia10/cetak/{id_asesi}', [IA10Controller::class, 'cetakPDF'])->name('ia10.cetak_pdf');
    Route::get('/ia02/cetak/{id}', [IA02Controller::class, 'cetakPDF'])->name('ia02.cetak_pdf');
    Route::get('/ia06/cetak/{id}', [Ia06Controller::class, 'cetakPDF'])->name('ia06.cetak_pdf');
    Route::get('/ia07/cetak/{id}', [IA07Controller::class, 'cetakPDF'])->name('ia07.cetak_pdf');
    Route::get('/apl01/cetak/{id}', [APL01Controller::class, 'cetakPDF'])->name('apl01.cetak_pdf');
    Route::get('/mapa01/cetak/{id}', [FrMapa01Controller::class, 'cetakPDF'])->name('mapa01.cetak_pdf');
    Route::get('/apl02/cetak/{id}', [PraasesmenController::class, 'generatePDF'])->name('apl02.cetak_pdf');
    Route::get('/ak01/cetak/{id}', [PersetujuanKerahasiaanAPIController::class, 'cetakPDF'])->name('ak01.cetak_pdf');
});

// ==========================================================
// API HELPERS
// ==========================================================
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');
