<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ia10 extends Model
{
    use HasFactory;
    
    // Tentukan nama tabel & primary key sesuai migrasi Anda
    protected $table = 'ia10';
    protected $primaryKey = 'id_ia10';
    protected $guarded = [];

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'pertanyaan',
    ];    
}
