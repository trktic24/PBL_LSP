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
use App\Http\Controllers\Admin\AsesorProfileController; // Asesor Profile
use App\Http\Controllers\Admin\AsesiProfileController; 
use App\Http\Controllers\Admin\AsesiController;
use App\Http\Controllers\Admin\TukAdminController;
use App\Http\Controllers\Admin\CategoryController;

// ==========================
// 4. ASESMEN & FORMULIR CONTROLLERS
// ==========================
use App\Http\Controllers\APL01Controller; // Permohonan
use App\Http\Controllers\Asesi\Apl02\PraasesmenController; // APL-02
/* use App\Http\Controllers\Asesi\Apl02\Apl02Controller; */
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController; // AK-01
use App\Http\Controllers\Asesi\Ak01Controller;
use App\Http\Controllers\Asesi\TrackerController;
use App\Http\Controllers\Asesi\Pdf\Ak01PdfController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\Asesi\asesmen\AssessmenFRIA04tController;
use App\Http\Controllers\Asesi\asesmen\AssessmenFRIA09Controller;

use App\Http\Controllers\FrMapa01Controller; // MAPA-01
use App\Http\Controllers\Mapa02Controller; // MAPA-02
use App\Http\Controllers\FrAk07Controller; // AK-07
use App\Http\Controllers\Ak02Controller; // AK-02
use App\Http\Controllers\Asesi\Ak03Controller; // AK-03
use App\Http\Controllers\Asesi\Ak04Controller; // AK-04
use App\Http\Controllers\Asesi\Pdf\Apl02PdfController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\Asesor\Ak05Controller; // AK-05

// Instrumen Asesmen
use App\Http\Controllers\IA01Controller;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\IA06Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\IA08Controller;
use App\Http\Controllers\IA09Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\Asesi\IA11\IA11Controller;
use App\Http\Controllers\Asesi\IA03\IA03Controller;
use App\Http\Controllers\FrIa04aController;

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
    // Route APL-02 Asesi form
    // Route::prefix('apl02')->name('apl02.')->group(function () {
    //     // Menampilkan Form
    //     // Route::get('/form/{id}', [Apl02Controller::class, 'show'])->name('show');

    //     // Menyimpan Form
    //     // Route::post('/store/{id}', [Apl02Controller::class, 'store'])->name('store');
    // });

    Route::get('/asesor/apl02/view/{id}', [App\Http\Controllers\Asesi\Apl02\PraasesmenController::class, 'view'])
        ->name('apl02.view');
    Route::post('/asesor/apl02/verifikasi/{id}', [App\Http\Controllers\Asesi\Apl02\PraasesmenController::class, 'verifikasi'])
        ->name('asesor.apl02.verifikasi'); // <--- BAGIAN INI YANG HILANG

    // FR-AK (Ceklis, Banding, dll)


    Route::get('/FR_AK_02', fn() => view('frontend/AK_02/FR_AK_02'))->name('FR_AK_02');
    Route::get('/ak01/form/{id}', [Ak01Controller::class, 'create'])->name('ak01.create');
    Route::post('/ak01/store/{id}', [Ak01Controller::class, 'store'])->name('ak01.store');
    Route::get('/FR_AK_03', fn() => view('frontend/AK_03/FR_AK_03'))->name('FR_AK_03');
    Route::get('/ak04/form/{id}', [Ak04Controller::class, 'create'])->name('ak04.create');
    Route::post('/ak04/store/{id}', [Ak04Controller::class, 'store'])->name('ak04.store');
    Route::get('/FR_AK_05', fn() => view('frontend/AK_05/FR_AK_05'))->name('FR_AK_05');

    // FR-AK-03
    Route::get('/ak03/form/{id}', [Ak03Controller::class, 'create'])->name('ak03.create');
    Route::post('/ak03/store/{id}', [Ak03Controller::class, 'store'])->name('ak03.store');



    // FR-AK-05
    Route::get('/ak05/{id_jadwal}', [Ak05Controller::class, 'index'])->name('ak05.index');
    // Route::get('/asesor/ak05/{id_jadwal}', [Ak05Controller::class, 'index'])->name('asesor.ak05'); // Alias REMOVED - handled in auth.php
    Route::post('/ak05/store/{id_jadwal}', [Ak05Controller::class, 'store'])->name('ak05.store');

    // FR-AK-07
    Route::get('/FR_AK_07/{id}', [FrAk07Controller::class, 'create'])->name('fr-ak-07.create');
    Route::post('/FR_AK_07/{id}', [FrAk07Controller::class, 'store'])->name('fr-ak-07.store');
    Route::get('/FR_AK_07/{id}/success', [FrAk07Controller::class, 'success'])->name('fr-ak-07.success');

    // ========================
    // 2. INSTRUMEN ASESMEN (IA) - PUBLIC / MIXED ACCESS
    // ========================

    Route::middleware(['auth', 'role:superadmin'])->prefix('validator')->group(function () {
        Route::get('/tracker/{id}', [ValidatorTrackerController::class, 'show'])->name('validator.tracker.show');
        Route::post('/tracker/{id}/validasi', [ValidatorTrackerController::class, 'validasi'])->name('validator.tracker.validasi');
    });

    Route::get('/ia01/success', fn() => view('frontend.IA_01.success'))->name('ia01.success_page');

    // IA-01 (READ ONLY / ADMIN / ASESI)
    Route::prefix('ia01/{id_sertifikasi}')->group(function () {
        Route::get('/view', [IA01Controller::class, 'showView'])->name('ia01.view');
        Route::get('/admin', [IA01Controller::class, 'showView'])->name('ia01.admin.show');
    });

    // IA-05 (ASESI VIEW)
    Route::get('/ia05/form-a/{id}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');

    // ========================
    // 3. INSTRUMEN ASESMEN (IA) - ASESOR ONLY
    // ========================
    Route::middleware(['auth', 'role:asesor'])->group(function () {

        // IA-01 (EDITABLE)
        Route::prefix('ia01/{id_sertifikasi}')->group(function () {
            Route::get('/', [IA01Controller::class, 'index'])->name('ia01.index');
            Route::get('/cover', [IA01Controller::class, 'index'])->name('ia01.cover');
            Route::post('/store', [IA01Controller::class, 'store'])->name('ia01.store');
            // Redirects
            Route::get('/step/{urutan}', fn($id) => redirect()->route('ia01.index', $id));
            Route::get('/finish', fn($id) => redirect()->route('ia01.index', $id));
        });

    }); // End of role:asesor group

    // ============================================================================
    // 4. INSTRUMEN ASESMEN (IA) - ASESOR & ADMIN SHARED ACCESS
    // ============================================================================
    // Dipindahkan keluar dari role:asesor agar Admin bisa akses (Master View)
    // [FIX] Secured with Middleware to prevent Asesi access
    Route::middleware(['role:asesor,admin'])->group(function () {
        
        // IA-01 (EDITABLE - Asesor / Admin with permission)
        // See above for IA-01 strict role

        // IA-02
        Route::get('/ia02/{id_sertifikasi}', [IA02Controller::class, 'show'])->name('ia02.show');
        Route::post('/ia02/{id_sertifikasi}', [IA02Controller::class, 'store'])->name('ia02.store');

        // IA-03
        Route::get('/ia03/{id}', [IA03Controller::class, 'index'])->name('ia03.index');
        Route::get('/ia03/{id}/show', [IA03Controller::class, 'show'])->name('ia03.show');

        // IA-04 (Asesor) is handled below in FRIA04_Asesor block

        // IA-05
        Route::get('/ia05/form-c/{id}', [IA05Controller::class, 'showJawabanForm'])->name('ia05.asesor');
        Route::post('/ia05/form-c/{id}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia05.store_penilaian');

        // IA-06
        Route::get('/ia06/asesor/{id}', [IA06Controller::class, 'asesorShow'])->name('asesor.ia06.edit');
        Route::post('/ia06/asesor/{id}', [IA06Controller::class, 'asesorStorePenilaian'])->name('asesor.ia06.update');

        // IA-07
        Route::get('/FR_IA_07/{id}', [IA07Controller::class, 'index'])->name('ia07.asesor');
        Route::post('/FR_IA_07/store', [IA07Controller::class, 'store'])->name('ia07.store');

        // IA-08
        Route::get('/ia08/{id_data_sertifikasi_asesi}', [IA08Controller::class, 'show'])->name('ia08.show');
        Route::post('/ia08/{id_data_sertifikasi_asesi}', [IA08Controller::class, 'store'])->name('ia08.store');

        // IA-09
        Route::get('/ia09/{id_data_sertifikasi_asesi}', [IA09Controller::class, 'showWawancara'])->name('ia09.edit');
        Route::post('/ia09/{id_data_sertifikasi_asesi}', [IA09Controller::class, 'storeWawancara'])->name('ia09.store');

        // IA-10
        Route::get('/FR_IA_10/{id}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
        Route::post('/FR_IA_10/store', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

        // IA-11
        Route::get('/FR_IA_11/{id}', [IA11Controller::class, 'show'])->name('ia11.show');
        Route::post('/FR_IA_11/store', [IA11Controller::class, 'store'])->name('ia11.store');
        Route::put('/FR_IA_11/{id}', [IA11Controller::class, 'update'])->name('ia11.update');

        // FRIA04_Asesor
        Route::get('/FRIA04_Asesor/{id}', [AssessmenFRIA04tController::class, 'showIA04A'])->name('fria04a.show');
        Route::post('/FRIA04_Asesor/{id}', [AssessmenFRIA04tController::class, 'storeIA04A'])->name('fria04a.store');

        // MAPA-02
        
        // Laporan Asesi (Admin) - Used for APL 01 link
        Route::get('/admin/master-asesi', [App\Http\Controllers\Admin\AsesiController::class, 'index'])->name('admin.master_asesi');
        Route::get('/admin/laporan', [App\Http\Controllers\Admin\AsesiController::class, 'index'])->name('admin.laporan.index');
    });

    // ========================
    // 5. ADMIN MASTER VIEWS (Role: Admin)
    // ========================
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // IA-02
        Route::get('/admin/skema/{id_skema}/ia02', [IA02Controller::class, 'adminShow'])->name('admin.ia02.show');

        Route::prefix('admin/skema/{id_skema}')->group(function () {
            // APL
            Route::get('/apl01', [APL01Controller::class, 'adminShow'])->name('admin.apl01.show'); // [NEW] Master View APL-01
            Route::get('/apl02', [App\Http\Controllers\Asesi\Apl02\PraasesmenController::class, 'adminShow'])->name('admin.apl02.show');
            
            // MAPA
            Route::get('/mapa01', [FrMapa01Controller::class, 'adminShow'])->name('admin.mapa01.show');
            Route::get('/mapa02', [Mapa02Controller::class, 'adminShow'])->name('admin.mapa02.show');
            
            // IA
            Route::get('/ia01', [IA01Controller::class, 'adminShow'])->name('admin.ia01.show');
            Route::get('/ia03', [IA03Controller::class, 'adminShow'])->name('admin.ia03.show');
            Route::get('/ia04', [FrIa04aController::class, 'adminShow'])->name('admin.ia04.show');
            Route::get('/ia05', [IA05Controller::class, 'adminShow'])->name('admin.ia05.show');
            Route::get('/ia06', [IA06Controller::class, 'adminShow'])->name('admin.ia06.show');
            Route::get('/ia07', [IA07Controller::class, 'adminShow'])->name('admin.ia07.show');
            Route::get('/ia08', [IA08Controller::class, 'adminShow'])->name('admin.ia08.show');
            Route::get('/ia09', [IA09Controller::class, 'adminShow'])->name('admin.ia09.show');
            Route::get('/ia10', [IA10Controller::class, 'adminShow'])->name('admin.ia10.show');
            Route::get('/ia11', [\App\Http\Controllers\IA11Controller::class, 'adminShow'])->name('admin.ia11.show');
            
            // AK (Reports)
            Route::get('/ak01', [Ak01Controller::class, 'adminShow'])->name('admin.ak01.show'); 
            Route::get('/ak02', [Ak02Controller::class, 'adminShow'])->name('admin.ak02.show');
            Route::get('/ak03', [Ak03Controller::class, 'adminShow'])->name('admin.ak03.show');
            Route::get('/ak04', [Ak04Controller::class, 'adminShow'])->name('admin.ak04.show');
            Route::get('/ak05', [Ak05Controller::class, 'adminShow'])->name('admin.ak05.show');
            Route::get('/ak06', [\App\Http\Controllers\FrAk06Controller::class, 'adminShow'])->name('admin.ak06.show');
            Route::get('/laporan', [AsesiController::class, 'adminShow'])->name('admin.laporan.show');
        });

        // Helper for individual view redirection from Master List (Outside skema prefix)
        Route::get('/admin/ak05/view/{id_sertifikasi}', [Ak05Controller::class, 'showBySertifikasi'])->name('admin.ak05.view');

        // Helper for individual tracker view from Laporan Master List
        Route::get('/admin/laporan/view/{id_data_sertifikasi_asesi}', [AsesiProfileController::class, 'showTrackerBySertifikasi'])->name('admin.laporan.asesi.view');

        // Helper for Asesor Tracker (Individual View)
        Route::get('/admin/asesor/tracker/view/{id_data_sertifikasi_asesi}', [AsesorProfileController::class, 'showTrackerBySertifikasi'])->name('admin.asesor.tracker.view');
    });

    // AK-02 Edit
    // AK-02 Edit (Shared)
    Route::get('/ak02/{id}', [Ak02Controller::class, 'edit'])->name('ak02.edit');
    Route::put('/ak02/{id}', [Ak02Controller::class, 'update'])->name('ak02.update');



    // Portofolio
    Route::get('/PORTOFOLIO', [PortofolioController::class, 'index'])->name('PORTOFOLIO');

    //FRIA04_Asesi
    Route::get('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'showIA04AAsesi'])->name('fria04a.asesi.show');
    Route::post('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'storeIA04AAsesi'])->name('fria04a.asesi.store');

    //FRIA04_Asesor - Moved to shared auth group above (line 295)
    // Removed redundant protected block.

    // MAPA-01
    Route::get('/mapa01/form/{id}', [FrMapa01Controller::class, 'index'])->name('mapa01.index');
    Route::post('/mapa01/store', [FrMapa01Controller::class, 'store'])->name('mapa01.store');

    // MAPA-02
    Route::get('/mapa02/form/{id}', [Mapa02Controller::class, 'show'])->name('mapa02.show');
    Route::post('/mapa02/form/{id}/store', [Mapa02Controller::class, 'store'])->name('mapa02.store');

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
        Route::get('/apl02/{id}', [Apl02PdfController::class, 'generateApl02'])->name('apl02.cetak_pdf');
        Route::get('/ak01/{id}', [Ak01PdfController::class, 'generateAk01'])->name('ak01.cetak_pdf');
        Route::get('/ak02/{id}', [Ak02Controller::class, 'cetakPDF'])->name('ak02.cetak_pdf');
        Route::get('/ia-01/cetak-pdf/{id_sertifikasi}', [IA01Controller::class, 'cetakPDF'])->name('ia01.cetak_pdf');
        Route::get('/ia-03/cetak-pdf/{id_sertifikasi}', [IA03Controller::class, 'cetakPDF'])->name('ia03.cetak_pdf');
        Route::get('/ia-04/cetak-pdf/{id_sertifikasi}', [FrIa04aController::class, 'cetakPDF'])->name('ia04.cetak_pdf');
        Route::get('/ia-08/cetak-pdf/{id_sertifikasi}', [IA08Controller::class, 'cetakPDF'])->name('ia08.cetak_pdf');
        Route::get('/ia-09/cetak-pdf/{id_sertifikasi}', [IA09Controller::class, 'cetakPDF'])->name('ia09.cetak_pdf');
        Route::get('/ia-11/cetak-pdf/{id_sertifikasi}', [IA11Controller::class, 'cetakPDF'])->name('ia11.cetak_pdf');
    });
    // Legacy mapping (just in case)
    Route::get('/mapa02/cetak/{id}', [Mapa02Controller::class, 'cetakPDF']);

    // ========================
    // 4. ASESOR FILE AJAX ROUTES
    // ========================
    Route::post('/asesor/{id}/bukti/store', [AsesorProfileController::class, 'storeBukti'])->name('asesor.bukti.store');
    Route::delete('/asesor/{id}/bukti/delete/{jenis_dokumen}', [AsesorProfileController::class, 'deleteBukti'])->name('asesor.bukti.delete');
    Route::post('/asesor/{id}/ttd/store', [AsesorProfileController::class, 'storeTtd'])->name('asesor.ttd.store');
    Route::delete('/asesor/{id}/ttd/delete', [AsesorProfileController::class, 'deleteTtd'])->name('asesor.ttd.delete');

    // Quick Verification Route
    Route::post('/asesor/{id}/document/verify', [AsesorProfileController::class, 'verifyDocument'])->name('asesor.document.verify');

    // ========================
    // 5. ASESOR PDF & REPORTS
    // ========================
    // AK.05
    Route::get('/asesor/ak05/pdf/{id}', [\App\Http\Controllers\Asesor\Ak05Controller::class, 'cetakPDF'])->name('asesor.ak05.pdf');
    
    // AK.06
    Route::get('/asesor/ak06/{id}', [\App\Http\Controllers\FrAk06Controller::class, 'index'])->name('asesor.ak06');
    Route::get('/asesor/ak06/pdf/{id}', [\App\Http\Controllers\FrAk06Controller::class, 'cetakPDF'])->name('asesor.ak06.pdf');

    // Daftar Hadir
    Route::get('/asesor/daftar-hadir/pdf/{id}', [\App\Http\Controllers\Admin\DaftarHadirController::class, 'exportPdfdaftarhadir'])->name('asesor.daftar_hadir.pdf');
    Route::get('/asesor/daftar-hadir/{id}', [\App\Http\Controllers\Admin\DaftarHadirController::class, 'daftarHadir'])->name('asesor.daftar_hadir');

    // Berita Acara
    Route::get('/asesor/berita-acara/{id}', [\App\Http\Controllers\Asesor\BeritaAcaraController::class, 'index'])->name('asesor.berita_acara');
    Route::get('/asesor/berita-acara/pdf/{id}', [\App\Http\Controllers\Asesor\BeritaAcaraController::class, 'cetakPDF'])->name('asesor.berita_acara.pdf');

});

// ==========================================================
// D. HELPER APIS (Mixed Use)
// ==========================================================
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');
Route::post('/api/jadwal/daftar', [TrackerController::class, 'daftarJadwal'])->name('api.jadwal.daftar');
Route::get('/tracking', [TrackerController::class, 'index'])->name('tracker');
