<?php

namespace App\Models;

use App\Models\Asesi;
use App\Models\Jadwal;
use App\Models\BuktiDasar;
use App\Models\BuktiKelengkapan;
use App\Models\Ia02;

// Pastikan semua model yang direlasikan di-import
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'status_sertifikasi',
    ];

    /**
     * Atribut yang tidak dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Atribut yang dapat diisi.
     */

    // Tahap Pendaftaran & Bayar (Asesi)
    public const STATUS_SEDANG_MENDAFTAR = 'sedang_mendaftar';
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
     * Mendapatkan semua data IA07 yang terkait dengan data sertifikasi ini.
     */
    public function ia07(): HasMany
    {
        // Tentukan foreign key dan local key karena tidak standar
        return $this->hasMany(Ia07::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function responbuktiAk01(): HasMany
    {
        // Tentukan foreign key dan local key karena tidak standar
        return $this->hasMany(ResponBuktiAk01::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }    

    /**
     * ACCESSOR: Menghitung 'Level Virtual' berdasarkan isi kolom database.
     * Logika ini MAPPING dari DATA NYATA -> ANGKA LEVEL VIRTUAL.
     */
    public function getLevelStatusAttribute()
    {
        // --- FASE AKHIR (Level 100 - 80) ---
        
        // 1. Level 100: Sertifikat Terbit (AK-05 sudah ada rekomendasi)
        if ($this->rekomendasi_hasil_asesmen_AK02 == 'kompeten') {
            return 100; // $LVL_SERTIFIKAT
        }

        // 1. Target Soal (Misal: 10 Soal)
        $idSkema = $this->jadwal->id_skema; 
        $totalSoal = Ia10::where('id_data_sertifikasi_asesi', $this->id_data_sertifikasi_asesi)->count(); 

        // 2. Hitung Jawaban Asesi yang VALID (Isinya tidak kosong)
        // Di sini kita pakai ide kamu: Kita filter hanya yang kolomnya TERISI.
        $jawabanValid = ResponIa10::where('id_data_sertifikasi_asesi', $this->id_data_sertifikasi_asesi)
                            ->where(function($query) {
                                // Logic: Dianggap terisi jika (Ya/Tidak dipilih) ATAU (Isian ada textnya)
                                $query->where('jawaban_pilihan_iya', 1)
                                    ->orWhere('jawaban_pilihan_tidak', 1)
                                    ->orWhereNotNull('jawaban_isian');
                            })
                            ->count();

        // 3. Bandingkan
        // Apakah jumlah jawaban valid SAMA DENGAN jumlah soal?
        if ($jawabanValid > 0 && $jawabanValid >= $totalSoal) {
            return 90; // ASESMEN SELESAII YEEE
        }

        // --- FASE ASESMEN REAL (Level 70) ---
        
        // 4. Level 70: Sedang Ujian
        // Asumsi: Kalau AK-02 belum diisi TAPI AK-01 (Persetujuan) sudah ada (catatan_asesi_AK03 atau kolom lain yang relevan)
        // TAPI, karena di tabelmu tidak ada kolom spesifik "status_AK01", kita pakai logika:
        // "Kalau APL-02 sudah diterima, berarti sudah masuk fase Asesmen/Persiapan Asesmen"
        
        // Kita perlu cek lebih detail untuk membedakan level 30, 45, 50, 70.
        // Karena kolom terbatas, kita pakai indikator yang ada.

        $ak01valid = ResponBuktiAk01::where('id_data_sertifikasi_asesi', $this->id_data_sertifikasi_asesi)
                                ->where('respon', 'Valid')                    
                                ->exists();        

        if ($ak01valid && $this->rekomendasi_apl02 == 'diterima') {
            return 40; // LANJUT KE ASESMENN
        }


        // Cek Level 25 (APL-02 Verifikasi) -> "diterima"
        if ($this->rekomendasi_apl02 == 'diterima') {
            // Jika APL-02 diterima, kita anggap sudah melewati fase verifikasi APL-02.
            // Karena tidak ada kolom khusus TUK atau AK-01 di tabel utama ini (mungkin ada di tabel lain atau hardcode flow),
            // Kita bisa return level tertinggi sebelum Asesmen Real.
            
            // Opsional: Cek tabel lain (TUK/AK01) disini kalau mau presisi level 30/45/50.
            // Tapi untuk simpelnya, jika APL-02 OK, anggap siap Asesmen Real (atau setidaknya TUK).
            return 30; // $LVL_VERIF_TUK (Minimal sampai sini kalau APL-02 beres)
        }

        // Cek Level 20 (APL-02 Submit) -> Tapi kolom rekomendasi masih NULL
        // Susah dicek dari tabel ini saja karena tidak ada kolom "tanggal_submit_apl02".
        // Tapi kita bisa berasumsi: Jika APL-01 diterima, user ada di tahap APL-02.
        
        // Cek Level 15 (Verifikasi Admin / APL-01 Diterima)
        if ($this->rekomendasi_apl01 == 'diterima') {
            // Jika APL-01 diterima, tapi APL-02 belum diterima (null/tidak),
            // berarti user sedang di tahap mengisi APL-02 (Level 20).
            return 20; // $LVL_SUBMIT_APL02
        }

        // Cek Level 10 (Submit APL-01)
        // Jika data ini ada (created), berarti minimal level 10.
        // Tapi rekomendasi_apl01 masih null.
        if ($this->rekomendasi_apl01 == null) {
            return 10; // $LVL_SUBMIT_APL01 (Menunggu Verifikasi Admin)
        }

        // Tambahan logic biar jelas (Opsional)
        if ($this->rekomendasi_apl01 == 'tidak_diterima' || $this->rekomendasi_apl01 == 'ditolak') {
            return 0; // Atau return -1 biar beda, artinya GAGAL APL-01
        }

        // Jika sampai sini, berarti ada status aneh yang tidak terhandle
        return 0; // Default Unknown
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

    public function lembarJawabIA05(): HasMany
    {
        return $this->hasMany(LembarJawabIA05::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function responAk04(): HasMany
    {
        return $this->hasMany(ResponAk04::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
    
    // shortcut relasi untuk ia03
    public function getAsesorAttribute()
    {
        return $this->jadwal?->asesor;
    }
    
    public function getSkemaAttribute()
    {
        return $this->jadwal?->skema;
    }

    public function getTukAtrribute()
    {
        return $this->jadwal?->tuk;
    }

    public function getJenisTukAttribute()
    {
        return $this->jadwal?->jenisTuk;
    }

    public function getTanggalPelaksanaanAttribute()
    {
        return $this->jadwal?->tanggal_pelaksanaan;
    }

     public function Ia02(): HasMany
    {
        return $this->hasMany(Ia02::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function daftarHadir()
    {
        return $this->hasOne(DaftarHadir::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function getIsSudahHadirAttribute()
    {
        // Cek apakah relasi ada DAN kolom ttd terisi
        return $this->daftarHadir && !empty($this->daftarHadir->tanda_tangan_asesi);
    }
}
