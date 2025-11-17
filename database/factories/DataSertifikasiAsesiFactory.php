<?php

namespace Database\Factories;

use App\Models\DataSertifikasiAsesi;
use App\Models\Asesi;   // Pastikan model ini ada
use App\Models\Jadwal;  // Pastikan model ini ada
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataSertifikasiAsesi>
 */
class DataSertifikasiAsesiFactory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = DataSertifikasiAsesi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $opsiYaTidak = ['ada', 'tidak ada'];
        $opsiKompeten = ['kompeten', 'belum kompeten'];
        $opsiDiterima = ['diterima', 'tidak diterima'];

        return [
            // Menggunakan factory Asesi dan Jadwal untuk mendapatkan ID yang valid
            'id_asesi' => Asesi::inRandomOrder()->first()->id_asesi,
            'id_jadwal' => Jadwal::inRandomOrder()->first()->id_jadwal,

            'rekomendasi_apl01' => fake()->randomElement($opsiDiterima),
            'tujuan_asesmen' => fake()->randomElement(['sertifikasi', 'PKT', 'rekognisi pembelajaran sebelumnya', 'lainnya']),
            'rekomendasi_apl02' => fake()->randomElement($opsiDiterima),
            'tanggal_daftar' => fake()->date(),
            'jawaban_mapa01' => fake()->randomElement(['hasil pelatihan', 'pekerjaan', 'pelatihan']),
            'karakteristik_kandidat' => fake()->randomElement($opsiYaTidak),
            'kebutuhan_kontekstualisasi_terkait_tempat_kerja' => fake()->randomElement($opsiYaTidak),
            'Saran_yang_diberikan_oleh_paket_pelatihan' => fake()->randomElement($opsiYaTidak),
            'penyesuaian_perangkat_asesmen' => fake()->randomElement($opsiYaTidak),
            'peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen' => fake()->randomElement($opsiYaTidak),
            'feedback_ia01' => fake()->randomElement($opsiYaTidak),
            'rekomendasi_IA04B' => fake()->randomElement($opsiKompeten),
            'rekomendasi_hasil_asesmen_AK02' => fake()->randomElement($opsiKompeten),
            'tindakan_lanjut_AK02' => fake()->sentence(5),
            'komentar_AK02' => fake()->sentence(10),
            'catatan_asesi_AK03' => fake()->paragraph(2),
            'rekomendasi_AK05' => fake()->randomElement($opsiKompeten),
            'keterangan_AK05' => fake()->sentence(7),
            'aspek_dalam_AK05' => fake()->sentence(8),
            'catatan_penolakan_AK05' => fake()->sentence(9),
            'saran_dan_perbaikan_AK05' => fake()->sentence(10),
            'catatan_AK05' => fake()->paragraph(1),
            'rekomendasi1_AK06' => fake()->sentence(12),
            'rekomendasi2_AK06' => fake()->sentence(12),
        ];
    }
}