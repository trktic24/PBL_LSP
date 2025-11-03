<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SoalSeederIA06::class,
            TujuanAssesmenMapa01::class,
        ]);
    }
}
