<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id_detail_peminjaman';
    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'id_aset',
        'jumlah',
        'keterangan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: DetailPeminjaman belongsTo Peminjaman.
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Relasi: DetailPeminjaman belongsTo Aset.
     */
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'id_aset', 'id_aset');
    }
}
