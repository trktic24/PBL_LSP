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
        Schema::create('Konfirmasi_dengan_orang_yang_relevan', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_konfirmasi_dengan_orang_yang_relevan');

            // --- INI PERBAIKAN LOGIKANYA ---
            // Pilihan-pilihan ('Manajer LSP', 'Master Asesor'...)
            // kita simpen sebagai BARIS DATA, bukan KOLOM.
            $table->text('pilihan');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Konfirmasi_dengan_orang_yang_relevan');
    }
};
