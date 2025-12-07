<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\GoogleApiController;
use App\Http\Controllers\Api\V1\SkemaController;
use App\Http\Controllers\Api\V1\TukController; // <-- TAMBAHKAN INI
use App\Http\Controllers\Api\V1\AsesorTableApiController;
use App\Http\Controllers\Api\V1\JadwalController;
use App\Http\Controllers\Api\AsesorApiController;
use App\Http\Controllers\Api\V1\DetailSkemaController;
use App\Http\Controllers\Api\V1\BeritaController;
use App\Http\Controllers\Api\V1\StrukturOrganisasiController;
use App\Http\Controllers\Api\V1\JadwalControllerAPI; // Sudah benar
use App\Http\Controllers\Api\V1\MitraController;
use App\Http\Controllers\Api\KelompokPekerjaanController;
use App\Http\Controllers\Api\UnitKompetensiController;
use App\Http\Controllers\Api\AsesorController;
use App\Http\Controllers\Api\TukAdminController;
use App\Http\Controllers\Api\AsesiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Asesi\IA02\Ia02Controller;
use App\Http\Controllers\Asesi\Apl02\PraasesmenController;
use App\Http\Controllers\Asesi\Ak04API\APIBandingController;
use App\Http\Controllers\Asesi\asesmen\AsesmenEsaiController;
use App\Http\Controllers\Asesi\JadwalTukAPI\JadwalTukAPIController;
use App\Http\Controllers\Asesi\pembayaran\PaymentCallbackController;
use App\Http\Controllers\Asesi\asesmen\AsesmenPilihanGandaController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\TandaTanganAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\DataSertifikasiAsesiController;

// =======================================================
// 🔓 RUTE PUBLIK (tidak butuh token)
// =======================================================
Route::prefix('v1')->group(function() {
  
    // ==========================
    // AUTHENTICATION ROUTES
    // ==========================

    // Login
    Route::post('/login', [LoginController::class, 'login']);

    // Register
/*
|--------------------------------------------------------------------------
| API Routes (VERSION 1)
|--------------------------------------------------------------------------
*/

// Bungkus semuanya dalam prefix 'v1'
// URL Jadinya: /api/v1/nama-route
Route::prefix('v1')->group(function () {
    // --- 1. AUTHENTICATION ---
    Route::post('/login', [LoginController::class, 'login']);

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

    //Mitra API
    // Menggunakan apiResource (otomatis membuat route index, store, show, update, destroy)
    Route::apiResource('mitra', MitraController::class);
    // =======================================================
    // 🔐 RUTE TERPROTEKSI (Harus pakai token Bearer)
    // =======================================================
    Route::middleware('auth:sanctum')->group(function () {

        // 🔸 Auth routes
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/me', [LoginController::class, 'me']);

        // =======================================================
        // 📌 MASTER ASESOR
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


    });
});
Route::get('/asesor', [AsesorTableApiController::class, 'index']);

// Rute API Jadwal
Route::apiResource('jadwal', JadwalControllerAPI::class, ['as' => 'api']);

Route::get('/asesor/{id}', [AsesorApiController::class, 'show']);
    // --- 3. PUBLIC DATA (Read Only) ---
    Route::get('/skema', [SkemaController::class, 'index']);
    Route::get('/asesor', [AsesorTableApiController::class, 'index']);

    // --- 4. RESOURCES ---
    Route::apiResource('tuks', TukController::class);

    // --- 5. FORMULIR ASESI (APL/AK) ---

    // A. Data Sertifikasi & Tujuan Asesmen
    Route::prefix('data-sertifikasi')->group(function () {
        Route::get('/detail/{id}', [DataSertifikasiAsesiController::class, 'getDetailSertifikasiApi'])->name('api.v1.sertifikasi.detail');

        Route::get('/{id}', [DataSertifikasiAsesiController::class, 'getDataSertifikasiAsesiApi'])->name('api.v1.data_sertifikasi.get');

        // Simpan Pilihan Tujuan Asesmen
        Route::post('/', [DataSertifikasiAsesiController::class, 'storeAjax'])->name('api.v1.data_sertifikasi.store');
    });

    // B. Bukti Kelengkapan (Upload File)
    Route::prefix('bukti-kelengkapan')->group(function () {
        Route::get('/list/{id_data_sertifikasi_asesi}', [BuktiKelengkapanController::class, 'getDataBuktiKelengkapanApi'])->name('api.v1.bukti_kelengkapan.get');

        Route::post('/store', [BuktiKelengkapanController::class, 'storeAjax'])->name('api.v1.bukti_kelengkapan.store');

        Route::delete('/delete/{id}', [BuktiKelengkapanController::class, 'deleteAjax'])->name('api.v1.bukti_kelengkapan.delete');
    });

    // C. Kerahasiaan (AK-01)
    Route::get('/get-frak01-data/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])->name('api.v1.get.frak01');
    Route::post('/setuju-kerahasiaan/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])->name('api.v1.setuju.frak01');

    // D. Tanda Tangan
    Route::get('/show-all', [TandaTanganAPIController::class, 'index']);
    Route::get('/show-detail/{id_asesi}', [TandaTanganAPIController::class, 'show']);
    Route::post('/ajax-simpan-tandatangan/{id_asesi}', [TandaTanganAPIController::class, 'storeAjax'])->name('api.v1.simpan.tandatangan');

    // --- 6. MIDTRANS PAYMENT CALLBACK ---
    Route::post('/midtrans-callback', [PaymentCallbackController::class, 'receive']);

    // ======================= PRA ASESMEN (APL.02) =======================
    Route::prefix('pra-asesmen')->group(function () {
        // Simpan Jawaban & File Bukti
        // URL: POST /api/v1/pra-asesmen/{id_sertifikasi}
        Route::post('/{id_sertifikasi}', [PraasesmenController::class, 'store'])->name('api.v1.apl02.store');
    });

    // ======================= KERAHASIAAN (AK.01) =======================

    Route::prefix('kerahasiaan')->group(function () {
        // GET: Ambil data form & checkbox
        Route::get('/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])->name('api.v1.get.frak01');

        // POST: Simpan checkbox & update status
        Route::post('/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])->name('api.v1.setuju.frak01');
    });

    // --- Jadwal & TUK ---
    Route::prefix('jadwal-tuk')->group(function () {
        Route::get('/{id_sertifikasi}', [JadwalTukAPIController::class, 'getJadwalData']);
        Route::post('/konfirmasi/{id_sertifikasi}', [JadwalTukAPIController::class, 'konfirmasiJadwal']);
    });

    // ======================= BANDING (AK.04) =======================
    Route::prefix('banding')->group(function () {
        //ambil data
        Route::get('/{id_sertifikasi}', [APIBandingController::class, 'getBandingData'])->name('api.v1.get.ak04');

        // POST: Simpan hasil banding
        Route::post('/{id_sertifikasi}', [APIBandingController::class, 'simpanBanding'])->name('api.v1.post.ak04');
    });

    // ======================= ASESMEN PILGAN (IA-05) =======================
    // GET: Ambil daftar soal
    Route::get('/asesmen-teori/{id_sertifikasi}/soal', [AsesmenPilihanGandaController::class, 'getQuestions']);

    // POST: Simpan jawaban
    Route::post('/asesmen-teori/{id_sertifikasi}/submit', [AsesmenPilihanGandaController::class, 'submitAnswers']);

    // ======================= ASESMEN ESSAI (IA-06) =======================
    // API Asesmen Essai (IA-06)
    Route::get('/asesmen-esai/{id_sertifikasi}/soal', [AsesmenEsaiController::class, 'getQuestions']);
    Route::post('/asesmen-esai/{id_sertifikasi}/submit', [AsesmenEsaiController::class, 'submitAnswers']);

    // =================================================================
    // ROUTE FR.IA.02 (API - DATA FETCH)
    // =================================================================

    // Prefix penuh: /api/v1/ia02/...
    Route::prefix('ia02')->group(function () {
        // GET: Ambil data IA.02 (API)
        // Jalur Penuh: /api/v1/ia02/{id_data_sertifikasi_asesi}/data
        // Catatan: Menggunakan method 'apiIndex' yang ada di Ia02Controller Anda
        Route::get('/{id_data_sertifikasi_asesi}/data', [Ia02Controller::class, 'apiDetail'])->name('api.v1.ia02.detail');
    });
});
