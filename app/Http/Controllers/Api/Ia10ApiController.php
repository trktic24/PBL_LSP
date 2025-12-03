<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia10;       // Bank Soal
use App\Models\ResponIa10; // Jawaban
use Illuminate\Support\Facades\DB;

class IA10ApiController extends Controller
{
    // --- PERBAIKAN: PINDAHKAN MAPPING KESINI (Properti Class) ---
    // Agar bisa diakses oleh function show() maupun store()
    private $text_mapping = [
        'supervisor_name' => 7,
        'workplace'       => 8,
        'address'         => 9,
        'phone'           => 10,
        'relation'        => 11,
        'duration'        => 12,
        'proximity'       => 13,
        'experience'      => 14,
        'consistency'     => 15,
        'training_needs'  => 16,
        'other_comments'  => 17,
    ];

    /**
     * GET: Ambil Data Soal, Jawaban Existing, dan Info Asesor
     * Endpoint: GET /api/fr-ia-10/{id_asesi}
     */
    public function show($id_asesi)
    {
        // --- 1. BYPASS LOGIN / DUMMY USER (Sesuai request Anda) ---
        $user = new \stdClass();
        $user->id = 3; 
        $user->role_id = 1; // Asesor
        
        // Cek Role (Simple Check)
        if ($user->role_id == 2) { 
            return response()->json(['message' => 'Akses Ditolak'], 403);
        }
        // ---------------------------------------------------------

        // Ambil data Asesi beserta relasi ke Jadwal dan Asesor
        $asesi = DataSertifikasiAsesi::with(['jadwal.asesor'])->find($id_asesi);

        if (!$asesi) {
            return response()->json(['message' => 'Data Sertifikasi tidak ditemukan'], 404);
        }

        // Ambil Semua Pertanyaan
        $daftar_soal = Ia10::where('id_data_sertifikasi_asesi', $id_asesi)->get();

        // Ambil Jawaban Existing
        $jawaban_db = ResponIa10::where('id_data_sertifikasi_asesi', $id_asesi)->get();
        
        // Mapping untuk Frontend
        $jawaban_map = []; // { "1": "ya", "2": "tidak" }
        $isian_map = [];   // { "50": "Nama Supervisor", "51": "Alamat" }

        foreach($jawaban_db as $resp) {
            // --- PERBAIKAN 2: LOGIKA MAPPING UNTUK SHOW ---
            
            // A. Kalau ID Soal ada di daftar Checklist (1-6)
            // (Asumsi soal checklist ID-nya lebih kecil dari soal isian pertama yaitu 7)
            if ($resp->id_ia10 < 7) {
                if($resp->jawaban_pilihan_iya == 1) $jawaban_map[$resp->id_ia10] = 'ya';
                elseif($resp->jawaban_pilihan_tidak == 1) $jawaban_map[$resp->id_ia10] = 'tidak';
            }
            
            // B. Kalau ID Soal ada di daftar Isian (7-17)
            else {
                // Kita cari tahu: ID 7 ini milik input yang namanya apa?
                // Reverse search: Cari key berdasarkan value
                $input_name = array_search($resp->id_ia10, $this->text_mapping);
                
                if ($input_name && !empty($resp->jawaban_isian)) {
                    // Hasilnya: $isian_map['supervisor_name'] = 'Budi';
                    $isian_map[$input_name] = $resp->jawaban_isian;
                }
            }
        }

        // Logika Tanda Tangan:
        // Jika sudah ada jawaban tersimpan di DB, kita anggap form sudah "selesai" / "progress"
        // Maka kirimkan URL tanda tangan asesor.
        // Logika Tanda Tangan Asesor
        $asesor_data = null;
        
        // Cek apakah jadwal ada, dan apakah asesor (collection) tidak kosong
        if ($asesi->jadwal && $asesi->jadwal->asesor && $asesi->jadwal->asesor->isNotEmpty()) {
            
            // AMBIL ITEM PERTAMA DARI LIST ASESOR
            $asesor = $asesi->jadwal->asesor->first(); 

            // Pastikan asesor ditemukan sebelum akses properti
            if ($asesor) {
                $asesor_data = [
                    'nama'   => $asesor->nama_lengkap,
                    'no_reg' => $asesor->nomor_regis ?? '-',
                    // Tampilkan TTD hanya jika data jawaban sudah ada (> 0)
                    'ttd_url' => count($jawaban_db) > 0 ? asset('storage/' . $asesor->tanda_tangan) : null
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'asesi' => [
                    'id' => $asesi->id_asesi,
                    'nama' => $asesi->asesi->nama_lengkap ?? 'Asesi', // Adjust relasi user
                ],
                'asesor' => $asesor_data,
                'daftar_soal' => $daftar_soal,
                'jawaban_existing' => $jawaban_map, // Untuk mengisi radio button
                'isian_existing' => $isian_map,     // Untuk mengisi text field (supervisor, dll)
            ]
        ]);
    }

    /**
     * POST: Simpan Jawaban (Radio & Text)
     * Endpoint: POST /api/fr-ia-10
     */
public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'jawaban' => 'nullable|array', 
            // Validasi field lain opsional...
        ]);

        try {
            // --- A. SIMPAN PILIHAN GANDA ---
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $id_soal => $nilai) {
                    // Langsung pakai Model
                    ResponIa10::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                            'id_ia10' => $id_soal 
                        ],
                        [
                            'jawaban_pilihan_iya'   => ($nilai == 'ya') ? 1 : 0,
                            'jawaban_pilihan_tidak' => ($nilai == 'tidak') ? 1 : 0,
                        ]
                    );
                }
            }

            foreach ($this->text_mapping as $input_name => $id_soal) {
                if ($request->has($input_name)) {
                    ResponIa10::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                            'id_ia10' => $id_soal 
                        ],
                        [
                            'jawaban_pilihan_iya' => 0,
                            'jawaban_pilihan_tidak' => 0,
                            'jawaban_isian' => $request->input($input_name)
                        ]
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Jawaban IA.10 Berhasil Disimpan!'
            ], 200);

        } catch (\Exception $e) {
            // Tanpa DB::rollBack(), data yang sempat tersimpan di loop awal
            // akan tetap ada di database meskipun loop berikutnya error.
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}