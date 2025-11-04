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
        Schema::create('master_poin_siapa_melakukan_asesmen', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_siapa_melakukan_asesmen');
            
            // Logikanya bener (pilihan jadi BARIS)
            $table->string('pilihan'); // 'Lembaga Sertifikasi', 'Organisasi...', dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_poin_siapa_melakukan_asesmen');
    }
};
