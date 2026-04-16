<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBarang extends Model
{
    protected $table = 'master_barang';
    protected $primaryKey = 'id_master_barang';
    public $timestamps = false;

    protected $fillable = [
        'nama_barang',
        'id_kategori',
        'id_merek',
        'id_satuan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: MasterBarang belongsTo Kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi: MasterBarang belongsTo Merek.
     */
    public function merek()
    {
        return $this->belongsTo(Merek::class, 'id_merek', 'id_merek');
    }

    /**
     * Relasi: MasterBarang belongsTo Satuan.
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id_satuan');
    }
}
