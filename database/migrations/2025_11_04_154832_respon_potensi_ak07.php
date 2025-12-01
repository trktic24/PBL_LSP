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
        Schema::create('respon_potensi_AK07', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_respon_potensi');

            // --- FK 1 (ke asesi) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'resp_potensi_sertifikasi_fk')
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- FK 2 (ke master-nya) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_poin_potensi_AK07');
            $table->foreign('id_poin_potensi_AK07', 'resp_potensi_master_fk')
                  ->references('id_poin_potensi_AK07')->on('poin_potensi_AK07')
                  ->onUpdate('cascade')->onDelete('cascade');
            
            // Kolom sisa dari ERD
            $table->text('respon_asesor')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_potensi_AK07');
    }
};
