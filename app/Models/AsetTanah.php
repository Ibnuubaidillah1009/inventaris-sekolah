<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsetTanah extends Model
{
    protected $table = 'aset_tanah';
    protected $primaryKey = 'id_aset_tanah';
    public $timestamps = false;

    protected $fillable = [
        'id_aset',
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
        return $this->belongsTo(Aset::class, 'id_aset', 'id_aset');
    }
}
