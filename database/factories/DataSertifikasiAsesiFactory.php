<?php

namespace Database\Factories;


use App\Models\Asesi;
use App\Models\Jadwal;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataSertifikasiAsesiFactory extends Factory
{
    protected $model = DataSertifikasiAsesi::class;

    public function definition(): array
    {
        // 1. Tentukan Nasib APL-01 (Gerbang Pertama)
        $statusApl01 = fake()->randomElement(array_merge(
            array_fill(0, 7, 'diterima'), // 70% Diterima
            array_fill(0, 2, null),       // 20% Baru daftar
            ['tidak diterima']            // 10% Ditolak
        ));

        // Data Dasar
        $data = [
            // FIX: Tambah ? (safe navigation) biar gak error kalau tabel kosong
            'id_asesi' => Asesi::inRandomOrder()->first()->id_asesi,
            'id_jadwal' => Jadwal::inRandomOrder()->first()->id_jadwal,
            'tanggal_daftar' => fake()->dateTimeThisYear()->format('Y-m-d'),
            'rekomendasi_apl01' => $statusApl01,
            'tujuan_asesmen' => fake()->randomElement(['sertifikasi', 'PKT', 'rekognisi pembelajaran sebelumnya', 'lainnya']),

            // Default NULL semua
            'rekomendasi_apl02' => null,
            'jawaban_mapa01' => null,
            'karakteristik_kandidat' => null,
            'kebutuhan_kontekstualisasi_terkait_tempat_kerja' => null,
            'Saran_yang_diberikan_oleh_paket_pelatihan' => null,
            'penyesuaian_perangkat_asesmen' => null,
            'peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen' => null,
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

        // STOP POINT 1: Jika APL-01 Gagal/Null
        if ($statusApl01 !== 'diterima') {
            return $data;
        }

        // 2. Tahap APL-02
        $statusApl02 = fake()->randomElement(array_merge(
            array_fill(0, 8, 'diterima'),
            ['tidak diterima', null]
        ));

        $data['rekomendasi_apl02'] = $statusApl02;
        $data['jawaban_mapa01'] = fake()->randomElement(['hasil pelatihan', 'pekerjaan']);

        // STOP POINT 2: Jika APL-02 Gagal/Null
        if ($statusApl02 !== 'diterima') {
            return $data;
        }

        // 3. Tahap Asesmen (FR-IA)
        $opsiYaTidak = ['ada', 'tidak ada'];
        $data['karakteristik_kandidat'] = fake()->randomElement($opsiYaTidak);
        $data['kebutuhan_kontekstualisasi_terkait_tempat_kerja'] = fake()->randomElement($opsiYaTidak);
        $data['Saran_yang_diberikan_oleh_paket_pelatihan'] = fake()->randomElement($opsiYaTidak);
        $data['penyesuaian_perangkat_asesmen'] = fake()->randomElement($opsiYaTidak);
        $data['peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen'] = fake()->randomElement($opsiYaTidak);
        $data['feedback_ia01'] = fake()->randomElement($opsiYaTidak);
        $data['rekomendasi_IA04B'] = 'kompeten';

        // 4. Tahap Keputusan (AK-02)
        // Chance K: 2/3, BK: 1/3
        $hasilAkhir = fake()->randomElement(['kompeten', 'kompeten', 'belum kompeten']);

        $data['rekomendasi_hasil_asesmen_AK02'] = NULL;//suruh cezar
        $data['tindakan_lanjut_AK02'] = $hasilAkhir == 'kompeten' ? 'Terbitkan sertifikat' : 'Perlu asesmen ulang';
        $data['komentar_AK02'] = fake()->sentence();
        $data['catatan_asesi_AK03'] = 'Setuju dengan keputusan asesor';

        // 5. Tahap Pleno (AK-05)
        $data['rekomendasi_AK05'] = $hasilAkhir;
        $data['keterangan_AK05'] = 'Proses berjalan lancar';
        $data['catatan_AK05'] = 'Tidak ada keberatan';
        $data['rekomendasi1_AK06'] = 'Lanjut proses sertifikasi';

        return $data;
    }
}