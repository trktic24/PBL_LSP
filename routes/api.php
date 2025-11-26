<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller API
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\AsesorController;
use App\Http\Controllers\Api\TukController;
use App\Http\Controllers\Api\AsesiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ScheduleController;

// =======================================================
// 🔓 RUTE PUBLIK (tidak butuh token)
// =======================================================
Route::prefix('v1')->group(function() {

    // ✅ Login (Tidak butuh token)
    Route::post('/login', [LoginController::class, 'login']);

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
        Route::get('/tuk', [TukController::class, 'index']);      // GET semua TUK
        Route::get('/tuk/{id}', [TukController::class, 'show']);  // GET berdasarkan ID
        Route::post('/tuk', [TukController::class, 'store']);     // POST buat baru
        Route::put('/tuk/{id}', [TukController::class, 'update']); // PUT update
        Route::delete('/tuk/{id}', [TukController::class, 'destroy']); // DELETE hapus

        // =======================================================
        // 📘 MASTER SKEMA (CRUD LENGKAP)
        // =======================================================
        Route::get('/skema', [SkemaController::class, 'index']);
        Route::get('/skema/{id}', [SkemaController::class, 'show']);
        Route::post('/skema', [SkemaController::class, 'store']);
        Route::put('/skema/{id}', [SkemaController::class, 'updateData']);
        Route::delete('/skema/{id}', [SkemaController::class, 'destroy']);


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
