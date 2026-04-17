<?php

use App\Http\Controllers\AksesController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KerusakanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenghapusanAsetController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\PeranController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DatabaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Sistem Informasi Manajemen Inventaris Sekolah
|--------------------------------------------------------------------------
|
| Semua route di-prefix dengan /api secara otomatis oleh Laravel.
| Route dikelompokkan berdasarkan modul dan dilindungi oleh:
|   1. Sanctum (auth:sanctum) — autentikasi token
|   2. CekHakAkses (cek.hak.akses:modul,hak) — otorisasi RBAC
|
*/

// =========================================================================
// PUBLIC ROUTES (tanpa autentikasi)
// =========================================================================

Route::post('/login', [AuthController::class, 'login']);

// =========================================================================
// PROTECTED ROUTES (memerlukan autentikasi Sanctum)
// =========================================================================

Route::middleware('auth:sanctum')->group(function () {

    // -----------------------------------------------------------------
    // AUTH
    // -----------------------------------------------------------------
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // -----------------------------------------------------------------
    // MODUL: MANAJEMEN PENGGUNA (Admin Only)
    // -----------------------------------------------------------------
    Route::prefix('pengguna')->group(function () {
        Route::get('/', [PenggunaController::class, 'index'])
            ->middleware('cek.hak.akses:pengguna,hak_baca');
        Route::post('/', [PenggunaController::class, 'store'])
            ->middleware('cek.hak.akses:pengguna,hak_buat');
        Route::get('/{pengguna}', [PenggunaController::class, 'show'])
            ->middleware('cek.hak.akses:pengguna,hak_baca');
        Route::put('/{pengguna}', [PenggunaController::class, 'update'])
            ->middleware('cek.hak.akses:pengguna,hak_ubah');
        Route::delete('/{pengguna}', [PenggunaController::class, 'destroy'])
            ->middleware('cek.hak.akses:pengguna,hak_hapus');
    });

    // -----------------------------------------------------------------
    // MODUL: MANAJEMEN PERAN (Role)
    // -----------------------------------------------------------------
    Route::prefix('peran')->group(function () {
        Route::get('/', [PeranController::class, 'index'])
            ->middleware('cek.hak.akses:peran,hak_baca');
        Route::post('/', [PeranController::class, 'store'])
            ->middleware('cek.hak.akses:peran,hak_buat');
        Route::get('/{peran}', [PeranController::class, 'show'])
            ->middleware('cek.hak.akses:peran,hak_baca');
        Route::put('/{peran}', [PeranController::class, 'update'])
            ->middleware('cek.hak.akses:peran,hak_ubah');
        Route::delete('/{peran}', [PeranController::class, 'destroy'])
            ->middleware('cek.hak.akses:peran,hak_hapus');

        // Sync hak akses ke peran (assign/unassign akses)
        Route::post('/{peran}/sync-akses', [PeranController::class, 'syncAkses'])
            ->middleware('cek.hak.akses:peran,hak_ubah');
    });

    // -----------------------------------------------------------------
    // MODUL: MANAJEMEN AKSES (Module Permissions)
    // -----------------------------------------------------------------
    Route::prefix('akses')->group(function () {
        Route::get('/', [AksesController::class, 'index'])
            ->middleware('cek.hak.akses:akses,hak_baca');
        Route::post('/', [AksesController::class, 'store'])
            ->middleware('cek.hak.akses:akses,hak_buat');
        Route::get('/{akses}', [AksesController::class, 'show'])
            ->middleware('cek.hak.akses:akses,hak_baca');
        Route::put('/{akses}', [AksesController::class, 'update'])
            ->middleware('cek.hak.akses:akses,hak_ubah');
        Route::delete('/{akses}', [AksesController::class, 'destroy'])
            ->middleware('cek.hak.akses:akses,hak_hapus');
    });

    // -----------------------------------------------------------------
    // MODUL: MASTER SEKOLAH
    // -----------------------------------------------------------------

    // Jurusan
    Route::prefix('jurusan')->group(function () {
        Route::get('/', [JurusanController::class, 'index'])
            ->middleware('cek.hak.akses:jurusan,hak_baca');
        Route::post('/', [JurusanController::class, 'store'])
            ->middleware('cek.hak.akses:jurusan,hak_buat');
        Route::get('/{jurusan}', [JurusanController::class, 'show'])
            ->middleware('cek.hak.akses:jurusan,hak_baca');
        Route::put('/{jurusan}', [JurusanController::class, 'update'])
            ->middleware('cek.hak.akses:jurusan,hak_ubah');
        Route::delete('/{jurusan}', [JurusanController::class, 'destroy'])
            ->middleware('cek.hak.akses:jurusan,hak_hapus');
    });

    // Rombel
    Route::prefix('rombel')->group(function () {
        Route::get('/', [RombelController::class, 'index'])
            ->middleware('cek.hak.akses:rombel,hak_baca');
        Route::post('/', [RombelController::class, 'store'])
            ->middleware('cek.hak.akses:rombel,hak_buat');
        Route::get('/{rombel}', [RombelController::class, 'show'])
            ->middleware('cek.hak.akses:rombel,hak_baca');
        Route::put('/{rombel}', [RombelController::class, 'update'])
            ->middleware('cek.hak.akses:rombel,hak_ubah');
        Route::delete('/{rombel}', [RombelController::class, 'destroy'])
            ->middleware('cek.hak.akses:rombel,hak_hapus');
    });

    // Kelas
    Route::prefix('kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index'])
            ->middleware('cek.hak.akses:kelas,hak_baca');
        Route::post('/', [KelasController::class, 'store'])
            ->middleware('cek.hak.akses:kelas,hak_buat');
        Route::get('/{kelas}', [KelasController::class, 'show'])
            ->middleware('cek.hak.akses:kelas,hak_baca');
        Route::put('/{kelas}', [KelasController::class, 'update'])
            ->middleware('cek.hak.akses:kelas,hak_ubah');
        Route::delete('/{kelas}', [KelasController::class, 'destroy'])
            ->middleware('cek.hak.akses:kelas,hak_hapus');
    });

    // Mapel
    Route::prefix('mapel')->group(function () {
        Route::get('/', [MapelController::class, 'index'])
            ->middleware('cek.hak.akses:mapel,hak_baca');
        Route::post('/', [MapelController::class, 'store'])
            ->middleware('cek.hak.akses:mapel,hak_buat');
        Route::get('/{mapel}', [MapelController::class, 'show'])
            ->middleware('cek.hak.akses:mapel,hak_baca');
        Route::put('/{mapel}', [MapelController::class, 'update'])
            ->middleware('cek.hak.akses:mapel,hak_ubah');
        Route::delete('/{mapel}', [MapelController::class, 'destroy'])
            ->middleware('cek.hak.akses:mapel,hak_hapus');
    });

    // Unit
    Route::prefix('unit')->group(function () {
        Route::get('/', [UnitController::class, 'index'])
            ->middleware('cek.hak.akses:unit,hak_baca');
        Route::post('/', [UnitController::class, 'store'])
            ->middleware('cek.hak.akses:unit,hak_buat');
        Route::get('/{unit}', [UnitController::class, 'show'])
            ->middleware('cek.hak.akses:unit,hak_baca');
        Route::put('/{unit}', [UnitController::class, 'update'])
            ->middleware('cek.hak.akses:unit,hak_ubah');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])
            ->middleware('cek.hak.akses:unit,hak_hapus');
    });

    // -----------------------------------------------------------------
    // MODUL: MASTER BARANG
    // -----------------------------------------------------------------

    // Kategori
    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])
            ->middleware('cek.hak.akses:kategori,hak_baca');
        Route::post('/', [KategoriController::class, 'store'])
            ->middleware('cek.hak.akses:kategori,hak_buat');
        Route::get('/{kategori}', [KategoriController::class, 'show'])
            ->middleware('cek.hak.akses:kategori,hak_baca');
        Route::put('/{kategori}', [KategoriController::class, 'update'])
            ->middleware('cek.hak.akses:kategori,hak_ubah');
        Route::delete('/{kategori}', [KategoriController::class, 'destroy'])
            ->middleware('cek.hak.akses:kategori,hak_hapus');
    });

    // Merek
    Route::prefix('merek')->group(function () {
        Route::get('/', [MerekController::class, 'index'])
            ->middleware('cek.hak.akses:merek,hak_baca');
        Route::post('/', [MerekController::class, 'store'])
            ->middleware('cek.hak.akses:merek,hak_buat');
        Route::get('/{merek}', [MerekController::class, 'show'])
            ->middleware('cek.hak.akses:merek,hak_baca');
        Route::put('/{merek}', [MerekController::class, 'update'])
            ->middleware('cek.hak.akses:merek,hak_ubah');
        Route::delete('/{merek}', [MerekController::class, 'destroy'])
            ->middleware('cek.hak.akses:merek,hak_hapus');
    });

    // Satuan
    Route::prefix('satuan')->group(function () {
        Route::get('/', [SatuanController::class, 'index'])
            ->middleware('cek.hak.akses:satuan,hak_baca');
        Route::post('/', [SatuanController::class, 'store'])
            ->middleware('cek.hak.akses:satuan,hak_buat');
        Route::get('/{satuan}', [SatuanController::class, 'show'])
            ->middleware('cek.hak.akses:satuan,hak_baca');
        Route::put('/{satuan}', [SatuanController::class, 'update'])
            ->middleware('cek.hak.akses:satuan,hak_ubah');
        Route::delete('/{satuan}', [SatuanController::class, 'destroy'])
            ->middleware('cek.hak.akses:satuan,hak_hapus');
    });

    // Master Barang
    Route::prefix('master-barang')->group(function () {
        Route::get('/', [MasterBarangController::class, 'index'])
            ->middleware('cek.hak.akses:master_barang,hak_baca');
        Route::post('/', [MasterBarangController::class, 'store'])
            ->middleware('cek.hak.akses:master_barang,hak_buat');
        Route::get('/{masterBarang}', [MasterBarangController::class, 'show'])
            ->middleware('cek.hak.akses:master_barang,hak_baca');
        Route::put('/{masterBarang}', [MasterBarangController::class, 'update'])
            ->middleware('cek.hak.akses:master_barang,hak_ubah');
        Route::delete('/{masterBarang}', [MasterBarangController::class, 'destroy'])
            ->middleware('cek.hak.akses:master_barang,hak_hapus');
    });

    // =================================================================
    // MODUL: MANAJEMEN ASET (Tahap 3)
    // =================================================================

    // Lokasi
    Route::prefix('lokasi')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])
            ->middleware('cek.hak.akses:lokasi,hak_baca');
        Route::post('/', [LokasiController::class, 'store'])
            ->middleware('cek.hak.akses:lokasi,hak_buat');
        Route::get('/{lokasi}', [LokasiController::class, 'show'])
            ->middleware('cek.hak.akses:lokasi,hak_baca');
        Route::put('/{lokasi}', [LokasiController::class, 'update'])
            ->middleware('cek.hak.akses:lokasi,hak_ubah');
        Route::delete('/{lokasi}', [LokasiController::class, 'destroy'])
            ->middleware('cek.hak.akses:lokasi,hak_hapus');
    });

    // Ruang
    Route::prefix('ruang')->group(function () {
        Route::get('/', [RuangController::class, 'index'])
            ->middleware('cek.hak.akses:ruang,hak_baca');
        Route::post('/', [RuangController::class, 'store'])
            ->middleware('cek.hak.akses:ruang,hak_buat');
        Route::get('/{ruang}', [RuangController::class, 'show'])
            ->middleware('cek.hak.akses:ruang,hak_baca');
        Route::put('/{ruang}', [RuangController::class, 'update'])
            ->middleware('cek.hak.akses:ruang,hak_ubah');
        Route::delete('/{ruang}', [RuangController::class, 'destroy'])
            ->middleware('cek.hak.akses:ruang,hak_hapus');
    });

    // Aset
    Route::prefix('aset')->group(function () {
        Route::get('/', [AsetController::class, 'index'])
            ->middleware('cek.hak.akses:aset,hak_baca');
        Route::post('/', [AsetController::class, 'store'])
            ->middleware('cek.hak.akses:aset,hak_buat');
        Route::get('/{aset}', [AsetController::class, 'show'])
            ->middleware('cek.hak.akses:aset,hak_baca');
        Route::put('/{aset}', [AsetController::class, 'update'])
            ->middleware('cek.hak.akses:aset,hak_ubah');
        Route::delete('/{aset}', [AsetController::class, 'destroy'])
            ->middleware('cek.hak.akses:aset,hak_hapus');
    });

    // =================================================================
    // MODUL: TRANSAKSI PEMINJAMAN & PERMINTAAN (Tahap 4)
    // =================================================================

    // Peminjaman
    Route::prefix('peminjaman')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])
            ->middleware('cek.hak.akses:peminjaman,hak_baca');
        Route::post('/', [PeminjamanController::class, 'store'])
            ->middleware('cek.hak.akses:peminjaman,hak_buat');
        Route::get('/{peminjaman}', [PeminjamanController::class, 'show'])
            ->middleware('cek.hak.akses:peminjaman,hak_baca');
        Route::delete('/{peminjaman}', [PeminjamanController::class, 'destroy'])
            ->middleware('cek.hak.akses:peminjaman,hak_hapus');

        // Endpoint pengembalian
        Route::put('/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])
            ->middleware('cek.hak.akses:peminjaman,hak_ubah');
    });

    // Permintaan
    Route::prefix('permintaan')->group(function () {
        Route::get('/', [PermintaanController::class, 'index'])
            ->middleware('cek.hak.akses:permintaan,hak_baca');
        Route::post('/', [PermintaanController::class, 'store'])
            ->middleware('cek.hak.akses:permintaan,hak_buat');
        Route::get('/{permintaan}', [PermintaanController::class, 'show'])
            ->middleware('cek.hak.akses:permintaan,hak_baca');
        Route::delete('/{permintaan}', [PermintaanController::class, 'destroy'])
            ->middleware('cek.hak.akses:permintaan,hak_hapus');

        // Endpoint persetujuan/penolakan
        Route::put('/{permintaan}/keputusan', [PermintaanController::class, 'keputusan'])
            ->middleware('cek.hak.akses:permintaan,hak_ubah');
    });

    // =================================================================
    // MODUL: PEMELIHARAAN & AKHIR SIKLUS ASET (Tahap 5)
    // =================================================================

    // Mutasi Aset
    Route::prefix('mutasi')->group(function () {
        Route::get('/', [MutasiController::class, 'index'])
            ->middleware('cek.hak.akses:mutasi,hak_baca');
        Route::post('/', [MutasiController::class, 'store'])
            ->middleware('cek.hak.akses:mutasi,hak_buat');
        Route::get('/{mutasi}', [MutasiController::class, 'show'])
            ->middleware('cek.hak.akses:mutasi,hak_baca');
        Route::delete('/{mutasi}', [MutasiController::class, 'destroy'])
            ->middleware('cek.hak.akses:mutasi,hak_hapus');
    });

    // Kerusakan
    Route::prefix('kerusakan')->group(function () {
        Route::get('/', [KerusakanController::class, 'index'])
            ->middleware('cek.hak.akses:kerusakan,hak_baca');
        Route::post('/', [KerusakanController::class, 'store'])
            ->middleware('cek.hak.akses:kerusakan,hak_buat');
        Route::get('/{kerusakan}', [KerusakanController::class, 'show'])
            ->middleware('cek.hak.akses:kerusakan,hak_baca');
        Route::delete('/{kerusakan}', [KerusakanController::class, 'destroy'])
            ->middleware('cek.hak.akses:kerusakan,hak_hapus');
    });

    // Perbaikan
    Route::prefix('perbaikan')->group(function () {
        Route::get('/', [PerbaikanController::class, 'index'])
            ->middleware('cek.hak.akses:perbaikan,hak_baca');
        Route::post('/', [PerbaikanController::class, 'store'])
            ->middleware('cek.hak.akses:perbaikan,hak_buat');
        Route::get('/{perbaikan}', [PerbaikanController::class, 'show'])
            ->middleware('cek.hak.akses:perbaikan,hak_baca');
        Route::delete('/{perbaikan}', [PerbaikanController::class, 'destroy'])
            ->middleware('cek.hak.akses:perbaikan,hak_hapus');
    });

    // Penghapusan Aset
    Route::prefix('penghapusan-aset')->group(function () {
        Route::get('/', [PenghapusanAsetController::class, 'index'])
            ->middleware('cek.hak.akses:penghapusan_aset,hak_baca');
        Route::post('/', [PenghapusanAsetController::class, 'store'])
            ->middleware('cek.hak.akses:penghapusan_aset,hak_buat');
        Route::get('/{penghapusanAset}', [PenghapusanAsetController::class, 'show'])
            ->middleware('cek.hak.akses:penghapusan_aset,hak_baca');
        Route::delete('/{penghapusanAset}', [PenghapusanAsetController::class, 'destroy'])
            ->middleware('cek.hak.akses:penghapusan_aset,hak_hapus');
    });

    // =================================================================
    // MODUL: MANAJEMEN DATABASE (Khusus Super Admin)
    // =================================================================
    Route::prefix('database')->group(function () {
        // Sesuaikan middleware hak aksesnya sesuai sistem Anda
        Route::post('/backup', [DatabaseController::class, 'backup']);
        Route::post('/restore', [DatabaseController::class, 'restore']);
        Route::post('/reset', [DatabaseController::class, 'reset']);
        Route::post('/change-connection', [DatabaseController::class, 'changeConnection']);
    });
});

