<?php

namespace Database\Seeders\IA11;

use Illuminate\Database\Seeder;
use App\Models\IA11\SpesifikasiIA11;

class SpesifikasiIA11Seeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Ukuran produk sesuai rencana atau gambar kerja',
            'Estetika/penampilan produk',
            'Kebersihan dan kerapian permukaan produk',
            'Keserasian bentuk dan fungsi',
            'Kesesuaian dengan standar bahan baku',
        ];

        foreach ($items as $deskripsi) {
            SpesifikasiIA11::firstOrCreate(['deskripsi_spesifikasi' => $deskripsi]);
        }
    }
}