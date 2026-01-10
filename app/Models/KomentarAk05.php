<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarAk05 extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'komentar_ak05';

    /**
     * Primary key yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_komentar_ak05';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_ak05',
        'rekomendasi',
        'keterangan',
        'catatan_ak05',
    ]; 

    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    } 

    /**
     * Mendapatkan data sertifikasi asesi yang memiliki IA07 ini.
     */
    public function Ak05(): BelongsTo
    {
        // Kita perlu menentukan foreign key dan owner key secara eksplisit
        // karena keduanya tidak mengikuti konvensi standar Laravel.
        return $this->belongsTo(Ak05::class, 'id_ak05', 'id_ak05');
    }    
}
