<?php

namespace App\Models;

use App\Models\Asesi;
use App\Models\Jadwal;
use App\Models\BuktiDasar;
use App\Models\BuktiKelengkapan;

// Pastikan semua model yang direlasikan di-import
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'tujuan_asesmen_lainnya',
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

    // Tahap Pendaftaran & Bayar (Asesi)
    public const STATUS_PENDAFTARAN_SELESAI = 'pendaftaran_selesai';
    public const STATUS_TUNGGU_VERIFIKASI_BAYAR = 'menunggu_verifikasi_bayar';
    public const STATUS_PEMBAYARAN_LUNAS = 'pembayaran_lunas';
    
    // Tahap Pra-Asesmen (Asesi)
    public const STATUS_PRA_ASESMEN_SELESAI = 'pra_asesmen_selesai';
    public const STATUS_PERSETUJUAN_ASESMEN_OK = 'persetujuan_asesmen_disetujui';

    // Tahap Asesmen (Asesor)
    public const STATUS_ASESMEN_PRAKTEK_SELESAI = 'asesmen_praktek_selesai';
    public const STATUS_ASESMEN_LISAN_SELESAI = 'asesmen_lisan_selesai';
    
    // Tahap Pasca-Asesmen (Asesi)
    public const STATUS_UMPAN_BALIK_SELESAI = 'umpan_balik_selesai';
    public const STATUS_BANDING_SELESAI = 'banding_selesai'; // Jika ada banding

    // Tahap Final (Admin/Komite)
    public const STATUS_DIREKOMENDASIKAN = 'direkomendasikan'; 
    public const STATUS_TIDAK_DIREKOMENDASIKAN = 'tidak_direkomendasikan';

    /**
     * Atribut yang harus di-cast.
     */
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function getProgresLevelAttribute(): int
    {
        return match ($this->status_sertifikasi) {
            self::STATUS_PENDAFTARAN_SELESAI       => 10,
            self::STATUS_TUNGGU_VERIFIKASI_BAYAR => 20,
            self::STATUS_PEMBAYARAN_LUNAS          => 30,
            self::STATUS_PRA_ASESMEN_SELESAI       => 40,
            self::STATUS_PERSETUJUAN_ASESMEN_OK    => 50,
            self::STATUS_ASESMEN_PRAKTEK_SELESAI   => 60,
            self::STATUS_ASESMEN_LISAN_SELESAI     => 70,
            self::STATUS_UMPAN_BALIK_SELESAI       => 80,
            self::STATUS_BANDING_SELESAI           => 90,
            self::STATUS_DIREKOMENDASIKAN          => 100,
            self::STATUS_TIDAK_DIREKOMENDASIKAN    => 101,
            default => 0, // Default case kalau statusnya aneh
        };
    }

    /**
     * Relasi ke Asesi (parent)
     */
    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    /**
     * Relasi ke Jadwal (parent)
     * Ini adalah fungsi yang kita perbaiki
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Relasi ke BuktiKelengkapan (children)
     */
    public function buktiKelengkapan(): HasMany
    {
        return $this->hasMany(BuktiKelengkapan::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function buktiDasar(): HasMany
    {
        return $this->hasMany(BuktiDasar::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
    
}