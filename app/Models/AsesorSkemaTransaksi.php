<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsesorSkemaTransaksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terasosiasi dengan model.
     * Berdasarkan file migrations: Transaksi_asesor_skema
     * Perhatikan penamaan, PHP dan Laravel umumnya menggunakan snake_case untuk nama tabel.
     * Saya mengikuti nama tabel yang Anda gunakan di migrations.
     *
     * Jika Anda menggunakan nama tabel 'asesor_skema' (tanpa 'Transaksi') dan *tanpa* auto-increment ID,
     * Anda bisa menggunakan 'using' di relasi belongsToMany.
     */
    protected $table = 'Transaksi_asesor_skema';

    /**
     * Primary Key untuk model.
     * Berdasarkan file migrations: id_transaksi
     */
    protected $primaryKey = 'id_transaksi';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'id_asesor',
        'id_skema',
        // Tambahkan kolom lain jika ada, misalnya tanggal transaksi, dll.
    ];

    /**
     * Relasi Many-to-One dengan model Asesor.
     * Satu transaksi dimiliki oleh satu Asesor.
     */
    public function asesor()
    {
        // Pastikan nama Foreign Key sudah benar
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }

    /**
     * Relasi Many-to-One dengan model Skema.
     * Satu transaksi terkait dengan satu Skema.
     */
    public function skema()
    {
        // Pastikan nama Foreign Key sudah benar
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }
}