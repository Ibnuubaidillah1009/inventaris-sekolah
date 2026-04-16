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
            'id_peminjaman'      => $this->id_peminjaman,
            'kode_peminjaman'    => $this->kode_peminjaman,
            'tanggal_pinjam'     => $this->tanggal_pinjam?->format('Y-m-d'),
            'tanggal_kembali'    => $this->tanggal_kembali?->format('Y-m-d'),
            'id_peminjam'        => $this->id_peminjam,
            'peminjam'           => new PenggunaResource($this->whenLoaded('peminjam')),
            'status_peminjaman'  => $this->status_peminjaman,
            'keterangan'         => $this->keterangan,
            'detail_peminjaman'  => DetailPeminjamanResource::collection($this->whenLoaded('detailPeminjaman')),
        ];
    }
}
