<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia10;       // Bank Soal
use App\Models\ResponIa10; // Jawaban
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IA10Controller extends Controller
{
    public function create($id_asesi)
    {
        $user = Auth::user();

        // Cek Role (jika perlu)
        if ($user->role_id == 2) { 
            return abort(403, 'Akses Ditolak');
        }

        $asesi = DataSertifikasiAsesi::findOrFail($id_asesi);

        // Ambil Semua Pertanyaan
        $daftar_soal = Ia10::all();

        // Ambil Jawaban Existing
        // LOGIC FIX: Kita mapping berdasarkan ID_IA10 (bukan teks pertanyaan) agar presisi
        $jawaban_db = ResponIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();
        
        $jawaban_map = [];
        foreach($jawaban_db as $resp) {
            // Gunakan ID sebagai key array untuk memudahkan pengecekan di Blade
            if($resp->jawaban_pilihan_iya == 1) $jawaban_map[$resp->id_ia10] = 'ya';
            elseif($resp->jawaban_pilihan_tidak == 1) $jawaban_map[$resp->id_ia10] = 'tidak';
        }

        return view('frontend.FR_IA_10', [
            'asesi'       => $asesi,
            'daftar_soal' => $daftar_soal,
            'jawaban_map' => $jawaban_map, 
            'user'        => $user
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
            // Loop array jawaban dari form
            // Format array dari Blade nanti harus: name="jawaban[ID_SOAL]"
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $id_soal => $nilai) {
                    
                    // Validasi apakah soal benar ada
                    $cek_soal = Ia10::find($id_soal);
                    
                    if($cek_soal) {
                        ResponIa10::updateOrCreate(
                            [
                                // KONDISI PENCARIAN (WHERE)
                                'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                                'id_ia10' => $id_soal // LOGIC FIX: Simpan ID, bukan Teks
                            ],
                            [
                                // DATA YANG DIUPDATE/INSERT
                                'jawaban_pilihan_iya'   => ($nilai == 'ya') ? 1 : 0,
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