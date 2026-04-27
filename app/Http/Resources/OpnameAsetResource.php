<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpnameAsetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_opname_aset'    => $this->id_opname_aset,
            'kode_barang'       => $this->kode_barang,
            'nama_barang'       => $this->aset?->masterBarang?->nama_barang,
            'tanggal_opname'    => $this->tanggal_opname,
            'kondisi_ditemukan' => $this->kondisi_ditemukan,
            'keterangan'        => $this->keterangan,
            'id_pemeriksa'      => $this->id_pemeriksa,
            'aset'              => $this->whenLoaded('aset', function () {
                return [
                    'kode_barang'  => $this->aset->kode_barang,
                    'master_barang' => $this->aset->masterBarang ? [
                        'id_master_barang' => $this->aset->masterBarang->id_master_barang,
                        'nama_barang'      => $this->aset->masterBarang->nama_barang,
                    ] : null,
                ];
            }),
        ];
    }
}
