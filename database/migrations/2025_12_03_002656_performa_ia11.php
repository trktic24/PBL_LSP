<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('performa_ia11', function (Blueprint $table) {
            $table->bigIncrements('id_performa_ia11');

            $table->string('deskripsi_performa', 500);

            $table->timestamps();

            // Tidak boleh ada performa dobel
            $table->unique('deskripsi_performa');

            // Index cepat untuk join PDF
            $table->index('id_performa_ia11');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performa_ia11');
    }
};
