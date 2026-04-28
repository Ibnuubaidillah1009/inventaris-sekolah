<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peran extends Model
{
    /**
     * Nama tabel di database.
     */
    protected $table = 'peran';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_peran';

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'nama_peran',
        'keterangan',
    ];

    /**
     * Nonaktifkan timestamps jika tabel tidak memiliki kolom created_at/updated_at.
     */
    public $timestamps = false;

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Peran hasMany Pengguna.
     */
    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'id_peran', 'id_peran');
    }

    /**
     * Relasi: Peran belongsToMany Akses (melalui tabel pivot peran_akses).
     */
    public function aksesList()
    {
        return $this->belongsToMany(
            Akses::class,
            'peran_akses',   // tabel pivot
            'id_peran',      // FK di pivot → peran
            'id_akses',      // FK di pivot → akses
            'id_peran',      // PK peran
            'id_akses'       // PK akses
        );
    }
}
