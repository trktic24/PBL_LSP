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
        Schema::create('komentar_ak05', function (Blueprint $table) {
            $table->id('id_komentar_ak05');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_ak05')->constrained('ak05', 'id_ak05')->onUpdate('cascade')->onDelete('cascade');

            // isi kolom tabel komentar_ak05
            $table->enum('rekomendasi', ['K', 'BK'])->comment('K=Kompeten, BK=Belum Kompeten');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar_ak05');
    }
};
