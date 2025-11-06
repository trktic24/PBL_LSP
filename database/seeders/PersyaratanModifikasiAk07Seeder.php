<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PersyaratanModifikasiAk07Seeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('Persyaratan_Modifikasi_AK07')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('Persyaratan_Modifikasi_AK07')->insert([
            ['poin_karakteristik_asesi' => 'Keterbatasan ases terhadap persyaratan bahasa, literasi, numerasi', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Penyediaan dukungan pembaca, pelayan, penulis, penerjemah', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Penggunaan   teknologi   adaptif   atau   peralatan khusus. (Tidak dapat menggunakan teknologi adaptif (misal: mengoperasikan komputer dan printer, peralatan digital dsb)', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Pelaksanaan  asesmen   secara  fleksibel  karena alasan keletihan atau keperluan pengobatan', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Pelaksanaan  asesmen   secara  fleksibel  karena alasan keletihan atau keperluan pengobatan', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Penyediaan  peralatan   asesmen  berupa  braille, audio/video-tape', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Penyesuaian tempat fisik/lingkungan asesmen', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Pertimbangan umur/usia lanjut/gender asesi (Adanya perbedaan usia dengan asesor yang lebih muda)', 'created_at' => now(), 'updated_at' => now()],
            ['poin_karakteristik_asesi' => 'Pertimbangan budaya/tradisi/agama', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}