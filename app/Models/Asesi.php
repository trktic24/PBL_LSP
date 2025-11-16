<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesi extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang sesuai dengan migrasi
    protected $table = 'asesi';

    // Menentukan primary key yang sesuai dengan migrasi (id_asesi)
    protected $primaryKey = 'id_asesi';

    // Menentukan kolom yang dapat diisi (fillable) untuk operasi mass assignment
    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'kebangsaan',
        'pendidikan',
        'pekerjaan',
        'alamat_rumah',
        'kode_pos',
        'kabupaten_kota',
        'provinsi',
        'nomor_hp',
        'tanda_tangan',
    ];

    // Menentukan tipe data untuk kolom tertentu (optional, tapi disarankan)
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relasi ke Model User (sesuai foreignId id_user)
     */
    public function user()
    {
        // Sesuaikan jika nama foreign key di model 'User' adalah 'id_user'
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}