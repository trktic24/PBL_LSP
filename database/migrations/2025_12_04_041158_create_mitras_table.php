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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('nama_mitra'); // Wajib diisi
            $table->text('alamat')->nullable(); // Boleh kosong
            $table->string('no_telp')->nullable(); // Boleh kosong
            $table->string('email')->nullable(); // Boleh kosong
            $table->string('logo')->nullable();
            $table->timestamps(); // Membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};