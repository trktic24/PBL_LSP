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
        Schema::create('penyusun', function (Blueprint $table) {
            $table->id('id_penyusun');
            $table->string('penyusun');
            $table->string('no_MET_penyusun');

            // 'ttd' (tanda tangan) kemungkinan path ke file, jadi bisa null
            $table->string('ttd')->nullable()->comment('Path ke file tanda tangan penyusun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyusun');
    }
};
