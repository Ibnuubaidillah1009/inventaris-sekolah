<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsetTanah extends Model
{
    protected $table = 'aset_tanah';
    protected $primaryKey = 'kode_barang_tanah';
    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'luas_tanah',
        'alamat_tanah',
        'no_sertifikat',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: AsetTanah belongsTo Aset.
     */
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'kode_barang', 'kode_barang');
    }
}
