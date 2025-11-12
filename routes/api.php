<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller API
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\AsesorController;
use App\Http\Controllers\Api\TukController;

// =======================================================
// 🔓 RUTE PUBLIK (tidak butuh token)
// =======================================================
Route::prefix('v1')->group(function() {

    // ✅ Login tidak butuh token
    Route::post('/login', [LoginController::class, 'login']);

    // =======================================================
    // 🔐 RUTE TERPROTEKSI (butuh Bearer Token dari login)
    // =======================================================
    Route::middleware('auth:sanctum')->group(function () {

        // 🔸 Auth routes
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/me', [LoginController::class, 'me']);

        // 🔸 Master Asesor
        Route::get('/asesors', [AsesorController::class, 'index']);
        Route::post('/asesors', [AsesorController::class, 'index']); // <- kamu pakai post untuk index juga

        // 🔸 Master TUK
        Route::get('/tuk', [TukController::class, 'index']);

        // =======================================================
        // 📘 RUTE MASTER SKEMA (CRUD Lengkap)
        // =======================================================
        // GET semua skema
        Route::get('/skema', [SkemaController::class, 'index']);

        // GET satu skema berdasarkan ID
        Route::get('/skema/{id}', [SkemaController::class, 'show']);

        // POST buat skema baru
        Route::post('/skema', [SkemaController::class, 'store']);

        // PUT update skema
        Route::put('/skema/{id}', [SkemaController::class, 'update']);

        // DELETE hapus skema
        Route::delete('/skema/{id}', [SkemaController::class, 'destroy']);
    });
});
