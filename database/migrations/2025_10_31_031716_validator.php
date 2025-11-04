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
        Schema::create('validator', function (Blueprint $table) {
            $table->id('id_validator');
            $table->string('nama_validator');
            $table->string('no_MET_validator');

            // 'ttd' (tanda tangan) kemungkinan path ke file, jadi bisa null
            $table->string('ttd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validator');
    }
};
