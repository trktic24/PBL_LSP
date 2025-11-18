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
        Schema::create('Transaksi_asesor_skema', function (Blueprint $table) {
            $table->id('id_transaksi');
<<<<<<< HEAD
            $table->foreignId('id_asesor')->constrained('asesor', 'id_asesor')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_skema')->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('cascade');
=======
            $table->foreignId('id_asesor')->constrained('asesor', 'id_asesor')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_skema')->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('restrict');
>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Transaksi_asesor_skema');
    }
};
