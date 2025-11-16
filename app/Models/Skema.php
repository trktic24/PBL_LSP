<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    use HasFactory;
    protected $table = 'skema';
    protected $guarded = ['id_skema'];

    protected $primaryKey = 'id_skema';

    protected $fillable =[
        'kode_unit',
        'nama_skema',
        'deskripsi_skema',
        'SKKNI',
        'gambar',
        'harga',
        'category_id',
    ];

    public function asesors(): HasMany
    {
        return $this->hasMany(UnitKompetensi::class, 'skema_id');
    }
    public function category()
    {
        // Satu skema milik SATU kategori
        return $this->belongsTo(Category::class, 'category_id');
    }
}
