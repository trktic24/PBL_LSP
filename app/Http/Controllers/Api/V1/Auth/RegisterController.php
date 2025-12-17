<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\DataPekerjaanAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class RegisterController extends Controller
{
    /**
     * REGISTER ASESI
     */

    public function registerAsesi(Request $request)
{
    $validated = $request->validate([
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8'],

        'nama_lengkap' => 'required|string|max:255',
        'nik' => 'required|string|size:16|unique:asesi,nik',

        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date_format:Y-m-d',
        'jenis_kelamin' => 'required|string',

        'kebangsaan' => 'required|string',
        'pendidikan' => 'required|string',

        'pekerjaan' => 'required|string',
        'alamat_rumah' => 'required|string',
        'kode_pos' => 'required|string|max:10',

        'kabupaten' => 'required|string',
        'provinsi' => 'required|string',
        'nomor_hp' => 'required|string|max:20',

        'nama_institusi_pekerjaan' => 'required|string|max:255',
        'jabatan' => 'required|string|max:100',
        'alamat_institusi' => 'required|string|max:255',
        'kode_pos_institusi' => 'required|string|max:10',
        'no_telepon_institusi' => 'required|string|max:20',
    ]);

    DB::beginTransaction();
    try {

        $role = Role::where('nama_role', 'asesi')->firstOrFail();

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'google_id' => null,
            'role_id' => $role->id_role,
        ]);

        $asesi = Asesi::create([
            'id_user' => $user->id_user,
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],

            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],

            'kebangsaan' => $validated['kebangsaan'],
            'pendidikan' => $validated['pendidikan'],

            'pekerjaan' => $validated['pekerjaan'],
            'alamat_rumah' => $validated['alamat_rumah'],
            'kode_pos' => $validated['kode_pos'],

            'kabupaten_kota' => $validated['kabupaten'],
            'provinsi' => $validated['provinsi'],
            'nomor_hp' => $validated['nomor_hp'],
        ]);

        DataPekerjaanAsesi::create([
            'id_asesi' => $asesi->id_asesi,
            'nama_institusi_pekerjaan' => $validated['nama_institusi_pekerjaan'],
            'jabatan' => $validated['jabatan'],
            'alamat_institusi' => $validated['alamat_institusi'],
            'kode_pos_institusi' => $validated['kode_pos_institusi'],
            'no_telepon_institusi' => $validated['no_telepon_institusi'],
        ]);

        DB::commit();

        // 🔥 Generate token seperti asesor
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Asesi registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Registration failed',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * REGISTER ASESOR
     */
    public function registerAsesor(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'nama_lengkap' => 'required|string|max:255',
            'no_registrasi_asesor' => 'required|string|unique:asesor,nomor_regis',
            'nik' => 'required|string|size:16|unique:asesor,nik',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|string',
            'pekerjaan' => 'required|string',
            'npwp' => 'required|string|max:25',
            'nama_bank' => 'required|string',
            'nomor_rekening' => 'required|string',
            'tempat_lahir' => 'required|string|max:100',
            'kebangsaan' => 'required|string|max:100',
            'alamat_rumah' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'nomor_hp' => 'required|string|max:14',

        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('nama_role', 'asesor')->firstOrFail();

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'google_id' => null,
                'role_id' => $role->id_role,
            ]);

            Asesor::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $validated['nama_lengkap'],
                'nomor_regis' => $validated['no_registrasi_asesor'],
                'nik' => $validated['nik'],

                // Wajib karena migration mewajibkan
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'kebangsaan' => $validated['kebangsaan'],
                'pekerjaan' => $validated['pekerjaan'],

                // Alamat wajib
                'alamat_rumah' => $validated['alamat_rumah'],
                'kode_pos' => $validated['kode_pos'],
                'kabupaten_kota' => $validated['kabupaten_kota'],
                'provinsi' => $validated['provinsi'],
                'nomor_hp' => $validated['nomor_hp'],

                // Lainnya
                'NPWP' => $validated['npwp'],
                'nama_bank' => $validated['nama_bank'],
                'norek' => $validated['nomor_rekening'],
            ]);


            DB::commit();

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Asesor registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Asesor registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
