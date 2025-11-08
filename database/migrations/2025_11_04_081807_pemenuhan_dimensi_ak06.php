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
        Schema::create('pemenuhan_dimensi_ak06', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_pemenuhan_dimensi_ak06');

            // --- INI PERBAIKAN LOGIKANYA ---
            // Kita bikin 1 kolom buat nampung 5 pilihan itu
            $table->string('nama_dimensi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemenuhan_dimensi_ak06');
    }
};
