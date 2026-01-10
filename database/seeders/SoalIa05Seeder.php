<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoalIA05;

class SoalIa05Seeder extends Seeder
{
    public function run()
    {
        // Membuat 10 data dummy soal
        SoalIA05::factory()->count(10)->create();
    }
}