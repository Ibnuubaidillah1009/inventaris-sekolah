<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPeminjamanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detail_peminjaman' => $this->id_detail_peminjaman,
            'id_peminjaman'        => $this->id_peminjaman,
            'id_aset'              => $this->id_aset,
            'aset'                 => new AsetResource($this->whenLoaded('aset')),
            'jumlah'               => $this->jumlah,
            'keterangan'           => $this->keterangan,
        ];
    }
}
