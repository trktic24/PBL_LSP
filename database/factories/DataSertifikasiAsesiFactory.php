<?php

namespace Database\Factories;

use App\Models\DataSertifikasiAsesi;
use App\Models\Asesi;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataSertifikasiAsesiFactory extends Factory
{
    protected $model = DataSertifikasiAsesi::class;

    public function definition(): array
    {
        return [
            // Kita gunakan factory default, tapi nanti akan di-override oleh Seeder
            'id_asesi' => Asesi::factory(),
            'id_jadwal' => Schedule::factory(),
            
            // Data Sertifikasi (Random Enum)
            'rekomendasi_apl01' => $this->faker->randomElement(['diterima', 'tidak diterima']),
            'tujuan_asesmen' => $this->faker->randomElement(['sertifikasi', 'PKT', 'rekognisi pembelajaran sebelumnya', 'lainnya']),
            'rekomendasi_apl02' => $this->faker->randomElement(['diterima', 'tidak diterima']),
            'tanggal_daftar' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'jawaban_mapa01' => $this->faker->randomElement(['hasil pelatihan', 'pekerjaan', 'pelatihan']),
            'karakteristik_kandidat' => $this->faker->randomElement(['ada', 'tidak ada']),
            'kebutuhan_kontekstualisasi_terkait_tempat_kerja' => $this->faker->randomElement(['ada', 'tidak ada']),
            'Saran_yang_diberikan_oleh_paket_pelatihan' => $this->faker->randomElement(['ada', 'tidak ada']),
            'penyesuaian_perangkat_asesmen' => $this->faker->randomElement(['ada', 'tidak ada']),
            'peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen' => $this->faker->randomElement(['ada', 'tidak ada']),
            'feedback_ia01' => $this->faker->randomElement(['ada', 'tidak ada']),
            'rekomendasi_IA04B' => $this->faker->randomElement(['kompeten', 'belum kompeten']),
            'rekomendasi_hasil_asesmen_AK02' => $this->faker->randomElement(['kompeten', 'belum kompeten']),
            
            // Text fields (Isi random text)
            'tindakan_lanjut_AK02' => $this->faker->sentence(),
            'komentar_AK02' => $this->faker->sentence(),
            'catatan_asesi_AK03' => $this->faker->sentence(),
            
            'rekomendasi_AK05' => $this->faker->randomElement(['kompeten', 'belum kompeten']),
            'keterangan_AK05' => $this->faker->sentence(),
            'aspek_dalam_AK05' => $this->faker->sentence(),
            'catatan_penolakan_AK05' => $this->faker->sentence(),
            'saran_dan_perbaikan_AK05' => $this->faker->sentence(),
            'catatan_AK05' => $this->faker->sentence(),
            'rekomendasi1_AK06' => $this->faker->sentence(),
            'rekomendasi2_AK06' => $this->faker->sentence(),
            
            'status_sertifikasi' => 'pendaftaran_selesai',
        ];
    }
}