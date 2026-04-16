<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermintaanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_permintaan'      => $this->id_permintaan,
            'tanggal_permintaan' => $this->tanggal_permintaan?->format('Y-m-d'),
            'id_pemohon'         => $this->id_pemohon,
            'pemohon'            => new PenggunaResource($this->whenLoaded('pemohon')),
            'id_penyetuju'       => $this->id_penyetuju,
            'penyetuju'          => new PenggunaResource($this->whenLoaded('penyetuju')),
            'status_permintaan'  => $this->status_permintaan,
            'keterangan'         => $this->keterangan,
            'detail_permintaan'  => DetailPermintaanResource::collection($this->whenLoaded('detailPermintaan')),
        ];
    }
}
