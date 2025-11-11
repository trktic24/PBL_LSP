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
    $table->id('id_asesi');

    $table->foreignId('id_user')->constrained('users', 'id_user')->onUpdate('cascade')->onDelete('restrict');

    $table->string('nama_lengkap')->nullable();
    $table->string('nik', 16)->nullable()->unique();
    $table->string('tempat_lahir', 100)->nullable();
    $table->date('tanggal_lahir')->nullable();
    $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->comment('Pilih jenis kelamin anda');
    $table->string('kebangsaan', 100)->nullable();
    $table->string('pendidikan')->nullable();
    $table->string('pekerjaan')->nullable();

    $table->text('alamat_rumah')->nullable();
    $table->string('kode_pos', 10)->nullable();
    $table->string('kabupaten_kota')->nullable();
    $table->string('provinsi')->nullable();
    $table->string('nomor_hp', 16)->nullable();
    $table->string('tanda_tangan')->nullable()->comment('Path ke file tanda tangan');

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
