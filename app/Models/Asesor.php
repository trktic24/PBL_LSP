<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';
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
        'provinsi',
        'nomor_hp',
        'NPWP',
        'nama_bank',
        'norek',
        'ktp',
        'pas_foto',
        'NPWP_foto',
        'rekening_foto',
        'CV',
        'ijazah',
        'sertifikat_asesor',
        'sertifikasi_kompetensi',
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