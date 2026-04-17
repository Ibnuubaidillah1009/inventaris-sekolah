<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';
    protected $primaryKey = 'id_mutasi';
    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'id_ruang_asal',
        'id_ruang_tujuan',
        'tanggal_mutasi',
        'alasan_mutasi',
        'id_penanggung_jawab',
    ];

    /**
     * Cast atribut.
     */
    protected function casts(): array
    {
        return [
            'tanggal_mutasi' => 'date',
        ];
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Mutasi belongsTo Aset.
     */
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'kode_barang', 'kode_barang');
    }

    /**
     * Relasi: Mutasi belongsTo Ruang sebagai ruang asal.
     */
    public function ruangAsal()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang_asal', 'id_ruang');
    }

    /**
     * Relasi: Mutasi belongsTo Ruang sebagai ruang tujuan.
     */
    public function ruangTujuan()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang_tujuan', 'id_ruang');
    }

    /**
     * Relasi: Mutasi belongsTo Pengguna sebagai penanggung jawab.
     */
    public function penanggungJawab()
    {
        return $this->belongsTo(Pengguna::class, 'id_penanggung_jawab', 'id_pengguna');
    }
}
