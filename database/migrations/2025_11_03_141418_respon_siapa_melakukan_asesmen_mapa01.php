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
        Schema::create('respon_siapa_melakukan_asesmen_mapa01', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_respon_siapa_melakukan_asesmen_mapa01');
            
            // FK PERSIS kayak ERD (ke asesi)
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // FK PERSIS kayak ERD (ke master-nya)
            $table->foreignId('id_siapa_melakukan_asesmen')
                  ->constrained('poin_siapa_melakukan_asesmen', 'id_siapa_melakukan_asesmen')
                  ->onUpdate('cascade')->onDelete('cascade');
            
            // Kolom 'Respon asesor' dari ERD
            $table->text('Respon_asesor')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_siapa_melakukan_asesmen_mapa01');
    }
};
