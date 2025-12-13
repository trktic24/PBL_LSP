<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ak02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ak02Controller extends Controller
{
    /**
     * Menampilkan Form Penilaian FR.AK.02
     */
    public function edit($id_asesi)
    {
        // 1. Ambil Data Asesi beserta Skema & Unit Kompetensinya
        // Menggunakan Eager Loading bertingkat untuk performa
        $asesi = DataSertifikasiAsesi::with([
            'skema.kelompokPekerjaan.unitKompetensi',
            'user'
        ])->findOrFail($id_asesi);

        // 2. Ambil Data Penilaian (AK-02) yang sudah ada (jika ada)
        // Kita ambil secara terpisah lalu jadikan Keyed Collection berdasarkan ID Unit
        // agar mudah dipanggil di View tanpa mengubah Model UnitKompetensi.
        $penilaianList = Ak02::where('id_data_sertifikasi_asesi', $id_asesi)
                             ->get()
                             ->keyBy('id_unit_kompetensi');

        // 3. Kirim ke View
        // Pastikan nama file view sesuai dengan folder project kamu
        return view('frontend.AK_02.FR_AK_02', compact('asesi', 'penilaianList'));
    }

    /**
     * Menyimpan Data Penilaian (Update or Create)
     */
    public function update(Request $request, $id_asesi)
    {
        // 1. Validasi Input
        $request->validate([
            // Validasi Array Penilaian (Checkbox Bukti per Unit)
            'penilaian' => 'required|array',

            // Validasi Input Global (Bagian Bawah Form)
            'global_kompeten' => 'nullable|in:Kompeten,Belum Kompeten',
            'global_tindak_lanjut' => 'nullable|string',
            'global_komentar' => 'nullable|string',
        ]);

        // 2. Ambil Data Global (Keputusan Akhir)
        // Data ini akan disalin ke setiap baris unit karena di database kita simpan per unit
        $globalKompeten = $request->input('global_kompeten');
        $globalTindakLanjut = $request->input('global_tindak_lanjut');
        $globalKomentar = $request->input('global_komentar');

        // 3. Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // Loop setiap Unit yang dikirim dari form
            // $idUnit adalah key, $data adalah array isinya (misal: jenis_bukti)
            foreach ($request->penilaian as $idUnit => $data) {

                // Ambil checklist bukti (jika ada yang dicentang)
                // Jika tidak ada, default array kosong []
                $bukti = isset($data['jenis_bukti']) ? $data['jenis_bukti'] : [];

                // Simpan atau Update ke Tabel ak02
                Ak02::updateOrCreate(
                    [
                        // KUNCI PENCARIAN (WHERE)
                        'id_unit_kompetensi' => $idUnit,
                        'id_data_sertifikasi_asesi' => $id_asesi,
                    ],
                    [
                        // DATA YANG DISIMPAN
                        // 1. Bukti Spesifik per Unit
                        'jenis_bukti'   => $bukti, // Otomatis di-cast ke JSON oleh Model

                        // 2. Data Global (Disalin ke semua unit)
                        'kompeten'      => $globalKompeten,
                        'tindak_lanjut' => $globalTindakLanjut,
                        'komentar'      => $globalKomentar,
                    ]
                );
            }

            // Jika semua lancar, simpan permanen
            DB::commit();

            return redirect()->back()->with('success', 'Rekaman Asesmen FR.AK.02 berhasil disimpan.');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua perubahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}