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
        Schema::create('poin_potensi_AK07', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_poin_potensi_AK07');

            // --- INI PERBAIKAN LOGIKANYA ---
            // 5 Pilihan itu kita simpen sebagai BARIS DATA
            $table->text('pilihan');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_potensi_AK07');
    }
};
