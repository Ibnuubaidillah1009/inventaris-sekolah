<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kode_barang'         => $this->kode_barang,
            'id_master_barang'    => $this->id_master_barang,
            'master_barang'       => new MasterBarangResource($this->whenLoaded('masterBarang')),
            'id_ruang'            => $this->id_ruang,
            'ruang'               => new RuangResource($this->whenLoaded('ruang')),
            'tanggal_registrasi'  => $this->tanggal_registrasi,
            'id_kondisi'          => $this->id_kondisi,
            'kondisi'             => new KondisiResource($this->whenLoaded('kondisi')),
            'nilai_residu'        => $this->nilai_residu,
            'id_status'           => $this->id_status,
            'status_barang'       => new StatusBarangResource($this->whenLoaded('statusBarang')),
            'gambar'              => $this->gambar,
            'keterangan'          => $this->keterangan,
        ];
    }
}

