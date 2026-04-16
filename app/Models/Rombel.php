<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    protected $table = 'rombel';
    protected $primaryKey = 'id_rombel';
    public $timestamps = false;

    protected $fillable = [
        'nama_rombel',
        'id_jurusan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Rombel belongsTo Jurusan.
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    /**
     * Relasi: Rombel hasMany Kelas.
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_rombel', 'id_rombel');
    }
}
