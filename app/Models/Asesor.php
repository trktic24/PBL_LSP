<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';

    // Primary key
    protected $primaryKey = 'id_asesor';

    // Kolom yang boleh diisi (sesuaikan dengan migration & form step 2 & 3 asesor)
    protected $fillable = [
        'id_user',
        'id_skema',
        'nomor_regis', // Mapping dari 'no_registrasi_asesor'
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir', // Simpan YYYY-MM-DD
        'jenis_kelamin', // Simpan 0/1
        'kebangsaan',
        'alamat_rumah',
        'kode_pos', // <-- Ditambahkan
        'kabupaten_kota', // <-- Ditambahkan (menggantikan tempat_tinggal)
        'provinsi',
        'nomor_hp',
        'NPWP',
        'nama_bank',
        'norek', // Mapping dari 'nomor_rekening'
        'pekerjaan', // Mapping dari 'pekerjaan_asesor'
        // Path file (nama samain sama migration)
        'ktp',
        'pas_foto',
        'NPWP_foto',
        'rekening_foto',
        'CV',
        'ijazah',
        'sertifikat_asesor',
        'sertifikasi_kompetensi',
        'tanda_tangan'
    ];

    // Casting
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi: Satu Asesor dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user'); // FK, Owner Key
    }

    // Relasi: Satu Asesor punya satu Skema
    public function skema()
    {
        return $this->belongsToMany(
            Skema::class,           // Model tujuan
            'transaksi_asesor_skema', // Nama tabel pivot
            'id_asesor',            // Foreign key di pivot untuk model ini
            'id_skema'              // Foreign key di pivot untuk model tujuan
        );
    }
}