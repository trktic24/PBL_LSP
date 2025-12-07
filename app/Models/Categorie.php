<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal (Mass Assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    /**
     * Mendefinisikan relasi "satu ke banyak" dengan model Skema.
     * Satu Kategori bisa memiliki banyak Skema.
     */
    public function skemas(): HasMany
    {
        // Laravel akan otomatis mencari 'categorie_id'
        // Jika primary key Anda di 'skema' BUKAN 'id_skema', sesuaikan di sini.
        // Tapi karena foreign key ('categorie_id') dan local key ('id')
        // sudah sesuai konvensi, ini saja sudah cukup.
        return $this->hasMany(Skema::class, 'category_id');
    }

    /**
     * Mengubah pencarian rute default untuk menggunakan 'slug' alih-alih 'id'.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
