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
        Schema::create('poin_ak03', function (Blueprint $table) {
            $table->id('id_poin_ak03');
            $table->string('komponen');
            $table->string('hasil')->nullable()->comment('ya/tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_ak03');
    }
};
