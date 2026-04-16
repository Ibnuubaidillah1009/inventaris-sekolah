<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cek atau Buat Peran "Admin"
        $peran = DB::table('peran')->where('nama_peran', 'Admin')->first();
        if (!$peran) {
            $idPeran = DB::table('peran')->insertGetId([
                'nama_peran' => 'Admin'
            ]);
        } else {
            $idPeran = $peran->id_peran;
        }

        // 2. Cek atau Buat Pengguna "admin" (Password: password)
        $admin = DB::table('pengguna')->where('username', 'admin')->first();
        if (!$admin) {
            DB::table('pengguna')->insert([
                'username' => 'admin',
                'password' => Hash::make('password'),
                'id_peran' => $idPeran,
            ]);
        }

        // 3. Daftar Seluruh Modul di API Anda
        $daftarModul = [
            'pengguna', 'peran', 'akses',
            'jurusan', 'rombel', 'kelas', 'mapel', 'unit',
            'kategori', 'merek', 'satuan', 'master_barang',
            'lokasi', 'ruang', 'aset',
            'peminjaman', 'permintaan',
            'mutasi', 'kerusakan', 'perbaikan', 'penghapusan_aset'
        ];

        // 4. Looping untuk membuat Akses Full (CRUD) dan menghubungkan ke Admin
        foreach ($daftarModul as $modul) {
            
            // Cek apakah konfigurasi akses full untuk modul ini sudah ada
            $akses = DB::table('akses')
                ->where('nama_modul', $modul)
                ->where('hak_buat', 1)
                ->where('hak_baca', 1)
                ->where('hak_ubah', 1)
                ->where('hak_hapus', 1)
                ->first();

            if (!$akses) {
                // Jika belum ada, buat baru
                $idAkses = DB::table('akses')->insertGetId([
                    'nama_modul' => $modul,
                    'hak_buat'   => 1,
                    'hak_baca'   => 1,
                    'hak_ubah'   => 1,
                    'hak_hapus'  => 1,
                ]);
            } else {
                $idAkses = $akses->id_akses;
            }

            // Hubungkan Akses tersebut ke Peran Admin di tabel peran_akses
            $peranAkses = DB::table('peran_akses')
                ->where('id_peran', $idPeran)
                ->where('id_akses', $idAkses)
                ->first();

            if (!$peranAkses) {
                DB::table('peran_akses')->insert([
                    'id_peran' => $idPeran,
                    'id_akses' => $idAkses,
                ]);
            }
        }

        $this->command->info('Seeder Berhasil: Akun Admin dan seluruh hak akses telah dibuat!');
    }
}