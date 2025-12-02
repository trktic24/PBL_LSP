<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TandaTanganController;
use App\Http\Controllers\UmpanBalikController;
use App\Http\Controllers\BandingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkemaController;


Route::get('/', function () {
    return view('halaman_ambil_skema');
});

Route::get('/home', function () {
    return view('frontend/home');
})->name('home');
Route::get('/jadwal', function () {
    return view('frontend/jadwal');
})->name('jadwal');
Route::get('/laporan', function () {
    return view('frontend/laporan');
})->name('laporan');
Route::get('/profil', function () {
    return view('frontend/profil');
})->name('profil');
Route::get('/daftar_asesi', function () {
return view('frontend/daftar_asesi');
})->name('daftar_asesi');
Route::get('/tracker', function () {
    return view('frontend/tracker');
})->name('tracker');



Route::get('/tracker', function () {
    return view('tracker');
});

Route::get('/data_sertifikasi', function () {
    return view('data_sertifikasi');
});

Route::get('/tunggu_upload_dokumen', function () {
    return view('tunggu_upload_dokumen');
});

Route::get('/belum_lulus', function () {
    return view('belum_lulus');
});

Route::get('/bukti_pemohon', function () {
    return view('bukti_pemohon');
});

Route::get('/upload_bukti_pembayaran', function () {
    return view('upload_bukti_pembayaran');
});

Route::get('/bukti_pemohon', function () {
    return view('bukti_pemohon');
});

Route::get('/praasesmen1', function () {
    return view('praasesmen1');
});

Route::get('/praasesmen2', function () {
    return view('praasesmen2');
});

Route::get('/praasesmen3', function () {
    return view('praasesmen3');
});

Route::get('/praasesmen4', function () {
    return view('praasesmen4');
});

Route::get('/praasesmen5', function () {
    return view('praasesmen5');
});

Route::get('/praasesmen6', function () {
    return view('praasesmen6');
});

Route::get('/praasesmen7', function () {
    return view('praasesmen7');
});

Route::get('/tunggu_pembayaran', function () {
    return view('tunggu_pembayaran');
});

Route::get('/praasesmen8', function () {
    return view('praasesmen8');
});

Route::get('/pertanyaan_lisan', function () {
    return view('pertanyaan_lisan');
});

Route::get('/fr_ak01', function () {
    return view('fr_ak01');
});

Route::get('/verifikasi_tuk', function () {
    return view('verifikasi_tuk');
});

Route::get('/tanda_tangan_pemohon', [TandaTanganController::class, 'showTandaTanganForm'])
    ->name('tanda_tangan_pemohon.show');
Route::get('/', [SkemaController::class, 'show'])->defaults('id', 1);

Route::get('/skema/{id}', [SkemaController::class, 'show'])->name('skema.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', function () {
    return view('frontend/home');
})->name('home');
Route::get('/jadwal', function () {
    return view('frontend/jadwal');
})->name('jadwal');
Route::get('/laporan', function () {
    return view('frontend/laporan');
})->name('laporan');
Route::get('/profil', function () {
    return view('frontend/profil');
})->name('profil');
Route::get('/terimakasih/{id?}', function ($id = null) {
    return view('terimakasih', compact('id'));
})->name('terimakasih');
Route::get('/terimakasih_banding/{id?}', function ($id = null) {
    return view('terimakasih_banding', compact('id'));
})->name('terimakasih_banding');


Route::post('/simpan/tandatangan', [TandaTanganController::class, 'simpanTandaTangan'])
    ->name('simpan.tandatangan'); 

Route::get('/detail_umpan_balik/{id}', [UmpanBalikController::class, 'show'])->name('detail_umpan_balik');
Route::get('/detail_banding/{id}', [BandingController::class, 'show'])->name('detail_banding');

Route::post('/umpan_balik/store', [UmpanBalikController::class, 'store']);
Route::get('/umpan_balik', [UmpanBalikController::class, 'index']);
Route::get('/umpan_balik/{id}', [UmpanBalikController::class, 'show']);
Route::put('/umpan_balik/{id}', [UmpanBalikController::class, 'update']);
Route::delete('/umpan_balik/{id}', [UmpanBalikController::class, 'destroy']);

Route::get('/banding', [BandingController::class, 'create']);
Route::post('/banding/store', [BandingController::class, 'store']);
Route::put('banding/{id}', [BandingController::class, 'update']);
Route::delete('/banding/{id}', [BandingController::class, 'destroy']);
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';