<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AksesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('peran_akses')->truncate();
        DB::table('akses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $modulList = [
            'Inventaris', 
            'Pengadaan Barang (Inv)', 
            'Input Tanah', 
            'Input Bangunan',
            
            'Proses', 
            'Mutasi Barang', 
            'Opname', 
            'Non Aktif Barang', 
            'Peminjaman', 
            'Pengembalian',
            
            'Brg. Habis Pakai', 
            'Master Data (BHP)', 
            'Data Barang (BHP)', 
            'Pengadaan Barang (BHP)', 
            'Barang Keluar (BHP)', 
            'Lap. Stok Barang (BHP)',
            
            'Admin', 
            'Data Master', 
            'Set Lembaga', 
            'User', 
            'Wallpaper',
            
            'Laporan', 
            'Sub Laporan', 
            'Tools', 
            'Koneksi', 
            'Backup', 
            'Help', 
            'About', 
            'Tutorial'
        ];

        foreach ($modulList as $index => $modul) {
            DB::table('akses')->insert([
                'id_akses'   => $index + 1,
                'nama_modul' => $modul,
                'hak_buat'   => 1,
                'hak_baca'   => 1,
                'hak_ubah'   => 1,
                'hak_hapus'  => 1,
            ]);
        }
    }
}