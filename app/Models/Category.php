<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    // Nama tabel sudah benar 'categories' (default)
    // Primary key sudah benar 'id' (default)

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    /**
     * Relasi ke Skema (Satu Kategori 'memiliki banyak' Skema).
     */
    public function skemas()
    {
        return $this->hasMany(Skema::class, 'categorie_id', 'id');
    }
}