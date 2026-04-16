<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeranAkses extends Model
{
    /**
     * Nama tabel di database.
     */
    protected $table = 'peran_akses';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_peran_akses';

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'id_peran',
        'id_akses',
    ];

    /**
     * Nonaktifkan timestamps.
     */
    public $timestamps = false;

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: PeranAkses belongsTo Peran.
     */
    public function peran()
    {
        return $this->belongsTo(Peran::class, 'id_peran', 'id_peran');
    }

    /**
     * Relasi: PeranAkses belongsTo Akses.
     */
    public function akses()
    {
        return $this->belongsTo(Akses::class, 'id_akses', 'id_akses');
    }
}
