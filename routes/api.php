<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\GoogleApiController;

use App\Http\Controllers\Api\V1\SkemaController;
use App\Http\Controllers\Api\V1\DetailSkemaController;
use App\Http\Controllers\Api\V1\TukController;
use App\Http\Controllers\Api\V1\AsesorTableApiController;
use App\Http\Controllers\Api\V1\BeritaController;
use App\Http\Controllers\Api\V1\StrukturOrganisasiController;
use App\Http\Controllers\Api\V1\JadwalControllerAPI; // Sudah benar

/*
|--------------------------------------------------------------------------
| API Routes V1
|--------------------------------------------------------------------------
| Base URL: /api/v1/...
*/

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
    // PROTECTED ROUTES (Requires Sanctum Token)
    // ==========================
    Route::middleware('auth:sanctum')->group(function () {
        
        // User Profile Check (Dua route sebelumnya digabung menjadi satu)
        Route::get('/user', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'User data retrieved successfully',
                // Mengambil user data (sama seperti return $request->user())
                'data' => $request->user() 
            ]);
        });

        // Logout
        Route::post('/logout', [LogoutController::class, 'logout']);
        
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
});