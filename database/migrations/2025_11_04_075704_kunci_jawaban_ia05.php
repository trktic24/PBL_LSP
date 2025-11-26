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

            // Relasi ke Tabel Soal
            // Kita buat unique agar 1 soal cuma bisa punya 1 kunci jawaban
            $table->foreignId('id_soal_ia05')
                  ->unique() 
                  ->constrained('soal_ia05', 'id_soal_ia05')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // KUNCI JAWABAN YANG BENAR (PENTING UNTUK KOREKSI OTOMATIS)
            // Isinya cukup 'a', 'b', 'c', atau 'd'
            $table->enum('jawaban_benar', ['a', 'b', 'c', 'd']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunci_jawaban_ia05');
    }
};