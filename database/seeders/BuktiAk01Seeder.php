<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BuktiAk01Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['bukti' => 'Hasil Verifikasi Portofolio'],
            ['bukti' => 'Hasil Observasi Langsung'],
            ['bukti' => 'Hasil Tanya Jawab'],
            ['bukti' => 'Hasil Reviu Produk'],
            ['bukti' => 'Hasil Kegiatan Terstruktur'],
            ['bukti' => 'Daftar Pertanyaan Tertulis'],
            ['bukti' => 'Daftar Pertanyaan Lisan'],
            ['bukti' => 'Daftar Pertanyaan Wawancara'],
            ['bukti' => 'Lainnya'],
        ];

        DB::table('bukti_ak01')->insert($data);
    }
}