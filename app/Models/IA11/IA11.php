<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IA11 extends Model
{
    use HasFactory;

    protected $table = 'ia11';
    protected $primaryKey = 'id_ia11';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'nama_produk',
        'rancangan_produk',
        'standar_industri',
        'tanggal_pengoperasian',
        'gambar_produk',
        'rekomendasi',
        'tuk_type',
        'tanggal_asesmen',
        'spesifikasi_umum',
        'dimensi_produk',
        'bahan_produk',
        'spesifikasi_teknis',
        'h1a_hasil',
        'p1a_pencapaian',
        'h1b_hasil',
        'p1b_pencapaian',
        'h2a_hasil',
        'p2a_pencapaian',
        'h3a_hasil',
        'p3a_pencapaian',
        'h3b_hasil',
        'p3b_pencapaian',
        'h3c_hasil',
        'p3c_pencapaian',
        'rekomendasi_kelompok',
        'rekomendasi_unit',
        'ttd_asesi',
        'ttd_asesor',
        'catatan_asesor',
        'penyusun_nama_1',
        'penyusun_nomor_met_1',
        'penyusun_ttd_1',
        'penyusun_nama_2',
        'penyusun_nomor_met_2',
        'penyusun_ttd_2',
        'validator_nama_1',
        'validator_nomor_met_1',
        'validator_ttd_1',
        'validator_nama_2',
        'validator_nomor_met_2',
        'validator_ttd_2',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pengoperasian' => 'date',
        'tanggal_asesmen' => 'date',
        'h1a_hasil' => 'boolean',
        'p1a_pencapaian' => 'boolean',
        'h1b_hasil' => 'boolean',
        'p1b_pencapaian' => 'boolean',
        'h2a_hasil' => 'boolean',
        'p2a_pencapaian' => 'boolean',
        'h3a_hasil' => 'boolean',
        'p3a_pencapaian' => 'boolean',
        'h3b_hasil' => 'boolean',
        'p3b_pencapaian' => 'boolean',
        'h3c_hasil' => 'boolean',
        'p3c_pencapaian' => 'boolean',
    ];

    // ================= RELATIONSHIPS =================

    /**
     * Get the data sertifikasi asesi that owns the IA11 record.
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(\App\Models\DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    }
}
