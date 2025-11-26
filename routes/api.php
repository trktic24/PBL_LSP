<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\TukController; // <-- TAMBAHKAN INI
use App\Http\Controllers\Api\AsesorTableApiController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\AsesorApiController;

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