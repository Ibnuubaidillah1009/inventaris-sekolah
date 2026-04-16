<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id_satuan';
    public $timestamps = false;

    protected $fillable = [
        'nama_satuan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Satuan hasMany MasterBarang.
     */
    public function masterBarang()
    {
        return $this->hasMany(MasterBarang::class, 'id_satuan', 'id_satuan');
    }
}
