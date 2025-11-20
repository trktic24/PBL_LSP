<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesi;
use Illuminate\Support\Facades\Validator;

class Test extends Controller
{
    public function index()
    {
        $asesi_index = Asesi::get();

        return view('test', compact('asesi_index'));
        
    }

    public function store(Request $request)
    {
        $validator = $request->validate( [
            'nik'          => 'required|numeric|unique:asesi,nik|min:16',
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email:dns|unique:asesi,email',
            'no_hp'        => 'required|numeric|digits:13',
            'alamat'       => 'required|string',
        ]);

        $asesi = Asesi::create([
            'nik'          => $validator['nik'],
            'nama_lengkap' => $validator['nama_lengkap'],
            'email'        => $validator['email'],
            'no_hp'        => $validator['no_hp'],
            'alamat'       => $validator['alamat']
        ]);

        if ($asesi) {
            return response()->json([
                'success' => true,
                'message' => 'Data Asesi Berhasil Disimpan',
                'data'    => $asesi
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data Gagal Disimpan',
        ], 409);
    }

    public function show(string $id)
    {
        $asesi_show = Asesi::find($id)->first();

        if (!$asesi_show) {
            return response()->json([
                'success' => false,
                'message' => 'Data Asesi Tidak Ditemukan',
            ], 404);
        }

        return view('test', compact('asesi_show'));
    }

    public function update(Request $request, string $id)
    {
        $asesi = Asesi::find($id);

        if (!$asesi) {
            return response()->json([
                'success' => false,
                'message' => 'Data Asesi Tidak Ditemukan',
            ], 404);
        }

        $validator = $request->validate( [
            'nik'          => 'required|numeric|unique:asesis,nik',
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:asesis,email',
            'no_hp'        => 'required|numeric',
            'alamat'       => 'required|string',
        ]);

        $asesi->update([
            'nik'          => $validator['nik'],
            'nama_lengkap' => $validator['nama_lengkap'],
            'email'        => $validator['email'],
            'no_hp'        => $validator['no_hp'],
            'alamat'       => $validator['alamat']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Asesi Berhasil Diupdate',
            'data'    => $asesi
        ], 200);
    }

    public function destroy(string $id)
    {
        $asesi = Asesi::find($id);

        if (!$asesi) {
            return response()->json([
                'success' => false,
                'message' => 'Data Asesi Tidak Ditemukan',
            ], 404);
        }

        $asesi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Asesi Berhasil Dihapus',
        ], 200);
    }
}