<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'kuota_maksimal',
        'kuota_minimal',
        'sesi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pelaksanaan',
        'waktu_mulai',
        'Status_jadwal',
    ];

    /**
     * Kosongkan/hapus protected $casts untuk kolom datetime.
     * Kita menggunakan Custom Accessor di bawah untuk Null Safety yang lebih baik.
     */
    protected $casts = [
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

    // --- RELASI (Relationships) ---

    public function jenisTuk()
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    public function masterTuk()
    {
        return $this->belongsTo(MasterTuk::class, 'id_tuk', 'id_tuk');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }

    public function asesi()
    {
        return $this->belongsToMany(Asesi::class, 'data_sertifikasi_asesi', 'id_jadwal', 'id_asesi');
    }
}