<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Skema;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Storage; 

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';
    protected $primaryKey = 'id_asesor';

    protected $fillable = [
        'id_user',
        'id_skema', // Skema Utama (One-to-Many)
        'nomor_regis',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'kebangsaan',
        'pekerjaan',
        'alamat_rumah',
        'kode_pos',
        'kabupaten_kota',
        'provinsi',
        'nomor_hp',
        'NPWP',
        'nama_bank',
        'norek',
        'ktp',
        'pas_foto',
        'NPWP_foto',
        'rekening_foto',
        'CV',
        'ijazah',
        'sertifikat_asesor',
        'sertifikasi_kompetensi',
        'tanda_tangan',
        'status_verifikasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Sumber 1: Skema Profil Utama (Kolom id_skema di tabel asesor)
     */
    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    /**
     * Sumber 2: Skema Lisensi (Tabel Pivot Transaksi_asesor_skema)
     * Sesuai file migrasi: 2025_10_30_080349_transaksi_asesor_skema.php
     */
    public function skemas()
    {
        return $this->belongsToMany(Skema::class, 'Transaksi_asesor_skema', 'id_asesor', 'id_skema');
    }

    /**
     * Sumber 3: Skema Riwayat Penugasan (Tabel Jadwal)
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_asesor', 'id_asesor');
    }
}