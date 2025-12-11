<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PoinAk03; // Pastikan nanti buat modelnya
use Illuminate\Support\Facades\DB;

class PoinAk03Seeder extends Seeder
{
    public function run()
    {
        $pertanyaan = [
            'Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi',
            'Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya',
            'Master Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki',
            'Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen',
            'Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen',
            'Master Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya',
            'Master Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya',
            'Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen',
            'Master Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen',
        ];

        foreach ($pertanyaan as $p) {
            DB::table('poin_ak03')->insert([
                'komponen' => $pertanyaan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}