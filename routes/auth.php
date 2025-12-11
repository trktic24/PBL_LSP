<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Asesi\DashboardController as AsesiDashboardController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\Admin\AsesiController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\DaftarHadirController;
use App\Http\Controllers\Admin\TukAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AsesiProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\APL01Controller;
use App\Http\Controllers\FrMapa01Controller;
use App\Http\Controllers\Mapa02Controller;
use App\Http\Controllers\Asesor\AsesiTrackerController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Ia06Controller;

// ======================================================
// --- RUTE GUEST (YANG BELUM LOGIN) ---
// ======================================================
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Admin Login Routes
    Route::prefix('admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login_admin');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login_admin.post');
        Route::get('forgot-password', [AdminLoginController::class, 'showForgotPassword'])->name('forgot_pass');
        Route::post('forgot-password', [AdminLoginController::class, 'sendResetLink'])->name('forgot_pass.send');
    });
});

// --- RUTE GOOGLE AUTH ---
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');


// ======================================================
// --- RUTE YANG WAJIB LOGIN ('auth') ---
// ======================================================
Route::middleware('auth')->group(function () {

    // ======================================================
    // 1. GENERAL AUTH ROUTES
    // ======================================================
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');


    // ======================================================
    // 2. SHARED / MIXED ROUTES (IA FORMS)
    // ======================================================
    /*
    |--------------------------------------------------------------------------
    | FORMULIR ASESMEN (IA)
    |--------------------------------------------------------------------------
    */

    // FR.IA.02
    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');
    Route::get('/fr-ia-02', fn() => view('frontend.FR_IA_02'))->name('FR_IA_02');

    // FR.IA.10
    Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');
    Route::get('/FR-IA-10-view', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10');

    // FR.IA.06 (Statis/View)
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');

    // FR.IA.07
    Route::get('/fr-ia-07', fn() => view('frontend.FR_IA_07'))->name('FR_IA_07');

    /*
    | FORMULIR IA-05 (Kompleks dengan Role Middleware)
    */
    // Form A: Soal
    Route::middleware(['role:admin,asesor,asesi'])->group(function () {
        Route::get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])->name('ia-05.store.soal');
    });
    Route::middleware(['role:asesi'])->group(function () {
        Route::post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])->name('ia-05.store.jawaban');
    });

    // Form B: Kunci Jawaban
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])->name('FR_IA_05_B');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])->name('ia-05.store.kunci');
    });

    // Form C: Penilaian
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])->name('FR_IA_05_C');
    });
    Route::middleware(['role:asesor'])->group(function () {
        Route::post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia-05.store.penilaian');
    });

    //APL01
    Route::get('/apl-01-1/view/{id}', [APL01Controller::class, 'step1'])->name('APL_01_1');
    Route::post('/apl-01-1/store', [APL01Controller::class, 'storeStep1'])->name('apl01_1.store');
    Route::get('/apl-01-2/view/{id}', [APL01Controller::class, 'step2'])->name('APL_01_2');
    Route::post('/apl-01-2/store', [APL01Controller::class, 'storeStep2'])->name('apl01_2.store');
    Route::get('/apl-01-3/view/{id}', [APL01Controller::class, 'step3'])->name('APL_01_3');

    //MAPA01
    Route::get('/mapa01/show/{id}', [FrMapa01Controller::class, 'index'])->name('mapa01.index');
    Route::post('/mapa01/store', [FrMapa01Controller::class, 'store'])->name('mapa01.store');

    //MAPA02
    Route::get('/mapa02/show/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'show'])->name('mapa02.show');
    Route::post('/mapa02/store/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'store'])->name('mapa02.store');


    // ======================================================
    // 3. ROLE: ADMIN
    // ======================================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
        });

        // Notification
        Route::get('/notifications', function () {
            return view('notifications.notifications_admin');
        })->name('notifications');

        // Profile Admin
        Route::get('/profile_admin', function () {
            return view('profile.profile_admin');
        })->name('profile_admin');

        // Rute profil bawaan Laravel (Admin context)
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
            Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
        });

        // Master - Skema
        Route::controller(SkemaController::class)->prefix('master/skema')->group(function () {
            Route::get('/', 'index')->name('master_skema');
            Route::get('/add', 'create')->name('add_skema');
            Route::post('/add', 'store')->name('add_skema.store');
            Route::get('/edit/{id_skema}', 'edit')->name('edit_skema');
            Route::patch('/update/{id_skema}', 'update')->name('update_skema');
            Route::delete('/delete/{id_skema}', 'destroy')->name('delete_skema');
        });

        Route::controller(\App\Http\Controllers\Admin\DetailSkemaController::class)
            ->prefix('master/skema/detail')
            ->group(function () {
                Route::get('/{id_skema}', 'index')->name('skema.detail');
                Route::get('/{id_skema}/add-kelompok', 'createKelompok')->name('skema.detail.add_kelompok');
                Route::post('/{id_skema}/add-kelompok', 'storeKelompok')->name('skema.detail.store_kelompok');
                Route::get('/kelompok/{id_kelompok}/edit', 'editKelompok')->name('skema.detail.edit_kelompok');
                Route::put('/kelompok/{id_kelompok}', 'updateKelompok')->name('skema.detail.update_kelompok');
                Route::delete('/kelompok/{id_kelompok}', 'destroyKelompok')->name('skema.detail.destroy_kelompok');
                Route::delete('/unit/{id_unit}', 'destroyUnit')->name('skema.detail.destroy_unit');
            });

        // Master - Asesor
        Route::get('/master_asesor', [AsesorController::class, 'index'])->name('master_asesor');
        Route::get('/add_asesor1', [AsesorController::class, 'createStep1'])->name('add_asesor1');
        Route::post('/add_asesor1', [AsesorController::class, 'postStep1'])->name('add_asesor1.post');
        Route::get('/add_asesor2', [AsesorController::class, 'createStep2'])->name('add_asesor2');
        Route::post('/add_asesor2', [AsesorController::class, 'postStep2'])->name('add_asesor2.post');
        Route::get('/add_asesor3', [AsesorController::class, 'createStep3'])->name('add_asesor3');
        Route::post('/asesor/store', [AsesorController::class, 'store'])->name('asesor.store');
        Route::get('/edit-asesor-step-1/{id_asesor}', [AsesorController::class, 'editStep1'])->name('edit_asesor1');
        Route::patch('/update-asesor-step-1/{id_asesor}', [AsesorController::class, 'updateStep1'])->name('asesor.update.step1');
        Route::get('/edit-asesor-step-2/{id_asesor}', [AsesorController::class, 'editStep2'])->name('edit_asesor2');
        Route::patch('/update-asesor-step-2/{id_asesor}', [AsesorController::class, 'updateStep2'])->name('asesor.update.step2');
        Route::get('/edit-asesor-step-3/{id_asesor}', [AsesorController::class, 'editStep3'])->name('edit_asesor3');
        Route::patch('/update-asesor-step-3/{id_asesor}', [AsesorController::class, 'updateStep3'])->name('asesor.update.step3');
        Route::delete('/asesor/{id_asesor}', [AsesorController::class, 'destroy'])->name('asesor.destroy');

        // Master - Asesi
        Route::controller(AsesiController::class)->prefix('master/asesi')->group(function () {
            Route::get('/', 'index')->name('master_asesi');
            Route::get('/add', 'create')->name('add_asesi');
            Route::post('/add', 'store')->name('add_asesi.store');
            Route::get('/edit/{id_asesi}', 'edit')->name('edit_asesi');
            Route::patch('/update/{id_asesi}', 'update')->name('update_asesi');
            Route::delete('/delete/{id_asesi}', 'destroy')->name('delete_asesi');
        });

        // Master - Jadwal
        Route::controller(AdminJadwalController::class)->prefix('master/jadwal')->group(function () {
            Route::get('/', 'index')->name('master_jadwal');
            Route::get('/add', 'create')->name('add_jadwal');
            Route::post('/add', 'store')->name('add_jadwal.store');
            Route::get('/edit/{id_jadwal}', 'edit')->name('edit_jadwal');
            Route::patch('/update/{id_jadwal}', 'update')->name('update_jadwal');
            Route::delete('/delete/{id_jadwal}', 'destroy')->name('delete_jadwal');        
        });
        Route::get('/jadwal_admin', [AdminJadwalController::class, 'showCalendar'])->name('jadwal_admin');

        // Daftar Hadir dan Berita Acara
        Route::controller(DaftarHadirController::class)->prefix('master/jadwal')->group(function () {
            // Daftar Hadir
            Route::get('/{id_jadwal}/daftar-hadir', 'daftarHadir')
                ->name('attendance.show');

            Route::get('/{id_jadwal}/daftar-hadir/pdf', 'exportPdfdaftarhadir')
                ->name('attendance.pdf');

            // Berita Acara
            Route::get('/{id_jadwal}/berita-acara', 'beritaAcara')
                ->name('berita_acara');

            Route::get('/{id_jadwal}/berita-acara/pdf', 'exportPdfberitaAcara')
                ->name('berita_acara.pdf');          
        });        

        // TUK
        Route::controller(TukAdminController::class)->prefix('master/tuk')->group(function () {
            Route::get('/', 'index')->name('master_tuk');
            Route::get('/add', 'create')->name('add_tuk');
            Route::post('/add', 'store')->name('add_tuk.store');
            Route::get('/edit/{id}', 'edit')->name('edit_tuk');
            Route::patch('/update/{id}', 'update')->name('update_tuk');
            Route::delete('/delete/{id}', 'destroy')->name('delete_tuk');
        });

        // Master - Category
        Route::controller(CategoryController::class)->prefix('master/category')->group(function () {
            Route::get('/', 'index')->name('master_category');
            Route::get('/add', 'create')->name('add_category');
            Route::post('/add', 'store')->name('add_category.store');
            Route::get('/edit/{category}', 'edit')->name('edit_category');
            Route::patch('/update/{category}', 'update')->name('update_category');
            Route::delete('/delete/{category}', 'destroy')->name('delete_category');
        });
    });


    // ======================================================
    // 4. ROLE: ASESOR
    // ======================================================
    Route::middleware(['role:asesor'])->prefix('asesor')->name('asesor.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/home', [AsesorDashboardController::class, 'index'])->name('home.index'); // Keep for backward compatibility if needed
        Route::get('/notifikasi', [AsesorDashboardController::class, 'semuaNotifikasi'])->name('notifikasi.index');

        // Manajemen Jadwal & Asesi
        Route::get('/jadwal', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/daftar-asesi/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'showAsesi'])->name('daftar_asesi');
        Route::get('/tracker/{id_sertifikasi_asesi}', [AsesiTrackerController::class, 'show'])->name('tracker');
        Route::get('/daftar-hadir/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'daftarHadir'])->name('daftar_hadir');
        Route::post('/daftar-hadir/{id_jadwal}/simpan', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'storeKehadiran'])->name('simpan_kehadiran'); 
        Route::get('/daftar-hadir/pdf/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'exportPdfdaftarhadir'])->name('daftar_hadir.pdf');


        Route::get('/berita-acara/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'beritaAcara'])->name('berita_acara');
        Route::get('/berita-acara/pdf/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'exportPdfberitaAcara'])->name('berita_acara.pdf');

        // AK Forms
        Route::get('/jadwal/{id_jadwal}/ak05', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'ak05'])->name('ak05');
        Route::get('/jadwal/{id_jadwal}/ak06', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'ak06'])->name('ak06');
        Route::get('/asesmen/{id_sertifikasi_asesi}/ak07', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'ak07'])->name('ak07');

        // Store Routes for AK Forms
        Route::post('/ak05/store/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'storeAk05'])->name('ak05.store');
        Route::post('/ak06/store/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'storeAk06'])->name('ak06.store');
        Route::post('/ak07/store/{id_sertifikasi_asesi}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'storeAk07'])->name('fr-ak-07.store');

        // Tools
        Route::get('/laporan', fn() => view('frontend.laporan'))->name('laporan');

        // Profile Asesor
        Route::get('/profil', [\App\Http\Controllers\Asesor\ProfileController::class, 'show'])->name('profil');
        Route::get('/{id_asesor}/bukti', [AsesorController::class, 'showBukti'])->name('bukti');
        // Route::get('/{id_asesor}/profile', [AsesorController::class, 'showProfile'])->name('profile'); // Replaced by /profil
        Route::get('/profile_tinjauan', function () {
            return view('profile_asesor.asesor_profile_tinjauan');
        })->name('profile_tinjauan');
        Route::get('/profile_tracker', function () {
            return view('profile_asesor.asesor_profile_tracker');
        })->name('profile_tracker');

        // Update Profile Asesor (Ajax)
        Route::post('/update', [\App\Http\Controllers\Asesor\ProfileController::class, 'updateAsesorAjax'])->name('update.ajax');
    });


    // ======================================================
    // 5. ROLE: ASESI
    // ======================================================
    Route::middleware(['role:asesi'])->prefix('asesi')->name('asesi.')->group(function () {

        // Dashboard (Redirected here usually)
        // Route::get('/dashboard', [AsesiDashboardController::class, 'index'])->name('dashboard');

        // Profile Asesi
        Route::controller(AsesiProfileController::class)->prefix('{id_asesi}')->group(function () {
            Route::get('/settings', 'settings')->name('profile.settings');
            Route::get('/form', 'form')->name('profile.form');
            Route::get('/bukti', 'bukti')->name('profile.bukti');
            Route::get('/tracker', 'tracker')->name('profile.tracker');
        });
    });

    // ==========================
    // AREA ADMIN
    // ==========================
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/bank-soal-ia06', [Ia06Controller::class, 'adminIndex'])->name('admin.ia06.index');
        Route::post('/bank-soal-ia06', [Ia06Controller::class, 'adminStoreSoal'])->name('admin.ia06.store');
        Route::put('/bank-soal-ia06/{id}', [Ia06Controller::class, 'adminUpdateSoal'])->name('admin.ia06.update');
        Route::delete('/bank-soal-ia06/{id}', [Ia06Controller::class, 'adminDestroySoal'])->name('admin.ia06.destroy');
    });

    // ==========================
    // AREA ASESI
    // ==========================
    Route::middleware(['auth', 'role:asesi'])->prefix('asesi')->group(function () {
        Route::get('/asesmen/ia-06/{id}', [Ia06Controller::class, 'asesiShow'])->name('asesi.ia06.index');
        Route::put('/asesmen/ia-06/{id}', [Ia06Controller::class, 'asesiStoreJawaban'])->name('asesi.ia06.update');
    });

    // ==========================
    // AREA ASESOR
    // ==========================
    Route::middleware(['auth', 'role:asesor'])->prefix('asesor')->group(function () {
        Route::get('/penilaian/ia-06/{id}', [Ia06Controller::class, 'asesorShow'])->name('asesor.ia06.edit');
        Route::put('/penilaian/ia-06/{id}', [Ia06Controller::class, 'asesorStorePenilaian'])->name('asesor.ia06.update');
    });



    // ======================================================
    // 6. TRAFFIC COP (HOME REDIRECT)
    // ======================================================
    Route::get('/dashboard', function () {
        return redirect()->route('home.index');
    })->name('dashboard');

    Route::get('/home', function (Request $request) {
        $user = Auth::user();
        $roleName = $user->role->nama_role ?? null;

        // 1. JIKA ASESI
        if ($roleName === 'asesi') {
            return app(AsesiDashboardController::class)->index($request);
        }

        // 2. JIKA ASESOR
        elseif ($roleName === 'asesor') {
            // --- CEK STATUS VERIFIKASI ---
            $status = $user->asesor?->status_verifikasi;
            if (!$user->asesor || $status !== 'approved') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('error', 'Akun Anda belum diverifikasi oleh Admin. Silakan tunggu persetujuan.');
            }
            return redirect()->route('asesor.dashboard');
        }

        // 3. JIKA ADMIN
        elseif ($roleName === 'admin' || $roleName === 'superadmin') {
            return app(AdminDashboardController::class)->index($request);
        }

        // 4. JIKA ROLE TIDAK DIKENALI
        Auth::logout();
        return redirect('/login')->with('error', 'Role Anda tidak terdefinisi.');
    })->name('home.index');
});

// Halaman tunggu verifikasi (Opsional, jika Anda ingin redirect ke sini alih-alih logout)
// Saya sesuaikan juga kolomnya jadi 'is_verified' untuk jaga-jaga.
Route::get('/tunggu-verifikasi', function () {
    $user = Auth::user();

    if (!$user) return redirect()->route('login');
    if ($user->role->nama_role !== 'asesor') return redirect()->route('home.index');

    // Cek status_verifikasi
    if ($user->asesor?->status_verifikasi === 'approved') {
        return redirect()->route('home.index');
    }

    return view('auth.verification-asesor');
})->name('auth.wait');
