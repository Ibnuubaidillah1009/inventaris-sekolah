<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KerusakanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Eager load: aset → masterBarang, pelapor, perbaikan
     */
    public function toArray(Request $request): array
    {
        return [
            'id_kerusakan'     => $this->id_kerusakan,
            'kode_barang'          => $this->kode_barang,
            'aset'             => new AsetResource($this->whenLoaded('aset')),
            'tanggal_kerusakan' => $this->tanggal_kerusakan?->format('Y-m-d'),
            'jenis_kerusakan'  => $this->jenis_kerusakan,
            'deskripsi'        => $this->deskripsi,
            'id_pelapor'       => $this->id_pelapor,
            'pelapor'          => new PenggunaResource($this->whenLoaded('pelapor')),
            'status_kerusakan' => $this->status_kerusakan,
            'keterangan'       => $this->keterangan,
            'perbaikan'        => PerbaikanResource::collection($this->whenLoaded('perbaikan')),
        ];
    }
}
