<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkemaController;

// [PERUBAHAN 1] Tambahkan impor Model Skema di sini
use App\Models\Skema; 

// Import Controller
use App\Http\Controllers\AsesmenController;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Apl01PdfController;
use App\Models\Asesor; // Pastikan use Model ini
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\Kerahasiaan\PersetujuanKerahasiaanController;
use App\Models\Asesi; // <-- [PENTING] Tambahin ini buat route /tracker
use App\Http\Controllers\FormulirPendaftaran\DataSertifikasiAsesiController;
use App\Http\Controllers\FormulirPendaftaran\BuktiKelengkapanController;
use App\Http\Controllers\TrackerController; // <-- [PERUBAHAN DITAMBAHKAN]
use App\Http\Controllers\Apl02\PraasesmenController; // WAJIB panggil Controller Anda di sini!

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. HALAMAN STATIS (VIEW ONLY)
// ====================================================

// [PERUBAHAN 2] Rute '/' Anda telah diperbaiki
// Route::get('/', function () {
    
//     // 1. Ambil skema PERTAMA dari database beserta relasinya
//     $skema_pertama = Skema::with(['unitKompetensi', 'detailSertifikasi'])->first();

//     // 2. Jika database kosong, beri pesan error
//     if (!$skema_pertama) {
//         abort(404, 'Tidak ada data skema yang ditemukan di database.');
//     }

//     // 3. Kirim data skema itu ke view dengan nama variabel 'skema'
//     return view('halaman_ambil_skema', [
//         'skema' => $skema_pertama
//     ]);
// });


// --- Formulir Pendaftaran Views ---
Route::get('/data_sertifikasi/{id_asesi}', function ($id_asesi) {
    try {
        // Kita ambil data asesi-nya pake ID dari URL
        $asesi = Asesi::findOrFail($id_asesi); 

        // Sekarang kita kirim data $asesi itu ke view
        return view('formulir_pendaftaran/data_sertifikasi', [
            'asesi' => $asesi
        ]);

    } catch (\Exception $e) {
        // Kalo Asesi ID-nya gak ada, mentalin ke home
        return redirect('/')->with('error', 'Data Asesi tidak ditemukan.');
    }
})->name('data.sertifikasi'); // Kita kasih nama route-nya

Route::get('/tunggu_upload_dokumen', function () { return view('formulir_pendaftaran/tunggu_upload_dokumen'); });
Route::get('/belum_memenuhi', function () { return view('formulir_pendaftaran/dokumen_belum_memenuhi'); });
Route::get('/bukti_pemohon/{id_asesi}', function ($id_asesi) {
    try {
        // Kita ambil data asesi-nya pake ID dari URL
        $asesi = Asesi::findOrFail($id_asesi); 

        // Sekarang kita kirim data $asesi itu ke view
        return view('formulir_pendaftaran/bukti_pemohon', [
            'asesi' => $asesi
        ]);

    } catch (\Exception $e) {
        // Kalo Asesi ID-nya gak ada, mentalin ke home
        return redirect('/')->with('error', 'Data Asesi tidak ditemukan.');
    }
})->name('bukti.pemohon'); // Kita kasih nama route-nya

// --- Pembayaran Views ---
Route::get('/pembayaran', function () { return view('pembayaran/pembayaran'); });
Route::get('/upload_bukti_pembayaran', function () { return view('upload_bukti_pembayaran'); });
Route::get('/tunggu_pembayaran', function () { return view('tunggu_pembayaran'); });

// Route::get('/praasesmen', [PraasesmenController::class, 'index']);

// OPSI 2: Menggunakan URL spesifik (Direkomendasikan)
// Ini lebih aman dan rapi, biasanya untuk formulir atau fitur tertentu.
// Contoh: Mengakses formulir di domainanda.com/asesmen/pra
// Route::get('/asesmen/pra', [PraAsesmenController::class, 'index']);

// // --- Pra-Asesmen Views ---
// Route::get('/praasesmen1', function () { return view('pra-assesmen.praasesmen1'); });
// Route::get('/praasesmen2', function () { return view('pra-assesmen.praasesmen2'); });
// Route::get('/praasesmen3', function () { return view('pra-assesmen.praasesmen3'); });
// Route::get('/praasesmen4', function () { return view('pra-assesmen.praasesmen4'); });
// Route::get('/praasesmen5', function () { return view('pra-assesmen.praasesmen5'); });
// Route::get('/praasesmen6', function () { return view('pra-assesmen.praasesmen6'); });
// Route::get('/praasesmen7', function () { return view('pra-assesmen.praasesmen7'); });
// Route::get('/praasesmen8', function () { return view('pra-assesmen.praasesmen8'); });

// --- Asesmen Lainnya Views ---
Route::get('/belum_lulus', function () { return view('belum_lulus'); });
Route::get('/banding', function () { return view('banding'); });
Route::get('/pertanyaan_lisan', function () { return view('pertanyaan_lisan'); });
Route::get('/umpan_balik', function () { return view('umpan_balik'); });
Route::get('/verifikasi_tuk', function () { return view('verifikasi_tuk'); });


// ====================================================
// 2. FEATURE ROUTES (CONTROLLER)
// ====================================================

// --- Tracker (FIXED) ---
// [PERUBAHAN INTI]
// Rute ini diubah untuk menggunakan TrackerController dan Auth.
// Tidak lagi perlu {id_asesi} di URL karena data diambil dari user yang login.
Route::get('/tracker', [TrackerController::class, 'index'])
        ->middleware('auth') // Wajibkan login untuk mengakses halaman ini
        ->name('tracker');

// [RUTE API BARU - DITAMBAHKAN]
// Rute ini akan dipanggil oleh JavaScript untuk mendaftarkan jadwal
Route::post('/api/jadwal/daftar', [TrackerController::class, 'daftarJadwal'])
        ->middleware('auth') // Wajibkan login
        ->name('api.jadwal.daftar');

// --- Tanda Tangan (Web View Only) ---
Route::get('/halaman-tanda-tangan/{id_asesi}', [TandaTanganController::class, 'showSignaturePage'])
       ->name('show.tandatangan');
Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');

// --- Data Sertifikasi ---
Route::get('/formulir/data-sertifikasi', [DataSertifikasiAsesiController::class, 'showDataSertifikasiAsesiPage'])
    ->name('formulir.data-sertifikasi');

//bukti kelengkapan
Route::get('/formulir/bukti-pemohon', [BuktiKelengkapanController::class, 'showBuktiKelengkapanPage'])
    ->name('web.bukti_pemohon.show');

// --- Kerahasiaan ---
//Route::get('/asesmen/fr_ak01', [AsesmenController::class, 'showFrAk01'])->name('asesmen.fr_ak01');
Route::get('/kerahasiaan/fr-ak01/{id_asesi}', [PersetujuanKerahasiaanController::class, 'showFrAk01'])
       ->name('kerahasiaan.fr_ak01');

// --- Pembayaran (Action) ---
Route::get('/bayar', [PaymentController::class, 'createTransaction'])->name('payment.create');

Route::get('/pembayaran_diproses', [PaymentController::class, 'processed'])
       ->name('pembayaran_diproses'); // <-- INI YANG MEMPERBAIKI ERROR

// --- PDF ---
Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])->name('apl01.download');
Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])->name('apl01.preview');


// ====================================================
// 3. AUTH & DASHBOARD
// ====================================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\SkemaWebController;

/*
|--------------------------------------------------------------------------
| Halaman Home & Detail Skema
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route resmi untuk detail skema
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');

// Tetap biarkan route /detail_skema/{id} jika masih dipakai di Blade lain
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// Rute Detail Jadwal
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');

/*
|--------------------------------------------------------------------------
| Custom Routes (Info TUK dan Alur)
|--------------------------------------------------------------------------
*/
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');

Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

/*
|--------------------------------------------------------------------------
| Web Profil Routes
|--------------------------------------------------------------------------
*/
Route::get('/visimisi', function () {
    return view('landing_page.page_profil.visimisi');
})->name('profil.visimisi');

Route::get('/struktur', function () {
    return view('landing_page.page_profil.struktur');
})->name('profil.struktur');

Route::get('/mitra', function () {
    return view('landing_page.page_profil.mitra');
})->name('profil.mitra');

/*
|--------------------------------------------------------------------------
| Halaman Utama & Menu Utama (YANG PAKE CONTROLLER)
|--------------------------------------------------------------------------
*/
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');

Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

/*
|--------------------------------------------------------------------------
| API & Keep Alive
|--------------------------------------------------------------------------
*/
Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});
Route::get('/api/search-countries', [CountryController::class, 'search'])
    ->name('api.countries.search');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';