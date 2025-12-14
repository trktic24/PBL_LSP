<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ia10;                 // Panggil Model Ia10
use App\Models\DataSertifikasiAsesi; // Panggil Model Data Sertifikasi
use Illuminate\Support\Facades\DB;

class Ia10Seeder extends Seeder
{
    public function run()
    {
        // 1. Ambil ID Sertifikasi Asesi yang Valid (Ambil data pertama yang ketemu)
        // Pastikan di tabel data_sertifikasi_asesi sudah ada minimal 1 data dummy.
        $dataAsesi = DataSertifikasiAsesi::first();

        // Cek dulu biar gak error kalau tabelnya kosong
        if (!$dataAsesi) {
            $this->command->error('Tabel data_sertifikasi_asesi kosong! Harap isi data asesi dulu sebelum menjalankan seeder ini.');
            return;
        }

        $id_valid = $dataAsesi->id_data_sertifikasi_asesi;

        // 2. Data Pertanyaan Checklist (Ya/Tidak)
        $pertanyaan_checklist = [
            'Apakah asesi mematuhi prosedur K3?',
            'Apakah asesi berinteraksi dengan harmonis didalam kelompoknya?',
            'Apakah asesi dapat mengelola tugas-tugas secara bersamaan?',
            'Apakah asesi dapat dengan cepat beradaptasi dengan peralatan dan lingkungan yang baru?',
            'Apakah asesi dapat merespon dengan cepat masalah-masalah yang ada di tempat kerjanya?',
            'Apakah Anda bersedia dihubungi jika verifikasi lebih lanjut dari pernyataan ini diperlukan?',
        ];

        foreach ($pertanyaan_checklist as $q) {
            Ia10::create([
                'id_data_sertifikasi_asesi' => $id_valid,
                'pertanyaan' => $q,
                // created_at & updated_at otomatis diisi oleh Eloquent
            ]);
        }

        // 3. Data Pertanyaan Isian (ID Manual 50-60)
        $pertanyaan_isian = [
            'Nama Pengawas/Penyelia',
            'Tempat Kerja',
            'Alamat',
            'Telepon',
            'Apa hubungan Anda dengan asesi?',
            'Berapa lama Anda bekerja dengan asesi?',
            'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
            'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)',
            'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?',
            'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
            'Ada komentar Lain',
        ];

        foreach ($pertanyaan_isian as $id => $q) {
            Ia10::create([
                'id_data_sertifikasi_asesi' => $id_valid,
                'pertanyaan' => $q,
            ]);
        }
    }
}