<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akses extends Model
{
    /**
     * Nama tabel di database.
     */
    protected $table = 'akses';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_akses';

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'nama_modul',
        'hak_buat',
        'hak_baca',
        'hak_ubah',
        'hak_hapus',
    ];

    /**
     * Nonaktifkan timestamps.
     */
    public $timestamps = false;

    /**
     * Cast atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hak_buat'  => 'boolean',
            'hak_baca'  => 'boolean',
            'hak_ubah'  => 'boolean',
            'hak_hapus' => 'boolean',
        ];
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Relasi: Akses belongsToMany Peran (melalui tabel pivot peran_akses).
     */
    public function peranList()
    {
        return $this->belongsToMany(
            Peran::class,
            'peran_akses',
            'id_akses',
            'id_peran',
            'id_akses',
            'id_peran'
        );
    }
}
