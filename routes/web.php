<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // halaman login utama
    return view('login_admin');
})->name('login');

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
