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

            // Data Pribadi Asesi
            $table->string('nama_lengkap');            
            $table->string('nik', 16)->unique(); // (int) -> Diubah ke string
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->boolean('jenis_kelamin')->comment('misal: 1 Laki-laki, 0 Perempuan'); // (bool) -> Diterapkan sebagai boolean
            $table->string('kebangsaan', 100)->nullable();
            $table->string('pendidikan');
            $table->string('pekerjaan');

            // Alamat dan Kontak
            $table->text('alamat_rumah'); // (str) -> Diubah ke text         
            $table->string('kode_pos', 10)->nullable(); // (int) -> Diubah ke string
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->string('nomor_hp', 30);

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