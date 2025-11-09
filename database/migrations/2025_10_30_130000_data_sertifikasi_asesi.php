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
            $table->foreignId('id_asesi')->constrained('asesi', 'id_asesi')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onUpdate('cascade')->onDelete('restrict');
            
            // Isi kolom yang dibutuhkan untuk data sertifikasi asesi
            
            // Kolom 'tujuan_asesmen' dan 'tanggal_daftar' WAJIB diisi saat pembuatan
            $table->enum('tujuan_asesmen',['sertifikasi','sertifikasi_ulang', 'pkt','rpl', 'lainnya'])->comment('Tujuan asesmen asesi');
            $table->string('tujuan_asesmen_lainnya')->nullable(); // Ini sudah benar
            $table->date('tanggal_daftar');

            // --- SEMUA KOLOM DI BAWAH INI SAYA TAMBAHKAN ->nullable() ---
            // Karena ini diisi oleh Asesor/Admin nanti, bukan saat pendaftaran
            
            $table->enum('rekomendasi_apl01', ['diterima', 'tidak diterima'])->nullable()->comment('Apakah asesi mendapatkan rekomendasi dari APL-01 untuk lanjut asesmen pada APL-02');
            $table->enum('rekomendasi_apl02', ['diterima', 'tidak diterima'])->nullable()->comment('Apakah asesi mendapatkan rekomendasi dari APL-02 untuk sertifikasi');
            $table->enum('karakteristik_kandidat', ['ada', 'tidak ada'])->nullable()->comment('Apakah karakteristik khusus kandidat ada atau tidak');
            $table->enum('kebutuhan_kontekstualisasi_terkait_tempat_kerja', ['ada', 'tidak ada'])->nullable()->comment('Apakah ada kebutuhan kontekstualisasi terkait tempat kerja kandidat');
            $table->enum('Saran_yang_diberikan_oleh_paket_pelatihan', ['ada', 'tidak ada'])->nullable()->comment('Apakah ada saran yang diberikan oleh paket pelatihan terkait kebutuhan kandidat');
            $table->enum('penyesuaian_perangkat_asesmen', ['ada', 'tidak ada'])->nullable()->comment('Apakah ada penyesuaian asesmen yang diperlukan untuk kandidat');
            $table->enum('peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen', ['ada', 'tidak ada'])->nullable()->comment('Apakah ada peluang untuk kegiatan asesmen terintegrasi dan perubahan alat asesmen untuk kandidat');
            $table->enum('feedback_ia01', ['ada', 'tidak ada'])->nullable()->comment('Apakah ada feedback dari IA01 mengenai proses asesmen kandidat');
            $table->enum('rekomendasi_IA04B', ['kompeten', 'belum kompeten'])->nullable()->comment('Apakah ada rekomendasi dari IA04 mengenai kelayakan sertifikasi kandidat');
            $table->enum('rekomendasi_hasil_asesmen_AK02', ['kompeten', 'belum kompeten'])->nullable()->comment('Rekomendasi hasil asesmen dari AK02 untuk sertifikasi kandidat');
            $table->string('tindakan_lanjut_AK02')->nullable();
            $table->string('komentar_AK02')->nullable();
            $table->string('catatan_asesi_AK03')->nullable();
            $table->enum('rekomendasi_AK05', ['kompeten', 'belum kompeten'])->nullable()->comment('Rekomendasi dari AK05 mengenai hasil akhir sertifikasi kandidat');
            $table->string('keterangan_AK05')->nullable();
            $table->string('aspek_dalam_AK05')->nullable();
            $table->string('catatan_penolakan_AK05')->nullable();
            $table->string('saran_dan_perbaikan_AK05')->nullable();
            $table->string('catatan_AK05')->nullable();
            $table->string('rekomendasi1_AK06')->nullable();
            $table->string('rekomendasi2_AK06')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Perbaikan kecil di sini: Harusnya 'dropIfExists' (I besar, x kecil)
        Schema::dropIfExists('data_sertifikasi_asesi');
    }
};