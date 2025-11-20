<?php

namespace Database\Factories;

use App\Models\DataSertifikasiAsesi;
use App\Models\Asesi; // Pastikan model Asesi ada
use App\Models\Jadwal; // Pastikan model Jadwal ada
use Illuminate\Database\Eloquent\Factories\Factory;

class DataSertifikasiAsesiFactory extends Factory
{
    protected $model = DataSertifikasiAsesi::class;

    public function definition(): array
    {
        // --- Ini "Kamus" berdasarkan migrasi BARU kamu ---
        $rekomendasiAPL = ['diterima', 'tidak diterima'];
        
        // [FIX] Menggunakan enum dari migrasi baru
        $tujuan = ['sertifikasi', 'PKT', 'rekognisi pembelajaran sebelumnya', 'lainnya']; 
        
        $jawabanMapa = ['hasil pelatihan', 'pekerjaan', 'pelatihan'];
        $adaTidak = ['ada', 'tidak ada'];
        $kompeten = ['kompeten', 'belum kompeten'];
        
        return [
            // --- Foreign Keys (WAJIB) ---
            'id_asesi' => Asesi::factory(),
            'id_jadwal' => Jadwal::factory(),
            
            // --- Kolom Data (Sesuai Migrasi, semua boleh null) ---
            'rekomendasi_apl01' => $this->faker->randomElement($rekomendasiAPL),
            'tujuan_asesmen' => $this->faker->randomElement($tujuan),
            'rekomendasi_apl02' => $this->faker->randomElement($rekomendasiAPL),
            'tanggal_daftar' => $this->faker->date(),
            'jawaban_mapa01' => $this->faker->randomElement($jawabanMapa),
            'karakteristik_kandidat' => $this->faker->randomElement($adaTidak),
            'kebutuhan_kontekstualisasi_terkait_tempat_kerja' => $this->faker->randomElement($adaTidak),
            'Saran_yang_diberikan_oleh_paket_pelatihan' => $this->faker->randomElement($adaTidak),
            'penyesuaian_perangkat_asesmen' => $this->faker->randomElement($adaTidak),
            'peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen' => $this->faker->randomElement($adaTidak),
            'feedback_ia01' => $this->faker->randomElement($adaTidak),
            'rekomendasi_IA04B' => $this->faker->randomElement($kompeten),
            'rekomendasi_hasil_asesmen_AK02' => $this->faker->randomElement($kompeten),
            'tindakan_lanjut_AK02' => $this->faker->sentence(),
            'komentar_AK02' => $this->faker->sentence(),
            'catatan_asesi_AK03' => $this->faker->sentence(),
            'rekomendasi_AK05' => $this->faker->randomElement($kompeten),
            'keterangan_AK05' => $this->faker->sentence(),
            'aspek_dalam_AK05' => $this->faker->sentence(),
            'catatan_penolakan_AK05' => $this->faker->sentence(),
            'saran_dan_perbaikan_AK05' => $this->faker->sentence(),
            'catatan_AK05' => $this->faker->sentence(),
            'rekomendasi1_AK06' => $this->faker->sentence(),
            'rekomendasi2_AK06' => $this->faker->sentence(),
            
            // [FIX] Sesuai migrasi baru kamu
            'status_sertifikasi' => 'pendaftaran_selesai',
        ];
    }
}