<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterTuk;
use Illuminate\Support\Facades\DB; // Import DB untuk truncate

class MasterTUKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. PANGGIL FACTORY DAN TIMPA 'nama_lokasi'
        MasterTuk::factory()->create([
            'nama_lokasi' => 'Teknik Elektro'
        ]);

        MasterTuk::factory()->create([
            'nama_lokasi' => 'Teknik Mesin'
        ]);

        MasterTuk::factory()->create([
            'nama_lokasi' => 'Teknik Sipil'
        ]);

        MasterTuk::factory()->create([
            'nama_lokasi' => 'Administrasi Bisnis'
        ]);

        MasterTuk::factory()->create([
            'nama_lokasi' => 'Akuntansi'
        ]);       
    }
}
