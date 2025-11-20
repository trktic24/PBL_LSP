<?php

namespace App\Http\Controllers;

use App\Models\IA02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IA02Controller extends Controller
{
    /**
     * Menampilkan halaman FR IA.02
     */
    public function show(string $id_data_sertifikasi_asesi)
    {
        // Ambil data sertifikasi beserta relasinya
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema',
            'jadwal.skema.asesor',
            'jadwal.skema.kelompokPekerjaans.UnitKompetensis'
        ])->find($id_data_sertifikasi_asesi);

        if (!$sertifikasi) {
            return redirect()
                ->route('daftar_asesi')
                ->with('error', 'Data Sertifikasi tidak ditemukan.');
        }

        // Ambil data IA02 untuk sertifikasi ini (jika sudah ada)
        $ia02 = IA02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->first();

        // Role: yang boleh input adalah admin (1) & superadmin (4)
        $isAdmin = in_array(Auth::user()->role_id, [1, 4]);

        return view('frontend.FR_IA_02', [
            'sertifikasi' => $sertifikasi,
            'ia02'        => $ia02,
            'isAdmin'     => $isAdmin,
        ]);
    }

    /**
     * Menyimpan atau mengupdate data IA.02
     */
    public function store(Request $request, string $id_sertifikasi)
    {
        // Hanya admin (1) atau superadmin (4)
        if (! in_array(Auth::user()->role_id, [1, 4])) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGUBAH DATA INI.');
        }

        $validated = $request->validate([
            'skenario'  => 'required|string',
            'peralatan' => 'required|string',
            'waktu'     => 'required|string|max:100',
        ]);

        IA02::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi],
            [
                'skenario'  => $validated['skenario'],
                'peralatan' => $validated['peralatan'],
                'waktu'     => $validated['waktu'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Data Instruksi Demonstrasi berhasil disimpan.');
    }
}
