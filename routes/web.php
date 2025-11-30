<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\IA01Controller;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\Mapa02Controller;
use App\Http\Controllers\FrAk06Controller;
use App\Http\Controllers\FrAk07Controller;
use App\Http\Controllers\Ia11Controller; // <-- DITAMBAHKAN
use App\Http\Controllers\IA06Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\IA05Controller;

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

//APL
Route::get('/APL_01_1', function () {
    return view('frontend/APL_01/APL_01_1');
})->name('APL_01_1');
Route::get('/APL_01_2', function () {
    return view('frontend/APL_01/APL_01_2');
})->name('APL_01_2');
Route::get('/APL_01_3', function () {
    return view('frontend/APL_01/APL_01_3');
})->name('APL_01_3');
Route::get('/APL_02', function () {
    return view('frontend/APL_02/APL_02');
})->name('APL_02');

// MAPA
// MAPA
// Route::get('/FR-MAPA-02', function () {
//     return view('frontend/FR_MAPA_02');
// })->name('MAPA-02');

//AK
Route::get('/FR_AK_01', function () {
    return view('frontend/FR_AK_01');
})->name('FR_AK_01');
Route::get('/FR_AK_02', function () {
    return view('frontend/FR_AK_02');
})->name('FR_AK_02');
Route::get('/FR_AK_03', function () {
    return view('frontend/FR_AK_03');
})->name('FR_AK_03');
Route::get('/FR_AK_04', function () {
    return view('frontend/FR_AK_04');
})->name('FR_AK_04');
Route::get('/FR_AK_05', function () {
    return view('frontend/FR_AK_05');
})->name('FR_AK_05');
Route::get('/FR_AK_07/{id}', [FrAk07Controller::class, 'create'])->name('fr-ak-07.create');


Route::get('/IA_08', function () {
    return view('frontend/IA_08/IA_08');
})->name('IA08');

// ============================
//        IA11 ROUTES
// ============================
// GANTI ROUTE LAMA (VIEW STATIS)
Route::get('/FR_IA_11', [Ia11Controller::class, 'create'])->name('ia11.create');
Route::post('/FR_IA_11/store', [Ia11Controller::class, 'store'])->name('ia11.store');
// ============================
//IA06
Route::get('/IA_06_A', function () {
    return view('frontend/fr_IA_06_a');
})->name('IA06-a');

Route::get('/IA_06_B', function () {
    return view('frontend/fr_IA_06_b');
})->name('IA06-b');

Route::get('/IA_06_C', function () {
    return view('frontend/fr_IA_06_c');
})->name('IA06-c');

// --- ROUTE UNTUK ADMIN (FR.IA.06.A) ---
// Menggunakan [NamaController::class, 'namaMethod']
Route::get('/IA_06_A', [IA06Controller::class, 'index'])->name('fr_IA_06_a');

Route::get('/IA_06_B', [IA06Controller::class, 'kunciIndex'])->name('fr_IA_06_b');

Route::get('/IA_06_C', [IA06Controller::class, 'jawabIndex'])->name('fr_IA_06_c');
Route::post('/IA_06_C', [IA06Controller::class, 'jawabStore'])->name('fr_IA_06_c.store');

// --- ROUTE UNTUK CRUD SOAL (Tambah/Edit/Hapus) ---
Route::get('/soal/create', [IA06Controller::class, 'create'])->name('soal.create');
Route::post('/soal', [IA06Controller::class, 'store'])->name('soal.store');
Route::get('/soal/{id}/edit', [IA06Controller::class, 'edit'])->name('soal.edit');
Route::put('/soal/{id}', [IA06Controller::class, 'update'])->name('soal.update');
Route::delete('/soal/{id}', [IA06Controller::class, 'destroy'])->name('soal.destroy');

//porto
Route::get('/PORTOFOLIO', function () {
    return view('frontend/PORTOFOLIO');
})->name('PORTOFOLIO');

//porto
Route::get('/PORTOFOLIO', [PortofolioController::class, 'index'])->name('PORTOFOLIO');

//IA07
Route::get('/FR_IA_07', [IA07Controller::class, 'index'])->name('ia07.asesor');
Route::post('/FR_IA_07/store', [IA07Controller::class, 'store'])->name('ia07.store');

// IA 01 (MOVED OUTSIDE AUTH FOR TESTING)
// Route Halaman Awal (Cover)
Route::get('/ia01/{id_sertifikasi}/cover', [IA01Controller::class, 'showCover'])->name('ia01.cover');
Route::post('/ia01/{id_sertifikasi}/cover', [IA01Controller::class, 'storeCover'])->name('ia01.storeCover');
// Route Step Wizard (Unit 1, 2, dst)
Route::get('/ia01/{id_sertifikasi}/step/{urutan}', [IA01Controller::class, 'showStep'])->name('ia01.showStep');
Route::post('/ia01/{id_sertifikasi}/step/{urutan}', [IA01Controller::class, 'storeStep'])->name('ia01.storeStep');
Route::get('/ia01/{id_sertifikasi}/finish', [IA01Controller::class, 'showFinish'])->name('ia01.finish');
Route::post('/ia01/{id_sertifikasi}/finish', [IA01Controller::class, 'storeFinish'])->name('ia01.storeFinish');
// Route Admin Read-Only
Route::get('/ia01/{id_sertifikasi}/admin', [IA01Controller::class, 'showAdmin'])->name('ia01.admin.show');
// Route Halaman Sukses (Image 2)
Route::get('/ia01/success', function() {
    return view('frontend.IA_01.success');
})->name('ia01.success_page');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/ia02-test', function() {
        return "Route IA02 Terhubung! Silakan akses dengan ID, misal: /ia02/1";
    });

    // Route Utama (Memerlukan ID)
    Route::get('/ia02/{id_sertifikasi}', [IA02Controller::class, 'show'])->name('ia02.show');
    Route::post('/ia02/{id_sertifikasi}', [IA02Controller::class, 'store'])->name('ia02.store');

    // MAPA 02
    Route::get('/mapa02/{id_sertifikasi}', [Mapa02Controller::class, 'show'])->name('mapa02.show');
    Route::post('/mapa02/{id_sertifikasi}', [Mapa02Controller::class, 'store'])->name('mapa02.store');

    // IA 01
    // Route Halaman Awal (Cover)
    // Route::get('/ia01/{id_sertifikasi}/cover', [IA01Controller::class, 'showCover'])->name('ia01.cover');
    // Route::post('/ia01/{id_sertifikasi}/cover', [IA01Controller::class, 'storeCover'])->name('ia01.storeCover');
    // Route Step Wizard (Unit 1, 2, dst)
    // Route::get('/ia01/{id_sertifikasi}/step/{urutan}', [IA01Controller::class, 'showStep'])->name('ia01.showStep');
    // Route::post('/ia01/{id_sertifikasi}/step/{urutan}', [IA01Controller::class, 'storeStep'])->name('ia01.storeStep');
    // Route::get('/ia01/{id_sertifikasi}/finish', [IA01Controller::class, 'showFinish'])->name('ia01.finish');
    // Route::post('/ia01/{id_sertifikasi}/finish', [IA01Controller::class, 'storeFinish'])->name('ia01.storeFinish');

    // Route Admin Read-Only
    // Route::get('/ia01/{id_sertifikasi}/admin', [IA01Controller::class, 'showAdmin'])->name('ia01.admin.show');

    // Route Halaman Sukses (Image 2)
    // Route::get('/ia01/success', function() {
    //     return view('frontend.IA_01.success');
    // })->name('ia01.success_page');

    //Ak07

    Route::get('/FR_AK_07/{id}', [FrAk07Controller::class, 'create'])->name('fr-ak-07.create');

    Route::post('/FR_AK_07/{id}', [FrAk07Controller::class, 'store'])->name('fr-ak-07.store');
    /*
    |--------------------------------------------------------------------------
    | FORMULIR FR.IA.10 (Verifikasi Pihak Ketiga)
    |--------------------------------------------------------------------------
    */
    // Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    // Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');
    // Route::get('/FR-IA-10-view', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10'); 

    // /*
    // |--------------------------------------------------------------------------
    // | FORMULIR IA-05 (Pertanyaan Tertulis Pilihan Ganda)
    // |--------------------------------------------------------------------------
    // */
    
    // // --- Form A: Soal & Lembar Jawab ---
    // // Bisa diakses Admin (edit soal), Asesor (lihat soal), Asesi (jawab soal)
    // Route::middleware(['role:admin,asesor,asesi'])->group(function () {
    //     Route::get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');
    // });
    // // Admin simpan soal
    // Route::middleware(['role:admin'])->group(function () {
    //     Route::post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])->name('ia-05.store.soal');
    // });
    // // Asesi kirim jawaban
    // Route::middleware(['role:asesi'])->group(function () {
    //     Route::post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])->name('ia-05.store.jawaban');
    // });

    // // --- Form B: Kunci Jawaban ---
    // // Admin & Asesor bisa lihat
    // Route::middleware(['role:admin,asesor'])->group(function () {
    //     Route::get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])->name('FR_IA_05_B');
    // });
    // // Hanya Admin yang bisa simpan kunci jawaban
    // Route::middleware(['role:admin'])->group(function () {
    //     Route::post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])->name('ia-05.store.kunci');
    // });

    // // --- Form C: Penilaian ---
    // // Admin & Asesor bisa lihat hasil
    // Route::middleware(['role:admin,asesor'])->group(function () {
    //     Route::get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])->name('FR_IA_05_C');
    // });
    // // Hanya Asesor yang memberi penilaian (Y/T)
    // Route::middleware(['role:asesor'])->group(function () {
    //     Route::post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia-05.store.penilaian');
    // });
});

Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');
Route::get('/soal/create', [SoalController::class, 'create'])->name('soal.create');
Route::post('/soal', [SoalController::class, 'store'])->name('soal.store');
Route::get('/soal/{id}/edit', [SoalController::class, 'edit'])->name('soal.edit');
Route::put('/soal/{id}', [SoalController::class, 'update'])->name('soal.update');
Route::delete('/soal/{id}', [SoalController::class, 'destroy'])->name('soal.destroy');
Route::get('/onlysoal', [SoalController::class, 'onlySoal'])->name('soal.only');

// CRUD kunci jawaban
Route::post('/soal/{id}/kunci', [SoalController::class, 'storeKunci'])->name('kunci.store');
Route::put('/kunci/{id}', [SoalController::class, 'updateKunci'])->name('kunci.update');
Route::delete('/kunci/{id}', [SoalController::class, 'destroyKunci'])->name('kunci.destroy');

// Menampilkan halaman menjawab soal
Route::get('/jawab', [SoalController::class, 'jawabIndex'])->name('jawab.index');

// Menyimpan jawaban user
Route::post('/jawab', [SoalController::class, 'jawabStore'])->name('jawab.store');


Route::get('/ia01/success', function() {
    return view('frontend.IA_01.success');
})->name('ia01.success_page');



Route::get('/FR_AK_06', function () {
    // Tambahkan 'frontend.' di depannya
    return view('frontend.FR_AK_06');
});

Route::get('/FR_IA_04A', function () {
    // Tambahkan 'frontend.' di depannya
    return view('frontend.FR_IA_04A'); 
});




// Route FR.AK.06
Route::get('/asesmen/ak-06', [FrAk06Controller::class, 'index'])->name('ak06.index');
Route::post('/asesmen/ak-06', [FrAk06Controller::class, 'store'])->name('ak06.store');

use App\Http\Controllers\FrMapa01Controller;

// Route untuk menampilkan form
Route::get('/asesmen/mapa-01', [FrMapa01Controller::class, 'index'])->name('mapa01.index');

// Route untuk memproses simpan data (WAJIB ADA)
Route::post('/asesmen/mapa-01', [FrMapa01Controller::class, 'store'])->name('mapa01.store');

use App\Http\Controllers\AssessmentController;

// Ubah route lama menjadi ini:
Route::get('/FR_IA_04B', [AssessmentController::class, 'index'])->name('fr_ia_04b.index');
Route::post('/FR_IA_04B/store', [AssessmentController::class, 'store'])->name('fr_ia_04b.store');

// --- TARUH DI PALING BAWAH FILE web.php (DI LUAR middleware auth) ---

/*
|--------------------------------------------------------------------------
| ROUTE TESTING (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// IA 10
Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

// IA 05
// Form A (Soal/Jawab)
Route::get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');
Route::post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])->name('ia-05.store.soal');
Route::post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])->name('ia-05.store.jawaban');

// Form B (Kunci)
Route::get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])->name('FR_IA_05_B');
Route::post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])->name('ia-05.store.kunci');

// Form C (Penilaian)
Route::get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])->name('FR_IA_05_C');
Route::post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia-05.store.penilaian');


require __DIR__.'/auth.php';
