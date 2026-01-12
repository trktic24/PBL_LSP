<?php

namespace App\Http\Controllers\Api\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilAsesorApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role->nama_role !== 'asesor' || !$user->asesor) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or profile not found',
            ], 403);
        }

        // Cek Status Verifikasi
        /*if ($user->asesor->status_verifikasi !== 'approved') {
             return response()->json([
                'success' => false,
                'message' => 'Akun belum diverifikasi.'
            ], 403);
        }*/

        $asesor = $user->asesor;

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_regis' => $asesor->nomor_regis,
                'nama_lengkap' => $asesor->nama_lengkap ?? $user->username,
                'nik' => $asesor->nik,
                'alamat_rumah' => $asesor->alamat_rumah,
                'nomor_hp' => $asesor->nomor_hp,
                'npwp' => $asesor->NPWP,
                'tempat_lahir' => $asesor->tempat_lahir,
                'tanggal_lahir' => $asesor->tanggal_lahir,
                'jenis_kelamin' => $asesor->jenis_kelamin,
                'kebangsaan' => $asesor->kebangsaan,
                'nama_bank' => $asesor->nama_bank,
                'norek' => $asesor->norek,
                'url_foto' => $asesor->url_foto,
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role->nama_role !== 'asesor' || !$user->asesor) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or profile not found',
            ], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'alamat_rumah'  => 'nullable|string|max:255',
            'nomor_hp'      => 'nullable|string|max:20',
            'npwp'          => 'nullable|string|max:25',
            'nama_bank'     => 'nullable|string|max:50',
            'nomor_rekening'=> 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $asesor = $user->asesor;

            $asesor->alamat_rumah = $request->input('alamat_rumah');
            $asesor->nomor_hp = $request->input('nomor_hp');
            $asesor->NPWP = $request->input('npwp');
            $asesor->nama_bank = $request->input('nama_bank');
            $asesor->norek = $request->input('nomor_rekening');

            $asesor->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }
}