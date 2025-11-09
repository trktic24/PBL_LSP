<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller API
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\AsesorController;
use App\Http\Controllers\Api\TukController; 

Route::prefix('v1')->middleware('auth:sanctum')->group(function() {

    // --- RUTE PUBLIK ---
    Route::post('/login', [LoginController::class, 'login']);

    // --- RUTE TERPROTEKSI ---
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/me', [LoginController::class, 'me']); 

        // RUTE MASTER ASESOR
        Route::get('/asesors', [AsesorController::class, 'index']);
        Route::post('/asesors', [AsesorController::class, 'index']);

        // RUTE MASTER
        Route::get('/skema', [SkemaController::class, 'index']);
        Route::get('/skema/{id}', [SkemaController::class, 'show']);
        Route::post('/skema', [SkemaController::class, 'store']);
        Route::put('/skema/{id}', [SkemaController::class, 'update']);
        Route::delete('/skema/{id}', [SkemaController::class, 'destroy']);
        Route::get('/tuk', [TukController::class, 'index']);
    });
});