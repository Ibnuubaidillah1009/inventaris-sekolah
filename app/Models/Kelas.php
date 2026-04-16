<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;

    protected $fillable = [
        'nama_kelas',
        'id_rombel',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Kelas belongsTo Rombel.
     */
    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel', 'id_rombel');
    }

    /**
     * Relasi: Kelas hasMany Pengguna.
     */
    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'id_kelas', 'id_kelas');
    }
}
