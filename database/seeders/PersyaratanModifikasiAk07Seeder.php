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
        DB::table('persyaratan_modifikasi_AK07')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('persyaratan_modifikasi_AK07')->insert([
            ['pertanyaan_karakteristik' => 'Keterbatasan ases terhadap persyaratan bahasa, literasi, numerasi', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Penyediaan dukungan pembaca, pelayan, penulis, penerjemah', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Penggunaan   teknologi   adaptif   atau   peralatan khusus. (Tidak dapat menggunakan teknologi adaptif (misal: mengoperasikan komputer dan printer, peralatan digital dsb)', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Pelaksanaan  asesmen   secara  fleksibel  karena alasan keletihan atau keperluan pengobatan', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Pelaksanaan  asesmen   secara  fleksibel  karena alasan keletihan atau keperluan pengobatan', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Penyediaan  peralatan   asesmen  berupa  braille, audio/video-tape', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Penyesuaian tempat fisik/lingkungan asesmen', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Pertimbangan umur/usia lanjut/gender asesi (Adanya perbedaan usia dengan asesor yang lebih muda)', 'created_at' => now(), 'updated_at' => now()],
            ['pertanyaan_karakteristik' => 'Pertimbangan budaya/tradisi/agama', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}