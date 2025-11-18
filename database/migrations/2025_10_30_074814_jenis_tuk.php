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
        Schema::create('jenis_tuk', function (Blueprint $table) {
            $table->id('id_jenis_tuk');

            // isi kolom sesuai kebutuhan
<<<<<<< HEAD
            $table->enum('jenis_tuk', ['Sewaktu', 'Tempat Kerja'])->comment('Jenis TUK: Sewaktu atau Tempat Kerja');
=======
            $table->string('sewaktu');
            $table->string('tempat_kerja');
            $table->string('mandiri');
>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_tuk');
    }
<<<<<<< HEAD
};
=======
};
>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101
