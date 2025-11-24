<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Import semua Model yang sudah kita buat
use App\Models\DataSertifikasiAsesi;
use App\Models\PoinPotensiAk07;
use App\Models\PersyaratanModifikasiAk07;
use App\Models\ResponPotensiAk07;
use App\Models\ResponDiperlukanPenyesuaianAk07;
use App\Models\HasilPenyesuaianAk07;

class FrAk07Controller extends Controller
{
    /**
     * MENAMPILKAN FORM (GET)
     * URL: /fr-ak-07/{id_sertifikasi}
     */
    // app/Http/Controllers/FrAk07Controller.php

    public function create($id)
    {
        // 1. Ambil Data
        // Kita tetap harus load 'jadwal.skema' karena jalur datanya lewat situ
        $sertifikasi = \App\Models\DataSertifikasiAsesi::with([
            'asesi', 
            'jadwal.skema',  // Load Skema via Jadwal
            'jadwal.asesor'  // Load Asesor via Jadwal
        ])->findOrFail($id);

        // 2. Cek apakah Skema ada (Validasi Logic)
        if (!$sertifikasi->skema) {
            return back()->with('error', 'Data Skema tidak ditemukan untuk sertifikasi ini.');
        }

        // 3. Ambil Master Data (Soal FR.AK.07)
        $masterPotensi = \App\Models\PoinPotensiAk07::all();
        $masterPersyaratan = \App\Models\PersyaratanModifikasiAk07::with('opsiKeterangan')->get();

        return view('frontend.FR_AK_07', compact('sertifikasi', 'masterPotensi', 'masterPersyaratan'));
    }

    /**
     * MENYIMPAN DATA (POST)
     * URL: /fr-ak-07/{id_sertifikasi}
     */
    public function store(Request $request, $id_sertifikasi)
    {
        // 1. Validasi Input
        $request->validate([
            // Validasi Bagian A (Array ID Potensi)
            'potensi_asesi' => 'nullable|array',
            'potensi_asesi.*' => 'exists:poin_potensi_AK07,id_poin_potensi_AK07',

            // Validasi Bagian B (Respon Q1-Q7)
            // Struktur form nanti: penyesuaian[id_soal][status] = Ya/Tidak
            'penyesuaian' => 'required|array',
            
            // Validasi Bagian C (Hasil Akhir)
            'acuan_pembanding' => 'nullable|string',
            'metode_asesmen' => 'nullable|string',
            'instrumen_asesmen' => 'nullable|string',
        ]);

        // Mulai Database Transaction (Biar kalau error di tengah, data ga masuk setengah-setengah)
        DB::beginTransaction();

        try {
            // --- SIMPAN BAGIAN A: POTENSI ASESI ---
            // Hapus data lama dulu jika ada (mekanisme update/replace)
            ResponPotensiAk07::where('id_data_sertifikasi_asesi', $id_sertifikasi)->delete();

            if ($request->has('potensi_asesi')) {
                foreach ($request->potensi_asesi as $id_potensi) {
                    ResponPotensiAk07::create([
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_poin_potensi_AK07' => $id_potensi,
                        'respon_asesor' => null // Bisa diisi jika ada input teks tambahan
                    ]);
                }
            }

            // --- SIMPAN BAGIAN B: PERSYARATAN MODIFIKASI (Q1-Q7) ---
            // Hapus data lama dulu
            ResponDiperlukanPenyesuaianAk07::where('id_data_sertifikasi_asesi', $id_sertifikasi)->delete();

            // Loop setiap soal yang dikirim dari form
            foreach ($request->penyesuaian as $id_soal => $data) {
                $status = $data['status'] ?? 'Tidak'; // Ya atau Tidak
                $keteranganIds = $data['keterangan'] ?? []; // Array ID checklist yg dipilih
                $catatanManual = $data['catatan_manual'] ?? null;

                // LOGIKA PENYIMPANAN:
                // Karena skema DB kita satu baris per-keterangan (Normalized),
                // Jika user pilih 'Ya' dan mencentang 3 opsi, kita simpan 3 baris.
                
                if ($status === 'Ya' && !empty($keteranganIds)) {
                    foreach ($keteranganIds as $id_ket) {
                        ResponDiperlukanPenyesuaianAk07::create([
                            'id_data_sertifikasi_asesi' => $id_sertifikasi,
                            'id_persyaratan_modifikasi_AK07' => $id_soal,
                            'id_catatan_keterangan_AK07' => $id_ket,
                            'respon_penyesuaian' => 'Ya',
                            'respon_catatan_keterangan' => $catatanManual
                        ]);
                    }
                } else {
                    // Jika 'Tidak' ATAU 'Ya' tapi tidak pilih checklist (simpan 1 baris default)
                    ResponDiperlukanPenyesuaianAk07::create([
                        'id_data_sertifikasi_asesi' => $id_sertifikasi,
                        'id_persyaratan_modifikasi_AK07' => $id_soal,
                        'id_catatan_keterangan_AK07' => null, // Null karena tidak ada checklist
                        'respon_penyesuaian' => $status,
                        'respon_catatan_keterangan' => $catatanManual
                    ]);
                }
            }

            // --- SIMPAN BAGIAN C: HASIL AKHIR ---
            // Pakai updateOrCreate biar aman (Insert baru atau Update yang lama)
            HasilPenyesuaianAk07::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $id_sertifikasi],
                [
                    'Acuan_Pembanding_Asesmen' => $request->acuan_pembanding,
                    'Metode_Asesmen' => $request->metode_asesmen,
                    'Instrumen_Asesmen' => $request->instrumen_asesmen
                ]
            );

            // Commit Transaksi (Simpan permanen)
            DB::commit();

            return redirect()->route('fr-ak-07.index') // Ganti route sesuai kebutuhan
                             ->with('success', 'Formulir FR.AK.07 berhasil disimpan!');

        } catch (\Exception $e) {
            // Rollback (Batalkan semua perubahan jika error)
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}