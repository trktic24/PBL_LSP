<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respon_standar_industri', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_respon_standar_industri_mapa01');

            // --- FK 1 (ke asesi) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'resp_std_industri_sertifikasi_fk')->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // --- FK 2 (ke master-nya) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_standar_industri_mapa01');
            $table->foreign('id_standar_industri_mapa01', 'resp_std_industri_master_fk')->references('id_standar_industri_mapa01')->on('standar_industri_mapa01')->onUpdate('cascade')->onDelete('cascade');

            // Kolom sisa dari ERD
            $table->text('Respon_asesi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_standar_industri');
    }
};
