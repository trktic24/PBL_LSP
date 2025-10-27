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
            )->onUpdate('cascade')->onDelete('restrict');

            // Nama institusi/perusahaan (str)
            $table->string('nama_institusi_perusahaan')->nullable();

            // Jabatan (str)
            $table->string('jabatan')->nullable();

            // Alamat Kantor (str) -> Diubah ke text
            $table->text('alamat_kantor')->nullable();

            // Kode Pos (int) -> Diubah ke string
            $table->string('kode_pos', 10)->nullable();

            // No. telepon Kantor (int) -> Diubah ke string
            $table->string('no_telepon_kantor', 20)->nullable();

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