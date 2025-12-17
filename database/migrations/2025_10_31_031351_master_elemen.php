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
        Schema::create('master_elemen', function (Blueprint $table) {
            $table->id('id_elemen');

            $table->foreignId('id_unit_kompetensi')->constrained('unit_kompetensi', 'id_unit_kompetensi')->onUpdate('cascade')->onDelete('cascade');
            // 'elemen' lebih baik pakai text() karena bisa jadi deskripsi panjang
            $table->text('elemen');
            $table->timestamps(); //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_elemen');
    }
};
