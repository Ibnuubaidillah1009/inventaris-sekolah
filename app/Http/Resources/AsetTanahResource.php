<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsetTanahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kode_barang_tanah'  => $this->kode_barang_tanah,
            'kode_barang'        => $this->kode_barang,
            'luas_tanah'     => $this->luas_tanah,
            'alamat_tanah'   => $this->alamat_tanah,
            'no_sertifikat'  => $this->no_sertifikat,
        ];
    }
}
