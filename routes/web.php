<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\TukController;
use App\Http\Controllers\AsesiTrackerController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\IA02Controller; // Ditambahkan karena ada route IA-02 di bawah
use App\Http\Controllers\APL01Controller;
use App\Http\Controllers\FrMapa01Controller;
use App\Http\Controllers\Mapa02Controller;
use App\Http\Controllers\DaftarHadirController;

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

    // Tambahkan Route ini:
    Route::get('/asesi/apl01/print/{id}', [APL01Controller::class, 'cetakPDF'])->name('apl01.print');

    // Jangan lupa untuk MAPA 01 juga jika nanti error yang sama muncul:
    Route::get('/asesi/mapa01/print/{id}', [MAPA01Controller::class, 'cetakPDF'])->name('mapa01.print');


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

    //APL01
    // Rute untuk Halaman 1 (Panggil fungsi step1)
    Route::get('/apl-01-1/view/{id}', [APL01Controller::class, 'step1'])->name('APL_01_1');

    // Rute untuk Simpan Halaman 1 (Method POST)
    Route::post('/apl-01-1/store', [APL01Controller::class, 'storeStep1'])->name('apl01_1.store');

    // Rute untuk Halaman 2 (Panggil fungsi step2)
    Route::get('/apl-01-2/view/{id}', [APL01Controller::class, 'step2'])->name('APL_01_2');

    Route::post('/apl-01-2/store', [APL01Controller::class, 'storeStep2'])->name('apl01_2.store');    

    // Rute untuk Halaman 3 (Panggil fungsi step3)
    Route::get('/apl-01-3/view/{id}', [APL01Controller::class, 'step3'])->name('APL_01_3');

    //MAPA01
    Route::get('/mapa01/show/{id}', [FrMapa01Controller::class, 'index'])->name('mapa01.index');
    Route::post('/mapa01/store', [FrMapa01Controller::class, 'store'])->name('mapa01.store'); 
    
    //MAPA02
    Route::get('/mapa02/show/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'show'])->name('mapa02.show');    
    Route::post('/mapa02/store/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'store'])->name('mapa02.store');     
});

/*
|--------------------------------------------------------------------------
| ROUTE CETAK DOKUMEN PDF (Bisa diakses Semua Role: Asesi, Asesor, Admin)
|--------------------------------------------------------------------------
| Kita taruh diluar prefix 'asesi'/'asesor' agar URL-nya netral.
*/
Route::middleware(['auth', 'role:asesi,asesor,admin'])->group(function () {

    // Cetak APL-01
    Route::get('/dokumen/apl01/print/{id}', [APL01Controller::class, 'cetakPDF'])->name('apl01.print');

    // Cetak MAPA-01
    Route::get('/dokumen/mapa01/print/{id}', [FrMapa01Controller::class, 'cetakPDF'])->name('mapa01.print');

});

/*
    |--------------------------------------------------------------------------
    | ZONA KHUSUS ASESI (NEW MIDDLEWARE)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:asesi'])->prefix('asesi')->group(function () {
        
        // 1. Tracker Asesi (Halaman Progress)
        Route::get('/tracker/{id}', [AsesiTrackerController::class, 'show'])->name('tracker');
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

    Route::get('/daftar_hadir/{id_jadwal}', [DaftarHadirController::class, 'index'])->name('daftar_hadir');  
    Route::post('/daftar-hadir/{id_jadwal}/simpan', [DaftarHadirController::class, 'simpan'])->name('simpan_kehadiran');
  

    // Tracker VIEW Asesor (Melihat progress milik asesi)
    Route::get('/tracker/{id_sertifikasi_asesi}', [AsesiTrackerController::class, 'show'])->name('asesor.tracker');
    // Tools
    Route::get('/laporan', fn() => view('frontend.laporan'))->name('laporan');
    //Route::get('/tracker', fn() => view('frontend.tracker'))->name('tracker');

});

/*
|--------------------------------------------------------------------------
| 4. AUTH ROUTES (Bawaan Laravel Breeze/Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';