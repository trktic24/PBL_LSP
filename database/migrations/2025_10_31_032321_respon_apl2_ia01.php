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
<<<<<<< HEAD
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

            // 3. Penilaian Lanjut
            $table->text('penilaian_lanjut_ia01')->nullable()->comment('Catatan tambahan dari asesor');

=======
        Schema::create('respon_apl2_ia01', function (Blueprint $table) {
            $table->id('id_respon_apl2');

            // --- KUNCI 1 ---
            // Bikin FK 'id_data_sertifikasi_asesi'
            // Asumsi: Nyambung ke tabel 'data_sertifikasi_asesi' dengan PK 'id_data_sertifikasi_asesi'
            // $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // --- KUNCI 2 ---
            // Bikin FK 'id_kriteria' yang nyambung ke:
            // Tabel: 'master_kriteria_unjuk_kerja' (dari file migrasi sebelumnya)
            // Kolom: 'id_kriteria'
            $table->foreignId('id_kriteria')->constrained('master_kriteria_unjuk_kerja', 'id_kriteria')->onUpdate('cascade')->onDelete('cascade');

            // Kolom sisanya (sesuai ERD)
            // Ini semua diisi nanti sama asesi/asesor, jadi kita buat 'nullable()'

            $table->text('respon_asesi_apl02')->nullable();
            $table->text('bukti_asesi_apl02')->nullable();
            $table->boolean('pencapaian_ia01')->nullable(); // K/BK
            $table->boolean('penilaian_lanjut_ia01')->nullable(); // V/A/T
>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101
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
