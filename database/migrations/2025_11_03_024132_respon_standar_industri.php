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
        Schema::create('respon_standar_industri', function(Blueprint $table) {
            $table->id('id_respon_standar_industri_mapa01');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_standar_industri_mapa01')->constrained('standar_industri_mapa01', 'id_standar_industri_mapa01')->onUpdate('cascade')->onDelete('cascade');
            $table->
        })
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
