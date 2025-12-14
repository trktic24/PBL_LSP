<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KunciIa06TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. KONFIGURASI JUMLAH SOAL
        $jumlahSoal = 5; 
        $now = now();

        $this->command->info("🔄 Memulai proses seeding Slot Lembar Jawab ESSAI (IA-06)...");

        // 2. AMBIL DATA SERTIFIKASI
        $listSertifikasi = DB::table('data_sertifikasi_asesi')
            ->join('jadwal', 'data_sertifikasi_asesi.id_jadwal', '=', 'jadwal.id_jadwal')
            ->select(
                'data_sertifikasi_asesi.id_data_sertifikasi_asesi', 
                'jadwal.id_skema'
            )
            ->get();

        if ($listSertifikasi->isEmpty()) {
            $this->command->warn("⚠️ Tidak ada data sertifikasi asesi ditemukan.");
            return;
        }

        DB::transaction(function () use ($listSertifikasi, $jumlahSoal, $now) {
            
            $totalTergenerate = 0;

            foreach ($listSertifikasi as $sertifikasi) {

                // 3. CEK DI TABEL LEMBAR JAWAB (Bukan kunci_ia06)
                // Pastikan nama tabelnya sesuai migrasi kamu (biasanya lembar_jawab_ia06)
                $sudahAda = DB::table('jawaban_ia06')
                    ->where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)
                    ->exists();

                if ($sudahAda) {
                    continue; 
                }

                // 4. AMBIL SOAL DARI MASTER SOAL (soal_ia06)
                $soalPaket = DB::table('soal_ia06')
                    ->where('id_skema', $sertifikasi->id_skema)
                    ->inRandomOrder()
                    ->take($jumlahSoal)
                    ->get();

                if ($soalPaket->isEmpty()) {
                    continue;
                }

                // 5. SIAPKAN DATA INSERT KE LEMBAR JAWAB
                $dataToInsert = [];
                foreach ($soalPaket as $soal) {
                    $dataToInsert[] = [
                        'id_data_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi,
                        'id_soal_ia06'              => $soal->id_soal_ia06,
                        
                        // Kolom jawaban asesi dikosongkan (NULL atau string kosong)
                        // Pastikan nama kolom ini sesuai di tabel lembar_jawab_ia06 kamu
                        'jawaban_asesi'        => null, 
                        
                        // Kolom nilai/rekomendasi juga kosong dulu
                        'pencapaian'           => null, // atau 'belum_kompeten' defaultnya
                        
                        'created_at'                => $now,
                        'updated_at'                => $now,
                    ];
                }

                // 6. INSERT KE TABEL LEMBAR JAWAB
                if (!empty($dataToInsert)) {
                    DB::table('jawaban_ia06')->insert($dataToInsert);
                    $totalTergenerate += count($dataToInsert);
                }
            }

            $this->command->info("✅ SUKSES! Total $totalTergenerate slot jawaban Essai berhasil disiapkan di tabel 'lembar_jawab_ia06'.");
        });
    }
}