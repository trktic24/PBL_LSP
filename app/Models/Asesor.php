<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- TAMBAHKAN INI
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Skema; // <--- PASTIKAN MODEL INI ADA
use App\Models\AsesorSkemaTransaksi;

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
    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema'); // FK, Owner Key
    }
    

    /**
     * Relasi Many-to-Many dengan model Skema.
     * (Asesor punya BANYAK Skema melalui tabel pivot 'Transaksi_asesor_skema')
     */
    public function skemas(): BelongsToMany
    {
        // belongsToMany(ModelTerkait, NamaTabelPivot, FKModelIniDiPivot, FKModelTerkaitDiPivot)
        return $this->belongsToMany(Skema::class, 'Transaksi_asesor_skema', 'id_asesor', 'id_skema')
                    // Menggunakan model pivot khusus untuk mengakses kolom tambahan
                    ->using(AsesorSkemaTransaksi::class)
                    // Karena tabel pivot memiliki timestamps
                    ->withTimestamps();
    }
    
    /**
     * Relasi One-to-Many ke model pivot (AsesorSkemaTransaksi).
     * (Opsional, untuk mengakses record transaksi secara langsung)
     */
    public function transaksiSkemaAsesor(): HasMany
    {
        return $this->hasMany(AsesorSkemaTransaksi::class, 'id_asesor', 'id_asesor');
    }
}