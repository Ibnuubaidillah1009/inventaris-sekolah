<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Sistem Informasi Manajemen Inventaris Sekolah API",
 *     version="1.0.0",
 *     description="REST API untuk Sistem Informasi Manajemen Inventaris Sekolah. Mendukung modul Auth, Pengguna, Peran & Akses, Master Sekolah, Master Barang, Manajemen Aset, Transaksi Peminjaman & Permintaan, Pemeliharaan & Akhir Siklus Aset, serta Manajemen Database.",
 *     @OA\Contact(
 *         email="admin@sekolah.sch.id",
 *         name="Admin Sekolah"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum"
 * )
 *
 * @OA\Tag(name="Auth", description="Autentikasi pengguna (login, logout, profil)")
 * @OA\Tag(name="Pengguna", description="Manajemen data pengguna sistem")
 * @OA\Tag(name="Peran", description="Manajemen peran (role) dan hak akses")
 * @OA\Tag(name="Akses", description="Manajemen modul akses / permission")
 * @OA\Tag(name="Jurusan", description="Master data jurusan sekolah")
 * @OA\Tag(name="Rombel", description="Master data rombongan belajar")
 * @OA\Tag(name="Kelas", description="Master data kelas")
 * @OA\Tag(name="Mapel", description="Master data mata pelajaran")
 * @OA\Tag(name="Unit", description="Master data unit sekolah")
 * @OA\Tag(name="Kategori", description="Master data kategori barang")
 * @OA\Tag(name="Merek", description="Master data merek barang")
 * @OA\Tag(name="Satuan", description="Master data satuan barang")
 * @OA\Tag(name="Master Barang", description="Master data barang inventaris")
 * @OA\Tag(name="Lokasi", description="Manajemen lokasi / gedung")
 * @OA\Tag(name="Ruang", description="Manajemen ruang di dalam lokasi")
 * @OA\Tag(name="Aset", description="Manajemen aset inventaris sekolah")
 * @OA\Tag(name="Peminjaman", description="Transaksi peminjaman aset")
 * @OA\Tag(name="Permintaan", description="Transaksi permintaan barang")
 * @OA\Tag(name="Mutasi", description="Mutasi perpindahan aset antar ruang")
 * @OA\Tag(name="Kerusakan", description="Laporan kerusakan aset")
 * @OA\Tag(name="Perbaikan", description="Data perbaikan aset rusak")
 * @OA\Tag(name="Penghapusan Aset", description="Penghapusan aset dari inventaris")
 * @OA\Tag(name="Database", description="Manajemen database (backup, restore, reset, koneksi)")
 */
class Controller
{
    //
}
