<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IA11 extends Model
{
    use HasFactory;

    protected $table = 'ia11';
    protected $primaryKey = 'id_ia11';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'rancangan_produk',
        'nama_produk',
        'standar_industri',
        'tanggal_pengoperasian',
        'gambar_produk',
        'rekomendasi'
    ];

    protected $casts = [
        'tanggal_pengoperasian' => 'date'
    ];

    // ================= RELATIONSHIPS =================

    public function dataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(\App\Models\DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    }

    public function spesifikasiProduk(): HasOne
    {
        return $this->hasOne(SpesifikasiProdukIA11::class, 'id_ia11');
    }

    public function bahanProduk(): HasMany
    {
        return $this->hasMany(BahanProdukIA11::class, 'id_ia11');
    }

    public function spesifikasiTeknis(): HasMany
    {
        return $this->hasMany(SpesifikasiTeknisIA11::class, 'id_ia11');
    }

    public function pencapaianSpesifikasi(): HasMany
    {
        return $this->hasMany(PencapaianSpesifikasiIA11::class, 'id_ia11');
    }

    public function pencapaianPerforma(): HasMany
    {
        return $this->hasMany(PencapaianPerformaIA11::class, 'id_ia11');
    }

    // ============ MASTER RELATIONS (M:M) =============

    public function spesifikasiMaster(): BelongsToMany
    {
        return $this->belongsToMany(
            SpesifikasiIA11::class,
            'pencapaian_spesifikasi_ia11',
            'id_ia11',
            'id_spesifikasi_ia11'
        )->withPivot(['hasil_reviu','catatan_temuan']);
    }

    public function performaMaster(): BelongsToMany
    {
        return $this->belongsToMany(
            PerformaIA11::class,
            'pencapaian_performa_ia11',
            'id_ia11',
            'id_performa_ia11'
        )->withPivot(['hasil_reviu','catatan_temuan']);
    }
}
