<?php

namespace App\Models;

use App\Models\Asesor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon; // PENTING: Import Carbon
class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_jenis_tuk',
        'id_tuk',
        'id_skema',
        'id_asesor',
        'sesi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pelaksanaan',
        'Status_jadwal',
        'kuota_maksimal',
        'kuota_minimal',
    ];

    /**
     * Kosongkan/hapus protected $casts untuk kolom datetime.
     * Kita menggunakan Custom Accessor di bawah untuk Null Safety yang lebih baik.
     */
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'date',
        'waktu_mulai' => 'datetime',
        // Dibiarkan kosong/dihapus karena kita menggunakan Accessor untuk null safety
    ];

    // --- CUSTOM ACCESSORS UNTUK NULL SAFETY (WAJIB DIBERIKAN UNTUK SEMUA KOLOM TANGGAL/WAKTU) ---

    public function getTanggalMulaiAttribute($value)
    {
        // Jika nilai (dari DB) kosong (null atau string kosong), kembalikan null. Jika tidak, parse.
        return $value ? Carbon::parse($value) : null;
    }

    public function getTanggalSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getTanggalPelaksanaanAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
    
    public function getWaktuMulaiAttribute($value)
    {
        // Accessor ini sekarang menangani waktu_mulai yang mungkin kosong.
        return $value ? Carbon::parse($value) : null;
    }

    // --- Relasi ke Parent (Many-to-One) ---

    public function jenisTuk(): BelongsTo
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    public function tuk(): BelongsTo
    {
        return $this->belongsTo(MasterTuk::class, 'id_tuk', 'id_tuk'); 
    }

    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function dataSertifikasiAsesi(): HasMany
    {
        // Jadwal (id_jadwal) memiliki banyak DataSertifikasiAsesi
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Alias for dataSertifikasiAsesi to match controller usage ($jadwal->asesi)
     */
    public function asesi(): HasMany
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_jadwal', 'id_jadwal');
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor'
        );
    }
}
