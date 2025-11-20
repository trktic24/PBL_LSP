<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\GoogleApiController;

use App\Http\Controllers\Api\V1\SkemaController;
use App\Http\Controllers\Api\V1\TukController;
use App\Http\Controllers\Api\V1\AsesorTableApiController;
use App\Http\Controllers\Api\V1\BeritaController;
use App\Http\Controllers\Api\V1\StrukturOrganisasiController;
use App\Http\Controllers\Api\V1\JadwalControllerAPI;

// =====================================
// PREFIX v1
// =====================================
Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'User data retrieved successfully',
            'data' => $request->user()
        ]);
    });

    // Login
    Route::post('/login', [LoginController::class, 'login']);

    // Register
    Route::prefix('register')->group(function () {
        Route::post('/asesi', [RegisterController::class, 'registerAsesi']);
        Route::post('/asesor', [RegisterController::class, 'registerAsesor']);
    });

    // Logout
    Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

    // Google OAuth
    Route::prefix('auth/google')->group(function () {
        Route::get('redirect', [GoogleApiController::class, 'redirect']);
        Route::get('callback', [GoogleApiController::class, 'callback']);
    });

    // TUK CRUD
    Route::apiResource('tuks', TukController::class);

    // Skema
    Route::get('/skema', [SkemaController::class, 'index']);

    // Asesor table
    Route::get('/asesor', [AsesorTableApiController::class, 'index']);

    // Berita CRUD
    Route::apiResource('berita', BeritaController::class);

    // Struktur organisasi CRUD
    Route::apiResource('struktur', StrukturOrganisasiController::class);

    // Jadwal API (barusan ditambahkan)
    Route::apiResource('jadwal', JadwalControllerAPI::class);

    // Route /user kedua - tetap dipertahankan
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

});
