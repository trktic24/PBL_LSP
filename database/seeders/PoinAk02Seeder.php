<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoinAk02Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poin = [
            'Observasi Demonstrasi',
            'Portofolio',
            'Pernyataan pihak ketiga Wawancara',
            'Pertanyaan lisan',
            'Pertanyaan Tulis',
            'Proyek kerja',
            'Lainnya',
        ];

        foreach ($poin as $p) {
            DB::table('poin_ak02')->insert([
                'poin' => $p,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
