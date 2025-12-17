<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunci_jawaban_ia05', function (Blueprint $table) {
            $table->id('id_kunci_jawaban_ia05'); // PK

            // Relasi ke Tabel Soal (Tetap One-to-One)
            $table->foreignId('id_soal_ia05')
                  ->unique() 
                  ->constrained('soal_ia05', 'id_soal_ia05')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // KUNCI JAWABAN YANG BENAR
            $table->enum('jawaban_benar_ia05', ['a', 'b', 'c', 'd']);
            
            // Penjelasan (Opsional)
            $table->text('penjelasan_ia05')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunci_jawaban_ia05');
    }
};