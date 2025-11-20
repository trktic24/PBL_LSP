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

    Route::post('/login', [LoginController::class, 'login']);

    Route::post('/register', [RegisterController::class, 'register']);

    // Google Auth
    Route::prefix('auth/google')->group(function () {
        Route::get('redirect', [GoogleApiController::class, 'redirect']);
        Route::get('callback', [GoogleApiController::class, 'callback']);
    });

    // Logout (Wajib punya token)
    Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

    // User Profile Check
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'User data retrieved successfully',
            'data' => $request->user()
        ]);
    });


    // ==========================
    // DATA RESOURCES ROUTES
    // ==========================

    // Skema
    Route::get('/skema', [SkemaController::class, 'index']);

    // Daftar Asesor
    Route::get('/asesor', [AsesorTableApiController::class, 'index']);

    // TUK (Tempat Uji Kompetensi) - CRUD Lengkap
    Route::apiResource('tuks', TukController::class);

    // Berita - CRUD Lengkap
    Route::apiResource('berita', BeritaController::class);

    // Struktur Organisasi - CRUD Lengkap
    Route::apiResource('struktur', StrukturOrganisasiController::class);

});
