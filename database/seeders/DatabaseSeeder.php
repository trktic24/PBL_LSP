<?php

namespace Database\Seeders;

use App\Models\Tuk;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Berita;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\SoalDanKunciSeeder;
use Database\Seeders\IA11\PerformaIA11Seeder;
use Database\Seeders\IA11\SpesifikasiIA11Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. LOGIKA PEMBERSIHAN FOLDER GAMBAR
        // Menghapus dan membuat ulang folder agar seeding gambar mulai dari nol
        $imageFolder = 'skema_images';
        Storage::disk('public')->deleteDirectory($imageFolder);
        Storage::disk('public')->makeDirectory($imageFolder);

        // 2. SEEDER DASAR (Master Data Tanpa Dependensi Berat)
        $this->call([
            RoleSeeder::class,          // Role dulu (Admin, Asesor, Asesi)
            CategorySeeder::class,      // Kategori Skema
            CountrySeeder::class,       // Data Negara
            JenisTukSeeder::class,      // Jenis TUK (Mandiri, Sewaktu, dll)
        ]);

        // 3. DATA USER & TEMPAT UJI
        $this->call([
            UserSeeder::class,          // User Admin/Staff
            MasterTukSeeder::class,     // Data TUK utama
        ]);

        // 4. GENERATE DATA DENGAN FACTORY (Bulk Data)
        // Kita buat data dasar menggunakan Factory untuk testing
        Tuk::factory(20)->create();
        Asesor::factory(20)->create();
        Skema::factory(20)->create(); // SkemaFactory biasanya menghandle gambar

        // 5. DATA DETAIL & SKEMA (Membutuhkan Skema & Asesor yang sudah ada)
        $this->call([
            SkemaSeeder::class,
            SkemaDetailSeeder::class,
            AsesorSeeder::class,
        ]);

        // 6. DATA ASESI & JADWAL
        // Pastikan User untuk asesi sudah ada atau dibuat di dalam factory
        Asesi::factory(200)->create();
        Schedule::factory(50)->create();

        // 7. SEEDER FORMULIR & DOKUMEN TEKNIS (Urutan Logis sesuai Proses Sertifikasi)
        $this->call([
            JadwalSeeder::class,
            DataSertifikasiAsesiSeeder::class,
            KonfirmasiOrangRelevanSeeder::class,
            StandarIndustriMapa01Seeder::class,
            PemenuhanDimensiAk06Seeder::class,
            StrukturOrganisasiSeeder::class,
            BuktiAk01Seeder::class,
            SoalDanKunciSeeder::class,
            SpesifikasiIA11Seeder::class,
            PerformaIA11Seeder::class,
            SkenarioIa02Seeder::class,
            PoinAk03Seeder::class,
        ]);

        // 8. DATA TAMBAHAN LAINNYA
        Berita::factory(15)->create();
    }
}