<?php

use App\Http\Controllers\BelajarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 2. Bikin Rute-nya
// "Kalo ada yang buka /api/skema, panggil SkemaController, fungsi 'apiIndex'"
Route::get('/belajar', [BelajarController::class, 'apiIndex']);