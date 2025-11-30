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
        Schema::create('ak05', function (Blueprint $table) {
            $table->id('id_ak05');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('rekomendasi', ['K', 'BK'])->comment('K=Kompeten, BK=Belum Kompeten');
            $table->text('keterangan')->nullable();
            $table->text('aspek_negatif_positif')->nullable();
            $table->text('penolakan_hasil_asesmen')->nullable();
            $table->text('saran_perbaikan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ak05');
    }
};
