<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * (Pastikan nama tabel di database Anda adalah 'jadwals')
     * @var string
     */
    protected $table = 'jadwals';

    /**
     * Kolom-kolom yang dapat diisi (mass assignable).
     * (Sesuaikan ini dengan nama kolom di database Anda)
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_skema',
        'tuk',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'deskripsi',
        'persyaratan',
        'harga',
        'tanggal_tutup',
    ];

    /**
     * Tipe data (casts) untuk atribut/kolom.
     * Ini PENTING agar ->format('d F Y') berfungsi.
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal'       => 'datetime',
        'tanggal_tutup' => 'datetime',
        'harga'         => 'integer',
    ];
}
