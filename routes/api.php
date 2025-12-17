<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==========================
// 1. AUTH CONTROLLERS
// ==========================
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\GoogleApiController;

// ==========================
// 2. MASTER DATA CONTROLLERS
// ==========================
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\V1\DetailSkemaController;
use App\Http\Controllers\Api\V1\TukController;
use App\Http\Controllers\Api\TukAdminController;
use App\Http\Controllers\Api\V1\BeritaAPIController;
use App\Http\Controllers\Api\V1\StrukturOrganisasiController;
use App\Http\Controllers\Api\V1\MitraController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\KelompokPekerjaanController;
use App\Http\Controllers\Api\UnitKompetensiController;

// ==========================
// 3. ASESOR CONTROLLERS
// ==========================
use App\Http\Controllers\Api\AsesorApiController;
use App\Http\Controllers\Api\AsesorController;
use App\Http\Controllers\Api\V1\AsesorTableApiController;
use App\Http\Controllers\Api\Asesor\ProfilAsesorApiController;
use App\Http\Controllers\Api\Asesor\JadwalAsesorApiController;

// ==========================
// 4. JADWAL & TRANSAKSI
// ==========================
use App\Http\Controllers\Api\V1\JadwalControllerAPI;
use App\Http\Controllers\Api\BeritaAcaraController;
use App\Http\Controllers\Api\DaftarHadirController;
use App\Http\Controllers\Asesi\JadwalTukAPI\JadwalTukAPIController;
use App\Http\Controllers\Asesi\pembayaran\PaymentCallbackController;

// ==========================
// 5. ASESI & ASESMEN CONTROLLERS
// ==========================
use App\Http\Controllers\Api\AsesiController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\DataSertifikasiAsesiController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController; // AK-01
use App\Http\Controllers\Asesi\Apl02\PraasesmenController; // APL-02
use App\Http\Controllers\Asesi\Ak04API\APIBandingController; // AK-04
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\TandaTanganAPIController;

// INSTRUMEN ASESMEN (IA)
use App\Http\Controllers\Api\SoalIA05ApiController; // IA-05
use App\Http\Controllers\Asesi\asesmen\AsesmenPilihanGandaController; // IA-05 Asesi
use App\Http\Controllers\Api\SoalIa06Controller; // IA-06
use App\Http\Controllers\Asesi\asesmen\AsesmenEsaiController; // IA-06 Asesi
use App\Http\Controllers\Api\Ia10ApiController; // IA-10
use App\Http\Controllers\Api\FrAk07ApiController; // FR-AK-07
use App\Http\Controllers\Api\Mapa02ApiController; // MAPA-02
use App\Http\Controllers\Api\Ia02ApiController; // IA-02
use App\Http\Controllers\Asesi\IA02\Ia02AsesiController; // IA-02 Asesi
use App\Http\Controllers\Api\Ia01ApiController; // IA-01


/*
|--------------------------------------------------------------------------
| API Routes (VERSION 1)
|--------------------------------------------------------------------------
| Base URL: /api/v1
*/

Route::prefix('v1')->group(function () {

    // ==========================
    // 🔓 PUBLIC ROUTES (No Token Required)
    // ==========================

    // --- Authentication ---
    Route::post('/login', [LoginController::class, 'login']);
    Route::prefix('register')->group(function () {
        Route::post('/asesi', [RegisterController::class, 'registerAsesi']);
        Route::post('/asesor', [RegisterController::class, 'registerAsesor']);
    });
    Route::prefix('auth/google')->group(function () {
        Route::get('redirect', [GoogleApiController::class, 'redirect']);
        Route::get('callback', [GoogleApiController::class, 'callback']);
    });
    Route::post('/midtrans-callback', [PaymentCallbackController::class, 'receive']);

    // --- Public Resources ---
    Route::get('/skema', [SkemaController::class, 'index']);
    Route::get('/skema/{id}', [DetailSkemaController::class, 'show']);
    Route::get('/asesor', [AsesorTableApiController::class, 'index']);
    
    // CRUD Resources (Publicly accessible for read usually, but standard resourceful routes created here)
    Route::apiResource('tuks', TukController::class);
    Route::apiResource('berita', BeritaAPIController::class);
    Route::apiResource('struktur', StrukturOrganisasiController::class);
    Route::apiResource('mitra', MitraController::class);
    Route::apiResource('jadwal', JadwalControllerAPI::class)->names([
        'index' => 'api.jadwal.index',
        'store' => 'api.jadwal.store',
        'show' => 'api.jadwal.show',
        'update' => 'api.jadwal.update',
        'destroy' => 'api.jadwal.destroy',
    ]);


    // ==========================
    // 🧑‍🎓 ASESI WORKFLOW
    // ==========================
    
    // 1. Data Sertifikasi & Tujuan
    Route::prefix('data-sertifikasi')->group(function () {
        Route::get('/detail/{id}', [DataSertifikasiAsesiController::class, 'getDetailSertifikasiApi'])->name('api.v1.sertifikasi.detail');
        Route::get('/{id}', [DataSertifikasiAsesiController::class, 'getDataSertifikasiAsesiApi'])->name('api.v1.data_sertifikasi.get');
        Route::post('/', [DataSertifikasiAsesiController::class, 'storeAjax'])->name('api.v1.data_sertifikasi.store');
    });

    // 2. Bukti Kelengkapan
    Route::prefix('bukti-kelengkapan')->group(function () {
        Route::get('/list/{id_data_sertifikasi_asesi}', [BuktiKelengkapanController::class, 'getDataBuktiKelengkapanApi'])->name('api.v1.bukti_kelengkapan.get');
        Route::post('/store', [BuktiKelengkapanController::class, 'storeAjax'])->name('api.v1.bukti_kelengkapan.store');
        Route::delete('/delete/{id}', [BuktiKelengkapanController::class, 'deleteAjax'])->name('api.v1.bukti_kelengkapan.delete');
    });

    // 3. Pra Asesmen (APL-02)
    Route::prefix('pra-asesmen')->group(function () {
        Route::post('/{id_sertifikasi}', [PraasesmenController::class, 'store'])->name('api.v1.apl02.store');
    });

    // 4. Kerahasiaan (AK-01)
    Route::prefix('kerahasiaan')->group(function () {
        Route::get('/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])->name('api.v1.get.frak01');
        Route::post('/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])->name('api.v1.setuju.frak01');
    });
    // Duplicate route handling (legacy support from conflicts)
    Route::get('/get-frak01-data/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data']);
    Route::post('/setuju-kerahasiaan/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan']);

    // 5. Jadwal & TUK
    Route::prefix('jadwal-tuk')->group(function () {
        Route::get('/{id_sertifikasi}', [JadwalTukAPIController::class, 'getJadwalData']);
        Route::post('/konfirmasi/{id_sertifikasi}', [JadwalTukAPIController::class, 'konfirmasiJadwal']);
    });

    // 6. Asesmen Teori (IA-05 & IA-06)
    // IA-05 Pilihan Ganda
    Route::get('/asesmen-teori/{id_sertifikasi}/soal', [AsesmenPilihanGandaController::class, 'getQuestions']);
    Route::post('/asesmen-teori/{id_sertifikasi}/submit', [AsesmenPilihanGandaController::class, 'submitAnswers']);

    // IA-06 Essai
    Route::get('/asesmen-esai/{id_sertifikasi}/soal', [AsesmenEsaiController::class, 'getQuestions']);
    Route::post('/asesmen-esai/{id_sertifikasi}/submit', [AsesmenEsaiController::class, 'submitAnswers']);

    // 7. IA-02 (Observasi)
    Route::prefix('ia02')->group(function () {
        Route::get('/{id_data_sertifikasi_asesi}/data', [Ia02AsesiController::class, 'apiDetail'])->name('api.v1.ia02.detail');
    });

    // 8. Banding (AK-04)
    Route::prefix('banding')->group(function () {
        Route::get('/{id_sertifikasi}', [APIBandingController::class, 'getBandingData'])->name('api.v1.get.ak04');
        Route::post('/{id_sertifikasi}', [APIBandingController::class, 'simpanBanding'])->name('api.v1.post.ak04');
    });

    // 9. Tanda Tangan
    Route::prefix('tanda-tangan')->group(function () {
            Route::get('/show-all', [TandaTanganAPIController::class, 'index']);
            Route::get('/show-detail/{id_asesi}', [TandaTanganAPIController::class, 'show']);
            Route::post('/simpan/{id_asesi}', [TandaTanganAPIController::class, 'storeAjax'])->name('api.v1.simpan.tandatangan');
    });
    // Legacy route support
    Route::get('/show-all', [TandaTanganAPIController::class, 'index']);
    Route::get('/show-detail/{id_asesi}', [TandaTanganAPIController::class, 'show']);
    Route::post('/ajax-simpan-tandatangan/{id_asesi}', [TandaTanganAPIController::class, 'storeAjax']);


    // ==========================
    // 🔐 PROTECTED ROUTES (Bearer Token Required)
    // ==========================
    Route::middleware('auth:sanctum')->group(function () {

        // --- User Info ---
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/me', [LoginController::class, 'me']);
        Route::get('/user', fn(Request $request) => response()->json([
            'success' => true, 
            'message' => 'User data retrieved', 
            'data' => $request->user()
        ]));

        // ==========================
        // 👷 ASESOR WORKFLOW
        // ==========================
        Route::prefix('asesor')->group(function () {
            // Profil
            Route::get('/profil', [ProfilAsesorApiController::class, 'show']);
            Route::match(['post', 'put'], '/profil', [ProfilAsesorApiController::class, 'update']);
            
            // CRUD Master Asesor (Admin Only usually, but here for completeness from conflicts)
            Route::post('/', [AsesorController::class, 'store']);
            Route::get('/{id}', [AsesorController::class, 'show']);
            Route::put('/{id}', [AsesorController::class, 'update']);
            Route::delete('/{id}', [AsesorController::class, 'destroy']);
        });

        // Jadwal Asesor
        Route::apiResource('jadwal-asesor', JadwalAsesorApiController::class);
        Route::get('/jadwal/{id}/berita-acara', [BeritaAcaraController::class, 'beritaAcara']);
        Route::get('/jadwal/{id_jadwal}/daftar-hadir',[DaftarHadirController::class, 'showKehadiran']);
        Route::post('/jadwal/{id_jadwal}/daftar-hadir',[DaftarHadirController::class, 'storeKehadiran']);
        
        // Penilaian (Instrumen)
        // IA-05, IA-06, IA-10 Assessment by Asesor
        Route::prefix('ia-05')->group(function () {
            Route::post('/soal', [SoalIA05ApiController::class, 'storeSoal']); // Tambah soal
        });

        Route::post('soal-ia06/penilaian', [SoalIa06Controller::class, 'storePenilaianAsesor']);
        Route::post('soal-ia06/umpan-balik', [SoalIa06Controller::class, 'storeUmpanBalikAsesi']);
        Route::get('soal-ia06/umpan-balik/{id_data_sertifikasi_asesi}', [SoalIa06Controller::class, 'getUmpanBalikAsesi']);

        Route::get('/ia-10/{id}', [Ia10ApiController::class, 'show']);
        Route::post('/ia-10', [Ia10ApiController::class, 'store']);

        // FR-AK-07, MAPA-02, IA-02
        Route::get('/fr-ak-07/{id}', [FrAk07ApiController::class, 'show']);
        Route::post('/fr-ak-07/{id}', [FrAk07ApiController::class, 'store']);
        
        Route::get('/mapa-02/{id}', [Mapa02ApiController::class, 'show']);
        Route::post('/mapa-02/{id}', [Mapa02ApiController::class, 'store']);

        Route::get('/ia-02/{id}', [Ia02ApiController::class, 'show']);
        Route::post('/ia-02/{id}', [Ia02ApiController::class, 'store']);

        Route::get('/ia-01/{id}', [Ia01ApiController::class, 'show']);
        Route::post('/ia-01/{id}', [Ia01ApiController::class, 'store']);

        

        // ==========================
        // 🔧 ADMIN / MASTER DATA CRUD
        // ==========================
        
        // Master Skema
        Route::post('/skema', [SkemaController::class, 'store']);
        Route::put('/skema/{id}', [SkemaController::class, 'updateData']);
        Route::delete('/skema/{id}', [SkemaController::class, 'destroy']);
        
        // Master TUK
        Route::prefix('tuk')->group(function() {
            Route::get('/', [TukAdminController::class, 'index']);
            Route::get('/{id}', [TukAdminController::class, 'show']);
            Route::post('/', [TukAdminController::class, 'store']);
            Route::put('/{id}', [TukAdminController::class, 'update']);
            Route::delete('/{id}', [TukAdminController::class, 'destroy']);
        });

        // Master Asesi
        Route::apiResource('asesi', AsesiController::class);
        
        // Master Category
        Route::apiResource('category', CategoryController::class);
        
        // Master Unit & Kelompok
        Route::apiResource('kelompokpekerjaan', KelompokPekerjaanController::class);
        Route::apiResource('unitkompetensi', UnitKompetensiController::class);

    });
});
