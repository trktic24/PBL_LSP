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
        // Tabel ini nyimpen CENTANG-nya
        Schema::create('respon_tujuan_assesmen_mapa01', function (Blueprint $table) {
            $table->id();

            // Nyambung ke siapa yang ngisi
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Nyambung ke pilihan apa yang dicentang
            $table->foreignId('id_tujuan')
                  ->constrained('master_tujuan_sertifikasi', 'id_tujuan')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
            // Buat nampung teks 'Lainnya'
            $table->string('lainnya_text')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_tujuan_assesmen_mapa01');
    }
};
