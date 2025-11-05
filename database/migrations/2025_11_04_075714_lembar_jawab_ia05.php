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
        Schema::create('lembar_jawab_ia05', function (Blueprint $table) {
            $table->id('id_lembar_jawab_ia05');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_soal_ia05')->constrained('soal_ia05', 'id_soal_ia05')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_kunci_jawaban_ia05')->constrained('kunci_jawaban_ia05', 'id_kunci_jawaban_ia05')->onUpdate('cascade')->onDelete('cascade');

            // isi dari database lembar_jawab_ia05
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembar_jawab_ia05');
    }
};
