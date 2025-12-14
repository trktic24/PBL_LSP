<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SoalIA05Resource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_soal_ia05, // Mapping ID
            'pertanyaan' => $this->soal_ia05, // Mapping Soal
            'opsi' => [
                'A' => $this->opsi_jawaban_a,
                'B' => $this->opsi_jawaban_b,
                'C' => $this->opsi_jawaban_c,
                'D' => $this->opsi_jawaban_d,
            ],
            // Kita tidak return kunci jawaban disini karena emang ga ada di tabel ini
        ];
    }
}