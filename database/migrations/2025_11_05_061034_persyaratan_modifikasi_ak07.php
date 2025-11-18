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
        Schema::create('Persyaratan_Modifikasi_AK07', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_persyaratan_modifikasi_AK07');

            // --- INI PERBAIKAN LOGIKANYA ---
            // 'poin karakteristik asesi' kita jadiin kolom buat nampung NAMA BARIS
            $table->text('poin_karakteristik_asesi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Persyaratan_Modifikasi_AK07');
    }
};
