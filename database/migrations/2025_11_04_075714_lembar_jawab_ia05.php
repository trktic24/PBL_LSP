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

            // Relasi ke Peserta (Data Sertifikasi)
            // (Pastikan nama tabel dan PK sesuai dengan database kamu)
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Relasi ke Soal Master yang dijawab
            $table->foreignId('id_soal_ia05')
                  ->constrained('soal_ia05', 'id_soal_ia05')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Jawaban yang dipilih peserta (a, b, c, atau d)
            // Bisa null sebelum peserta menjawab
            $table->enum('jawaban_asesi', ['a', 'b', 'c', 'd'])->nullable();

            // [PENCAPAIAN - HASIL KOREKSI OTOMATIS]
            // Kolom ini menggantikan 'status_koreksi'.
            // Berisi 'ya' (jika jawaban benar) atau 'tidak' (jika jawaban salah).
            // Dibuat nullable() karena saat soal baru disiapkan, belum ada hasilnya.
            // Nanti Controller yang akan mengisi ini otomatis saat submit.
            $table->enum('pencapaian', ['ya', 'tidak'])->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lembar_jawab_ia05');
    }
};