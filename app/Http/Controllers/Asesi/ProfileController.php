<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Asesi;
use App\Models\DataPekerjaanAsesi;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Eager load asesi and their job data
        // Assuming asesi relationship exists on User model
        $asesi = $user->asesi;
        $pekerjaan = $asesi ? $asesi->dataPekerjaan()->first() : null;

        return view('asesi.edit_profile', [
            'user' => $user,
            'asesi' => $asesi,
            'pekerjaan' => $pekerjaan,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Validate Input (Custom validation here instead of generic ProfileUpdateRequest)
        $validated = $request->validate([
            // User Data
            // 'username' => ['required', 'string', 'max:255'], // Username not in migration update request but good to have
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id_user . ',id_user'],
            
            // Asesi Data (Personal)
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:16', 'unique:asesi,nik,' . ($request->user()->asesi->id_asesi ?? 'NULL') . ',id_asesi'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'kebangsaan' => ['nullable', 'string', 'max:100'],
            'pendidikan' => ['required', 'string', 'max:255'],
            'pekerjaan_pribadi' => ['required', 'string', 'max:255'], // Maps to asesi.pekerjaan
            'alamat_rumah' => ['required', 'string'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'kabupaten_kota' => ['required', 'string', 'max:255'],
            'provinsi' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['required', 'string', 'max:16'],
            
            // Job Data (Institutional)
            'nama_institusi_pekerjaan' => ['required', 'string', 'max:255'],
            'alamat_institusi' => ['required', 'string'],
            'jabatan_institusi' => ['required', 'string', 'max:255'], // Maps to data_pekerjaan_asesi.jabatan
            'kode_pos_institusi' => ['nullable', 'string', 'max:10'],
            'no_telepon_institusi' => ['required', 'string', 'max:16'],
        ]);

        $user = $request->user();

        // 2. Update User Table
        $user->email = $validated['email'];
        // $user->username = $validated['username']; // If we want to allow username update
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // SANITIZATION ROUTINE (Security Fix)
        // Strip malicious tags from all string inputs in the validated array
        \Illuminate\Support\Facades\Log::info('Profile Update Input - Raw:', $validated);

        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }
        
        \Illuminate\Support\Facades\Log::info('Profile Update Input - Sanitized:', $validated);

        // request->merge is optional if we only use $validated below, but good for consistency
        $request->merge($validated);

        // 3. Update/Create Asesi Table
        $asesi = Asesi::updateOrCreate(
            ['id_user' => $user->id_user],
            [
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'kebangsaan' => $validated['kebangsaan'],
                'pendidikan' => $validated['pendidikan'],
                'pekerjaan' => $validated['pekerjaan_pribadi'], // asesi.pekerjaan
                'alamat_rumah' => $validated['alamat_rumah'],
                'kode_pos' => $validated['kode_pos'],
                'kabupaten_kota' => $validated['kabupaten_kota'],
                'provinsi' => $validated['provinsi'],
                'nomor_hp' => $validated['nomor_hp'],
                // 'tanda_tangan' => handled separately if needed
            ]
        );

        // 4. Update/Create DataPekerjaanAsesi Table
        // We assume 1 job for profile edit simplicity.
        DataPekerjaanAsesi::updateOrCreate(
            ['id_asesi' => $asesi->id_asesi], // Find by asesi ID (assuming 1-to-1 behavior for profile edit)
            [
                'nama_institusi_pekerjaan' => $validated['nama_institusi_pekerjaan'],
                'alamat_institusi' => $validated['alamat_institusi'],
                'jabatan' => $validated['jabatan_institusi'],
                'kode_pos_institusi' => $validated['kode_pos_institusi'],
                'no_telepon_institusi' => $validated['no_telepon_institusi'],
            ]
        );

        return Redirect::route('asesi.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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
}
