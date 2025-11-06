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
        Schema::create('respon_pemenuhan_dimensi_ak06', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_respon_pemenuhan_dimensi_ak06');

            // Kolom 'aspek_yang_ditinjau' dari ERD
            $table->string('aspek_yang_ditinjau');

            // --- FK 1 (ke asesi) ---
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'resp_dimensi_sertifikasi_fk')
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- FK 2 (ke master-nya) ---
            $table->foreignId('id_pemenuhan_dimensi_AK06');
            $table->foreign('id_pemenuhan_dimensi_AK06', 'resp_dimensi_master_fk')
                  ->references('id_pemenuhan_dimensi_ak06')->on('pemenuhan_dimensi_ak06')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_pemenuhan_dimensi_ak06');
    }
};
