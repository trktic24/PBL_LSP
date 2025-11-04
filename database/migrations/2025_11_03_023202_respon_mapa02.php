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
        Schema::create('respon_mapa02', function (Blueprint $table) {
            $table->id('id_respon_mapa02');
            $table->foreignId('id_instrumen_asesmen_mapa02')->constrained('instrumen_asesmen_mapa02', 'id_instrumen_asesmen_mapa02')->onUpdate('cascade')->onDelete('cascade');

            // isi model DB
            $table->enum('potensi_asesi_mapa02', [1, 2, 3, 4, 5])->comment('Poin penilaian potensi asesi Mapa 02');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_mapa02');
    }
};
