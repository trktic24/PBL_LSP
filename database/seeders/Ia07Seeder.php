<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ia07;

class Ia07Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 10 data dummy Ia07
        // Ini juga akan otomatis membuat 10 data DataSertifikasiAsesi
        // berkat definisi di dalam factory.
        Ia07::factory()->count(10)->create();
    }
}