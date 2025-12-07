<?php

namespace Database\Seeders;

// use App\Models\User; // Nggak kepake, bisa hapus
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skema;
use App\Models\DataSertifikasiAsesi;
use Database\Seeders\IA11\SpesifikasiIA11Seeder;
use Database\Seeders\IA11\PerformaIA11Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // GANTI DI SINI:
        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(JenisTukSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BuktiAk01Seeder::class);
        $this->call(SoalDanKunciSeeder::class);
        $this->call(PoinAk03Seeder::class);
        $this->call(SpesifikasiIA11Seeder::class);
        $this->call(PerformaIA11Seeder::class);
        $this->call(SkenarioIa02Seeder::class);

        // Baris ini udah bener
        \App\Models\Asesi::factory(20)->create();
        \App\Models\Skema::factory(20)->create();
        \App\Models\KelompokPekerjaan::factory(30)->create();
        \App\Models\UnitKompetensi::factory(25)->create();
        \App\Models\Elemen::factory(26)->create();
        \App\Models\KriteriaUnjukKerja::factory(20)->create();
        \App\Models\ResponApl2Ia01::factory(20)->create();
        \App\Models\BuktiKelengkapan::factory(20)->create();
        \App\Models\DataSertifikasiAsesi::factory(20)->create();
        \App\Models\Asesor::factory(20)->create();
        \App\Models\Jadwal::factory(20)->create();
        \App\Models\MasterTuk::factory(20)->create();
        \App\Models\DataPekerjaanAsesi::factory(20)->create();
        \App\Models\ResponBuktiAk01::factory(20)->create();
        \App\Models\IA03::factory(20)->create();
        $sertifikasiIds = DataSertifikasiAsesi::pluck('id_data_sertifikasi_asesi');
        foreach ($sertifikasiIds as $id) {
            \App\Models\IA11\IA11::factory()->create([
                'id_data_sertifikasi_asesi' => $id,
            ]);
        }
        \App\Models\Ia02::factory(20)->create();
    }
}
