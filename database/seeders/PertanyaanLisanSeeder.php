<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKompetensi;
use App\Models\PertanyaanLisan;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ia07;
use App\Models\Skema; // Pastikan import model Skema
use Illuminate\Support\Facades\DB;

class PertanyaanLisanSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // TAHAP 1: PASTIKAN SEMUA UNIT PUNYA PERTANYAAN
        // ==========================================
        $allUnits = UnitKompetensi::all();

        if ($allUnits->isEmpty()) {
            $this->command->info('Unit Kompetensi kosong. Pastikan Master Unit sudah di-seed.');
            return;
        }

        foreach ($allUnits as $unit) {
            // Cek apakah unit ini sudah punya pertanyaan?
            $jumlahSoal = PertanyaanLisan::where('id_unit_kompetensi', $unit->id_unit_kompetensi)->count();
            
            // Jika soal kurang dari 2, tambahkan soal dummy
            if ($jumlahSoal < 2) {
                PertanyaanLisan::create([
                    'id_unit_kompetensi' => $unit->id_unit_kompetensi,
                    'pertanyaan'         => "Jelaskan langkah-langkah prosedural dalam melakukan " . $unit->judul_unit . " sesuai standar SOP?",
                    'kunci_jawaban'      => "Langkah awal adalah persiapan alat, kemudian pelaksanaan sesuai instruksi kerja, dan diakhiri dengan pelaporan.",
                ]);

                PertanyaanLisan::create([
                    'id_unit_kompetensi' => $unit->id_unit_kompetensi,
                    'pertanyaan'         => "Bagaimana cara Anda menangani kendala teknis yang muncul saat menerapkan unit " . $unit->judul_unit . "?",
                    'kunci_jawaban'      => "Melakukan identifikasi masalah, mencari solusi berdasarkan manual, dan jika tidak bisa diselesaikan melapor ke atasan.",
                ]);
            }
        }
        $this->command->info('Sukses: Semua Unit Kompetensi sekarang memiliki pertanyaan lisan.');


        // ==========================================
        // TAHAP 2: ISI JAWABAN UNTUK SEMUA ASESI (DIGITALISASI)
        // ==========================================
        
        // Ambil semua data sertifikasi asesi yang ada
        $allSertifikasi = DataSertifikasiAsesi::with(['jadwal.skema'])->get();

        foreach ($allSertifikasi as $sertifikasi) {
            
            // 1. Cari Skema dari sertifikasi ini
            $skema = $sertifikasi->jadwal->skema ?? null;

            if (!$skema) continue;

            // 2. Cari Unit Kompetensi milik Skema ini (lewat Kelompok Pekerjaan)
            // Query ini sama dengan yang kita pakai di Controller agar datanya sinkron
            $unitsSertifikasi = UnitKompetensi::whereHas('kelompokPekerjaan', function($q) use ($skema) {
                $q->where('id_skema', $skema->id_skema);
            })->get();

            // 3. Loop setiap unit, ambil pertanyaannya, lalu buat jawaban dummy
            foreach ($unitsSertifikasi as $unit) {
                // Ambil pertanyaan milik unit ini
                $pertanyaans = PertanyaanLisan::where('id_unit_kompetensi', $unit->id_unit_kompetensi)->get();

                foreach ($pertanyaans as $tanya) {
                    // Simpan Jawaban (Simulasi)
                    Ia07::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                            'id_pertanyaan_lisan'       => $tanya->id_pertanyaan_lisan
                        ],
                        [
                            'jawaban_asesi' => "Saya melakukan " . strtolower($unit->judul_unit) . " dengan memperhatikan aspek keselamatan kerja dan efisiensi waktu. (Simulasi Jawaban Digital)",
                            'rekomendasi'   => 'K' // Default Kompeten
                        ]
                    );
                }
            }
        }
        $this->command->info('Sukses: Semua Asesi telah memiliki jawaban simulasi.');
    }
}