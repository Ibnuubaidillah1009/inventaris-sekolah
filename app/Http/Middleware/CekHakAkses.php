<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekHakAkses
{
    /**
     * Middleware untuk mengecek hak akses pengguna berdasarkan:
     * user → peran → peran_akses → akses (nama_modul + jenis hak).
     *
     * Contoh penggunaan di route:
     *   ->middleware('cek.hak.akses:master_barang,hak_baca')
     *   ->middleware('cek.hak.akses:pengguna,hak_buat')
     *
     * @param  string  $namaModul  Nama modul yang akan dicek (kolom nama_modul di tabel akses)
     * @param  string  $jenisHak   Jenis hak: hak_buat | hak_baca | hak_ubah | hak_hapus
     */
    public function handle(Request $request, Closure $next, string $namaModul, string $jenisHak): Response
    {
        $user = $request->user();

        // Jika belum login
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
            ], 401);
        }

        // Validasi jenis hak yang diperbolehkan
        $hakValid = ['hak_buat', 'hak_baca', 'hak_ubah', 'hak_hapus'];
        if (!in_array($jenisHak, $hakValid)) {
            return response()->json([
                'status'  => false,
                'message' => "Jenis hak '{$jenisHak}' tidak valid. Gunakan: " . implode(', ', $hakValid),
            ], 400);
        }

        // Ambil relasi peran beserta daftar akses-nya
        $user->loadMissing('peran.aksesList');

        $peran = $user->peran;

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Pengguna tidak memiliki peran yang ditentukan.',
            ], 403);
        }

        // Cari akses yang sesuai dengan nama_modul dan jenis hak = true
        $punyaAkses = $peran->aksesList
            ->where('nama_modul', $namaModul)
            ->where($jenisHak, true)
            ->isNotEmpty();

        if (!$punyaAkses) {
            return response()->json([
                'status'  => false,
                'message' => "Anda tidak memiliki hak '{$jenisHak}' pada modul '{$namaModul}'.",
            ], 403);
        }

        return $next($request);
    }
}
