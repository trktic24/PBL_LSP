<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ia07 extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'ia07';

    /**
     * Primary key yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_ia07';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_unit_kompetensi',
        'pertanyaan',
        'jawaban_asesi',
        'jawaban_diharapkan',
        'pencapaian',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pencapaian' => 'boolean',
    ];

    /**
     * Mendapatkan data sertifikasi asesi yang memiliki IA07 ini.
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        // Kita perlu menentukan foreign key dan owner key secara eksplisit
        // karena keduanya tidak mengikuti konvensi standar Laravel.
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function unitKompetensi(): BelongsTo
    {
        return $this->belongsTo(UnitKompetensi::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }
}