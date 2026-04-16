<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPermintaanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detail_permintaan' => $this->id_detail_permintaan,
            'id_permintaan'        => $this->id_permintaan,
            'id_master_barang'     => $this->id_master_barang,
            'master_barang'        => new MasterBarangResource($this->whenLoaded('masterBarang')),
            'jumlah'               => $this->jumlah,
            'keterangan'           => $this->keterangan,
        ];
    }
}
