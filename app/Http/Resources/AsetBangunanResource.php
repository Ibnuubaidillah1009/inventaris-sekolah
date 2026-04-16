<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsetBangunanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_aset_bangunan' => $this->id_aset_bangunan,
            'id_aset'          => $this->id_aset,
            'luas_bangunan'    => $this->luas_bangunan,
            'jumlah_lantai'    => $this->jumlah_lantai,
            'tahun_dibangun'   => $this->tahun_dibangun,
            'alamat_bangunan'  => $this->alamat_bangunan,
        ];
    }
}
