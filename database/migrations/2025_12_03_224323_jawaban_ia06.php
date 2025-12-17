<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jawaban_ia06', function (Blueprint $table) {
            $table->id('id_jawaban_ia06');

            // Relasi ke Tabel Soal (Jika soal dihapus, jawaban ikut terhapus)
            $table->foreignId('id_soal_ia06')
                  ->constrained('soal_ia06', 'id_soal_ia06')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Relasi ke Tabel Asesi (Pastikan tabel 'data_sertifikasi_asesi' sudah ada sebelumnya)
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Jawaban inputan dari Asesi
            $table->text('jawaban_asesi')->nullable()->comment('Jawaban inputan user');

            // Penilaian dari Asesor (Di sini letaknya!)
            // 1 = Ya (Kompeten), 0 = Tidak, NULL = Belum dinilai
            $table->boolean('pencapaian')->nullable()->comment('1: Kompeten, 0: Belum Kompeten');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_ia06');
    }
};