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
        Schema::create('respon_ia10', function (Blueprint $table) {
            $table->id('id_respon_ia10');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_pertanyaan_ia10')->constrained('pertanyaan_ia10', 'id_pertanyaan_ia10')->onUpdate('cascade')->onDelete('cascade');

            // isi kolom Database respon_ia10
            $table->enum('jawaban_pilihan', ['Ya', 'Tidak']);
            $table->string('jawaban_isian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_ia10');
    }
};
