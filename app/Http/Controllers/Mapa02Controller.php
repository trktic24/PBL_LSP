<?php

namespace App\Http\Controllers;

use App\Models\Mapa02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Mapa02Controller extends Controller
{
    /**
     * Menampilkan halaman FR MAPA 02
     */
    public function show(string $id_data_sertifikasi_asesi)
    {
        $userRole = Auth::user()->role_id;

        // Asesi (2) tidak boleh akses
        if ($userRole == 2) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        // Ambil data sertifikasi beserta relasinya
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema',
            'jadwal.skema.asesor',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi',            
        ])->find($id_data_sertifikasi_asesi);

        if (!$sertifikasi) {
            return redirect()
                ->route('daftar_asesi')
                ->with('error', 'Data Sertifikasi tidak ditemukan.');
        }

        // Ambil SEMUA data Mapa02 untuk sertifikasi ini
        // Map: [ id_kelompok_pekerjaan => [ 'Nama Instrumen' => 'Nilai Potensi' ] ]
        $mapa02Collection = Mapa02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->get();
        
        $mapa02Map = [];
        foreach ($mapa02Collection as $item) {
            $mapa02Map[$item->id_kelompok_pekerjaan][$item->instrumen_asesmen] = $item->potensi_asesi;
        }

        // Yang boleh edit: Asesor (3) & Superadmin (4)
        // Admin (1) hanya view
        $canEdit = in_array($userRole, [3, 4]);

        return view('frontend.FR_MAPA_02', [
            'sertifikasi' => $sertifikasi,
            'mapa02Map'   => $mapa02Map,
            'canEdit'     => $canEdit,
            'backUrl'     => route('tracker', $sertifikasi->id_data_sertifikasi_asesi), 
            'jadwal'      => $sertifikasi->jadwal,   
            'asesi'      => $sertifikasi->asesi,                    
        ]);
    }

    /**
     * Menyimpan atau mengupdate data MAPA 02
     */
    public function store(Request $request, string $id_sertifikasi)
    {
        $userRole = Auth::user()->role_id;

        // Hanya Asesor (3) atau Superadmin (4) yang boleh simpan
        if (! in_array($userRole, [3, 4])) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGUBAH DATA INI.');
        }

        $validated = $request->validate([
            'potensi'       => 'required|array',
            'potensi.*'     => 'required|array', // id_kp => array of instruments
            'potensi.*.*'   => 'required|in:1,2,3,4,5', // instrument => value
        ]);

        // Loop setiap Kelompok Pekerjaan
        foreach ($validated['potensi'] as $id_kp => $instruments) {
            // Loop setiap Instrumen dalam KP tersebut
            foreach ($instruments as $instrumen => $nilai) {
                Mapa02::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_kelompok_pekerjaan'     => $id_kp,
                        'instrumen_asesmen'         => $instrumen,
                    ],
                    [
                        'potensi_asesi' => $nilai,
                    ]
                );
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Data MAPA 02 berhasil disimpan.');
    }
}
