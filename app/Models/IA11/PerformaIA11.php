<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformaIA11 extends Model
{
    use HasFactory;

    protected $table = 'performa_ia11';
    protected $primaryKey = 'id_performa_ia11';
    public $timestamps = true;

    protected $fillable = [
        'deskripsi_performa',
    ];

    public function pencapaianPerforma(): HasMany
    {
        return $this->hasMany(
            PencapaianPerformaIA11::class,
            'id_performa_ia11',
            'id_performa_ia11'
        );
    }
}
