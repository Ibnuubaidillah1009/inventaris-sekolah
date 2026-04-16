<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $table = 'permintaan';
    protected $primaryKey = 'id_permintaan';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_permintaan',
        'id_pemohon',
        'id_penyetuju',
        'status_permintaan',
        'keterangan',
    ];

    /**
     * Cast atribut.
     */
    protected function casts(): array
    {
        return [
            'tanggal_permintaan' => 'date',
        ];
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Permintaan belongsTo Pengguna sebagai pemohon.
     */
    public function pemohon()
    {
        return $this->belongsTo(Pengguna::class, 'id_pemohon', 'id_pengguna');
    }

    /**
     * Relasi: Permintaan belongsTo Pengguna sebagai penyetuju.
     */
    public function penyetuju()
    {
        return $this->belongsTo(Pengguna::class, 'id_penyetuju', 'id_pengguna');
    }

    /**
     * Relasi: Permintaan hasMany DetailPermintaan.
     */
    public function detailPermintaan()
    {
        return $this->hasMany(DetailPermintaan::class, 'id_permintaan', 'id_permintaan');
    }
}
