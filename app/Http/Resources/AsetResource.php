<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kode_barang'          => $this->kode_barang,
            'id_master_barang' => $this->id_master_barang,
            'master_barang'    => new MasterBarangResource($this->whenLoaded('masterBarang')),
            'id_ruang'         => $this->id_ruang,
            'nama_ruang'       => $this->ruang->nama_ruang,
            'nilai_residu'     => $this->nilai_residu,
            'kondisi_barang'   => $this->kondisi_barang,
            'aset_bangunan'    => new AsetBangunanResource($this->whenLoaded('asetBangunan')),
            'aset_tanah'       => new AsetTanahResource($this->whenLoaded('asetTanah')),
        ];
    }
}
