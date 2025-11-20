<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini jika Model Category menggunakan BelongsTo

// Pastikan semua Model relasi di-import di sini:
use App\Models\Category;
use App\Models\Asesor; 
use App\Models\Jadwal; 

class Skema extends Model
{
    use HasFactory;

    protected $table = 'skema';

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
        return $this->hasMany(Asesor::class, 'id_skema', 'id_skema');
    }

    public function category(): BelongsTo // Gunakan BelongsTo untuk ketepatan tipe
    {
        // Satu skema milik SATU kategori
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function jadwal(): HasMany // Gunakan HasMany untuk ketepatan tipe
    {
        return $this->hasMany(Jadwal::class, 'id_skema', 'id_skema');
    }
}