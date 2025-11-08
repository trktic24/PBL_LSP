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
        Schema::create('instrumen_asesmen_mapa02', function (Blueprint $table) {
            $table->id('id_instrumen_asesmen_mapa02');

            // isi model DB
            $table->string('instrumen_asesmen_mapa02')->comment('Instrumen asesmen Mapa 02');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumen_asesmen_mapa02');
    }
};
