<?php

namespace Database\Factories;

use App\Models\DataSertifikasiAsesi;
use App\Models\Asesi; // Asumsi kamu punya model Asesi
use App\Models\Jadwal; // Asumsi kamu punya model Jadwal
use Illuminate\Database\Eloquent\Factories\Factory;

class DataSertifikasiAsesiFactory extends Factory
{
    protected $model = DataSertifikasiAsesi::class;

    public function definition(): array
    {
        $tujuan = $this->faker->randomElement([
            'sertifikasi', 
            'sertifikasi_ulang', 
            'pkt', 
            'rpl', 
            'lainnya'
        ]);

        return [
            'id_asesi' => Asesi::factory(),
            'id_jadwal' => Jadwal::factory(),
            'tujuan_asesmen' => $tujuan,
            'tujuan_asesmen_lainnya' => ($tujuan === 'lainnya') ? $this->faker->sentence(2) : null,
            'tanggal_daftar' => $this->faker->date(),
            
            // Kolom lain bisa di-set ke default (nullable)
            'rekomendasi_apl01' => 'diterima',
            'rekomendasi_apl02' => null,
            'karakteristik_kandidat' => 'tidak ada',
            'kebutuhan_kontekstualisasi_terkait_tempat_kerja' => 'tidak ada',
            'Saran_yang_diberikan_oleh_paket_pelatihan' => 'tidak ada',
            'penyesuaian_perangkat_asesmen' => 'tidak ada',
            'peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen' => 'tidak ada',
            'feedback_ia01' => null,
            'rekomendasi_IA04B' => null,
            'rekomendasi_hasil_asesmen_AK02' => null,
            'tindakan_lanjut_AK02' => null,
            'komentar_AK02' => null,
            'catatan_asesi_AK03' => null,
            'rekomendasi_AK05' => null,
            'keterangan_AK05' => null,
            'aspek_dalam_AK05' => null,
            'catatan_penolakan_AK05' => null,
            'saran_dan_perbaikan_AK05' => null,
            'catatan_AK05' => null,
            'rekomendasi1_AK06' => null,
            'rekomendasi2_AK06' => null,
        ];
    }
}