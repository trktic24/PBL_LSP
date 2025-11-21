<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage; // <-- PENTING: Tambahkan ini

class Asesor extends Model
{
    use HasFactory;
    protected $table = 'asesor';
    protected $primaryKey = 'id_asesor';
    protected $guarded = ['id_asesor'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // HAPUS atau GANTI FUNGSI INI:
    // public function skema()
    // {
    //     return $this->belongsTo(Skema::class, 'skema_id');
    // }

    // GANTI DENGAN FUNGSI INI:
    /**
     * Skema yang dimiliki/diampu oleh Asesor ini.
     */
    public function skema()
    {
        return $this->belongsToMany(
            Skema::class,           // Model tujuan
            'transaksi_asesor_skema', // Nama tabel pivot
            'id_asesor',            // Foreign key di pivot untuk model ini
            'id_skema'              // Foreign key di pivot untuk model tujuan
        );
    }

    public function skemas()
    {
        return $this->hasMany(Skema::class, 'id_skema');
    }

    public function jadwals()
    {
    return $this->hasMany(Jadwal::class, 'id_asesor', 'id_asesor');
    }


    /**
     * Accessor untuk mendapatkan URL Foto Profil yang aman.
     * Cara panggil di blade: $asesor->url_foto
     */
    public function getUrlFotoAttribute()
    {
        // 1. Bersihkan path dulu (buang 'public/' jika ada)
        $pathDiDatabase = $this->pas_foto;
        $cleanPath = str_replace('public/', '', $pathDiDatabase);

        // 2. LOGIKA BARU:
        // Cek jika database kosong ATAU file fisiknya TIDAK ADA di storage
        if (empty($pathDiDatabase) || !Storage::disk('public')->exists($cleanPath)) {
            // Jika kosong atau file hilang, pakai default
            return asset('images/profil_asesor.jpeg');
        }

        // 3. Jika file benar-benar ada, kembalikan URL aslinya
        return asset('storage/' . $cleanPath);
    }
}