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
            $table->foreignId('user_id')
                  ->constrained('users', 'id_user') // <-- Tambahkan 'id_user' di sini
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

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

            // Informasi Bank
            $table->string('nama_bank', 100);
            $table->string('norek', 20);

            // Atribut untuk path file
            $table->string('ktp')->comment('Path ke file KTP');
            $table->string('pas_foto')->comment('Path ke file pas foto');
            $table->string('NPWP_foto')->comment('Path ke file foto NPWP');
            $table->string('rekening_foto')->comment('Path ke file foto rekening');
            $table->string('CV')->comment('Path ke file CV');
            $table->string('ijazah')->comment('Path ke file ijazah');
            $table->string('sertifikat_asesor')->comment('Path ke file sertifikat');
            $table->string('sertifikasi_kompetensi')->comment('Path ke file sertifikasi');
            $table->string('tanda_tangan')->comment('Path ke file tanda tangan');
            $table->boolean('is_verified')->default(false)->comment('Status verifikasi asesor');

            // Standar timestamp seperti di tabel users
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