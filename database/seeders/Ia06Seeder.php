<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SoalIa06;
use App\Models\UmpanBalikIa06;
use App\Models\DataSertifikasiAsesi;

class Ia06Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat 10 Soal Dummy
        SoalIa06::factory()->count(10)->create();
        $this->command->info('Berhasil membuat 10 Soal IA-06!');

        // 2. Buat Umpan Balik Dummy
        // Cek dulu apakah ada data sertifikasi asesi di database?
        // Karena umpan balik butuh foreign key ke tabel data_sertifikasi_asesi

        $asesi = DataSertifikasiAsesi::first();

        if ($asesi) {
            UmpanBalikIa06::factory()->create([
                'id_data_sertifikasi_asesi' => $asesi->id_data_sertifikasi_asesi, // Ambil ID yang valid
                'umpan_balik' => 'Kompetensi asesi sudah sangat baik, namun perlu pendalaman di bagian teknis.',
            ]);
            $this->command->info('Berhasil membuat contoh Umpan Balik untuk Asesi ID: ' . $asesi->id_data_sertifikasi_asesi);
        } else {
            $this->command->warn('SKIP: Tidak bisa membuat dummy Umpan Balik karena tabel data_sertifikasi_asesi masih kosong.');
            $this->command->warn('Silakan buat data asesi manual terlebih dahulu atau jalankan seeder asesi.');
        }
    }
}