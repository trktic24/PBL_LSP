<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSertifikasiAsesi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'data_sertifikasi_asesi';

    /**
     * Primary key yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_data_sertifikasi_asesi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
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
        'rekomendasi_ia01',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    /**
     * Mendapatkan data asesi yang memiliki data sertifikasi ini.
     */
    public function asesi(): BelongsTo
    {
        // Relasi ke model Asesi (bukan User)
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    /**
     * Mendapatkan data jadwal yang memiliki data sertifikasi ini.
     */
    public function jadwal(): BelongsTo
    {
        // Tentukan foreign key dan owner key karena tidak standar
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Mendapatkan semua data IA07 yang terkait dengan data sertifikasi ini.
     */
    public function ia07(): HasMany
    {
        // Tentukan foreign key dan local key karena tidak standar
        return $this->hasMany(Ia07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia02(): HasMany
    {
        // Tentukan foreign key dan local key karena tidak standar
        return $this->hasMany(ia02::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function getSkemaAttribute()
    {
        // Logika: Ambil jadwal dulu, baru ambil skema di dalamnya
        return $this->jadwal->skema ?? null;
    }

    public function getAsesorAttribute()
    {
        return $this->jadwal->asesor ?? null;
    }

    // Relasi ke Respon Potensi (One to Many)
    public function responPotensiAk07()
    {
        return $this->hasMany(ResponPotensiAK07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    // Relasi ke Respon Penyesuaian Q1-Q7 (One to Many)
    public function responPenyesuaianAk07()
    {
        return $this->hasMany(ResponDiperlukanPenyesuaianAK07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    // Relasi ke Hasil Akhir (One to One)
    public function hasilPenyesuaianAk07()
    {
        return $this->hasOne(HasilPenyesuaianAK07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function penyusunValidator(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PenyusunValidator::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function portofolio()
    {
        return $this->hasMany(Portofolio::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function buktiPortofolioIA08IA09()
    {
        return $this->hasManyThrough(
            BuktiPortofolioIA08IA09::class,
            Portofolio::class,
            'id_data_sertifikasi_asesi', // Foreign key di tabel portofolio
            'id_portofolio',             // Foreign key di tabel bukti_portofolio_ia08_ia09
            'id_data_sertifikasi_asesi', // Local key di tabel data_sertifikasi_asesi
            'id_portofolio'              // Local key di tabel portofolio
        );
    }
}
