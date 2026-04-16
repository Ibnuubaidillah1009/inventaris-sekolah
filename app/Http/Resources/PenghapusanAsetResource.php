<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenghapusanAsetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Eager load: aset → masterBarang, penyetuju
     */
    public function toArray(Request $request): array
    {
        return [
            'id_penghapusan'      => $this->id_penghapusan,
            'id_aset'             => $this->id_aset,
            'aset'                => new AsetResource($this->whenLoaded('aset')),
            'tanggal_penghapusan' => $this->tanggal_penghapusan?->format('Y-m-d'),
            'alasan'              => $this->alasan,
            'metode_penghapusan'  => $this->metode_penghapusan,
            'id_penyetuju'        => $this->id_penyetuju,
            'penyetuju'           => new PenggunaResource($this->whenLoaded('penyetuju')),
            'dokumen_pendukung'   => $this->dokumen_pendukung,
            'keterangan'          => $this->keterangan,
        ];
    }
}
