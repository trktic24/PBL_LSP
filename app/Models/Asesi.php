<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
>>>>>>> origin/kelompok_1

class Asesi extends Model
{
    use HasFactory;

<<<<<<< HEAD
    // Menentukan nama tabel yang sesuai dengan migrasi
    protected $table = 'asesi';

    // Menentukan primary key yang sesuai dengan migrasi (id_asesi)
    protected $primaryKey = 'id_asesi';

    // Menentukan kolom yang dapat diisi (fillable) untuk operasi mass assignment
=======
    protected $table = 'asesi';
    protected $primaryKey = 'id_asesi';

>>>>>>> origin/kelompok_1
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

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke data pekerjaan
    public function pekerjaan(): HasOne
    {
        return $this->hasOne(DataPekerjaanAsesi::class, 'id_asesi', 'id_asesi');
    }
}
