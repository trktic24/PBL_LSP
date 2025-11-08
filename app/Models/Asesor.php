<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  
use App\Models\Skema; 
use Illuminate\Support\Facades\Storage; // Pastikan Storage di-import

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
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
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
        'is_verified',
    ];

    /**
     * Relasi one-to-one ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Relasi many-to-many ke model Skema.
     * Ini adalah perbaikan utamanya.
     */
    public function skemas()
    {
        return $this->belongsToMany(Skema::class, 'Transaksi_asesor_skema', 'id_asesor', 'id_skema');
    }
}