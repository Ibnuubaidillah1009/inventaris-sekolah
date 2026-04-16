<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerbaikanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Eager load: kerusakan → aset → masterBarang
     */
    public function toArray(Request $request): array
    {
        return [
            'id_perbaikan'      => $this->id_perbaikan,
            'id_kerusakan'      => $this->id_kerusakan,
            'kerusakan'         => new KerusakanResource($this->whenLoaded('kerusakan')),
            'tanggal_perbaikan' => $this->tanggal_perbaikan?->format('Y-m-d'),
            'tanggal_selesai'   => $this->tanggal_selesai?->format('Y-m-d'),
            'pelaksana'         => $this->pelaksana,
            'biaya'             => $this->biaya,
            'status_perbaikan'  => $this->status_perbaikan,
            'keterangan'        => $this->keterangan,
        ];
    }
}
