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
        Schema::create('umpan_balik_ia03', function (Blueprint $table) {
            $table->id('id_umpan_balik_ia03');
            $table->foreignId('id_ia03')->constrained('ia03', 'id_ia03')->onUpdate('cascade')->onDelete('cascade');

            // isi kolom umpan_balik_ia03
            $table->text('umpan_balik')->nullable()->comment('Umpan balik dari asesor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umpan_balik_ia03');
    }
};
