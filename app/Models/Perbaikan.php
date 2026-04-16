<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    protected $table = 'perbaikan';
    protected $primaryKey = 'id_perbaikan';
    public $timestamps = false;

    protected $fillable = [
        'id_kerusakan',
        'tanggal_perbaikan',
        'tanggal_selesai',
        'pelaksana',
        'biaya',
        'status_perbaikan',
        'keterangan',
    ];

    /**
     * Cast atribut.
     */
    protected function casts(): array
    {
        return [
            'tanggal_perbaikan' => 'date',
            'tanggal_selesai'   => 'date',
            'biaya'             => 'decimal:2',
        ];
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Perbaikan belongsTo Kerusakan.
     */
    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class, 'id_kerusakan', 'id_kerusakan');
    }
}
