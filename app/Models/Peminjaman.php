<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $timestamps = false;

    protected $fillable = [
        'kode_peminjaman',
        'tanggal_pinjam',
        'tanggal_kembali',
        'id_peminjam',
        'status_peminjaman',
        'keterangan',
    ];

    /**
     * Cast atribut.
     */
    protected function casts(): array
    {
        return [
            'tanggal_pinjam'  => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Peminjaman belongsTo Pengguna sebagai peminjam.
     */
    public function peminjam()
    {
        return $this->belongsTo(Pengguna::class, 'id_peminjam', 'id_pengguna');
    }

    /**
     * Relasi: Peminjaman hasMany DetailPeminjaman.
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}
