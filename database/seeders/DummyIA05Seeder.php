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
        $soal1 = SoalIA05::updateOrCreate(['id_soal_ia05' => 1], [
            'soal_ia05' => 'Apa kepanjangan dari HTML?',
            'opsi_jawaban_a' => 'Hyper Text Markup Language',
            'opsi_jawaban_b' => 'High Text Make Language',
            'opsi_jawaban_c' => 'Hyper Tool Markup Language',
            'opsi_jawaban_d' => 'None of the above',
        ]);

        // [BARU] Buat Kunci Jawaban untuk Soal 1 (Misal kuncinya A)
        $kunci1 = KunciJawabanIA05::updateOrCreate(['id_soal_ia05' => 1], [
            'teks_kunci_jawaban_ia05' => 'A',
            'nomor_kunci_jawaban_ia05' => 1,
        ]);

        $soal2 = SoalIA05::updateOrCreate(['id_soal_ia05' => 2], [
            'soal_ia05' => 'Tag untuk membuat baris baru adalah?',
            'opsi_jawaban_a' => '<nl>',
            'opsi_jawaban_b' => '<br>',
            'opsi_jawaban_c' => '<break>',
            'opsi_jawaban_d' => '<lb>',
        ]);

        // [BARU] Buat Kunci Jawaban untuk Soal 2 (Misal kuncinya B)
        $kunci2 = KunciJawabanIA05::updateOrCreate(['id_soal_ia05' => 2], [
            'teks_kunci_jawaban_ia05' => 'B',
            'nomor_kunci_jawaban_ia05' => 1,
        ]);

        // Contoh: Ambil ID pertama yang ada di tabel induk
        $sertifikasi = \App\Models\DataSertifikasiAsesi::first();

        if (!$sertifikasi) {
            // Kalo kosong, buat dulu dummy-nya (opsional) atau return error
            $this->command->error('Tabel Data Sertifikasi Asesi masih kosong! Jalankan seeder induk dulu.');
            return;
        }

        $id_sertifikasi_test = $sertifikasi->id_data_sertifikasi_asesi; // Gunakan ID asli yang ada di DB

        LembarJawabIA05::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi_test, 'id_soal_ia05' => 1],
            [
                'id_kunci_jawaban_ia05' => $kunci1->id_kunci_jawaban_ia05 ?? $kunci1->id,                
                'teks_jawaban_asesi_ia05' => 'A', // Asesi jawab A
                'pencapaian_ia05_iya' => 0,       // Belum dinilai
                'pencapaian_ia05_tidak' => 0
            ]
        );

        LembarJawabIA05::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi_test, 'id_soal_ia05' => 2],
            [
                'id_kunci_jawaban_ia05' => $kunci2->id_kunci_jawaban_ia05 ?? $kunci2->id,                
                'teks_jawaban_asesi_ia05' => 'C', // Asesi jawab C (Salah)
                'pencapaian_ia05_iya' => 0,
                'pencapaian_ia05_tidak' => 0
            ]
        );
    }
}