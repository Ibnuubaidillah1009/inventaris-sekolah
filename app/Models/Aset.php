<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';
    protected $primaryKey = 'kode_barang';
    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'id_master_barang',
        'id_ruang',
        'tahun_perolehan',
        'nilai_perolehan',
        'sumber_dana',
        'kondisi',
        'status',
        'keterangan',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Aset belongsTo MasterBarang.
     */
    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'id_master_barang', 'id_master_barang');
    }

    /**
     * Relasi: Aset belongsTo Ruang.
     */
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang', 'id_ruang');
    }

    /**
     * Relasi: Aset hasOne AsetBangunan (jika tipe aset adalah bangunan).
     */
    public function asetBangunan()
    {
        return $this->hasOne(AsetBangunan::class, 'id_aset', 'id_aset');
    }

    /**
     * Relasi: Aset hasOne AsetTanah (jika tipe aset adalah tanah).
     */
    public function asetTanah()
    {
        return $this->hasOne(AsetTanah::class, 'id_aset', 'id_aset');
    }

    /**
     * Relasi: Aset hasMany DetailPeminjaman.
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_aset', 'id_aset');
    }

    /**
     * Relasi: Aset hasMany Mutasi.
     */
    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'id_aset', 'id_aset');
    }

    /**
     * Relasi: Aset hasMany Kerusakan.
     */
    public function kerusakan()
    {
        return $this->hasMany(Kerusakan::class, 'id_aset', 'id_aset');
    }

    /**
     * Relasi: Aset hasMany PenghapusanAset.
     */
    public function penghapusanAset()
    {
        return $this->hasMany(PenghapusanAset::class, 'id_aset', 'id_aset');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope: hanya aset yang tersedia dan kondisi baik.
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'Tersedia')->where('kondisi', 'Baik');
    }
}
