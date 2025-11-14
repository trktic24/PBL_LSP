<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;
=======
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';
<<<<<<< HEAD
    protected $primaryKey = 'id_asesor';

    protected $fillable = [
        'id_skema',
        'id_user',
        'nomor_regis',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'kebangsaan',
        'pekerjaan',
        'alamat_rumah',
        'kode_pos',
        'kabupaten_kota',
=======

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
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
        'provinsi',
        'nomor_hp',
        'NPWP',
        'nama_bank',
<<<<<<< HEAD
        'norek',
=======
        'norek', // Mapping dari 'nomor_rekening'
        'pekerjaan', // Mapping dari 'pekerjaan_asesor'
        // Path file (nama samain sama migration)
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
        'ktp',
        'pas_foto',
        'NPWP_foto',
        'rekening_foto',
        'CV',
        'ijazah',
        'sertifikat_asesor',
        'sertifikasi_kompetensi',
<<<<<<< HEAD
        'tanda_tangan',
        'is_verified',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jenis_kelamin' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /** Relasi ke User (Parent) */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /** Relasi ke Skema (Parent) */
    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    /** Relasi ke Jadwal (Children) */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_asesor', 'id_asesor');
    }
}
=======
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
    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema'); // FK, Owner Key
    }
}
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
