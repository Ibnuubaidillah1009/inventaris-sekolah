<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenghapusanAset extends Model
{
    protected $table = 'penghapusan_aset';
    protected $primaryKey = 'id_penghapusan';
    public $timestamps = false;

    protected $fillable = [
        'id_aset',
        'tanggal_penghapusan',
        'alasan',
        'metode_penghapusan',
        'id_penyetuju',
        'dokumen_pendukung',
        'keterangan',
    ];

    /**
     * Cast atribut.
     */
    protected function casts(): array
    {
        return [
            'tanggal_penghapusan' => 'date',
        ];
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: PenghapusanAset belongsTo Aset.
     */
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'id_aset', 'id_aset');
    }

    /**
     * Relasi: PenghapusanAset belongsTo Pengguna sebagai penyetuju.
     */
    public function penyetuju()
    {
        return $this->belongsTo(Pengguna::class, 'id_penyetuju', 'id_pengguna');
    }
}
