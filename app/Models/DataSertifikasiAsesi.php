<?php

namespace App\Models;

use App\Models\Asesi;
use App\Models\Jadwal;
use App\Models\BuktiDasar;
use App\Models\BuktiKelengkapan;
use App\Models\Ia02;
use App\Models\Ak02;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\DetailIa10;
use App\Models\PertanyaanIa10;
use App\Models\Ia10;
use App\Models\Ia07;
use App\Models\JawabanIa06;
use App\Models\LembarJawabIA05;
use App\Models\ResponBuktiAk01;
use App\Models\ResponPotensiAK07;
use App\Models\ResponDiperlukanPenyesuaianAK07;
use App\Models\HasilPenyesuaianAK07;
use App\Models\DaftarHadirAsesi;
use App\Models\KomentarAk05;
use App\Models\ResponApl02Ia01;
use App\Models\Skema; // Tambahan
use App\Models\PenyusunValidator;

class DataSertifikasiAsesi extends Model
{
    use HasFactory;

    protected $table = 'data_sertifikasi_asesi';
    protected $primaryKey = 'id_data_sertifikasi_asesi';

    protected $fillable = [
        'id_asesi',
        'id_jadwal',
        'rekomendasi_apl01',
        'rekomendasi_mapa01',
        'rekomendasi_mapa02',
        'rekomendasi_ak01',
        'rekomendasi_ia02',
        'rekomendasi_ia05',
        'rekomendasi_ia06',
        'rekomendasi_ia07',
        'rekomendasi_ia10',
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
        'status_validasi',
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
        'status_sertifikasi',
        'respon_bukti_ak01', // <--- SAYA SUDAH TAMBAHKAN INI (WAJIB ADA)
    ];

    /**
     * Atribut yang tidak dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    // ... (SISA KODE KE BAWAH BIARKAN SAMA, TIDAK PERLU DIUBAH) ...
    
    public const STATUS_SEDANG_MENDAFTAR = 'sedang_mendaftar';
    public const STATUS_PENDAFTARAN_SELESAI = 'pendaftaran_selesai';
    public const STATUS_TUNGGU_VERIFIKASI_BAYAR = 'menunggu_verifikasi_bayar';
    public const STATUS_PEMBAYARAN_LUNAS = 'pembayaran_lunas';
    public const STATUS_PRA_ASESMEN_SELESAI = 'pra_asesmen_selesai';
    public const STATUS_PERSETUJUAN_ASESMEN_OK = 'persetujuan_asesmen_disetujui';
    public const STATUS_ASESMEN_PRAKTEK_SELESAI = 'asesmen_praktek_selesai';
    public const STATUS_ASESMEN_LISAN_SELESAI = 'asesmen_lisan_selesai';
    public const STATUS_UMPAN_BALIK_SELESAI = 'umpan_balik_selesai';
    public const STATUS_BANDING_SELESAI = 'banding_selesai';
    public const STATUS_DIREKOMENDASIKAN = 'direkomendasikan';
    public const STATUS_TIDAK_DIREKOMENDASIKAN = 'tidak_direkomendasikan';

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function getProgresLevelAttribute(): int
    {
        return match ($this->status_sertifikasi) {
            self::STATUS_SEDANG_MENDAFTAR => 5,
            self::STATUS_PENDAFTARAN_SELESAI => 10,
            self::STATUS_TUNGGU_VERIFIKASI_BAYAR => 20,
            self::STATUS_PEMBAYARAN_LUNAS => 30,
            self::STATUS_PRA_ASESMEN_SELESAI => 40,
            self::STATUS_PERSETUJUAN_ASESMEN_OK => 50,
            self::STATUS_ASESMEN_PRAKTEK_SELESAI => 60,
            self::STATUS_ASESMEN_LISAN_SELESAI => 70,
            self::STATUS_UMPAN_BALIK_SELESAI => 80,
            self::STATUS_BANDING_SELESAI => 90,
            self::STATUS_DIREKOMENDASIKAN => 100,
            self::STATUS_TIDAK_DIREKOMENDASIKAN => 101,
            default => 0,
        };
    }

    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    public function ia07(): HasMany
    {
        return $this->hasMany(Ia07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia01(): HasOne
    {
        return $this->hasOne(ResponApl02Ia01::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia10(): HasOne
    {
        return $this->hasOne(Ia10::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia02(): HasOne
    {
        return $this->hasOne(Ia02::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia04(): HasOne
    {
        return $this->hasOne(ResponIA04A::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia08(): HasOne
    {
        return $this->hasOne(\App\Models\Ia08::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia09(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            BuktiPortofolioIA08IA09::class,
            DataPortofolio::class,
            'id_data_sertifikasi_asesi',
            'id_portofolio',
            'id_data_sertifikasi_asesi',
            'id_portofolio'
        );
    }

    public function ia06Answers(): HasMany
    {
        return $this->hasMany(JawabanIa06::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }



    public function responBuktiAk01(): HasMany
    {
        return $this->hasMany(ResponBuktiAk01::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function presensi(): HasOne
    {
        return $this->hasOne(DaftarHadirAsesi::class, 'id_data_sertifikasi_asesi');
    }

    public function komentarAk05(): HasOne
    {
        return $this->hasOne(KomentarAk05::class, 'id_data_sertifikasi_asesi');
    }

    public function responApl02Ia01(): HasMany
    {
        return $this->hasMany(ResponApl02Ia01::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function lembarJawabIa05(): HasMany
    {
        return $this->hasMany(LembarJawabIA05::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function responPotensiAk07(): HasMany
    {
        return $this->hasMany(ResponPotensiAK07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function responPenyesuaianAk07(): HasMany
    {
        return $this->hasMany(ResponDiperlukanPenyesuaianAK07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function hasilPenyesuaianAk07(): HasOne
    {
        return $this->hasOne(HasilPenyesuaianAK07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function getLevelStatusAttribute()
    {
        if (!is_null($this->rekomendasi_hasil_asesmen_AK02)) {
            return 100;
        }

        $jmlChecklist = PertanyaanIa10::where('id_data_sertifikasi_asesi', $this->id_data_sertifikasi_asesi)->count();
        $jmlEssay = 7;
        $totalSoal = $jmlChecklist + $jmlEssay;
        $jawabanValid = 0;

        $headerIa10 = $this->ia10;

        if ($headerIa10) {
            $isiChecklist = PertanyaanIa10::where('id_data_sertifikasi_asesi', $this->id_data_sertifikasi_asesi)
                ->whereNotNull('jawaban_pilihan_iya_tidak')
                ->count();

            $isiEssay = DetailIa10::where('id_ia10', $headerIa10->id_ia10)
                ->whereNotNull('jawaban')
                ->where('jawaban', '!=', '')
                ->count();

            $jawabanValid = $isiChecklist + $isiEssay;
        }

        if ($totalSoal > 0 && $jawabanValid >= $totalSoal) {
            return 90;
        }

        $ak01Valid = $this->responbuktiAk01()->where('respon', 'Valid')->exists();

        if ($ak01Valid && $this->rekomendasi_apl02 == 'diterima') {
            return 40;
        }

        if ($this->rekomendasi_apl02 == 'diterima') {
            return 30;
        }

        if ($this->rekomendasi_apl01 == 'diterima') {
            return 20;
        }

        return 10;
    }

    public function getUserAttribute()
    {
        if ($this->relationLoaded('asesi') && $this->asesi && $this->asesi->relationLoaded('user')) {
            return $this->asesi->user;
        }
        return $this->asesi ? $this->asesi->user : null;
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function buktiKelengkapan(): HasMany
    {
        return $this->hasMany(BuktiKelengkapan::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function buktiDasar(): HasMany
    {
        return $this->hasMany(BuktiDasar::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function responAk04(): HasMany
    {
        return $this->hasMany(ResponAk04::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function getAsesorAttribute()
    {
        return $this->jadwal?->asesor;
    }

    public function getSkemaAttribute()
    {
        return $this->jadwal?->skema;
    }

    public function getTukAttribute()
    {
        return $this->jadwal?->masterTuk;
    }

    public function getJenisTukAttribute()
    {
        return $this->jadwal?->jenisTuk;
    }

    public function getTanggalPelaksanaanAttribute()
    {
        return $this->jadwal?->tanggal_pelaksanaan;
    }

    public function daftarHadir()
    {
        return $this->hasOne(DaftarHadir::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function getIsSudahHadirAttribute()
    {
        return $this->daftarHadir && !empty($this->daftarHadir->tanda_tangan_asesi);
    }

    public function portofolio(): HasMany
    {
        return $this->hasMany(DataPortofolio::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function penyusunValidator(): HasOne
    {
        return $this->hasOne(PenyusunValidator::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
    
    public function ak02(): HasMany
    {
        return $this->hasMany(Ak02::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function asesmenMandiri()
    {
        return $this->hasMany(\App\Models\ResponApl02Ia01::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}