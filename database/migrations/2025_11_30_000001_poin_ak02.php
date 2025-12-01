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
        Schema::create('poin_ak02', function (Blueprint $table) {
            $table->id('id_poin_ak02');

            // isi kolom tabel poin_ak02
            $table->string('poin')->nullable()->comment('isi sesuai poin yang digunakan');
            $table->boolean('respon')->default(false)->nullable()->comment('respon mengikuti poin yang digunakan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_ak02');
    }
};
