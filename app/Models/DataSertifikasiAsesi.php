<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSertifikasiAsesi extends Model
{
    use HasFactory;

    protected $table = 'data_sertifikasi_asesi';
    protected $primaryKey = 'id_data_sertifikasi_asesi';

    /**
     * Atribut yang dapat diisi.
     */
    protected $fillable = [
        'id_asesi',
        'id_jadwal',
        'tujuan_asesmen',
        'tujuan_asesmen_lainnya', // Kolom baru kita
        'tanggal_daftar',
        'rekomendasi_apl01',
        'rekomendasi_apl02',
        'karakteristik_kandidat',
        'kebutuhan_kontekstualisasi_terkait_tempat_kerja',
        'Saran_yang_diberikan_oleh_paket_pelatihan',
        'penyesuaian_perangkat_asesmen',
        'peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen',
        'feedback_ia01',
        'rekomendasi_IA04B',
        'rekomendasi_hasil_asesmen_AK02',
        'tindakan_lanjut_AK02',
        'komentar_AK02',
        'catatan_asesi_AK03',
        'rekomendasi_AK05',
        'keterangan_AK05',
        'aspek_dalam_AK05',
        'catatan_penolakan_AK05',
        'saran_dan_perbaikan_AK05',
        'catatan_AK05',
        'rekomendasi1_AK06',
        'rekomendasi2_AK06',
    ];

    /**
     * Atribut yang harus di-cast.
     */
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    /**
     * Relasi ke Asesi (parent)
     */
    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    // /**
    //  * Relasi ke Jadwal (parent)
    //  */
    // public function jadwal(): BelongsTo
    // {
    //     return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    // }

    /**
     * Relasi ke BuktiKelengkapan (children)
     */
    public function buktiKelengkapan(): HasMany
    {
        return $this->hasMany(BuktiKelengkapan::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}