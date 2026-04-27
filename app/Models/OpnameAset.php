<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpnameAset extends Model
{
    protected $table = 'opname_aset';
    protected $primaryKey = 'id_opname_aset';

    protected $fillable = [
        'kode_barang',
        'tanggal_opname',
        'kondisi_ditemukan',
        'keterangan',
        'id_pemeriksa',
    ];

    public $timestamps = false;

    // =========================================================================
    // RELASI
    // =========================================================================

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'kode_barang', 'kode_barang');
    }
}
