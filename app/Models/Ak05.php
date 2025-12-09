<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ak05 extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'ak05';

    /**
     * Primary key yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_ak05';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'aspek_negatif_positif',
        'penolakan_hasil_asesmen',
        'saran_perbaikan',
    ];    
    
}
