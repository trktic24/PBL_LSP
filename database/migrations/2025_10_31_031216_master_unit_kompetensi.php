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
        Schema::create('master_unit_kompetensi', function (Blueprint $table) {
            $table->id('id_unit_kompetensi'); // bigint unsigned

            $table->unsignedBigInteger('id_kelompok_pekerjaan'); // harus unsigned bigint
            $table->foreign('id_kelompok_pekerjaan')
                ->references('id_kelompok_pekerjaan')
                ->on('kelompok_pekerjaans')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('kode_unit');
            $table->string('judul_unit');
            $table->string('jenis_standar');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_unit_kompetensi');
    }
};
