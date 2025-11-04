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
        Schema::create('data_pekerjaan_asesi', function (Blueprint $table) {
        $table->id('id_pekerjaan');

        $table->foreignId('id_asesi')->nullable()->constrained('asesi', 'id_asesi')->onUpdate('cascade')->onDelete('restrict');

        $table->string('nama_institusi_pekerjaan')->nullable();
        $table->text('alamat_institusi')->nullable();
        $table->string('jabatan')->nullable();
        $table->string('kode_pos_institusi', 10)->nullable();
        $table->string('no_telepon_institusi', 16)->nullable();

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pekerjaan_asesi');
    }
};
