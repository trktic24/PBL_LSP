<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataSertifikasiAsesi;

class DataSertifikasiAsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 5 data dummy DataSertifikasiAsesi
        // Ini juga akan otomatis membuat data Asesi dan Jadwal
        // yang terkait, berkat definisi di dalam factory.
        DataSertifikasiAsesi::factory()->count(5)->create();
    }
}