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
        Schema::create('asesi', function (Blueprint $table) {
            // Sesuai ERD: id_asesi (PK)
            $table->id('id_asesi');

            // Sesuai ERD: id_user (FK)
            // Ini akan membuat foreign key ke kolom 'id' di tabel 'users'
            $table->foreignId('id_user')->constrained('users', 'id_user')->onUpdate('cascade')->onDelete('restrict');

            // Atribut lainnya
            $table->string('nama_lengkap');

            // (int) -> Diubah ke string
            $table->string('nik', 16)->nullable()->unique();

            // (bool) -> Diterapkan sebagai boolean
            $table->boolean('jenis_kelamin')->nullable()->comment('misal: 1 Laki-laki, 0 Perempuan');

            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kebangsaan', 100)->nullable();

            // (str) -> Diubah ke text
            $table->text('alamat_rumah')->nullable();

            // (int) -> Diubah ke string
            $table->string('kode_pos', 10)->nullable();
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->string('nomor_hp', 20)->nullable();
            $table->text('email');

            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();

            // (str: path) -> Diubah ke string
            $table->string('tanda_tangan')->nullable()->comment('Path ke file tanda tangan');

            // Standar timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesi');
    }
};
