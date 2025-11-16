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
            // Sesuai permintaan: id_asesor (primary) (bigint)
            $table->id('id_asesor');
            $table->foreignId('id_user')
                  ->constrained('users', 'id_user') // <-- Tambahkan 'id_user' di sini
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('id_skema')->nullable()->constrained('skema', 'id_skema')->onUpdate('cascade')->onDelete('restrict');

            // Data Pribadi Asesor
            $table->string('nomor_regis', 50)->unique();
            $table->string('nama_lengkap'); // Asumsi nama lengkap wajib diisi
            $table->string('nik', 16)->unique();

            // Informasi Pribadi Lainnya
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->comment('Pilih jenis kelamin anda');
            $table->string('kebangsaan', 100);
            $table->string('pekerjaan');

            // Alamat dan Kontak
            $table->text('alamat_rumah');
            $table->string('kode_pos', 10);
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->string('nomor_hp', 14);
            $table->string('NPWP', 25);

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
        $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->default('pending');

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
