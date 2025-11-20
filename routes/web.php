<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Asesor\FormIA07Controller; // <-- Sudah dikembalikan!
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\IA02Controller; // Ditambahkan karena ada route IA-02 di bawah

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK (Landing Page, Info, Jadwal Umum)
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Detail Skema
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// Jadwal Publik (List & Detail)
Route::get('/jadwal', [HomeController::class, 'jadwal'])->name('jadwal');
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetailPublik'])->name('jadwal.detail');

// Info TUK & Alur & Profil
Route::get('/alur-sertifikasi', fn() => view('landing_page.page_info.alur-sertifikasi'))->name('info.alur');
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

Route::get('/visimisi', fn() => view('landing_page.page_profil.visimisi'))->name('profil.visimisi');
Route::get('/struktur', fn() => view('landing_page.page_profil.struktur'))->name('profil.struktur');
Route::get('/mitra', fn() => view('landing_page.page_profil.mitra'))->name('profil.mitra');

Route::get('/sertifikasi', fn() => "Halaman Sertifikasi")->name('sertifikasi');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

// Register User Baru
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// API Helper & Keep Alive
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');


/*
|--------------------------------------------------------------------------
| 2. ROUTE AUTHENTICATED (General - Harus Login Dulu)
|--------------------------------------------------------------------------
| Mencakup Profil, Dashboard Umum, dan Formulir Asesmen (IA)
*/
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD & HOME ---
    Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [AsesorDashboardController::class, 'index'])->name('home.index');

    // --- PROFIL USER ---
    Route::post('/myprofil/asesor/update', [ProfileController::class, 'updateAsesorAjax'])->name('profil.asesor.update');
    Route::post('/myprofil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::get('/myprofil', [ProfileController::class, 'show'])->name('profil.show');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil');

    /*
    |--------------------------------------------------------------------------
    | FORMULIR ASESMEN (IA)
    |--------------------------------------------------------------------------
    */

    // FR.IA.02
    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show'); 
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');
    Route::get('/fr-ia-02', fn() => view('frontend.FR_IA_02'))->name('FR_IA_02');

    // FR.IA.10
    Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');
    Route::get('/FR-IA-10-view', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10'); 

    // FR.IA.06 (Statis/View)
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');

    // FR.IA.07 
    // (Di versi HEAD pakai view statis, tapi Controller sudah di-load di atas jaga-jaga mau diganti nanti)
    Route::get('/fr-ia-07', fn() => view('frontend.FR_IA_07'))->name('FR_IA_07');

    /*
    | FORMULIR IA-05 (Kompleks dengan Role Middleware)
    */
    // Form A: Soal
    Route::middleware(['role:admin,asesor,asesi'])->group(function () {
        Route::get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])->name('ia-05.store.soal');
    });
    Route::middleware(['role:asesi'])->group(function () {
        Route::post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])->name('ia-05.store.jawaban');
    });

    // Form B: Kunci Jawaban
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])->name('FR_IA_05_B');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])->name('ia-05.store.kunci');
    });

    // Form C: Penilaian
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])->name('FR_IA_05_C');
    });
    Route::middleware(['role:asesor'])->group(function () {
        Route::post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia-05.store.penilaian');
    });

});


/*
|--------------------------------------------------------------------------
| 3. ROUTE KHUSUS ASESOR (Prefix: /asesor/...)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('asesor')->group(function () {
    
    // Dashboard khusus Asesor 
    Route::get('/home', [AsesorDashboardController::class, 'index'])->name('asesor.home.index');
    
    // Manajemen Jadwal & Asesi
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/daftar-asesi/{id_jadwal}', [JadwalController::class, 'showAsesi'])->name('daftar_asesi');
    
    // Tools
    Route::get('/laporan', fn() => view('frontend.laporan'))->name('laporan');
    Route::get('/tracker', fn() => view('frontend.tracker'))->name('tracker');

});

/*
|--------------------------------------------------------------------------
| 4. AUTH ROUTES (Bawaan Laravel Breeze/Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';