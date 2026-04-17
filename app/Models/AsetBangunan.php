<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsetBangunan extends Model
{
    protected $table = 'aset_bangunan';
    protected $primaryKey = 'kode_barang_bangunan';
    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'luas_bangunan',
        'jumlah_lantai',
        'tahun_dibangun',
        'alamat_bangunan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: AsetBangunan belongsTo Aset.
     */
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'kode_barang', 'kode_barang');
    }
}
