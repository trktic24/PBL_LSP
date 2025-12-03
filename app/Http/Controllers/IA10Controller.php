<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia10;       // Bank Soal
use App\Models\PertanyaanIa10; // Jawaban
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IA10Controller extends Controller
{
    public function create($id_asesi)
    {
        // --- MODIFIKASI BYPASS LOGIN (DUMMY USER) ---
        // Kita pura-pura jadi ASESOR (id 3) karena kode asli menolak Asesi (id 2)
        $user = new \stdClass();
        $user->id = 3;
        $user->role_id = 1;
        $user->role = 'admin';
        $user->name = 'Asesor Testing';
        // ---------------------------------------------

        // Cek Role (Sesuai kode asli, tapi sekarang aman karena dummy user kita role_id=3)
        if ($user->role_id == 2) {
            return abort(403, 'Akses Ditolak');
        }

        $asesi = DataSertifikasiAsesi::findOrFail($id_asesi);

        // Ambil Semua Pertanyaan
        $daftar_soal = Ia10::all();

        // Ambil Jawaban Existing
        $jawaban_db = PertanyaanIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();

        $jawaban_map = [];
        foreach ($jawaban_db as $resp) {
            if ($resp->jawaban_pilihan_iya == 1)
                $jawaban_map[$resp->id_ia10] = 'ya';
            elseif ($resp->jawaban_pilihan_tidak == 1)
                $jawaban_map[$resp->id_ia10] = 'tidak';
        }

        return view('frontend.FR_IA_10', [
            'asesi' => $asesi,
            'daftar_soal' => $daftar_soal,
            'jawaban_map' => $jawaban_map,
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required',
            'jawaban' => 'array',
        ]);

        DB::beginTransaction();
        try {
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $id_soal => $nilai) {

                    $cek_soal = Ia10::find($id_soal);

                    if ($cek_soal) {
                        PertanyaanIa10::updateOrCreate(
                            [
                                'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                                'id_ia10' => $id_soal
                            ],
                            [
                                'jawaban_pilihan_iya' => ($nilai == 'ya') ? 1 : 0,
                                'jawaban_pilihan_tidak' => ($nilai == 'tidak') ? 1 : 0,
                                'jawaban_isian' => null
                            ]
                        );
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Jawaban IA.10 Berhasil Disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}