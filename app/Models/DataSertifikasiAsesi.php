<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataSertifikasiAsesi extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai migrasi.
     */
    protected $table = 'data_sertifikasi_asesi';

    /**
     * Primary key kustom sesuai migrasi.
     */
    protected $primaryKey = 'id_data_sertifikasi_asesi';

    /**
     * Kolom tanggal yang harus di-cast otomatis oleh Laravel.
     */
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    /**
     * Daftar kolom yang aman untuk diisi (Mass Assignable).
     * Sesuai dengan file migrasi Anda.
     */
    protected $fillable = [
        'id_asesi',
        'id_jadwal',
        'rekomendasi_apl01',
        'tujuan_asesmen',
        'rekomendasi_apl02',
        'tanggal_daftar',
        'jawaban_mapa01',
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
        'status_sertifikasi',
    ];

    /**
     * Relasi: Data ini milik satu Asesi.
     * Kita bisa ambil nama, nik, dll dari sini.
     */
    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    /**
     * Relasi: Data ini milik satu Jadwal.
     */
    public function jadwal(): BelongsTo
    {
        // Pastikan nama modelnya Schedule (sesuai controller Anda) atau Jadwal
        return $this->belongsTo(Schedule::class, 'id_jadwal', 'id_jadwal');
    }
}