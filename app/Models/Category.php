<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['nama_kategori', 'slug'];

    public function skemas()
    {
        // Satu kategori punya BANYAK skema
        return $this->hasMany(Skema::class, 'category_id');
    }
}
