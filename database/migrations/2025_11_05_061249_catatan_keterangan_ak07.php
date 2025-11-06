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
        Schema::create('catatan_keterangan_AK07', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_catatan_keterangan_AK07');

            // --- INI PERBAIKAN LOGIKANYA ---
            // 'catatan' kita jadiin kolom buat nampung NAMA PILIHAN CHECKBOX
            $table->text('catatan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_keterangan_AK07');
    }
};
