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
        Schema::create('kelompok_pekerjaans', function (Blueprint $table) {
            $table->id('id_kelompok_pekerjaan'); // bigint unsigned primary key
            $table->foreignId('id_skema')
                ->constrained('skema', 'id_skema')
                ->onDelete('cascade');
            $table->string('kode_unit'); 
            $table->string('judul_unit');
            $table->string('jenis_standar')->nullable(); 
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_pekerjaans');
    }
};
