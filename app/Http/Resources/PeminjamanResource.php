<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Menggunakan eager loading untuk menghindari N+1 Query Problem:
     * peminjaman → detailPeminjaman → aset → masterBarang
     */
    public function toArray(Request $request): array
    {
        return [
            'nomor_peminjaman'   => $this->nomor_peminjaman,
            'tanggal_pinjam'     => $this->tanggal_pinjam?->format('Y-m-d'),
            'id_peminjam'        => $this->id_peminjam,
            'nomor_telepon'      => $this->nomor_telepon,
            'lama_pinjam_hari'   => $this->lama_pinjam_hari,
            'status_peminjaman'  => $this->status_peminjaman,
            'keterangan'         => $this->keterangan,
            'peminjam'           => new PenggunaResource($this->whenLoaded('peminjam')),
            'detail_peminjaman'  => DetailPeminjamanResource::collection($this->whenLoaded('detailPeminjaman')),
        ];
    }
}
