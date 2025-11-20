<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- KUMPULAN SEMUA CONTROLLER ---
use App\Http\Controllers\Api\TukController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\AsesorTableApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\FormulirPendaftaranAPI\TandaTanganAPIController;
use App\Http\Controllers\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\FormulirPendaftaranAPI\DataSertifikasiAsesiController;

/*
|--------------------------------------------------------------------------
| API Routes (VERSION 1)
|--------------------------------------------------------------------------
*/

// Bungkus semuanya dalam prefix 'v1'
// URL Jadinya: /api/v1/nama-route
Route::prefix('v1')->group(function () {

    // // --- 1. AUTHENTICATION ---
    // Route::post('/login', [LoginController::class, 'login']);
    
    // Route::prefix('register')->group(function () {
    //     Route::post('/asesi', [RegisterController::class, 'registerAsesi']);
    //     Route::post('/asesor', [RegisterController::class, 'registerAsesor']);
    // });

    // Route::prefix('auth/google')->group(function () {
    //     Route::get('redirect', [GoogleApiController::class, 'redirect']);
    //     Route::get('callback', [GoogleApiController::class, 'callback']);
    // });

    // // --- 2. PROTECTED ROUTES (Sanctum) ---
    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::get('/user', function (Request $request) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User data retrieved successfully',
    //             'data' => $request->user()
    //         ]);
    //     });
    //     Route::post('/logout', [LogoutController::class, 'logout']);
    // });

    // // --- 3. PUBLIC DATA (Read Only) ---
    // Route::get('/skema', [SkemaController::class, 'index']);
    // Route::get('/asesor', [AsesorTableApiController::class, 'index']);

    // // --- 4. RESOURCES ---
    // Route::apiResource('tuks', TukController::class);

    // // --- 5. FORMULIR ASESI (APL/AK) ---

    // // A. Data Sertifikasi & Tujuan Asesmen
    // Route::prefix('data-sertifikasi')->group(function () {
    //     Route::get('/detail/{id}', [DataSertifikasiAsesiController::class, 'getDetailSertifikasiApi'])
    //         ->name('api.v1.sertifikasi.detail');
        
    //     Route::get('/{id}', [DataSertifikasiAsesiController::class, 'getDataSertifikasiAsesiApi'])
    //         ->name('api.v1.data_sertifikasi.get');
        
    //     // Simpan Pilihan Tujuan Asesmen
    //     Route::post('/', [DataSertifikasiAsesiController::class, 'storeAjax'])
    //         ->name('api.v1.data_sertifikasi.store');
            
    //     Route::delete('/{id}', [DataSertifikasiAsesiController::class, 'deleteAjax'])
    //         ->name('api.v1.data_sertifikasi.delete');
    // });

    // // B. Bukti Kelengkapan (Upload File)
    // Route::prefix('bukti-kelengkapan')->group(function () {
    //     Route::get('/list/{id_data_sertifikasi_asesi}', [BuktiKelengkapanController::class, 'getDataBuktiKelengkapanApi'])
    //         ->name('api.v1.bukti_kelengkapan.get');

    //     Route::post('/store', [BuktiKelengkapanController::class, 'storeAjax'])
    //         ->name('api.v1.bukti_kelengkapan.store');

    //     Route::delete('/delete/{id}', [BuktiKelengkapanController::class, 'deleteAjax'])
    //         ->name('api.v1.bukti_kelengkapan.delete');
    // });

    // // C. Kerahasiaan (AK-01)
    // Route::get('/get-frak01-data/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])
    //     ->name('api.v1.get.frak01');
    // Route::post('/setuju-kerahasiaan/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])
    //     ->name('api.v1.setuju.frak01');

    // // D. Tanda Tangan
    // Route::get('/show-all', [TandaTanganAPIController::class, 'index']);
    // Route::get('/show-detail/{id_asesi}', [TandaTanganAPIController::class, 'show']);
    // Route::post('/ajax-simpan-tandatangan/{id_asesi}', [TandaTanganAPIController::class, 'storeAjax'])
    //     ->name('api.v1.simpan.tandatangan');

    // // --- 6. MIDTRANS PAYMENT CALLBACK ---
    // Route::post('/midtrans-callback', [PaymentCallbackController::class, 'receive']);

});