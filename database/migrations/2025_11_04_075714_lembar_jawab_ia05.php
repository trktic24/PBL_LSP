<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lembar_jawab_ia05', function (Blueprint $table) {
            $table->id('id_lembar_jawab_ia05'); // PK

            // [PENTING] Relasi ke Peserta (Data Sertifikasi)
            // Ini yang menentukan soal ini milik siapa
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Relasi ke Soal Master yang harus dijawab
            $table->foreignId('id_soal_ia05')
                  ->constrained('soal_ia05', 'id_soal_ia05')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Jawaban yang dipilih peserta
            $table->enum('jawaban_asesi_ia05', ['a', 'b', 'c', 'd'])->nullable();

            // 'ya' = Benar, 'tidak' = Salah. Nullable di awal.
            $table->enum('pencapaian_ia05', ['ya', 'tidak'])->nullable();
            $table->text('umpan_balik')->nullable()->comment('Catatan dari asesor untuk asesi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lembar_jawab_ia05');
    }
};