<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsesiTTDResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_asesi' => $this->id_asesi,
            'nama_lengkap' => $this->nama_lengkap,
            'tanda_tangan' => $this->tanda_tangan,
            'alamat_rumah' => $this->alamat_rumah,
            'data_pekerjaan' => $this->whenLoaded('dataPekerjaan')
        ];
    }
}
