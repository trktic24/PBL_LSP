<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  
use App\Models\Skema; 
use Illuminate\Support\Facades\Storage; // Pastikan Storage di-import

class Asesor extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'asesor';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_asesor';

    /**
     * Kolom yang DIJAGA (selain ini boleh diisi).
     * Kosongkan array ini agar semua kolom bisa diisi.
     */
    protected $guarded = [];

    /**
     * Booted method untuk mendaftarkan model event.
     * LOGIKA DIPERBAIKI: Pisahkan antara 'deleting' dan 'deleted'
     */
    protected static function booted()
    {
        /**
         * Event 'deleting' (SEBELUM asesor dihapus)
         * Kita gunakan untuk menghapus file-filenya.
         * Ini aman karena tidak ada constraint database.
         */
        static::deleting(function ($asesor) {
            
            // Hapus folder file terkait
            // Asumsi path: public/asesor_files/{id_user}
            if ($asesor->id_user) {
                $uploadPath = 'public/asesor_files/' . $asesor->id_user;
                if (Storage::directoryExists($uploadPath)) {
                    // Hapus direktori beserta semua isinya
                    Storage::deleteDirectory($uploadPath);
                }
            }
        });

        /**
         * Event 'deleted' (SETELAH asesor dihapus)
         * Kita gunakan untuk menghapus user (parent) terkait.
         */
        static::deleted(function ($asesor) {

            // Hapus User (akun login) terkait
            // Ini aman dilakukan karena data 'asesor' (child) sudah terhapus
            // dari database, sehingga foreign key constraint tidak akan gagal.
            
            // Kita perlu mencari user-nya secara manual menggunakan find
            $user = User::find($asesor->id_user);
            
            if ($user) {
                $user->delete();
            }
        });
    }

    /**
     * Relasi ke model User.
     * Satu Asesor dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke model Skema.
     * Satu Asesor terkait dengan satu Skema.
     */
    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }
}