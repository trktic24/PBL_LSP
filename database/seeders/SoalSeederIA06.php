<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoalIA06;
use App\Models\KunciIA06;

class SoalSeederIA06 extends Seeder
{
    public function run(): void
    {
        $soal1 = SoalIA06::create([
            'soal_IA06' => 'Apa ibu kota dari Indonesia?',
        ]);

        $soal1->kuncis()->createMany([
            ['kunci_IA06' => 'Jakarta'],
            ['kunci_IA06' => 'Surabaya'],
            ['kunci_IA06' => 'Bandung'],
        ]);

        $soal2 = SoalIA06::create([
            'soal_IA06' => 'Siapa penemu bola lampu?',
        ]);

        $soal2->kuncis()->createMany([
            ['kunci_IA06' => 'Thomas Alva Edison'],
            ['kunci_IA06' => 'Nikola Tesla'],
        ]);
    }
}
