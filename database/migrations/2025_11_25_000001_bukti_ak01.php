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
        Schema::create('bukti_ak01', function (Blueprint $table) {
            $table->id('id_bukti_ak01');

            // isi kolom bukti ak01
            $table->string('bukti')->comment('template bukti ak01 dari seeder');
            $table->string('bukti_lainnya')->nullable()->default(null)->comment('bukti ak01 dari user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_ak01');
    }
};
