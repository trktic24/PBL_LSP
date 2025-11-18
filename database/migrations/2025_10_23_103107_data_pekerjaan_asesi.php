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
            // Sesuai ERD: id_pekerjaan (PK)
            $table->id('id_pekerjaan');

            // Sesuai ERD: id_asesi (FK)
            // Asumsi: Ini merujuk ke PK 'id_asesi' di tabel 'asesi'
            // Jika tabel asesi Anda belum ada, Anda harus membuatnya terlebih dahulu.
            $table->foreignId('id_asesi')->constrained(
                table: 'asesi', column: 'id_asesi'
<<<<<<< HEAD
            )->onUpdate('cascade')->onDelete('cascade');
=======
            )->onUpdate('cascade')->onDelete('restrict');
>>>>>>> 867fbf1f11206d464c9dfc53537a3ebf60030101

            // Data Pekerjaan Sekarang
            $table->string('nama_institusi_pekerjaan');
            $table->text('alamat_institusi');
            $table->string('jabatan');
            $table->string('kode_pos_institusi', 10)->nullable(); // (int) -> Diubah ke string
            $table->string('no_telepon_institusi', 16);
            
            // Standar timestamp
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