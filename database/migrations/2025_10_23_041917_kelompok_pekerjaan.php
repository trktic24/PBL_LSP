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
        Schema::create('kelompok_pekerjaan', function (Blueprint $table) {
            $table->id('id_kelompok_pekerjaan');
            $table->foreignId('id_skema')->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('cascade');
<<<<<<< HEAD

=======
>>>>>>> 9bc4b2c3cbfda0a22adb2d09acd03862131a3a02
            // isi kolom sesuai ERD
            $table->string('nama_kelompok_pekerjaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_pekerjaan');
    }
};
