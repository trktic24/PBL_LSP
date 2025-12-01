<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;  

use Illuminate\Database\Eloquent\Relations\HasOne;

class Asesi extends Model
{
    use HasFactory;

    protected $table = 'asesi';
    protected $primaryKey = 'id_asesi';
    protected $guarded = [];


    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'kebangsaan',
        'pendidikan',
        'pekerjaan',
        'alamat_rumah',
        'kode_pos',
        'kabupaten_kota',
        'provinsi',
        'nomor_hp',
        'tanda_tangan',
    ];

    /**
     * Tentukan kolom yang harus diperlakukan sebagai tanggal.
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function dataPekerjaan()
    {
        return $this->hasOne(DataPekerjaanAsesi::class, 'id_asesi', 'id_asesi');
    }
    
    /**
     * Relasi: Asesi bisa punya banyak data sertifikasi (history).
     */
    public function dataSertifikasi()
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_asesi', 'id_asesi');
    }
}
