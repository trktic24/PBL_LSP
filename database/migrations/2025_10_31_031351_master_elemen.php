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

<<<<<<< HEAD
            $table->foreignId('id_unit_kompetensi')->constrained('unit_kompetensi', 'id_unit_kompetensi')->onUpdate('cascade')->onDelete('cascade');
=======
            $table->foreignId('id_unit_kompetensi')->constrained('master_unit_kompetensi', 'id_unit_kompetensi')->onUpdate('cascade')->onDelete('cascade');

            // Kolom sisanya (sesuai ERD)
            $table->string('no_elemen');

>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101
            // 'elemen' lebih baik pakai text() karena bisa jadi deskripsi panjang
            $table->text('elemen');
            $table->timestamps();
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
