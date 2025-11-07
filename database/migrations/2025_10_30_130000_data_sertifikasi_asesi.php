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
            $table->foreignId('id_asesi')->constrained('asesi', 'id_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onUpdate('cascade')->onDelete('cascade');
            // Isi kolom yang dibutuhkan untuk data sertifikasi asesi
            $table->enum('rekomendasi_apl01', ['diterima', 'tidak diterima'])->comment('Apakah asesi mendapatkan rekomendasi dari APL-01 untuk lanjut asesmen pada APL-02');
            $table->enum('tujuan_asesmen',['sertifikasi', 'PKT', 'rekognisi pmbelajaran sebelumnya', 'lainnya'])->comment('Tujuan asesmen asesi');
            $table->enum('rekomendasi_apl02', ['diterima', 'tidak diterima'])->comment('Apakah asesi mendapatkan rekomendasi dari APL-02 untuk sertifikasi');
            $table->date('tanggal_daftar');
            $table->enum('karakteristik_kandidat', ['ada', 'tidak ada'])->comment('Apakah karakteristik khusus kandidat ada atau tidak');
            $table->enum('kebutuhan_kontekstualisasi_terkait_tempat_kerja', ['ada', 'tidak ada'])->comment('Apakah ada kebutuhan kontekstualisasi terkait tempat kerja kandidat');
            $table->enum('Saran_yang_diberikan_oleh_paket_pelatihan', ['ada', 'tidak ada'])->comment('Apakah ada saran yang diberikan oleh paket pelatihan terkait kebutuhan kandidat');
            $table->enum('penyesuaian_perangkat_asesmen', ['ada', 'tidak ada'])->comment('Apakah ada penyesuaian asesmen yang diperlukan untuk kandidat');
            $table->enum('peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen', ['ada', 'tidak ada'])->comment('Apakah ada peluang untuk kegiatan asesmen terintegrasi dan perubahan alat asesmen untuk kandidat');
            $table->enum('feedback_ia01', ['ada', 'tidak ada'])->comment('Apakah ada feedback dari IA01 mengenai proses asesmen kandidat');
            $table->enum('rekomendasi_IA04B', ['kompeten', 'belum kompeten'])->comment('Apakah ada rekomendasi dari IA04 mengenai kelayakan sertifikasi kandidat');
            $table->enum('rekomendasi_hasil_asesmen_AK02', ['kompeten', 'belum kompeten'])->comment('Rekomendasi hasil asesmen dari AK02 untuk sertifikasi kandidat');
            $table->string('tindakan_lanjut_AK02');
            $table->string('komentar_AK02');
            $table->string('catatan_asesi_AK03');
            $table->enum('rekomendasi_AK05', ['kompeten', 'belum kompeten'])->comment('Rekomendasi dari AK05 mengenai hasil akhir sertifikasi kandidat');
            $table->string('keterangan_AK05');
            $table->string('aspek_dalam_AK05');
            $table->string('catatan_penolakan_AK05');
            $table->string('saran_dan_perbaikan_AK05');
            $table->string('catatan_AK05');
            $table->string('rekomendasi1_AK06');
            $table->string('rekomendasi2_AK06');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExixts('data_sertifikasi_asesi');
    }
};
