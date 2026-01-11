<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataPortofolio;
use App\Models\DataSertifikasiAsesi;

class DataportofolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil data sertifikasi asesi yang BELUM memiliki data di tabel portofolio
        // Catatan: Pastikan relasi 'portofolio' sudah didefinisikan di Model DataSertifikasiAsesi
        $dataSertifikasiList = DataSertifikasiAsesi::doesntHave('portofolio')->get();

        if ($dataSertifikasiList->isEmpty()) {
            $this->command->info('Semua data sertifikasi sudah memiliki portofolio. Membuat data tambahan secara acak...');
            
            // Jika sudah ada semua, kita ambil 5 sampel acak untuk dibuatkan portofolio baru (jika id_portofolio auto-increment)
            $dataSertifikasiList = DataSertifikasiAsesi::inRandomOrder()->limit(5)->get();
        }

        foreach ($dataSertifikasiList as $dataSertifikasi) {
            // Menggunakan Factory untuk membuat data dummy yang bervariasi
            DataPortofolio::create([
                'id_data_sertifikasi_asesi' => $dataSertifikasi->id_data_sertifikasi_asesi,
                'persyaratan_dasar' => 'Fotocopy KTP, Ijazah Terakhir, Pas Foto 3x4',
                'persyaratan_administratif' => 'Surat Pengalaman Kerja, Sertifikat Pelatihan, Logbook Pekerjaan',
            ]);

            $this->command->info("Berhasil membuat portofolio untuk Asesi ID: {$dataSertifikasi->id_data_sertifikasi_asesi}");
        }

        // 2. Opsi tambahan: Menggunakan Factory secara langsung untuk membuat 10 data acak
        // DataPortofolio::factory()->count(10)->create();

        $this->command->info('DataportofolioSeeder selesai dijalankan!');
    }
}