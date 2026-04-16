<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPermintaan extends Model
{
    protected $table = 'detail_permintaan';
    protected $primaryKey = 'id_detail_permintaan';
    public $timestamps = false;

    protected $fillable = [
        'id_permintaan',
        'id_master_barang',
        'jumlah',
        'keterangan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: DetailPermintaan belongsTo Permintaan.
     */
    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'id_permintaan', 'id_permintaan');
    }

    /**
     * Relasi: DetailPermintaan belongsTo MasterBarang.
     */
    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'id_master_barang', 'id_master_barang');
    }
}
