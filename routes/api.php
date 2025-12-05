<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\V1\TukController; // <-- TAMBAHKAN INI
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\AsesorApiController;
use App\Http\Controllers\Api\Ia10ApiController;
use App\Http\Controllers\Api\V1\DetailSkemaController;
use App\Http\Controllers\Api\V1\BeritaController;
use App\Http\Controllers\Api\V1\StrukturOrganisasiController;
use App\Http\Controllers\Api\V1\JadwalControllerAPI; // Sudah benar
use App\Http\Controllers\Api\V1\AsesorTableApiController;
use App\Http\Controllers\Api\Asesor\JadwalAsesorApiController;
use App\Http\Controllers\Api\Asesor\ProfilAsesorApiController;
use App\Http\Controllers\Api\SoalIA05ApiController;
use App\Http\Controllers\Api\KelompokPekerjaanController;
use App\Http\Controllers\Api\UnitKompetensiController;
use App\Http\Controllers\Api\AsesorController;
use App\Http\Controllers\Api\TukAdminController;
use App\Http\Controllers\Api\AsesiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ScheduleController;

use App\Http\Controllers\Api\SoalIa06Controller;

// =======================================================
// 🔓 RUTE PUBLIK (tidak butuh token)
// =======================================================
Route::prefix('v1')->group(function () {

    // ==========================
    // AUTHENTICATION ROUTES
    // ==========================

    // Login
    Route::post('/login', [LoginController::class, 'login']);

    // Register
    Route::prefix('register')->group(function () {
        Route::post('/asesi', [RegisterController::class, 'registerAsesi']);
        Route::post('/asesor', [RegisterController::class, 'registerAsesor']);
    });

    // Google OAuth
    Route::prefix('auth/google')->group(function () {
        Route::get('redirect', [GoogleApiController::class, 'redirect']);
        Route::get('callback', [GoogleApiController::class, 'callback']);
    });


    // ==========================
    // DATA RESOURCES ROUTES (Public/Unprotected - Bisa diakses tanpa token)
    // ==========================

    // Skema Index
    Route::get('/skema', [SkemaController::class, 'index']);

    // Detail Skema
    Route::get('/skema/{id}', [DetailSkemaController::class, 'show']);

    // Daftar Asesor
    Route::get('/asesor', [AsesorTableApiController::class, 'index']);

    // TUK CRUD
    Route::apiResource('tuks', TukController::class);

    // Berita CRUD
    Route::apiResource('berita', BeritaController::class);

    // Struktur organisasi CRUD
    Route::apiResource('struktur', StrukturOrganisasiController::class);

    // Jadwal API
    Route::apiResource('jadwal', JadwalControllerAPI::class);
    // =======================================================
    // 🔐 RUTE TERPROTEKSI (Harus pakai token Bearer)
    // =======================================================
    Route::middleware('auth:sanctum')->group(function () {

        // 🔸 Auth routes
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/me', [LoginController::class, 'me']);

        // =======================================================
        // � PROFIL ASESOR (Harus sebelum /asesor/{id})
        // =======================================================
        Route::get('/asesor/profil', [ProfilAsesorApiController::class, 'show']);
        Route::post('/asesor/profil', [ProfilAsesorApiController::class, 'update']);
        Route::put('/asesor/profil', [ProfilAsesorApiController::class, 'update']);

        // =======================================================
        // �📌 MASTER ASESOR
        // =======================================================
        Route::get('/asesor', [AsesorController::class, 'index']);
        Route::get('/asesors', [AsesorController::class, 'index']);
        Route::post('/asesors', [AsesorController::class, 'index']); // kamu pakai POST versi lain

        Route::post('/asesor', [AsesorController::class, 'store']);
        Route::get('/asesor/{id}', [AsesorController::class, 'show']);
        Route::put('/asesor/{id}', [AsesorController::class, 'update']);
        Route::delete('/asesor/{id}', [AsesorController::class, 'destroy']);

        // =======================================================
        // 📌 MASTER TUK (CRUD LENGKAP)
        // =======================================================
        Route::get('/tuk', [TukAdminController::class, 'index']);      // GET semua TUK
        Route::get('/tuk/{id}', [TukAdminController::class, 'show']);  // GET berdasarkan ID
        Route::post('/tuk', [TukAdminController::class, 'store']);     // POST buat baru
        Route::put('/tuk/{id}', [TukAdminController::class, 'update']); // PUT update
        Route::delete('/tuk/{id}', [TukAdminController::class, 'destroy']); // DELETE hapus

        // =======================================================
        // 📘 MASTER SKEMA (CRUD LENGKAP)
        // =======================================================
        Route::get('/skema', [SkemaController::class, 'index']);
        Route::get('/skema/{id}', [SkemaController::class, 'show']);
        Route::post('/skema', [SkemaController::class, 'store']);
        Route::put('/skema/{id}', [SkemaController::class, 'updateData']);
        Route::delete('/skema/{id}', [SkemaController::class, 'destroy']);

        // =======================================================
        // 🏗️ DETAIL SKEMA (KELOMPOK & UNIT)
        // =======================================================

        // Kelompok Pekerjaan
        Route::get('/kelompokpekerjaan', [KelompokPekerjaanController::class, 'index']);
        Route::get('/kelompokpekerjaan/{id}', [KelompokPekerjaanController::class, 'show']);
        Route::post('/kelompokpekerjaan', [KelompokPekerjaanController::class, 'store']);
        Route::put('/kelompokpekerjaan/{id}', [KelompokPekerjaanController::class, 'update']);
        Route::delete('/kelompokpekerjaan/{id}', [KelompokPekerjaanController::class, 'destroy']);

        // Unit Kompetensi
        Route::get('/unitkompetensi', [UnitKompetensiController::class, 'index']);
        Route::get('/unitkompetensi/{id}', [UnitKompetensiController::class, 'show']);
        Route::post('/unitkompetensi', [UnitKompetensiController::class, 'store']);
        Route::put('/unitkompetensi/{id}', [UnitKompetensiController::class, 'update']);
        Route::delete('/unitkompetensi/{id}', [UnitKompetensiController::class, 'destroy']);

        // =======================================================
        // 📘 MASTER ASESI (CRUD LENGKAP)
        // =======================================================
        Route::get('/asesi', [AsesiController::class, 'index']);
        Route::get('/asesi/{id}', [AsesiController::class, 'show']);
        Route::post('/asesi', [AsesiController::class, 'store']);
        Route::put('/asesi/{id}', [AsesiController::class, 'update']);
        Route::delete('/asesi/{id}', [AsesiController::class, 'destroy']);


        // =======================================================
        // 📘 MASTER Category (CRUD LENGKAP)
        // =======================================================

        Route::get('/category', [CategoryController::class, 'index']);
        Route::post('/category', [CategoryController::class, 'store']);
        Route::get('/category/{id}', [CategoryController::class, 'show']);
        Route::put('/category/{id}', [CategoryController::class, 'putUpdate']);
        Route::delete('/category/{id}', [CategoryController::class, 'destroy']);


        // =======================================================
        // MASTER Schedule (CRUD LENGKAP)
        // =======================================================
        Route::get('/jadwal', [ScheduleController::class, 'index']);
        Route::get('/jadwal/{id}', [ScheduleController::class, 'show']);
        Route::post('/jadwal', [ScheduleController::class, 'store']);
        Route::put('/jadwal/{id}', [ScheduleController::class, 'update']);
        Route::delete('/jadwal/{id}', [ScheduleController::class, 'destroy']);

        // =======================================================
        // 🗓️ JADWAL ASESOR (CRUD & FILTERING)
        // =======================================================
        Route::apiResource('jadwal-asesor', JadwalAsesorApiController::class);



        // =======================================================
        // IA 06
        // =======================================================
        // 1. Bank Soal (CRUD)
        Route::apiResource('soal-ia06', SoalIa06Controller::class);

        // 2. Asesi: Jawab & Lihat Jawaban
        Route::post('soal-ia06/jawab', [SoalIa06Controller::class, 'storeJawabanAsesi']);
        Route::get('soal-ia06/jawaban/{id_data_sertifikasi_asesi}', [SoalIa06Controller::class, 'getJawabanAsesi']);

        // 3. Asesor: Penilaian & Umpan Balik
        Route::post('soal-ia06/penilaian', [SoalIa06Controller::class, 'storePenilaianAsesor']);
        Route::post('soal-ia06/umpan-balik', [SoalIa06Controller::class, 'storeUmpanBalikAsesi']);
        Route::get('soal-ia06/umpan-balik/{id_data_sertifikasi_asesi}', [SoalIa06Controller::class, 'getUmpanBalikAsesi']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'User data retrieved successfully',
        'data' => $request->user()
    ]); 
});

Route::post('/login', [LoginController::class, 'login']);
Route::prefix('register')->group(function () {
    Route::post('/asesi', [RegisterController::class, 'registerAsesi']);
    Route::post('/asesor', [RegisterController::class, 'registerAsesor']);
});
Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);
Route::prefix('auth/google')->group(function () {
    Route::get('redirect', [GoogleApiController::class, 'redirect']);
    Route::get('callback', [GoogleApiController::class, 'callback']);
});

Route::prefix('ia-05')->group(function () {
    
    // GET: Ambil daftar soal (Dipakai di Mobile/Frontend load via AJAX)
    Route::get('/soal', [SoalIA05ApiController::class, 'index']);

    // POST: Admin tambah soal baru
    Route::post('/soal', [SoalIA05ApiController::class, 'storeSoal']);

    // POST: Asesi kirim jawaban
    Route::post('/submit', [SoalIA05ApiController::class, 'submitJawaban']);

});


// Rute API TUK (Tempat Uji Kompetensi)
// Menggunakan apiResource untuk mendaftarkan semua metode CRUD (index, show, store, update, destroy)
Route::apiResource('tuks', TukController::class); // <-- BARIS BARU

// Rute API Skema
Route::get('/skema', [SkemaController::class, 'index']);

Route::get('/asesor', [AsesorTableApiController::class, 'index']);

// Rute API Jadwal
Route::apiResource('jadwal', JadwalController::class, ['as' => 'api']);

// Rute API Asesor
Route::get('/asesor/{id}', [AsesorApiController::class, 'show']);


// Menampilkan soal untuk dinilai (butuh ID data sertifikasi)
Route::get('/ia-10/{id}', [Ia10ApiController::class, 'show']);

// Mengirim jawaban penilaian
Route::post('/ia-10', [Ia10ApiController::class, 'store']);
