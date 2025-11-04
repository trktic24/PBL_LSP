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
        Schema::create('asesor', function (Blueprint $table) {
        $table->id('id_asesor');

        $table->foreignId('id_skema')->nullable()->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('restrict');
        $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onUpdate('cascade')->onDelete('restrict');

        $table->string('nomor_regis', 50)->nullable()->unique();
        $table->string('nama_lengkap')->nullable();
        $table->string('nik', 16)->nullable()->unique();
        $table->string('tempat_lahir', 100)->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->boolean('jenis_kelamin')->nullable()->comment('1 Laki-laki, 0 Perempuan');
        $table->string('kebangsaan', 100)->nullable();
        $table->string('pekerjaan')->nullable();

        $table->text('alamat_rumah')->nullable();
        $table->string('kode_pos', 10)->nullable();
        $table->string('kabupaten_kota')->nullable();
        $table->string('provinsi')->nullable();
        $table->string('nomor_hp', 14)->nullable();
        $table->string('NPWP', 25)->nullable();

        $table->string('nama_bank', 100)->nullable();
        $table->string('norek', 20)->nullable();

        $table->string('ktp')->nullable();
        $table->string('pas_foto')->nullable();
        $table->string('NPWP_foto')->nullable();
        $table->string('rekening_foto')->nullable();
        $table->string('CV')->nullable();
        $table->string('ijazah')->nullable();
        $table->string('sertifikat_asesor')->nullable();
        $table->string('sertifikasi_kompetensi')->nullable();
        $table->string('tanda_tangan')->nullable();
        $table->boolean('is_verified')->default(false);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor');
    }
};
