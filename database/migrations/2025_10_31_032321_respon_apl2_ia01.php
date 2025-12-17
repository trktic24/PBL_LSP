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
        Schema::create('respon_apl02_ia01', function (Blueprint $table) {
            $table->id('id_respon_apl02'); // Sesuai ERD primary key-nya ini

            // --- Foreign Keys ---
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_kriteria')->constrained('master_kriteria_unjuk_kerja', 'id_kriteria')->onUpdate('cascade')->onDelete('cascade');


            // 1. Bagian APL-02 (Asesmen Mandiri oleh Asesi)
            // Kita pake boolean: 1 = K (Kompeten), 0 = BK (Belum Kompeten)
            $table->boolean('respon_asesi_apl02')->nullable()->comment('1=K, 0=BK (Diisi Asesi)');
            $table->string('bukti_asesi_apl02')->nullable()->comment('Path file bukti portofolio');

            // 2. Bagian IA.01 (Observasi oleh Asesor - YANG SEDANG KITA KERJAKAN)
            // Kita pake boolean juga: 1 = Ya/K, 0 = Tidak/BK
            // Nanti frontend yang nentuin labelnya "Ya" atau "K" berdasarkan tipe KUK-nya
            $table->boolean('pencapaian_ia01')->nullable()->comment('1=Ya/K, 0=Tidak/BK (Diisi Asesor)');

            $table->string('standar_industri_ia01')->nullable()->comment('Isian manual asesor jika beda dari master');
            // 3. Penilaian Lanjut
            $table->text('penilaian_lanjut_ia01')->nullable()->comment('Catatan tambahan dari asesor');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_apl02_ia01');
    }
};
