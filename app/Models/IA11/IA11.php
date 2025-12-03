<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\IA11\SpesifikasiProdukIA11;
use App\Models\IA11\BahanProdukIA11;
use App\Models\IA11\SpesifikasiTeknisIA11;
use App\Models\IA11\SpesifikasiIA11;
use App\Models\IA11\PerformaIA11;

class IA11 extends Model
{
    use HasFactory;

    protected $table = 'ia11';
    protected $primaryKey = 'id_ia11';
    protected $guarded = [];

    /**
     * Atribut yang dapat diisi.
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'rancangan_produk',
        'nama_produk',
        'standar_industri',
        'tanggal_pengoperasian',
        'gambar_produk',
    ];

    // --- DEFINISI RELASI (RELATIONSHIPS) ---

    /**
     * Relasi ke Data Sertifikasi Asesi (Parent)
     * Relasi: Many-to-One (M:1)
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        // Asumsi DataSertifikasiAsesi ada di App\Models\
        return $this->belongsTo(\App\Models\DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    /**
     * Relasi ke Spesifikasi Produk (Data Tunggal)
     * Relasi: One-to-One (1:1)
     */
    public function spesifikasiProduk(): HasOne
    {
        return $this->hasOne(SpesifikasiProdukIA11::class, 'id_ia11', 'id_ia11');
    }

    /**
     * Relasi ke Bahan Produk (Daftar Banyak Bahan)
     * Relasi: One-to-Many (1:M)
     */
    public function bahanProduk(): HasMany
    {
        return $this->hasMany(BahanProdukIA11::class, 'id_ia11', 'id_ia11');
    }

    /**
     * Relasi ke Spesifikasi Teknis (Daftar Banyak Data Teknis)
     * Relasi: One-to-Many (1:M)
     */
    public function spesifikasiTeknis(): HasMany
    {
        return $this->hasMany(SpesifikasiTeknisIA11::class, 'id_ia11', 'id_ia11');
    }

    /**
     * Relasi ke Spesifikasi Master (Melalui Tabel Pencapaian)
     * Relasi: Many-to-Many (M:M)
     */
    public function pencapaianSpesifikasi(): BelongsToMany
    {
        // Menggunakan withPivot untuk mengambil kolom hasil_reviu dan catatan_temuan
        return $this->belongsToMany(SpesifikasiIA11::class, 'pencapaian_spesifikasi_ia11', 'id_ia11', 'id_spesifikasi_ia11')
                    ->withPivot(['hasil_reviu', 'catatan_temuan'])
                    ->withTimestamps();
    }

    /**
     * Relasi ke Performa Master (Melalui Tabel Pencapaian)
     * Relasi: Many-to-Many (M:M)
     */
    public function pencapaianPerforma(): BelongsToMany
    {
        // Menggunakan withPivot untuk mengambil kolom hasil_reviu dan catatan_temuan
        return $this->belongsToMany(PerformaIA11::class, 'pencapaian_performa_ia11', 'id_ia11', 'id_performa_ia11')
                    ->withPivot(['hasil_reviu', 'catatan_temuan'])
                    ->withTimestamps();
    }
}