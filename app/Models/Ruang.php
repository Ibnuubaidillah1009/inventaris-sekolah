<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';
    public $timestamps = false;

    protected $fillable = [
        'nama_ruang',
        'id_lokasi',
        'keterangan',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function aset()
    {
        return $this->hasMany(Aset::class, 'id_ruang', 'id_ruang');
    }
}
