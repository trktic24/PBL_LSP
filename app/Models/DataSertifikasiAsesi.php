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
use App\Models\Ia02; // Tambahkan ini agar tidak error di relasi
use App\Models\Ia07;
use App\Models\JawabanIa06;
use App\Models\LembarJawabIa05;
use App\Models\ResponBuktiAk01;
use App\Models\DaftarHadirAsesi;
use App\Models\KomentarAk05;
use App\Models\ResponApl2Ia01;

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

    // --- RELASI (Saya rapihkan return type-nya biar standar) ---

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
        // Perbaikan: Pakai Ia02::class (Huruf besar)
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
     * Mapping Data Real -> Angka Level (10, 20, 30, 40, 90, 100)
     */
    public function getLevelStatusAttribute()
    {
        // 1. CEK FINISH: Level 100 (Sertifikat Terbit / Keputusan AK.02 Ada)
        if (!is_null($this->rekomendasi_hasil_asesmen_AK02)) {
            return 100; 
        }

        // 2. CEK ASESMEN SELESAI: Level 90 (Siap isi AK.02)
        // Logika Khusus IA.10 (Sesuai kode kamu)
        $jmlChecklist = PertanyaanIa10::where('id_data_sertifikasi_asesi', $this->id_data_sertifikasi_asesi)->count();
        $jmlEssay = 7; 
        $totalSoal = $jmlChecklist + $jmlEssay;
        $jawabanValid = 0;

        $headerIa10 = $this->ia10; // Menggunakan relasi yang sudah ada biar hemat query
        
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

        // Jika IA.10 Selesai, kita anggap Level 90 (Siap Keputusan)
        // Catatan: Jika ingin menambah syarat IA.02/IA.05 harus selesai juga, tambahkan && di sini.
        if ($totalSoal > 0 && $jawabanValid >= $totalSoal) {
            return 90; 
        }

        // 3. CEK SIAP ASESMEN: Level 40 (AK.01 Valid)
        // Menggunakan relasi hasMany untuk cek apakah ada respon 'Valid'
        $ak01Valid = $this->responbuktiAk01()->where('respon', 'Valid')->exists();

        // Syarat masuk Asesmen: APL.02 Diterima DAN AK.01 Valid
        if ($ak01Valid && $this->rekomendasi_apl02 == 'diterima') {
            return 40; // Tracker akan membuka bagian Asesmen (IA.xx)
        }

        // 4. CEK SIAP AK.01: Level 30 (APL.02 Diterima)
        if ($this->rekomendasi_apl02 == 'diterima') {
            return 30; // Tracker membuka tombol Verifikasi AK.01
        }

        // 5. CEK SEDANG ISI APL.02: Level 20 (APL.01 Diterima)
        if ($this->rekomendasi_apl01 == 'diterima') {
            return 20; // Tracker membuka tombol Verifikasi APL.02
        }

        // 6. DEFAULT: Level 10 (Baru Submit APL.01 / Menunggu Verifikasi APL.01)
        return 10; 
    }
}