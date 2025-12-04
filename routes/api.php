<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- KUMPULAN SEMUA CONTROLLER ---
use App\Http\Controllers\Api\TukController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\Apl02\PraasesmenController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\AsesorTableApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\asesmen\AsesmenEsaiController;
use App\Http\Controllers\JadwalTukAPI\JadwalTukAPIController;
use App\Http\Controllers\asesmen\AsesmenPilihanGandaController;
use App\Http\Controllers\FormulirPendaftaranAPI\TandaTanganAPIController;
use App\Http\Controllers\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\FormulirPendaftaranAPI\DataSertifikasiAsesiController;
use App\Http\Controllers\Ak04API\APIBandingController;
use App\Http\Controllers\Ia02Controller;
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
                'data' => $request->user()
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
        Route::get('/detail/{id}', [DataSertifikasiAsesiController::class, 'getDetailSertifikasiApi'])
            ->name('api.v1.sertifikasi.detail');
        
        Route::get('/{id}', [DataSertifikasiAsesiController::class, 'getDataSertifikasiAsesiApi'])
            ->name('api.v1.data_sertifikasi.get');
        
        // Simpan Pilihan Tujuan Asesmen
        Route::post('/', [DataSertifikasiAsesiController::class, 'storeAjax'])
            ->name('api.v1.data_sertifikasi.store');
    });

    // B. Bukti Kelengkapan (Upload File)
    Route::prefix('bukti-kelengkapan')->group(function () {
        Route::get('/list/{id_data_sertifikasi_asesi}', [BuktiKelengkapanController::class, 'getDataBuktiKelengkapanApi'])
            ->name('api.v1.bukti_kelengkapan.get');

        Route::post('/store', [BuktiKelengkapanController::class, 'storeAjax'])
            ->name('api.v1.bukti_kelengkapan.store');

        Route::delete('/delete/{id}', [BuktiKelengkapanController::class, 'deleteAjax'])
            ->name('api.v1.bukti_kelengkapan.delete');
    });

    // C. Kerahasiaan (AK-01)
    Route::get('/get-frak01-data/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])
        ->name('api.v1.get.frak01');
    Route::post('/setuju-kerahasiaan/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])
        ->name('api.v1.setuju.frak01');

    // D. Tanda Tangan
    Route::get('/show-all', [TandaTanganAPIController::class, 'index']);
    Route::get('/show-detail/{id_asesi}', [TandaTanganAPIController::class, 'show']);
    Route::post('/ajax-simpan-tandatangan/{id_asesi}', [TandaTanganAPIController::class, 'storeAjax'])
        ->name('api.v1.simpan.tandatangan');

    // --- 6. MIDTRANS PAYMENT CALLBACK ---
    Route::post('/midtrans-callback', [PaymentCallbackController::class, 'receive']);

    Route::prefix('pra-asesmen')->group(function () {
        
        // Simpan Jawaban & File Bukti
        // URL: POST /api/v1/pra-asesmen/{id_sertifikasi}
        Route::post('/{id_sertifikasi}', [PraasesmenController::class, 'store'])
            ->name('api.v1.apl02.store');

    });

    Route::prefix('kerahasiaan')->group(function () {
    
        // GET: Ambil data form & checkbox
        Route::get('/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])
            ->name('api.v1.get.frak01');
        
        // POST: Simpan checkbox & update status
        Route::post('/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])
            ->name('api.v1.setuju.frak01');          
    });
    
    // --- Jadwal & TUK ---
    Route::prefix('jadwal-tuk')->group(function () {
        Route::get('/{id_sertifikasi}', [JadwalTukAPIController::class, 'getJadwalData']);
        Route::post('/konfirmasi/{id_sertifikasi}', [JadwalTukAPIController::class, 'konfirmasiJadwal']);
    });

   // ======================= BANDING (AK.04) =======================
    Route::prefix('banding')->group(function () {

        //ambil data
         Route::get('/{id_sertifikasi}', [APIBandingController::class, 'getBandingData']
         )->name('api.v1.get.ak04');
         
         // POST: Simpan hasil banding
        Route::post('/{id_sertifikasi}', [APIBandingController::class, 'simpanBanding'])
        ->name('api.v1.post.ak04');
        
        });


    // GET: Ambil daftar soal
    Route::get('/asesmen-teori/{id_sertifikasi}/soal', [AsesmenPilihanGandaController::class, 'getQuestions']);

    // POST: Simpan jawaban
    Route::post('/asesmen-teori/{id_sertifikasi}/submit', [AsesmenPilihanGandaController::class, 'submitAnswers']);

    // API Asesmen Essai (IA-06)
    Route::get('/asesmen-esai/{id_sertifikasi}/soal', [AsesmenEsaiController::class, 'getQuestions']);
    Route::post('/asesmen-esai/{id_sertifikasi}/submit', [AsesmenEsaiController::class, 'submitAnswers']);
});


Route::prefix('v1')->group(function () {
    
    // =================================================================
    // ROUTE FR.IA.02 (API - DATA FETCH)
    // =================================================================
    
    // Prefix penuh: /api/v1/ia02/...
    Route::prefix('ia02')->group(function () {

        // GET: Ambil data IA.02 (API)
        // Jalur Penuh: /api/v1/ia02/{id_data_sertifikasi_asesi}/data
        // Catatan: Menggunakan method 'apiIndex' yang ada di Ia02Controller Anda
        Route::get('/{id_data_sertifikasi_asesi}/data', 
            [Ia02Controller::class, 'apiDetail'] 
        )->name('api.v1.ia02.detail');
    });

    // TIDAK ADA ROUTE IA03 di sini (Sesuai permintaan)
    
});
// ==============================================================
// 🛠️ RUTE KHUSUS DEV: UPDATE STATUS MANUAL (CHEAT)
// ==============================================================
// HAPUS RUTE INI KALAU SUDAH PRODUCTION YA!
Route::post('/dev/update-status', function (Illuminate\Http\Request $request) {
    
    // 1. Cari Data
    $sertifikasi = \App\Models\DataSertifikasiAsesi::find($request->id);
    
    if (!$sertifikasi) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    // 2. Update Status Sesuai Request
    $sertifikasi->status_sertifikasi = $request->status;
    $sertifikasi->save();

    return response()->json([
        'success' => true,
        'message' => 'Status berhasil diubah paksa!',
        'data' => [
            'id' => $sertifikasi->id_data_sertifikasi_asesi,
            'status_baru' => $sertifikasi->status_sertifikasi,
            'level_baru' => $sertifikasi->progres_level // Biar lu bisa cek levelnya juga
        ]
    ]);
});