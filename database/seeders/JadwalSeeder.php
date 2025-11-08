<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jadwal;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 20 jadwal dummy.
        // Ini akan otomatis membuat data JenisTuk, MasterTuk, Skema, dan Asesor
        // yang baru untuk setiap jadwal, berkat definisi di Factory.
        Jadwal::factory()->count(20)->create();
    }
}