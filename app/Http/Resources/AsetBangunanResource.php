<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsetBangunanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kode_barang_bangunan' => $this->kode_barang_bangunan,
            'kode_barang'          => $this->kode_barang,
            'luas_bangunan'    => $this->luas_bangunan,
            'jumlah_lantai'    => $this->jumlah_lantai,
            'tahun_dibangun'   => $this->tahun_dibangun,
            'alamat_bangunan'  => $this->alamat_bangunan,
        ];
    }
}
