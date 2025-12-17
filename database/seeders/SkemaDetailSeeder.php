<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use Faker\Factory as Faker;

class SkemaDetailSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $skemas = Skema::all();

        if ($skemas->isEmpty()) {
            $this->command->info('⚠️ Tidak ada data Skema. Pastikan SkemaSeeder sudah dijalankan.');
            return;
        }

        // Reset data lama biar bersih (Opsional, hati-hati di production)
        // KelompokPekerjaan::truncate(); 
        // UnitKompetensi::truncate();

        foreach ($skemas as $skema) {
            
            // [REVISI] Hanya buat 1 Kelompok Pekerjaan per Skema
            $kelompok = KelompokPekerjaan::create([
                'id_skema' => $skema->id_skema,
                // Nama kelompok biasanya generik jika cuma satu, misal: "Unit Kompetensi Inti"
                'nama_kelompok_pekerjaan' => 'Unit Kompetensi Teknis' 
            ]);

            // Isi dengan Banyak Unit
            $jumlahUnit = rand(5, 8);
            
            for ($i = 1; $i <= $jumlahUnit; $i++) {
                UnitKompetensi::create([
                    'id_kelompok_pekerjaan' => $kelompok->id_kelompok_pekerjaan,
                    'urutan' => $i,
                    // Generate Kode Unit (J.620100.XXX.01)
                    'kode_unit' => 'J.' . $faker->numberBetween(100000, 999999) . '.' . str_pad($faker->numberBetween(1, 99), 3, '0', STR_PAD_LEFT) . '.01',
                    // Generate Judul Unit
                    'judul_unit' => $this->generateJudulUnit($faker),
                ]);
            }
        }
    }

    private function generateJudulUnit($faker)
    {
        $kataKerja = ['Merancang', 'Mengembangkan', 'Menganalisis', 'Melakukan', 'Menyusun', 'Mengelola', 'Mengevaluasi', 'Menerapkan', 'Mengkonfigurasi', 'Memvalidasi'];
        $kataBenda = ['Database SQL', 'Algoritma Pemrograman', 'Jaringan Komputer', 'Antarmuka Pengguna', 'Keamanan Siber', 'Dokumentasi Teknis', 'Aplikasi Mobile', 'Sistem Cloud', 'API Backend', 'Data Warehouse'];
        
        return $faker->randomElement($kataKerja) . ' ' . $faker->randomElement($kataBenda);
    }
}