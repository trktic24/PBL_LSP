<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// Pastikan semua Model terimport
use App\Models\DetailIa10;
use App\Models\PertanyaanIa10;
use App\Models\Ia10;
use App\Models\Ia02;
use App\Models\Ia07;
use App\Models\JawabanIa06;
use App\Models\LembarJawabIa05;
use App\Models\ResponBuktiAk01;
use App\Models\DaftarHadirAsesi;
use App\Models\KomentarAk05;
use App\Models\ResponApl2Ia01;
use App\Models\Skema; // Tambahan

class DataSertifikasiAsesi extends Model
{
    use HasFactory;

    const STATUS_PERSETUJUAN_ASESMEN_OK = 40;

    protected $table = 'data_sertifikasi_asesi';
    protected $primaryKey = 'id_data_sertifikasi_asesi';

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

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    // --- RELASI ---

    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function ia07(): HasMany
    {
        return $this->hasMany(Ia07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia10(): HasOne
    {
        return $this->hasOne(Ia10::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia02(): HasOne
    {
        return $this->hasOne(Ia02::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function ia06Answers(): HasMany
    {
        return $this->hasMany(JawabanIa06::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function lembarJawabIa05(): HasOne
    {
        return $this->hasOne(LembarJawabIa05::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function responbuktiAk01(): HasMany
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

    public function responApl2Ia01(): HasOne
    {
        return $this->hasOne(ResponApl2Ia01::class, 'id_data_sertifikasi_asesi');
    }

    /**
     * ACCESSOR: Menghitung Level Status untuk Tracker
     */
    public function getLevelStatusAttribute()
    {
        // 1. CEK FINISH
        if (!is_null($this->rekomendasi_hasil_asesmen_AK02)) {
            return 100;
        }

        // 2. CEK ASESMEN SELESAI
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

        // 3. CEK SIAP ASESMEN
        $ak01Valid = $this->responbuktiAk01()->where('respon', 'Valid')->exists();

        if (($ak01Valid || $this->status_sertifikasi >= 40) && $this->rekomendasi_apl02 == 'diterima') {
            return 40;
        }

        // 4. CEK SIAP AK.01
        if ($this->rekomendasi_apl02 == 'diterima') {
            return 30;
        }

        // 5. CEK SEDANG ISI APL.02
        if ($this->rekomendasi_apl01 == 'diterima') {
            return 20;
        }

        return 10;
    }

    /**
     * [WAJIB ADA]
     * Accessor 'skema' agar $asesi->skema di View bisa berjalan tanpa error.
     * Mengambil data skema melewati relasi 'jadwal'.
     */
    public function getUserAttribute()
    {
        // Jika relasi asesi & user sudah diload, ambil langsung
        if ($this->relationLoaded('asesi') && $this->asesi && $this->asesi->relationLoaded('user')) {
            return $this->asesi->user;
        }

        // Fallback: Ambil manual
        return $this->asesi ? $this->asesi->user : null;
    }
    public function getSkemaAttribute()
    {
        // 1. Cek apakah relasi jadwal sudah di-load sebelumnya?
        if ($this->relationLoaded('jadwal')) {
            // Ambil skema dari object jadwal yang sudah ada di memori
            return $this->jadwal->skema;
        }

        // 2. Jika belum, load manual (Lazy Loading)
        return $this->jadwal ? $this->jadwal->skema : null;
    }
}