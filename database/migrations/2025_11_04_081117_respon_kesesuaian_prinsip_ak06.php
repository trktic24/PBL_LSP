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
        Schema::create('respon_kesesuaian_prinsip_ak06', function (Blueprint $table) {
            // PK PERSIS kayak ERD (gua benerin jadi lowercase)
            $table->id('id_respon_kesesuaian_prinsip_ak06');


            // --- KOLOM-KOLOM DARI ERD ---
            // (Kita benerin, nama kolom gak boleh pake spasi)

            $table->boolean('rencana_asesmen')->nullable();
            $table->boolean('persiapan_asesmen')->nullable();
            $table->boolean('implementasi_asesmen')->nullable();
            $table->boolean('keputusan_asesmen')->nullable();
            $table->boolean('umpan_balik_asesmen')->nullable();


            // --- FK 1 (ke asesi) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'resp_ak06_sertifikasi_fk')
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- FK 2 (ke master-nya) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_kesesuaian_prinsip_ak06');
            $table->foreign('id_kesesuaian_prinsip_ak06', 'resp_ak06_prinsip_fk')
                  ->references('id_kesesuaian_prinsip_ak06')->on('kesesuaian_prinsip_ak06')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_kesesuaian_prinsip_ak06');
    }
};
