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
        Schema::create('ia07', function (Blueprint $table) {
    $table->id('id_ia07');
    // FK ke Asesi
    $table->foreignId('id_data_sertifikasi_asesi')->constrained(...);
    // FK ke Master Soal (BUKAN menyimpan teks soal lagi)
    $table->foreignId('id_pertanyaan_lisan')->constrained('master_pertanyaan_lisan', 'id_pertanyaan_lisan');
    // Jawaban & Hasil
    $table->text('jawaban_asesi')->nullable(); // Ringkasan dari asesor
    $table->enum('rekomendasi', ['K', 'BK'])->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Scheama::dropIfExists('ia07');
    }
};
