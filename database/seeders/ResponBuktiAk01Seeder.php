<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\BuktiAk01;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponBuktiAk01;
use Illuminate\Database\Seeder;

class ResponBuktiAk01Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil semua ID Data Sertifikasi
        $allDataSertifikasi = DataSertifikasiAsesi::pluck('id_data_sertifikasi_asesi');

        // 2. Ambil semua ID Bukti yang ada (Master Data)
        $allBuktiAk01 = BuktiAk01::pluck('id_bukti_ak01');

        if ($allBuktiAk01->isEmpty()) {
            $this->command->error('Tabel bukti_ak01 kosong. Jalankan BuktiAk01Seeder dulu.');
            return;
        }

        $this->command->info("Memproses " . $allDataSertifikasi->count() . " Data Sertifikasi...");

        foreach ($allDataSertifikasi as $idSertifikasi) {
            // Cek apakah sudah ada respon untuk sertifikasi ini
            $exists = ResponBuktiAk01::where('id_data_sertifikasi_asesi', $idSertifikasi)->exists();

            if (!$exists) {
                // Pilih 1 bukti secara acak dari master data
                $randomBuktiId = $allBuktiAk01->random();

                ResponBuktiAk01::create([
                    'id_data_sertifikasi_asesi' => $idSertifikasi,
                    'id_bukti_ak01' => $randomBuktiId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $this->command->info('Berhasil seeding ResponBuktiAk01 (Random Pick).');
    }
}
