<?php
// FILE: database/migrations/2025_12_03_155335_respon_ia04_a.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respon_ia04A', function (Blueprint $table) {
            // PK: id_respon_ia04A
            $table->id('id_respon_ia04A'); 

            // FK 1: Ke tabel 'data_sertifikasi_asesi'
            $table->unsignedBigInteger('id_data_sertifikasi_asesi'); 

            // FK 2: Ke tabel 'poin_ia04A'
            // (Diasumsikan ini juga BigInt Unsigned)
            $table->unsignedBigInteger('id_poin_ia04A');

            // Field Data
            $table->text('respon_poin_ia04A')->nullable();
            $table->text('umpan_balik_untuk_asesi')->nullable();
            $table->string('ttd_supervisor')->nullable(); 
            
            // Definisi Foreign Keys
            
            // *** KOREKSI DI SINI ***
            $table->foreign('id_data_sertifikasi_asesi')
                  ->references('id_data_sertifikasi_asesi') // *** MERUJUK KE PK YANG BENAR ***
                  ->on('data_sertifikasi_asesi')
                  ->onDelete('cascade');

            // Relasi ke tabel 'poin_ia04A'
            // CATATAN: Pastikan PK di 'poin_ia04A' benar-benar bernama 'id_poin_ia04A'
            $table->foreign('id_poin_ia04A')
                  ->references('id_poin_ia04A')
                  ->on('poin_ia04A')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respon_ia04A');
    }
};