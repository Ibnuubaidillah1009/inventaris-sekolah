<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsetBangunan extends Model
{
    protected $table = 'aset_bangunan';
    protected $primaryKey = 'id_aset_bangunan';
    public $timestamps = false;

    protected $fillable = [
        'id_aset',
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
        return $this->belongsTo(Aset::class, 'id_aset', 'id_aset');
    }
}
