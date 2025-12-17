<?php

namespace Database\Seeders\IA11;

use Illuminate\Database\Seeder;
use App\Models\IA11\PerformaIA11;

class PerformaIA11Seeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Kesesuaian ukuran (dimensi dan/atau berat)',
            'Kerapian dan kerapatan sambungan',
            'Pemasangan perlengkapan bahan penolong',
            'Kualitas produk sesuai dengan rujukan',
            'Fungsi produk berjalan normal',
        ];

        foreach ($items as $deskripsi) {
            PerformaIA11::firstOrCreate(['deskripsi_performa' => $deskripsi]);
        }
    }
}