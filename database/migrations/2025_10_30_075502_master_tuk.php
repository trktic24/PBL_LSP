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
        Schema::create('master_tuk', function (Blueprint $table) {
            $table->id('id_tuk');

            // isi master TUK
            $table->string('nama_lokasi');
            $table->string('alamat_tuk');
            $table->string('kontak_tuk');
            $table->string('foto_tuk')->comment('Path ke file foto TUK');
            $table->text('link_gmap')->comment('Link Google Maps lokasi TUK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tuk');
    }
};
