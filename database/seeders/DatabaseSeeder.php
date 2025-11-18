<?php

namespace Database\Seeders;

// use App\Models\User; // Nggak kepake, bisa hapus
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // GANTI DI SINI:
        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(JenisTukSeeder::class);
        
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
    }
}