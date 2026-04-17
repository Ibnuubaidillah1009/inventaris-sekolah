<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MutasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Eager load: aset → masterBarang, ruangAsal → lokasi, ruangTujuan → lokasi, petugas
     */
    public function toArray(Request $request): array
    {
        return [
            'id_mutasi'       => $this->id_mutasi,
            'kode_barang'         => $this->kode_barang,
            'aset'            => new AsetResource($this->whenLoaded('aset')),
            'id_ruang_asal'   => $this->id_ruang_asal,
            'ruang_asal'      => new RuangResource($this->whenLoaded('ruangAsal')),
            'id_ruang_tujuan' => $this->id_ruang_tujuan,
            'ruang_tujuan'    => new RuangResource($this->whenLoaded('ruangTujuan')),
            'tanggal_mutasi'  => $this->tanggal_mutasi?->format('Y-m-d'),
            'id_petugas'      => $this->id_petugas,
            'petugas'         => new PenggunaResource($this->whenLoaded('petugas')),
            'keterangan'      => $this->keterangan,
        ];
    }
}
