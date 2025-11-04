<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel ini nyimpen CENTANG-nya
        Schema::create('master_tujuan_assesmen_mapa01', function (Blueprint $table) {
            $table->id('id_tujuan'); // ID (1, 2, 3, 4)
            $table->string('nama_tujuan'); // 'Sertifikasi', 'PKT', dll
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('master_tujuan_assesmen_mapa01');
    }
};