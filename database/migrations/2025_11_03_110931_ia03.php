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
        Schema::create('ia03', function (Blueprint $table) {
            // PK-nya 'id_IA03'
            $table->id('id_IA03');

            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
            $table->text('pertanyaan');
            $table->text('tanggapan')->nullable();

            // 'pencapaian' (Ya/Tidak) -> boolean, diisi belakangan
            $table->boolean('pencapaian')->nullable();

            // 'catatan_umpan_balik' diisi belakangan
            $table->text('catatan_umpan_balik')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia03');
    }
};
