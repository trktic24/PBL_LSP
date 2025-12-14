<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SoalIA05;
use App\Models\KunciJawabanIA05;
use App\Models\LembarJawabIA05;

class DummyIA05Seeder extends Seeder
{
    public function run()
    {
        // 1. Buat Dummy SOAL (Seolah-olah Admin sudah kerja)
        // Kita pakai updateOrCreate biar gak duplikat kalau di-run berkali-kali
        
        $sertifikasi = \App\Models\DataSertifikasiAsesi::find(7) ?? \App\Models\DataSertifikasiAsesi::first();

        if (!$sertifikasi) {
            $this->command->error('Tabel Data Sertifikasi Asesi masih kosong! Jalankan seeder induk dulu.');
            return;
        }

        $id_sertifikasi_test = $sertifikasi->id_data_sertifikasi_asesi;

        // Soal 1
        SoalIA05::updateOrCreate(['id_soal_ia05' => 1], [
            'id_data_sertifikasi_asesi' => $id_sertifikasi_test, 
            'soal_ia05' => 'Apa kepanjangan dari HTML?',
            'opsi_a_ia05' => 'Hyper Text Markup Language',
            'opsi_b_ia05' => 'High Text Make Language',
            'opsi_c_ia05' => 'Hyper Tool Markup Language',
            'opsi_d_ia05' => 'None of the above',
        ]);

        KunciJawabanIA05::updateOrCreate(['id_soal_ia05' => 1], [
            'jawaban_benar_ia05' => 'a',
            'penjelasan_ia05' => 'HTML stands for Hyper Text Markup Language',
        ]);

        // Soal 2
        SoalIA05::updateOrCreate(['id_soal_ia05' => 2], [
            'id_data_sertifikasi_asesi' => $id_sertifikasi_test,
            'soal_ia05' => 'Tag untuk membuat baris baru adalah?',
            'opsi_a_ia05' => '<nl>',
            'opsi_b_ia05' => '<br>',
            'opsi_c_ia05' => '<break>',
            'opsi_d_ia05' => '<lb>',
        ]);

        KunciJawabanIA05::updateOrCreate(['id_soal_ia05' => 2], [
            'jawaban_benar_ia05' => 'b',
            'penjelasan_ia05' => 'br tag inserts a single line break',
        ]);

        // Lembar Jawab
        LembarJawabIA05::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi_test, 'id_soal_ia05' => 1],
            [
                'jawaban_asesi_ia05' => 'a', 
                'pencapaian_ia05' => null 
            ]
        );

        LembarJawabIA05::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi_test, 'id_soal_ia05' => 2],
            [
                'jawaban_asesi_ia05' => 'c', 
                'pencapaian_ia05' => null
            ]
        );
    }
}