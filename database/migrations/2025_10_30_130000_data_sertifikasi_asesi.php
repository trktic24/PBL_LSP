<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_sertifikasi_asesi', function (Blueprint $table) {
            $table->id('id_data_sertifikasi_asesi');
            
            // --- Kolom ID (WAJIB, TIDAK NULL) ---
            $table->foreignId('id_asesi')->constrained('asesi', 'id_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onUpdate('cascade')->onDelete('cascade');
            
            // --- Kolom Data (BOLEH NULL) ---
            $table->enum('rekomendasi_apl01', ['diterima', 'tidak diterima'])->comment('Apakah asesi mendapatkan rekomendasi dari APL-01 untuk lanjut asesmen pada APL-02')->nullable();
            $table->enum('tujuan_asesmen',['sertifikasi', 'PKT', 'rekognisi pembelajaran sebelumnya', 'lainnya'])->comment('Tujuan asesmen asesi')->nullable();
            $table->enum('rekomendasi_apl02', ['diterima', 'tidak diterima'])->comment('Apakah asesi mendapatkan rekomendasi dari APL-02 untuk sertifikasi')->nullable();
            $table->date('tanggal_daftar')->nullable(); // <-- Dibuat nullable
            $table->enum('jawaban_mapa01', ['hasil pelatihan', 'pekerjaan', 'pelatihan'])->comment('Jawaban MAPA-01')->nullable();
            $table->enum('karakteristik_kandidat', ['ada', 'tidak ada'])->comment('Apakah karakteristik khusus kandidat ada atau tidak')->nullable();
            $table->enum('kebutuhan_kontekstualisasi_terkait_tempat_kerja', ['ada', 'tidak ada'])->comment('Apakah ada kebutuhan kontekstualisasi terkait tempat kerja kandidat')->nullable();
            $table->enum('Saran_yang_diberikan_oleh_paket_pelatihan', ['ada', 'tidak ada'])->comment('Apakah ada saran yang diberikan oleh paket pelatihan terkait kebutuhan kandidat')->nullable();
            $table->enum('penyesuaian_perangkat_asesmen', ['ada', 'tidak ada'])->comment('Apakah ada penyesuaian asesmen yang diperlukan untuk kandidat')->nullable();
            $table->enum('peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen', ['ada', 'tidak ada'])->comment('Apakah ada peluang untuk kegiatan asesmen terintegrasi dan perubahan alat asesmen untuk kandidat')->nullable();
            $table->enum('feedback_ia01', ['ada', 'tidak ada'])->comment('Apakah ada feedback dari IA01 mengenai proses asesmen kandidat')->nullable();
            $table->enum('rekomendasi_IA04B', ['kompeten', 'belum kompeten'])->comment('Apakah ada rekomendasi dari IA04 mengenai kelayakan sertifikasi kandidat')->nullable();
            $table->enum('rekomendasi_hasil_asesmen_AK02', ['kompeten', 'belum kompeten'])->comment('Rekomendasi hasil asesmen dari AK02 untuk sertifikasi kandidat')->nullable();
            $table->text('tindakan_lanjut_AK02')->nullable(); // <-- Dibuat nullable
            $table->text('komentar_AK02')->nullable(); // <-- Dibuat nullable
            $table->text('catatan_asesi_AK03')->nullable(); // <-- Dibuat nullable
            $table->enum('rekomendasi_AK05', ['kompeten', 'belum kompeten'])->comment('Rekomendasi dari AK05 mengenai hasil akhir sertifikasi kandidat')->nullable();
            $table->text('keterangan_AK05')->nullable(); // <-- Dibuat nullable
            $table->text('aspek_dalam_AK05')->nullable(); // <-- Dibuat nullable
            $table->text('catatan_penolakan_AK05')->nullable(); // <-- Dibuat nullable
            $table->text('saran_dan_perbaikan_AK05')->nullable(); // <-- Dibuat nullable
            $table->text('catatan_AK05')->nullable(); // <-- Dibuat nullable
            $table->text('rekomendasi1_AK06')->nullable(); // <-- Dibuat nullable
            $table->text('rekomendasi2_AK06')->nullable(); // <-- Dibuat nullable
            
            // Kolom status ini sudah punya default, tapi kita buat nullable juga
            // agar bisa diisi null secara eksplisit jika diperlukan.
            $table->string('status_sertifikasi', 50)->default('pendaftaran_selesai')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sertifikasi_asesi');
    }
};
