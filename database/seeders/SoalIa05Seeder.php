<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoalIa05;

class SoalIa05Seeder extends Seeder
{
    public function run()
    {
        // Membuat 10 data dummy soal
        SoalIa05::factory()->count(5)->create();
    }
}