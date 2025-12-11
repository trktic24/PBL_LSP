<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- KUMPULAN SEMUA CONTROLLER ---
use App\Http\Controllers\Api\TukController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Asesi\IA02\Ia02Controller;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\AsesorTableApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
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

    Route::prefix('auth/google')->group(function () {
        Route::get('redirect', [GoogleApiController::class, 'redirect']);
        Route::get('callback', [GoogleApiController::class, 'callback']);
    });

    // --- 2. PROTECTED ROUTES (Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'User data retrieved successfully',
                'data' => $request->user(),
            ]);
        });
        Route::post('/logout', [LogoutController::class, 'logout']);
    });

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
