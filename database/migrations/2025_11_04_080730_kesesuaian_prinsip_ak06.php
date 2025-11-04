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
        // Nama tabel PERSIS kayak ERD
        Schema::create('kesesuaian_prinsip_ak06', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_kesesuaian_prinsip_ak06');

            // --- INI KOLOM-KOLOM DARI ERD ---
            // (Kita asumsi ini semua adalah checkbox, jadi pake boolean)

            $table->boolean('validitas')->nullable();

            // 'relaibel' di ERD typo, kita benerin jadi 'reliabel'
            $table->boolean('reliabel')->nullable();

            $table->boolean('fleksibel')->nullable();
            $table->boolean('adil')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kesesuaian_prinsip_ak06');
    }
};
