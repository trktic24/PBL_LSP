<?php

use Illuminate\Support\Facades\Route;

// ==========================
// 1. FRONTEND / PUBLIC CONTROLLERS
// ==========================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\MitraController;

// ==========================
// 2. AUTHENTICATION & USER
// ==========================

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
use App\Http\Controllers\Asesi\Pdf\Ak01PdfController;
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
use App\Http\Controllers\IA06Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\IA08Controller;
use App\Http\Controllers\IA09Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\IA11Controller;

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
Route::get('/skema/{id}/pdf', [HomeController::class, 'viewPdf'])->name('skema.pdf');
Route::get('/skema/{id}/download', [HomeController::class, 'downloadPdf'])->name('skema.download');

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
    // User Profile

    // ========================
    // 0. SECURE FILE ACCESS (SURGICAL REFACTOR)
    // ========================
    Route::get('secure-doc/{path}', [App\Http\Controllers\SecureFileController::class, 'show'])
        ->where('path', '.*')
        ->name('secure.file');

    // ========================
    // 1. ASESMEN & FORMULIR
    // ========================

    /// ========================
    // APL-01 (Updated sesuai Controller)
    // ========================
    
    // Halaman 1: Data Pribadi (Ini yang tadi error)
    Route::get('/apl01/step-1/{id}', [APL01Controller::class, 'step1'])->name('APL_01_1');
    Route::post('/apl01/step-1/store', [APL01Controller::class, 'storeStep1'])->name('apl01.step1.store');

    // Halaman 2: Data Pekerjaan (Dipanggil pas redirect dari step 1)
    Route::get('/apl01/step-2/{id}', [APL01Controller::class, 'step2'])->name('APL_01_2');
    Route::post('/apl01/step-2/store', [APL01Controller::class, 'storeStep2'])->name('apl01.step2.store');

    // Halaman 3: Bukti Kelengkapan
    Route::get('/apl01/step-3/{id}', [APL01Controller::class, 'step3'])->name('APL_01_3');

    

    // APL-02 (Asesmen Mandiri)
    Route::get('/APL_02', fn() => view('frontend/APL_02/APL_02'))->name('APL_02');

    Route::post('/asesor/apl02/verifikasi/{id}', [App\Http\Controllers\Asesi\Apl02\PraasesmenController::class, 'verifikasi'])
        ->name('asesor.apl02.verifikasi'); // <--- BAGIAN INI YANG HILANG

    // FR-AK (Ceklis, Banding, dll)
    Route::get('/FR_AK_01', fn() => view('frontend/FR_AK_01'))->name('FR_AK_01');

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

    Route::get('/ia01/success', fn() => view('frontend.IA_01.success'))->name('ia01.success_page');

    // IA-01
    Route::prefix('ia01/{id_sertifikasi}')->group(function () {
        // Form Editable (Asesor Only)
        Route::get('/', [IA01Controller::class, 'index'])->name('ia01.index');
        Route::get('/cover', [IA01Controller::class, 'index'])->name('ia01.cover'); // Alias for compatibility
        
        // Store (Asesor Only - protected in controller)
        Route::post('/store', [IA01Controller::class, 'store'])->name('ia01.store');

        // View Read-Only (Admin & Asesi)
        Route::get('/view', [IA01Controller::class, 'showView'])->name('ia01.view');
        
        // Legacy route for backward compatibility
        Route::get('/admin', [IA01Controller::class, 'showView'])->name('ia01.admin.show');

        // Deprecated / Redirects
        Route::get('/step/{urutan}', fn($id) => redirect()->route('ia01.index', $id));
        Route::get('/finish', fn($id) => redirect()->route('ia01.index', $id));
    });

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
    Route::get('/FR_IA_11', [IA11Controller::class, 'create'])->name('ia11.create');
    Route::post('/FR_IA_11/store', [IA11Controller::class, 'store'])->name('ia11.store');

    // MAPA-02


    // Portofolio
    Route::get('/PORTOFOLIO', [PortofolioController::class, 'index'])->name('PORTOFOLIO');

    //FRIA04_Asesi
    Route::get('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'showIA04AAsesi'])->name('fria04a.asesi.show');
    Route::post('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'storeIA04AAsesi'])->name('fria04a.asesi.store');

    //FRIA04_Asesor
    Route::get('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'showIA04A'])->name('fria04a.show');
    Route::post('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'storeIA04A'])->name('fria04a.store');

    // ========================
    // 3. CETAK PDF
    // ========================
    Route::prefix('cetak')->group(function () {
        Route::get('/mapa02/{id}', [Mapa02Controller::class, 'cetakPDF'])->name('mapa02.cetak_pdf');
        Route::get('/ia05/{id_asesi}', [IA05Controller::class, 'cetakPDF'])->name('ia05.cetak_pdf');
        Route::get('/ia10/{id_asesi}', [IA10Controller::class, 'cetakPDF'])->name('ia10.cetak_pdf');
        Route::get('/ia02/{id}', [IA02Controller::class, 'cetakPDF'])->name('ia02.cetak_pdf');
        Route::get('/ia06/{id}', [IA06Controller::class, 'cetakPDF'])->name('ia06.cetak_pdf');
        Route::get('/ia07/{id}', [IA07Controller::class, 'cetakPDF'])->name('ia07.cetak_pdf');
        Route::get('/apl01/{id}', [APL01Controller::class, 'cetakPDF'])->name('apl01.cetak_pdf');
        Route::get('/mapa01/{id}', [FrMapa01Controller::class, 'cetakPDF'])->name('mapa01.cetak_pdf');
        Route::get('/apl02/{id}', [PraasesmenController::class, 'generatePDF'])->name('apl02.cetak_pdf');
        Route::get('/ak01/{id}', [Ak01PdfController::class, 'generateAk01'])->name('ak01.cetak_pdf');
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
