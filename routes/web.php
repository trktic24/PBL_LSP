<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// halaman login utama
Route::get('/', function () {
    return view('login_admin');
})->name('login');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard_admin');
})->name('dashboard');

// Notifikasi
Route::get('/notifications', function () {
    return view('notifications_admin');
})->name('notifications');

// Profil Admin
Route::get('/profile_admin', function () {
    return view('profile_admin'); 
})->name('profile_admin');

// Master Skema
Route::get('/master_skema', function () {
    return view('master_skema'); 
})->name('master_skema');

// Master Asesi
Route::get('/master_asesi', function () {
    return view('master_asesi'); 
})->name('master_asesi');

// Master Asesor
Route::get('/master_asesor', function () {
    return view('master_asesor'); 
})->name('master_asesor');

// TUK SEWAKTU
Route::get('/tuk_sewaktu', function () {
    return view('tuk_sewaktu'); 
})->name('tuk_sewaktu');

// Schedule
Route::get('/schedule_admin', function () {
    return view('schedule_admin'); 
})->name('schedule_admin');

// User Profile Settings
Route::get('/user_profile_settings', function () {
    return view('user_profile_settings'); 
})->name('user_profile_settings');

// User Profile Form
Route::get('/user_profile_form', function () {
    return view('user_profile_form'); 
})->name('user_profile_form');

// User Profile Bukti
Route::get('/user_profile_bukti', function () {
    return view('user_profile_bukti'); 
})->name('user_profile_bukti');

// proses login pakai kolom "name"
Route::post('/login', function (Request $request) {
    // validasi input
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required'],
    ]);

    // ubah agar Auth::attempt pakai kolom "name"
    if (Auth::attempt([
        'name' => $credentials['username'],
        'password' => $credentials['password']
    ])) {
        $request->session()->regenerate(); // amankan session
        return redirect()->intended('/dashboard_admin');
    }

    // jika gagal
    return back()->with('error', 'Username atau password salah!');
});

// halaman dashboard admin
Route::get('/dashboard_admin', function () {
    return view('dashboard_admin');
})->middleware('auth')->name('dashboard_admin');

// logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// route profil bawaan Laravel
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
