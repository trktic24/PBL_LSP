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
        Schema::create('hasil_penyesuaian_AK07', function (Blueprint $table) {
            
            // Bikin PK baru yang bener
            $table->id('id_hasil_penyesuaian_AK07');

            // --- FK ke asesi ---
            $table->foreignId('id_sertifikasi_asesi'); // (Ini di ERD lu 'id_sertifikasi_asesi')
            $table->foreign('id_sertifikasi_asesi', 'hasil_ak07_sertifikasi_fk')
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi') // (Nyambung ke tabel 'data_sertifikasi_asesi' lu)
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- Kolom isian (Sesuai permintaan lu, pake text) ---
            
            // 'Acuan Pembanding Asesmen' -> jadi 'Acuan_Pembanding_Asesmen'
            $table->text('Acuan_Pembanding_Asesmen')->nullable();
            
            // 'Metode Asesmen' -> jadi 'Metode_Asesmen'
            $table->text('Metode_Asesmen')->nullable();
            
            // 'Instrumen Asesmen' -> jadi 'Instrumen_Asesmen'
            $table->text('Instrumen_Asesmen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_penyesuaian_AK07');
    }
};
