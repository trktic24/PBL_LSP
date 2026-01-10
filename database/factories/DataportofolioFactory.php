<?php

namespace Database\Factories;

use App\Models\DataPortofolio;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataportofolioFactory extends Factory
{
    /**
     * Nama model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = DataPortofolio::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Contoh bukti persyaratan dasar
        $buktiPersyaratanDasar = [
            'Fotocopy KTP',
            'Pas foto 3x4 (2 lembar)',
            'Surat keterangan sehat dari dokter',
            'Ijazah terakhir',
        ];

        // Contoh bukti persyaratan administratif
        $buktiPersyaratanAdministratif = [
            'Laporan hasil pengolahan data spreadsheet',
            'File presentasi (.pptx)',
            'Sertifikat pelatihan terkait',
            'Surat Keterangan Pengalaman Kerja',
            'Portofolio proyek yang pernah dikerjakan',
        ];

        return [
            // Mengambil ID dari DataSertifikasiAsesi yang sudah ada secara acak
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::inRandomOrder()->first()?->id_data_sertifikasi_asesi 
                                            ?? DataSertifikasiAsesi::factory(),
            
            // Disimpan sebagai string sesuai kolom tipe data migration
            'persyaratan_dasar' => implode(', ', $this->faker->randomElements($buktiPersyaratanDasar, rand(2, 4))),
            'persyaratan_administratif' => implode(', ', $this->faker->randomElements($buktiPersyaratanAdministratif, rand(3, 5))),
        ];
    }

    /**
     * State khusus untuk bidang IT
     */
    public function forIT()
    {
        return $this->state(function (array $attributes) {
            $buktiIT = [
                'Laporan hasil pengolahan data spreadsheet',
                'Presentasi sistem informasi (.pptx)',
                'Sertifikat pelatihan IT (CCNA/MTCNA)',
                'Surat pengalaman kerja bidang IT Support',
                'Portofolio website atau aplikasi Laravel',
                'Source code project GitHub',
            ];

            return [
                'persyaratan_administratif' => implode(', ', $this->faker->randomElements($buktiIT, 4)),
            ];
        });
    }

    /**
     * State untuk data minimal
     */
    public function minimal()
    {
        return $this->state(function (array $attributes) {
            return [
                'persyaratan_dasar' => 'Fotocopy KTP, Pas foto 3x4',
                'persyaratan_administratif' => 'Laporan kerja bulanan',
            ];
        });
    }
}