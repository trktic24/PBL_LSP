<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;



class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show()
    {
        $user = Auth::user();

        // Deteksi peran dan ambil relasi profil
        if ($user->role->nama_role === 'asesor' && $user->asesor) {
            $profil = $user->asesor;
        } elseif ($user->role->nama_role === 'asesi' && $user->asesi) {
            $profil = $user->asesi;
        } elseif ($user->role->nama_role === 'admin' && $user->admin) {
            $profil = $user->admin;
        } else {
            $profil = null;
        }

        // Tampilkan view profil custom kamu
        return view('frontend.profil', compact('user', 'profil'));
    }

    public function updateAsesorAjax(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'alamat_rumah'  => 'nullable|string|max:255',
            'nomor_hp'      => 'nullable|string|max:20',
            'npwp'          => 'nullable|string|max:25',
            'nama_bank'     => 'nullable|string|max:50',
            'nomor_rekening'=> 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            // Kirim balasan error validasi sebagai JSON
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // 2. Dapatkan model (Sama seperti di fungsi show() Anda)
            $user = Auth::user();
            if (!$user->asesor) {
                return response()->json(['success' => false, 'message' => 'Profil asesor tidak ditemukan.'], 404);
            }
            $asesor = $user->asesor;

            // 3. Update data
            // (Sesuaikan nama kolom jika perlu)
            $asesor->alamat_rumah = $request->input('alamat_rumah');
            $asesor->nomor_hp = $request->input('nomor_hp');
            $asesor->NPWP = $request->input('npwp'); // Pastikan ID 'npwp' di HTML cocok dengan kolom 'NPWP'
            $asesor->nama_bank = $request->input('nama_bank');
            $asesor->norek = $request->input('nomor_rekening'); // Pastikan ID 'nomor_rekening' cocok dengan kolom 'norek'

            $asesor->save();

            // 4. Kirim balasan sukses sebagai JSON
            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);

        } catch (\Exception $e) {
            // 5. Kirim balasan error server sebagai JSON
            // (Sebaiknya Anda catat errornya: Log::error($e->getMessage());)
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }
}