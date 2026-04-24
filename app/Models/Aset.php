<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';
    protected $primaryKey = 'kode_barang';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'id_master_barang',
        'id_ruang',
        'tanggal_registrasi',
        'id_status',
        'nilai_residu',
        'id_kondisi',
        'gambar',
        'keterangan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'id_master_barang', 'id_master_barang');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang', 'id_ruang');
    }

    public function asetBangunan()
    {
        return $this->hasOne(AsetBangunan::class, 'kode_barang', 'kode_barang');
    }

    public function asetTanah()
    {
        return $this->hasOne(AsetTanah::class, 'kode_barang', 'kode_barang');
    }

    public function kondisi()
    {
        return $this->belongsTo(Kondisi::class, 'id_kondisi', 'id_kondisi');
    }

    public function statusBarang()
    {
        return $this->belongsTo(StatusBarang::class, 'id_status', 'id_status');
    }
}
