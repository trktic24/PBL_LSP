<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('respon_diperlukan_penyesuaian_AK07', function (Blueprint $table) {
            $table->id('id_diperlukan_penyesuaian_AK07');

            // 1. Definisi Kolomnya dulu (Big Integer Unsigned)
            $table->unsignedBigInteger('id_data_sertifikasi_asesi');
            $table->unsignedBigInteger('id_persyaratan_modifikasi_AK07');
            $table->unsignedBigInteger('id_catatan_keterangan_AK07')->nullable();

            // 2. Definisi Foreign Key secara MANUAL (Agar nama alias & kolom target benar)

            // FK ke Data Sertifikasi
            // Format: foreign('kolom_lokal', 'nama_alias_pendek')->references('kolom_target')->on('tabel_target')
            $table->foreign('id_data_sertifikasi_asesi', 'fk_rdpa_sertifikasi')
                ->references('id_data_sertifikasi_asesi')
                ->on('data_sertifikasi_asesi')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // FK ke Persyaratan Modifikasi
            $table->foreign('id_persyaratan_modifikasi_AK07', 'fk_rdpa_modifikasi')
                ->references('id_persyaratan_modifikasi_AK07')
                ->on('persyaratan_modifikasi_AK07')
                ->onUpdate('cascade');

            // FK ke Catatan Keterangan
            $table->foreign('id_catatan_keterangan_AK07', 'fk_rdpa_keterangan')
                ->references('id_catatan_keterangan_AK07')
                ->on('catatan_keterangan_AK07')
                ->onUpdate('cascade');

            // --- DATA RESPON ---
            $table->enum('respon_penyesuaian', ['Ya', 'Tidak'])->comment('Jawaban Ya atau Tidak');
            $table->text('respon_catatan_keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respon_diperlukan_penyesuaian_AK07');
    }
};