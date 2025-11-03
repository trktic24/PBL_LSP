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
        Schema::create('respon_hubungan_standar_mapa01', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');
            
            // Nyambung ke master-nya
            $table->foreignId('id_hubungan_antara_standar_kompetensi', 'id_hub_std_komp_fk') // Nama FK-nya dipendekin
                  ->constrained('poin_hubungan_antara_standar_kompetensi', 'id_hubungan_antara_standar_kompetensi')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_hubungan_standar_mapa01');
    }
};
