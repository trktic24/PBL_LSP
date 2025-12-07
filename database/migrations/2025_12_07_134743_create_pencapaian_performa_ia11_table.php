<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pencapaian_performa_ia11', function (Blueprint $table) {
            //pk
            $table->id('id_pencapaian_performa_ia11');
            //fk
            $table->unsignedBigInteger('id_ia11');
            $table->unsignedBigInteger('id_performa_ia11');
            //field
            $table->boolean('hasil_reviu')->nullable();
            $table->text('catatan_temuan')->nullable();

            $table->timestamps();

            //fk ke ia11
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');

            //fk ke performa
            $table->foreign('id_performa_ia11')->references('id_performa_ia11')->on('performa_ia11')->onDelete('cascade');

            $table->unique(['id_ia11', 'id_performa_ia11'], 'ia11_performa_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencapaian_performa_ia11');
    }
};