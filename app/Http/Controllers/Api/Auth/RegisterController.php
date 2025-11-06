<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\DataPekerjaanAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $roleName = $request->input('role'); // asesi / asesor
        $role = Role::where('nama_role', $roleName)->firstOrFail();

        $rules = [
            'role' => ['required', 'string', 'in:asesi,asesor'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ];

        // ✳️ ASESI RULES
        if ($roleName === 'asesi') {
            $rules = array_merge($rules, [
                'nama_lengkap' => 'required|string|max:255',
                'nik' => 'required|string|size:16|unique:asesi,nik',
                'tanggal_lahir' => 'required|date_format:Y-m-d',
                'jenis_kelamin' => 'required|string',
                'pekerjaan' => 'required|string',
                'alamat_rumah' => 'required|string',
                'kabupaten' => 'required|string',
                'provinsi' => 'required|string',
                'nama_institusi_pekerjaan' => 'required|string|max:255',
                'jabatan' => 'required|string|max:100',
                'alamat_institusi' => 'required|string|max:255',
                'kode_pos_institusi' => 'required|string|max:10',
                'no_telepon_institusi' => 'required|string|max:20',
            ]);
        }

        // ✳️ ASESOR RULES
        if ($roleName === 'asesor') {
            $rules = array_merge($rules, [
                'nama_lengkap' => 'required|string|max:255',
                'no_registrasi_asesor' => 'required|string|unique:asesor,nomor_regis',
                'nik' => 'required|string|size:16|unique:asesor,nik',
                'tanggal_lahir' => 'required|date_format:Y-m-d',
                'jenis_kelamin' => 'required|string',
                'pekerjaan' => 'required|string',
                'npwp' => 'required|string|max:25',
                'nama_bank' => 'required|string',
                'nomor_rekening' => 'required|string',
            ]);
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // 🧠 Create user
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'google_id' => null,
                'role_id' => $role->id_role,
            ]);

            $tanggalLahir = $validated['tanggal_lahir'];

            // 🧠 Role specific insert
            if ($roleName === 'asesi') {
                $asesi = Asesi::create([
                    'id_user' => $user->id_user,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'nik' => $validated['nik'],
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'pekerjaan' => $validated['pekerjaan'],
                    'alamat_rumah' => $validated['alamat_rumah'],
                    'kabupaten_kota' => $validated['kabupaten'],
                    'provinsi' => $validated['provinsi'],
                ]);
                DataPekerjaanAsesi::create([
                    'id_asesi' => $asesi->id_asesi,
                    'nama_institusi_pekerjaan' => $validated['nama_institusi_pekerjaan'],
                    'jabatan' => $validated['jabatan'],
                    'alamat_institusi' => $validated['alamat_institusi'],
                    'kode_pos_institusi' => $validated['kode_pos_institusi'],
                    'no_telepon_institusi' => $validated['no_telepon_institusi'],
                ]);
            }

            if ($roleName === 'asesor') {
                Asesor::create([
                    'id_user' => $user->id_user,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'nomor_regis' => $validated['no_registrasi_asesor'],
                    'nik' => $validated['nik'],
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'pekerjaan' => $validated['pekerjaan'],
                    'NPWP' => $validated['npwp'],
                    'nama_bank' => $validated['nama_bank'],
                    'norek' => $validated['nomor_rekening'],
                ]);
            }

            DB::commit();

            // 🪄 Token Sanctum
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
