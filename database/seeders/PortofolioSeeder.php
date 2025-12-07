<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portofolio;
use App\Models\DataSertifikasiAsesi;

class PortofolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua data sertifikasi asesi yang belum punya portofolio
        $dataSertifikasiList = DataSertifikasiAsesi::whereDoesntHave('portofolio')->get();

        if ($dataSertifikasiList->isEmpty()) {
            $this->command->info('Semua data sertifikasi sudah memiliki portofolio.');
            
            // Jika ingin tetap membuat beberapa portofolio tambahan
            $dataSertifikasiList = DataSertifikasiAsesi::inRandomOrder()->limit(5)->get();
        }

        foreach ($dataSertifikasiList as $dataSertifikasi) {
            // Buat portofolio untuk setiap data sertifikasi
            Portofolio::factory()->create([
                'id_data_sertifikasi_asesi' => $dataSertifikasi->id_data_sertifikasi_asesi,
            ]);

            $this->command->info("Portofolio dibuat untuk data_sertifikasi_asesi ID: {$dataSertifikasi->id_data_sertifikasi_asesi}");
        }

        $this->command->info('Portofolio seeder selesai dijalankan!');
    }
}