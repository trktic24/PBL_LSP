<?php

use Illuminate\Support\Facades\Route;
use App\Models\Skema; 
use App\Models\Asesi; 

// --- KUMPULAN SEMUA CONTROLLER (JADI SATU DI ATAS) ---

// Kontroler Bawaan
use App\Http\Controllers\ProfileController;

// Kontroler Landing Page / Publik
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\JadwalController;

// Kontroler Formulir Pendaftaran Asesi
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\FormulirPendaftaran\DataSertifikasiAsesiController;
use App\Http\Controllers\FormulirPendaftaran\BuktiKelengkapanController;

// Kontroler Asesmen
use App\Http\Controllers\Kerahasiaan\PersetujuanKerahasiaanController;
use App\Http\Controllers\AsesmenController;

// Kontroler Aksi / Utility
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Apl01PdfController;
use App\Http\Controllers\Api\CountryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. HALAMAN PUBLIK (LANDING PAGE & INFORMASI)
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');
Route::view('/sertifikasi', 'sertifikasi_placeholder')->name('sertifikasi'); // Asumsi ini placeholder
Route::view('/alur-sertifikasi', 'landing_page.page_info.alur-sertifikasi')->name('info.alur');

// Grup Halaman Profil LSP
Route::prefix('profil-lsp')->name('profil.')->group(function () {
    Route::view('/visimisi', 'landing_page.page_profil.visimisi')->name('visimisi');
    Route::view('/struktur', 'landing_page.page_profil.struktur')->name('struktur');
    Route::view('/mitra', 'landing_page.page_profil.mitra')->name('mitra');
});

// Grup Halaman TUK (Tempat Uji Kompetensi)
Route::prefix('tuk')->name('tuk.')->group(function () {
    Route::get('/informasi', [TukController::class, 'index'])->name('info');
    Route::get('/detail/{id}', [TukController::class, 'showDetail'])->name('detail');
});


// ====================================================
// 2. RUTE AUTENTIKASI (LOGIN, REGISTER, DLL)
// ====================================================

require __DIR__.'/auth.php';


// ====================================================
// 3. RUTE YANG BUTUH LOGIN (DASHBOARD, FORMULIR, ASESMEN)
// ====================================================

Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD & PROFILE ---
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // --- TRACKER ---
    Route::get('/tracker/{id_asesi}', function ($id_asesi) {
        try {
            $asesi = Asesi::with('skema')->findOrFail($id_asesi); 
            return view('tracker', ['asesi' => $asesi]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/')->with('error', 'Data Asesi tidak ditemukan.');
        }
    })->name('tracker');


    // --- GRUP FORMULIR PENDAFTARAN (APL-01) ---
    Route::prefix('formulir-pendaftaran')->name('formulir.')->group(function () {
        
        // Halaman Form (dengan data Asesi)
        Route::get('/data-sertifikasi/{id_asesi}', [DataSertifikasiAsesiController::class, 'showDataSertifikasiAsesiPage'])->name('data-sertifikasi');
        Route::get('/bukti-pemohon/{id_asesi}', [BuktiKelengkapanController::class, 'showBuktiKelengkapanPage'])->name('bukti-pemohon');
        Route::get('/tanda-tangan/{id_asesi}', [TandaTanganController::class, 'showSignaturePage'])->name('tanda-tangan');
        
        // Halaman Statis
        Route::view('/menunggu-upload', 'formulir_pendaftaran/tunggu_upload_dokumen')->name('tunggu-upload');
        Route::view('/dokumen-ditolak', 'formulir_pendaftaran/dokumen_belum_memenuhi')->name('dokumen-ditolak');
    });

    // --- GRUP PEMBAYARAN ---
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::view('/', 'pembayaran/pembayaran')->name('index');
        Route::view('/upload-bukti', 'upload_bukti_pembayaran')->name('upload');
        Route::view('/menunggu-validasi', 'tunggu_pembayaran')->name('menunggu');
        
        // Aksi Pembayaran
        Route::get('/bayar', [PaymentController::class, 'createTransaction'])->name('create');
        Route::get('/diproses', [PaymentController::class, 'processed'])->name('diproses');
    });

    // --- GRUP ASESMEN (APL-02, AK, DLL) ---
    Route::prefix('asesmen')->name('asesmen.')->group(function () {
        
        // Pra-Asesmen (APL-02)
        Route::prefix('praasesmen')->name('praasesmen.')->group(function () {
            Route::view('/1', 'pra-assesmen.praasesmen1')->name('1');
            Route::view('/2', 'pra-assesmen.praasesmen2')->name('2');
            Route::view('/3', 'pra-assesmen.praasesmen3')->name('3');
            Route::view('/4', 'pra-assesmen.praasesmen4')->name('4');
            Route::view('/5', 'pra-assesmen.praasesmen5')->name('5');
            Route::view('/6', 'pra-assesmen.praasesmen6')->name('6');
            Route::view('/7', 'pra-assesmen.praasesmen7')->name('7');
            Route::view('/8', 'pra-assesmen.praasesmen8')->name('8');
        });

        // Halaman Asesmen Lainnya
        Route::view('/banding', 'banding')->name('banding');
        Route::view('/pertanyaan-lisan', 'pertanyaan_lisan')->name('pertanyaan-lisan');
        Route::view('/umpan-balik', 'umpan_balik')->name('umpan-balik');
        Route::view('/verifikasi-tuk', 'verifikasi_tuk')->name('verifikasi-tuk');
        Route::view('/belum-lulus', 'belum_lulus')->name('belum-lulus');

        // Form Kerahasiaan (AK-01)
        Route::get('/kerahasiaan/fr-ak01/{id_asesi}', [PersetujuanKerahasiaanController::class, 'showFrAk01'])->name('kerahasiaan.fr-ak01');
    });

    // --- GRUP PDF ---
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('/apl01/download/{id_asesi}', [Apl01PdfController::class, 'download'])->name('apl01.download');
        Route::get('/apl01/preview/{id_asesi}', [Apl01PdfController::class, 'preview'])->name('apl01.preview');
    });
});


// ====================================================
// 4. RUTE UTILITY (API, KEEPALIVE)
// ====================================================
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');


// ====================================================
// 5. RUTE DUPLIKAT/LAMA (DIKOMEN BIAR GAK BENTROK)
// ====================================================

// Rute ini '/bukti_pemohon' udah ada di dalem grup '/formulir-pendaftaran'
// Route::get('/bukti_pemohon', function () { return view('formulir pendaftaran/bukti_pemohon'); });

// Rute ini '/tanda_tangan_pemohon' udah ada di dalem grup '/formulir-pendaftaran'
// Route::get('/tanda_tangan_pemohon', function () { return view('formulir pendaftaran/tanda_tangan_pemohon'); });

// Rute ini '/skema/{id}' udah di-handle sama HomeController di atas
// Route::get('/skema/{id}', [SkemaController::class, 'show'])->name('skema.show');

// Rute ini '/' (root) udah di-handle sama HomeController di atas
// Route::get('/', [SkemaController::class, 'show'])->defaults('id', 1);

// Rute ini '/daftar-skema' udah ada di grup Publik (atas)
// Route::get('/daftar-skema', [BelajarController::class, 'index']);

// Rute ini '/fr_ak01' udah ada di dalem grup '/asesmen'
// Route::get('/fr_ak01', function () { return view('fr_ak01'); });

// Rute ini '/pembayaran_diproses' udah ada di dalem grup '/pembayaran'
// Route::get('/pembayaran_diproses', [PaymentController::class, 'processed'])->name('pembayaran_diproses');

// Rute ini '/tracker' udah dipindahin ke atas (grup auth)
// Route::get('/tracker', function () { return view('tracker'); });