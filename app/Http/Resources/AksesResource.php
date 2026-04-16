<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AksesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_akses'   => $this->id_akses,
            'nama_modul' => $this->nama_modul,
            'hak_buat'   => (bool) $this->hak_buat,
            'hak_baca'   => (bool) $this->hak_baca,
            'hak_ubah'   => (bool) $this->hak_ubah,
            'hak_hapus'  => (bool) $this->hak_hapus,
        ];
    }
}
