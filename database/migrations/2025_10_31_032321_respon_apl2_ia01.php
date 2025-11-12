<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respon_apl2_ia01', function (Blueprint $table) {
            $table->id('id_respon_apl2');

            // --- KUNCI 1 ---
            // Bikin FK 'id_data_sertifikasi_asesi'
            // Asumsi: Nyambung ke tabel 'data_sertifikasi_asesi' dengan PK 'id_data_sertifikasi_asesi'
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // --- KUNCI 2 ---
            // Bikin FK 'id_kriteria' yang nyambung ke:
            // Tabel: 'master_kriteria_unjuk_kerja' (dari file migrasi sebelumnya)
            // Kolom: 'id_kriteria'
            $table->foreignId('id_kriteria')->constrained('master_kriteria_unjuk_kerja', 'id_kriteria')->onUpdate('cascade')->onDelete('cascade');

            // Kolom sisanya (sesuai ERD)
            // Ini semua diisi nanti sama asesi/asesor, jadi kita buat 'nullable()'

            $table->text('respon_asesi_apl02')->comment('Respon asesi untuk pertanyaan APL-02');
            $table->text('bukti_asesi_apl02')->comment('Bukti asesi untuk pertanyaan APL-02');
            $table->boolean('pencapaian_ia01')->comment('Pencapaian IA01 (ya/tidak)');
            $table->string('penilaian_lanjut_ia01')->comment('Penilaian Lanjut IA01 (V/A/T)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_apl2_ia01');
    }
};
