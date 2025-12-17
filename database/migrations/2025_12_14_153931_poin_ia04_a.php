<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poin_ia04A', function (Blueprint $table) {
            $table->id('id_poin_ia04A');

            // 👇 TAMBAHKAN FOREIGN KEY
            $table->foreignId('id_data_sertifikasi_asesi')
                ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                ->onDelete('cascade');
            
            // Kolom Data untuk Hal yang harus disiapkan/dilakukan/dihasilkan (Skenario)
            $table->text('hal_yang_disiapkan')->nullable();
            $table->date('waktu_disiapkan_menit')->nullable(); // Ganti nama menjadi date jika yang disimpan adalah tanggal

            // Kolom Data untuk Hal yang harus didemonstrasikan (Hasil Asesor Input)
            $table->text('hal_yang_didemonstrasikan')->nullable();
            $table->date('waktu_demonstrasi_menit')->nullable(); // Ganti nama menjadi date jika yang disimpan adalah tanggal

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poin_ia04A');
    }
};