<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'categories'; // Sesuai nama tabel migrasi

    /**
     * Kolom yang bisa diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    /**
     * Mendefinisikan relasi "satu-ke-banyak" (one-to-many).
     * Satu Kategori BISA MEMILIKI BANYAK Skema.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skemas(): HasMany
    {
        // 'categorie_id' -> foreign key di tabel 'skema'
        // 'id'           -> primary key di tabel 'categories' ini
        return $this->hasMany(Skema::class, 'category_id', 'id');
    }
}