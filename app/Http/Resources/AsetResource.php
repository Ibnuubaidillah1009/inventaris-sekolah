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
            'kode_aset'        => $this->kode_aset,
            'id_master_barang' => $this->id_master_barang,
            'master_barang'    => new MasterBarangResource($this->whenLoaded('masterBarang')),
            'id_ruang'         => $this->id_ruang,
            'ruang'            => new RuangResource($this->whenLoaded('ruang')),
            'tahun_perolehan'  => $this->tahun_perolehan,
            'nilai_perolehan'  => $this->nilai_perolehan,
            'sumber_dana'      => $this->sumber_dana,
            'kondisi'          => $this->kondisi,
            'status'           => $this->status,
            'keterangan'       => $this->keterangan,
            'aset_bangunan'    => new AsetBangunanResource($this->whenLoaded('asetBangunan')),
            'aset_tanah'       => new AsetTanahResource($this->whenLoaded('asetTanah')),
        ];
    }
}
