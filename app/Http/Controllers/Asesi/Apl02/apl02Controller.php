<?php

namespace App\Http\Controllers\Asesi\Apl02;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponApl02Ia01; // Pastikan Model Respon Anda benar (Cek nama file modelnya)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Apl02Controller extends Controller
{
    // MENAMPILKAN FORM (VIEW BARU)
    public function show($id_sertifikasi)
    {
        $asesi_id = Auth::user()->asesi->id_asesi;

        // Ambil data sertifikasi
        // Kita sesuaikan relasi dengan kebutuhan View Baru
        $sertifikasi = DataSertifikasiAsesi::with([
            'jadwal.skema.unitKompetensi.elemen.kriteria',
            'asesi.user',
            'jadwal.asesor',
            'asesmenMandiri' // Relasi HasMany yang baru kita buat di Model DataSertifikasiAsesi
        ])
        ->where('id_asesi', $asesi_id)
        ->findOrFail($id_sertifikasi);

        // Cek Status (Opsional: Redirect jika sudah selesai)
        // if ($sertifikasi->status_sertifikasi == 'pra_asesmen_selesai') {
        //     return redirect()->route('tracker')->with('info', 'Anda sudah menyelesaikan asesmen mandiri.');
        // }

        // Mapping jawaban agar mudah diakses di View: $jawaban[id_kriteria]
        $jawaban = $sertifikasi->asesmenMandiri->keyBy('id_kriteria');

        // PANGGIL VIEW BARU ANDA
        return view('frontend.APL_02.APL_02', compact('sertifikasi', 'jawaban'));
    }

    // MENYIMPAN DATA (LOGIKA BARU)
    public function store(Request $request, $id_sertifikasi)
    {
        // 1. Validasi Input sesuai Form Baru (K/BK)
        $request->validate([
            'respon' => 'required|array',
            'respon.*.status' => 'required|in:K,BK', // Form baru kirimnya status, bukan k
            'respon.*.bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->respon as $id_kriteria => $data) {
                
                // Cek data lama untuk handle file upload (agar tidak numpuk sampah file)
                $existing = ResponApl02Ia01::where('id_data_sertifikasi_asesi', $id_sertifikasi)
                            ->where('id_kriteria', $id_kriteria)
                            ->first();

                $filePath = $existing ? $existing->bukti_asesi_apl02 : null;

                // 2. Proses Upload File
                if ($request->hasFile("respon.$id_kriteria.bukti")) {
                    // Hapus file lama jika ada
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    
                    // Simpan file baru ke public storage
                    $filePath = $request->file("respon.$id_kriteria.bukti")
                                ->store('bukti_apl02/' . $id_sertifikasi, 'public');
                }

                // 3. Konversi Nilai K/BK ke Format Database
                // Jika database Anda pakai integer (1/0), kita konversi di sini.
                // K => 1, BK => 0
                $nilaiRespon = ($data['status'] == 'K') ? 1 : 0;

                // 4. Simpan ke Database
                ResponApl02Ia01::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_kriteria' => $id_kriteria
                    ],
                    [
                        'respon_asesi_apl02' => $nilaiRespon, // Masuk sebagai 1 atau 0
                        'bukti_asesi_apl02' => $filePath,
                        'pencapaian_ia01' => 1, // Default value dari controller lama
                        // 'penilaian_lanjut_ia01' => 0, // Opsional
                    ]
                );
            }

            // 5. Update Status Sertifikasi (Logic dari Controller Lama)
            $sertifikasi = DataSertifikasiAsesi::find($id_sertifikasi);
            if ($sertifikasi->status_sertifikasi != 'pra_asesmen_selesai') {
                // Cek progress level atau logika lain jika perlu
                $sertifikasi->status_sertifikasi = 'pra_asesmen_selesai';
                $sertifikasi->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Asesmen Mandiri berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}