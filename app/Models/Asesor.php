<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Skema;
use Illuminate\Support\Facades\Storage; 

class Asesor extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'asesor';

    /**
     * Primary key untuk model.
     *
     * @var string
     */
    protected $primaryKey = 'id_asesor';

    /**
     * Atribut yang dapat diisi secara massal.
     * PERBAIKAN: 
     * 1. Mengubah 'user_id' menjadi 'id_user' sesuai controller & migrasi.
     * 2. Mengubah 'is_verified' menjadi 'status_verifikasi' sesuai controller.
     * @var array
     */
    protected $fillable = [
        'id_user',          // FIXED: Disesuaikan dengan foreign key di DB
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
        'status_verifikasi', // FIXED: Disesuaikan dengan kolom DB
    ];

    /**
     * Relasi one-to-one ke model User.
     * PERBAIKAN: Foreign key disesuaikan menjadi 'id_user'
     */
    public function user()
    {
        // Parameter: (Model Tujuan, Foreign Key di tabel ini, Primary Key di tabel tujuan)
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi many-to-many ke model Skema.
     */
    public function skemas()
    {
        return $this->belongsToMany(Skema::class, 'Transaksi_asesor_skema', 'id_asesor', 'id_skema');
    }
}