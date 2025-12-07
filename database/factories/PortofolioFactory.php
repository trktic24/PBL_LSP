<?php

namespace Database\Factories;

use App\Models\Portofolio;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortofolioFactory extends Factory
{
    protected $model = Portofolio::class;

    public function definition()
    {
        // Daftar contoh bukti portofolio yang umum
        $buktiPersyaratanDasar = [
            '1. Fotocopy KTP',
            '2. Pas foto 3x4 (2 lembar)',
            '3. Surat keterangan sehat dari dokter',
            '4. Ijazah terakhir',
        ];

        $buktiPersyaratanAdministratif = [
            '1. Laporan hasil pengolahan data spreadsheet',
            '2. File presentasi (.pptx)',
            '3. Sertifikat pelatihan terkait',
            '4. Surat Keterangan Pengalaman Kerja',
            '5. Portfolio proyek yang pernah dikerjakan',
        ];

        return [
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::inRandomOrder()->first()->id_data_sertifikasi_asesi ?? null,
            'persyaratan_dasar' => json_encode($buktiPersyaratanDasar),
            'persyaratan_administratif' => json_encode($buktiPersyaratanAdministratif),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Dengan bukti portofolio khusus untuk IT/Komputer
     */
    public function forIT()
    {
        return $this->state(function (array $attributes) {
            $buktiPersyaratanAdministratif = [
                '1. Laporan hasil pengolahan data spreadsheet',
                '2. File presentasi (.pptx) tentang sistem informasi',
                '3. Sertifikat pelatihan Microsoft Office',
                '4. Surat Keterangan Pengalaman Kerja di bidang IT',
                '5. Portfolio website atau aplikasi yang pernah dibuat',
                '6. Source code project GitHub',
            ];

            return [
                'persyaratan_administratif' => json_encode($buktiPersyaratanAdministratif),
            ];
        });
    }

    /**
     * Dengan bukti portofolio minimal
     */
    public function minimal()
    {
        return $this->state(function (array $attributes) {
            $buktiPersyaratanDasar = [
                '1. Fotocopy KTP',
                '2. Pas foto 3x4 (2 lembar)',
            ];

            $buktiPersyaratanAdministratif = [
                '1. Laporan hasil pengolahan data',
                '2. File presentasi',
            ];

            return [
                'persyaratan_dasar' => json_encode($buktiPersyaratanDasar),
                'persyaratan_administratif' => json_encode($buktiPersyaratanAdministratif),
            ];
        });
    }
}