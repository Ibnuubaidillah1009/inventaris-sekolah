<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsetTanahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_aset_tanah'  => $this->id_aset_tanah,
            'id_aset'        => $this->id_aset,
            'luas_tanah'     => $this->luas_tanah,
            'alamat_tanah'   => $this->alamat_tanah,
            'no_sertifikat'  => $this->no_sertifikat,
        ];
    }
}
