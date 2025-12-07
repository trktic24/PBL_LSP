<?php

namespace Database\Seeders;

use App\Models\Ia02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class SkenarioIa02Seeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        // Nonaktifkan Foreign Key Checks sementara untuk TRUNCATE
        Schema::disableForeignKeyConstraints();
        DB::table('ia02')->truncate();
        Schema::enableForeignKeyConstraints();
        
        // Data statis Skenario dan Peralatan
        $skenarioData = [
            [
                'skenario' => 'Lakukan instalasi dan konfigurasi dasar pada sistem operasi Linux (Ubuntu/CentOS).',
                'peralatan' => 'Komputer/Laptop dengan spesifikasi minimal i5, RAM 8GB; koneksi internet stabil.',
                'waktu' => '02:00:00',
            ],
            [
                'skenario' => 'Buat laporan keuangan triwulan menggunakan perangkat lunak akuntansi.',
                'peralatan' => 'Perangkat lunak Microsoft Office; Kalkulator ilmiah; Kertas dan Alat tulis.',
                'waktu' => '03:30:00',
            ],
            [
                'skenario' => 'Lakukan pengujian fungsional pada aplikasi web yang baru dikembangkan.',
                'peralatan' => 'Software testing (Selenium/Postman); Dokumentasi user requirement.',
                'waktu' => '04:00:00',
            ],
            [
                'skenario' => 'Rancang dan buat desain poster promosi untuk acara seminar menggunakan tools desain grafis.',
                'peralatan' => 'Software desain (Adobe Photoshop/Illustrator/Canva); Materi logo dan teks seminar.',
                'waktu' => '02:30:00',
            ],
            [
                'skenario' => 'Lakukan perbaikan (troubleshooting) jaringan lokal pada satu unit kerja yang mengalami gangguan koneksi.',
                'peralatan' => 'Tester kabel jaringan; Laptop dengan software diagnostik jaringan (Ping/Traceroute).',
                'waktu' => '01:30:00',
            ],
            [
                'skenario' => 'Laksanakan presentasi hasil proyek akhir di depan panel asesor.',
                'peralatan' => 'Proyektor; Komputer/Laptop; Dokumen proyek dan materi presentasi.',
                'waktu' => '01:00:00',
            ],
            [
                'skenario' => 'Lakukan analisis data penjualan bulanan dan buat rekomendasi strategi pemasaran.',
                'peralatan' => 'Perangkat lunak spreadsheet (MS Excel/Google Sheets); Dataset data penjualan.',
                'waktu' => '02:45:00',
            ],
        ];

        // 1. Ambil semua ID dari DataSertifikasiAsesi yang sudah ada
        $sertifikasiAsesis = DataSertifikasiAsesi::pluck('id_data_sertifikasi_asesi')->toArray();

        if (empty($sertifikasiAsesis)) {
            echo "Peringatan: Tidak ada data DataSertifikasiAsesi. Melewatkan seeding IA02.\n";
            return;
        }

        $ia02DataToInsert = [];
        $timestamp = now();

        // 2. Iterasi setiap ID Asesi dan tambahkan 1 sampai 2 skenario acak
        foreach ($sertifikasiAsesis as $idSertifikasi) {
            // Ambil 1 sampai 3 skenario acak dari daftar statis
            $selectedSkenarios = Arr::random($skenarioData, rand(1, 3));

            foreach ($selectedSkenarios as $skenario) {
                $ia02DataToInsert[] = [
                    'id_data_sertifikasi_asesi' => $idSertifikasi,
                    'skenario' => $skenario['skenario'],
                    'peralatan' => $skenario['peralatan'],
                    'waktu' => $skenario['waktu'],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

       // 3. Masukkan semua data IA02 ke dalam database
       DB::table('ia02')->insert($ia02DataToInsert);
    }
}