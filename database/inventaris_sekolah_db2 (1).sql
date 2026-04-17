-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250718.d42db65a1e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 17, 2026 at 03:56 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris_sekolah_db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `akses`
--

CREATE TABLE `akses` (
  `id_akses` int NOT NULL,
  `nama_modul` varchar(100) NOT NULL,
  `hak_buat` tinyint(1) DEFAULT '0',
  `hak_baca` tinyint(1) DEFAULT '1',
  `hak_ubah` tinyint(1) DEFAULT '0',
  `hak_hapus` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `akses`
--

INSERT INTO `akses` (`id_akses`, `nama_modul`, `hak_buat`, `hak_baca`, `hak_ubah`, `hak_hapus`) VALUES
(1, 'akses', 1, 1, 1, 1),
(2, 'peran', 1, 1, 1, 1),
(3, 'pengguna', 1, 1, 1, 1),
(4, 'jurusan', 1, 1, 1, 1),
(5, 'rombel', 1, 1, 1, 1),
(6, 'kelas', 1, 1, 1, 1),
(7, 'mapel', 1, 1, 1, 1),
(8, 'unit', 1, 1, 1, 1),
(9, 'kategori', 1, 1, 1, 1),
(10, 'merek', 1, 1, 1, 1),
(11, 'satuan', 1, 1, 1, 1),
(12, 'master_barang', 1, 1, 1, 1),
(13, 'lokasi', 1, 1, 1, 1),
(14, 'ruang', 1, 1, 1, 1),
(15, 'aset', 1, 1, 1, 1),
(16, 'peminjaman', 1, 1, 1, 1),
(17, 'permintaan', 1, 1, 1, 1),
(18, 'mutasi', 1, 1, 1, 1),
(19, 'kerusakan', 1, 1, 1, 1),
(20, 'perbaikan', 1, 1, 1, 1),
(21, 'penghapusan_aset', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `kode_barang` varchar(50) NOT NULL,
  `id_master_barang` int NOT NULL,
  `id_ruang` int DEFAULT NULL,
  `tanggal_registrasi` date NOT NULL,
  `kondisi_barang` enum('Baik','Rusak Ringan','Rusak Berat') DEFAULT 'Baik',
  `nilai_residu` decimal(15,2) DEFAULT '0.00',
  `status_ketersediaan` enum('Tersedia','Dipinjam','Non-Aktif','Dihapus') DEFAULT 'Tersedia',
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aset_bangunan`
--

CREATE TABLE `aset_bangunan` (
  `id_bangunan` int NOT NULL,
  `nama_bangunan` varchar(150) NOT NULL,
  `id_tanah` int DEFAULT NULL,
  `luas_bangunan` int NOT NULL,
  `tahun_bangun` year NOT NULL,
  `kondisi_bangunan` enum('Baik','Rusak Ringan','Rusak Berat') DEFAULT 'Baik',
  `konstruksi_bertingkat` enum('Ya','Tidak') DEFAULT 'Tidak',
  `konstruksi_beton` enum('Ya','Tidak') DEFAULT 'Ya',
  `nilai_aset` decimal(15,2) DEFAULT '0.00',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aset_tanah`
--

CREATE TABLE `aset_tanah` (
  `id_tanah` int NOT NULL,
  `nama_tanah` varchar(150) NOT NULL,
  `id_lokasi` int DEFAULT NULL,
  `luas_tanah` int NOT NULL,
  `tahun_pengadaan` year NOT NULL,
  `alamat_lokasi` text NOT NULL,
  `nomor_sertifikat` varchar(100) DEFAULT NULL,
  `status_hak` enum('Hak Milik','Hak Pakai','Hak Guna Bangunan','Sewa','Lainnya') DEFAULT 'Hak Milik',
  `nilai_aset` decimal(15,2) DEFAULT '0.00',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_barang_keluar` int NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `id_master_barang` int NOT NULL,
  `jumlah_keluar` int NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail_pinjam` int NOT NULL,
  `nomor_peminjaman` varchar(50) NOT NULL,
  `kode_barang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengadaan`
--

CREATE TABLE `detail_pengadaan` (
  `id_detail_pengadaan` int NOT NULL,
  `nomor_pengadaan` varchar(50) NOT NULL,
  `id_master_barang` int NOT NULL,
  `jumlah_masuk` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_permintaan`
--

CREATE TABLE `detail_permintaan` (
  `id_detail_permintaan` int NOT NULL,
  `nomor_permintaan` varchar(50) NOT NULL,
  `id_master_barang` int NOT NULL,
  `jumlah_diminta` int NOT NULL,
  `alasan_kebutuhan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Laptop');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `id_rombel` int NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan`
--

CREATE TABLE `kerusakan` (
  `id_kerusakan` int NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `tanggal_lapor` date NOT NULL,
  `id_pelapor` int NOT NULL,
  `deskripsi_kerusakan` text NOT NULL,
  `tingkat_kerusakan` enum('Ringan','Sedang','Berat') NOT NULL,
  `status_kerusakan` enum('Menunggu Pemeriksaan','Sedang Diperbaiki','Selesai','Tidak Bisa Diperbaiki') DEFAULT 'Menunggu Pemeriksaan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id_lokasi` int NOT NULL,
  `nama_lokasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int NOT NULL,
  `nama_mapel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_barang`
--

CREATE TABLE `master_barang` (
  `id_master_barang` int NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `id_kategori` int DEFAULT NULL,
  `id_merek` int DEFAULT NULL,
  `id_satuan` int DEFAULT NULL,
  `jenis_barang` enum('Inventaris','Habis Pakai') NOT NULL,
  `stok_minimal` int DEFAULT '0',
  `stok_aktual` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `master_barang`
--

INSERT INTO `master_barang` (`id_master_barang`, `nama_barang`, `id_kategori`, `id_merek`, `id_satuan`, `jenis_barang`, `stok_minimal`, `stok_aktual`) VALUES
(1, 'contoh', 1, 1, 1, 'Inventaris', 0, 0),
(2, 'Proyektor Epson EB-X51', 1, 1, 1, 'Inventaris', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `merek`
--

CREATE TABLE `merek` (
  `id_merek` int NOT NULL,
  `nama_merek` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `merek`
--

INSERT INTO `merek` (`id_merek`, `nama_merek`) VALUES
(1, 'Asus');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_04_15_064709_create_personal_access_tokens_table', 1),
(4, '2026_04_16_015440_create_sessions_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `mutasi`
--

CREATE TABLE `mutasi` (
  `id_mutasi` int NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `tanggal_mutasi` date NOT NULL,
  `id_ruang_asal` int NOT NULL,
  `id_ruang_tujuan` int NOT NULL,
  `alasan_mutasi` text,
  `id_penanggung_jawab` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opname_aset`
--

CREATE TABLE `opname_aset` (
  `id_opname_aset` int NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `tanggal_opname` date NOT NULL,
  `kondisi_ditemukan` enum('Baik','Rusak Ringan','Rusak Berat','Hilang') NOT NULL,
  `keterangan` text,
  `id_pemeriksa` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opname_stok`
--

CREATE TABLE `opname_stok` (
  `id_opname_stok` int NOT NULL,
  `id_master_barang` int NOT NULL,
  `tanggal_opname` date NOT NULL,
  `stok_sistem` int NOT NULL,
  `stok_fisik` int NOT NULL,
  `selisih` int NOT NULL,
  `keterangan` text,
  `id_pemeriksa` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE `pemasok` (
  `id_pemasok` int NOT NULL,
  `nama_pemasok` varchar(150) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `nomor_peminjaman` varchar(50) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `id_peminjam` int NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `lama_pinjam_hari` int NOT NULL,
  `keterangan` text,
  `status_peminjaman` enum('Sedang Dipinjam','Dikembalikan') DEFAULT 'Sedang Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`nomor_peminjaman`, `tanggal_pinjam`, `id_peminjam`, `nomor_telepon`, `lama_pinjam_hari`, `keterangan`, `status_peminjaman`) VALUES
('', '2026-04-17', 1, '089509242323', 10, 'contoh', 'Sedang Dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `pengadaan`
--

CREATE TABLE `pengadaan` (
  `nomor_pengadaan` varchar(50) NOT NULL,
  `tanggal_pengadaan` date NOT NULL,
  `nomor_permintaan` varchar(50) DEFAULT NULL,
  `id_pemasok` int DEFAULT NULL,
  `total_harga` decimal(15,2) DEFAULT '0.00',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_pengaturan` int NOT NULL,
  `nama_instansi` varchar(150) NOT NULL,
  `alamat_instansi` text,
  `logo_instansi` varchar(255) DEFAULT NULL,
  `wallpaper_aplikasi` varchar(255) DEFAULT NULL,
  `telpon` int DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `kepala_sekolah` varchar(100) DEFAULT NULL,
  `NIP` varchar(25) DEFAULT NULL,
  `bagian_inventaris` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id_pengaturan`, `nama_instansi`, `alamat_instansi`, `logo_instansi`, `wallpaper_aplikasi`, `telpon`, `website`, `email`, `kota`, `kepala_sekolah`, `NIP`, `bagian_inventaris`) VALUES
(1, 'SMKN 1 Bangil', NULL, NULL, 'default_wallpaper.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int NOT NULL,
  `nomor_peminjaman` varchar(50) NOT NULL,
  `tanggal_kembali` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_peran` int NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `id_mapel` int DEFAULT NULL,
  `id_unit` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `id_peran`, `id_kelas`, `id_mapel`, `id_unit`) VALUES
(1, 'admin', 'admin', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penghapusan_aset`
--

CREATE TABLE `penghapusan_aset` (
  `id_penghapusan` int NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `tanggal_hapus` date NOT NULL,
  `alasan_hapus` text NOT NULL,
  `id_penyetuju` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peran`
--

CREATE TABLE `peran` (
  `id_peran` int NOT NULL,
  `nama_peran` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peran`
--

INSERT INTO `peran` (`id_peran`, `nama_peran`) VALUES
(1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `peran_akses`
--

CREATE TABLE `peran_akses` (
  `id_peran_akses` int NOT NULL,
  `id_peran` int NOT NULL,
  `id_akses` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peran_akses`
--

INSERT INTO `peran_akses` (`id_peran_akses`, `id_peran`, `id_akses`) VALUES
(1, 1, 1),
(3, 1, 2),
(4, 1, 3),
(5, 1, 4),
(6, 1, 5),
(7, 1, 6),
(8, 1, 7),
(9, 1, 8),
(10, 1, 9),
(11, 1, 10),
(12, 1, 11),
(13, 1, 12),
(14, 1, 13),
(15, 1, 14),
(16, 1, 15),
(17, 1, 16),
(18, 1, 17),
(19, 1, 18),
(20, 1, 19),
(21, 1, 20),
(22, 1, 21);

-- --------------------------------------------------------

--
-- Table structure for table `perbaikan`
--

CREATE TABLE `perbaikan` (
  `id_perbaikan` int NOT NULL,
  `id_kerusakan` int NOT NULL,
  `tanggal_perbaikan` date NOT NULL,
  `teknisi` varchar(150) DEFAULT NULL,
  `biaya_perbaikan` decimal(15,2) DEFAULT '0.00',
  `tindakan_perbaikan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permintaan`
--

CREATE TABLE `permintaan` (
  `nomor_permintaan` varchar(50) NOT NULL,
  `tanggal_permintaan` date NOT NULL,
  `id_pemohon` int NOT NULL,
  `keterangan_keperluan` text NOT NULL,
  `status_persetujuan` enum('Menunggu','Disetujui','Ditolak') DEFAULT 'Menunggu',
  `tanggal_persetujuan` date DEFAULT NULL,
  `id_penyetuju` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Pengguna', 1, 'auth_token', '594c0e5579aa43424ba00f6f81cb57b2b95286ff8027ba0502c60d25e725a718', '[\"*\"]', NULL, NULL, '2026-04-15 19:33:20', '2026-04-15 19:33:20'),
(2, 'App\\Models\\Pengguna', 1, 'auth_token', 'f7be14c8661360014b501819d4089c7e05f369ac3afeb202b3f92c2710ea0d28', '[\"*\"]', NULL, NULL, '2026-04-15 19:44:22', '2026-04-15 19:44:22'),
(3, 'App\\Models\\Pengguna', 1, 'auth_token', '9de343e3f893b17ddf4d18f5386ec75f93bed08a1e3b5b1828b9c7fd7dbf1683', '[\"*\"]', '2026-04-15 20:02:09', NULL, '2026-04-15 19:58:40', '2026-04-15 20:02:09'),
(4, 'App\\Models\\Pengguna', 1, 'auth_token', 'f9d2b5931c011d4f3dd49ba3c6d9c1ab40a74e10ecf7024205e81b33504a6da0', '[\"*\"]', '2026-04-15 23:51:50', NULL, '2026-04-15 23:44:52', '2026-04-15 23:51:50'),
(5, 'App\\Models\\Pengguna', 1, 'auth_token', '156e6f1c27225e8ca2c98baa2daa97fc71e7e2475a0b4fce3ec822c39db74536', '[\"*\"]', '2026-04-16 00:33:58', NULL, '2026-04-16 00:11:47', '2026-04-16 00:33:58'),
(6, 'App\\Models\\Pengguna', 1, 'auth_token', '47cdd5f5466a0a07c17b630e29a70d50741b13edbf20bed90b3e221843563930', '[\"*\"]', NULL, NULL, '2026-04-16 00:28:42', '2026-04-16 00:28:42'),
(7, 'App\\Models\\Pengguna', 1, 'auth_token', 'ca7d9a38474742900005a22f2efc236de1515a8cfb5a36b97f7be247dad8f47b', '[\"*\"]', NULL, NULL, '2026-04-16 00:31:59', '2026-04-16 00:31:59'),
(8, 'App\\Models\\Pengguna', 1, 'auth_token', '42325189a2cbe761df497d5688ee216512cd0bc366d7b9ee5f9ff44f4c5b782e', '[\"*\"]', '2026-04-16 00:42:40', NULL, '2026-04-16 00:34:38', '2026-04-16 00:42:40'),
(9, 'App\\Models\\Pengguna', 1, 'auth_token', 'c192c464a12cf0f721f1ca324d0d2719ab23c1e7f5f507d341fa64cbc6554ff0', '[\"*\"]', '2026-04-16 09:13:00', NULL, '2026-04-16 09:11:00', '2026-04-16 09:13:00'),
(10, 'App\\Models\\Pengguna', 1, 'auth_token', '554abb5696ccf4ecf248a5810b89390bffb723643e5f436569e9a8d53cb44efd', '[\"*\"]', '2026-04-16 20:55:02', NULL, '2026-04-16 20:48:56', '2026-04-16 20:55:02');

-- --------------------------------------------------------

--
-- Table structure for table `rombel`
--

CREATE TABLE `rombel` (
  `id_rombel` int NOT NULL,
  `id_jurusan` int NOT NULL,
  `nama_rombel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ruang`
--

CREATE TABLE `ruang` (
  `id_ruang` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `nama_ruang` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id_satuan` int NOT NULL,
  `nama_satuan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id_satuan`, `nama_satuan`) VALUES
(1, 'contoh');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6W90p37ku8ECbcVfYP3VCiJUJYIqZUXtaM9jODBE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJmeUt6RXRabEdMVlgxTVREU1YzMllqa1A4N2UwbEd4SjBpVm1OTktKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776321844),
('CXqoXtRDBu8ZFlKacdijXTMqgoMBa7nC7ddTfHe6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJod0RXalIzWUd1WWNpSGxueXZwVjlxNFpFMmN6d3FZb2padHBMRk1pIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776308179),
('HUtLNzqAFdUEa0k5KfkmboW3NYUUM2bBmQ8BBkOg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJOemlzMERGNnZWVXV4RmZxQTdVWVhicWRYdVVlY2c0bjJWdlp3elVkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776355782),
('l87XAVYgEJAJXJ6jr4EINLNfeazIXm50NFkR71RH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIzUGk1MXN4RFJxejZRV2hpcFAzZ2FMNVR5aVFxcGp3YUdtZkNwUnZiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776397722),
('QMmgL5SzLKS5L74gnhgqzKGSzofoJKPSPu8sohGD', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJxdWFPRjVBSWNxcktOZjZDcnV2ZUlHQWY4Y01rbVNmNExVWWdGNGlUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776323165),
('shtYmS02LbqCaubQ8z6gwJOi9RGATF6YsZkq0iU1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqSmVIc0hIY2ZwdlM0UnFVQmtIUENzSU1MRGx5MHB0a0x1UlpqZFdWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776309127);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id_unit` int NOT NULL,
  `nama_unit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akses`
--
ALTER TABLE `akses`
  ADD PRIMARY KEY (`id_akses`);

--
-- Indexes for table `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`kode_barang`),
  ADD KEY `id_master_barang` (`id_master_barang`),
  ADD KEY `id_ruang` (`id_ruang`);

--
-- Indexes for table `aset_bangunan`
--
ALTER TABLE `aset_bangunan`
  ADD PRIMARY KEY (`id_bangunan`),
  ADD KEY `id_tanah` (`id_tanah`);

--
-- Indexes for table `aset_tanah`
--
ALTER TABLE `aset_tanah`
  ADD PRIMARY KEY (`id_tanah`),
  ADD KEY `id_lokasi` (`id_lokasi`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_barang_keluar`),
  ADD KEY `id_master_barang` (`id_master_barang`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail_pinjam`),
  ADD KEY `nomor_peminjaman` (`nomor_peminjaman`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  ADD PRIMARY KEY (`id_detail_pengadaan`),
  ADD KEY `nomor_pengadaan` (`nomor_pengadaan`),
  ADD KEY `id_master_barang` (`id_master_barang`);

--
-- Indexes for table `detail_permintaan`
--
ALTER TABLE `detail_permintaan`
  ADD PRIMARY KEY (`id_detail_permintaan`),
  ADD KEY `nomor_permintaan` (`nomor_permintaan`),
  ADD KEY `id_master_barang` (`id_master_barang`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_rombel` (`id_rombel`);

--
-- Indexes for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD PRIMARY KEY (`id_kerusakan`),
  ADD KEY `kode_barang` (`kode_barang`),
  ADD KEY `id_pelapor` (`id_pelapor`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `master_barang`
--
ALTER TABLE `master_barang`
  ADD PRIMARY KEY (`id_master_barang`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_merek` (`id_merek`),
  ADD KEY `id_satuan` (`id_satuan`);

--
-- Indexes for table `merek`
--
ALTER TABLE `merek`
  ADD PRIMARY KEY (`id_merek`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`id_mutasi`),
  ADD KEY `kode_barang` (`kode_barang`),
  ADD KEY `id_ruang_asal` (`id_ruang_asal`),
  ADD KEY `id_ruang_tujuan` (`id_ruang_tujuan`),
  ADD KEY `id_penanggung_jawab` (`id_penanggung_jawab`);

--
-- Indexes for table `opname_aset`
--
ALTER TABLE `opname_aset`
  ADD PRIMARY KEY (`id_opname_aset`),
  ADD KEY `kode_barang` (`kode_barang`),
  ADD KEY `id_pemeriksa` (`id_pemeriksa`);

--
-- Indexes for table `opname_stok`
--
ALTER TABLE `opname_stok`
  ADD PRIMARY KEY (`id_opname_stok`),
  ADD KEY `id_master_barang` (`id_master_barang`),
  ADD KEY `id_pemeriksa` (`id_pemeriksa`);

--
-- Indexes for table `pemasok`
--
ALTER TABLE `pemasok`
  ADD PRIMARY KEY (`id_pemasok`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`nomor_peminjaman`),
  ADD KEY `id_peminjam` (`id_peminjam`);

--
-- Indexes for table `pengadaan`
--
ALTER TABLE `pengadaan`
  ADD PRIMARY KEY (`nomor_pengadaan`),
  ADD KEY `nomor_permintaan` (`nomor_permintaan`),
  ADD KEY `id_pemasok` (`id_pemasok`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `nomor_peminjaman` (`nomor_peminjaman`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD KEY `id_peran` (`id_peran`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_mapel` (`id_mapel`),
  ADD KEY `id_unit` (`id_unit`);

--
-- Indexes for table `penghapusan_aset`
--
ALTER TABLE `penghapusan_aset`
  ADD PRIMARY KEY (`id_penghapusan`),
  ADD KEY `kode_barang` (`kode_barang`),
  ADD KEY `id_penyetuju` (`id_penyetuju`);

--
-- Indexes for table `peran`
--
ALTER TABLE `peran`
  ADD PRIMARY KEY (`id_peran`);

--
-- Indexes for table `peran_akses`
--
ALTER TABLE `peran_akses`
  ADD PRIMARY KEY (`id_peran_akses`),
  ADD KEY `id_peran` (`id_peran`),
  ADD KEY `id_akses` (`id_akses`);

--
-- Indexes for table `perbaikan`
--
ALTER TABLE `perbaikan`
  ADD PRIMARY KEY (`id_perbaikan`),
  ADD KEY `id_kerusakan` (`id_kerusakan`);

--
-- Indexes for table `permintaan`
--
ALTER TABLE `permintaan`
  ADD PRIMARY KEY (`nomor_permintaan`),
  ADD KEY `id_pemohon` (`id_pemohon`),
  ADD KEY `id_penyetuju` (`id_penyetuju`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `rombel`
--
ALTER TABLE `rombel`
  ADD PRIMARY KEY (`id_rombel`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indexes for table `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`id_ruang`),
  ADD KEY `id_lokasi` (`id_lokasi`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id_unit`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akses`
--
ALTER TABLE `akses`
  MODIFY `id_akses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `aset_bangunan`
--
ALTER TABLE `aset_bangunan`
  MODIFY `id_bangunan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aset_tanah`
--
ALTER TABLE `aset_tanah`
  MODIFY `id_tanah` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_barang_keluar` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail_pinjam` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  MODIFY `id_detail_pengadaan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_permintaan`
--
ALTER TABLE `detail_permintaan`
  MODIFY `id_detail_permintaan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `id_kerusakan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id_lokasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_barang`
--
ALTER TABLE `master_barang`
  MODIFY `id_master_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `merek`
--
ALTER TABLE `merek`
  MODIFY `id_merek` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mutasi`
--
ALTER TABLE `mutasi`
  MODIFY `id_mutasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opname_aset`
--
ALTER TABLE `opname_aset`
  MODIFY `id_opname_aset` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opname_stok`
--
ALTER TABLE `opname_stok`
  MODIFY `id_opname_stok` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemasok`
--
ALTER TABLE `pemasok`
  MODIFY `id_pemasok` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penghapusan_aset`
--
ALTER TABLE `penghapusan_aset`
  MODIFY `id_penghapusan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peran`
--
ALTER TABLE `peran`
  MODIFY `id_peran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `peran_akses`
--
ALTER TABLE `peran_akses`
  MODIFY `id_peran_akses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `perbaikan`
--
ALTER TABLE `perbaikan`
  MODIFY `id_perbaikan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rombel`
--
ALTER TABLE `rombel`
  MODIFY `id_rombel` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruang`
--
ALTER TABLE `ruang`
  MODIFY `id_ruang` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id_satuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id_unit` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `aset_ibfk_2` FOREIGN KEY (`id_ruang`) REFERENCES `ruang` (`id_ruang`) ON DELETE SET NULL;

--
-- Constraints for table `aset_bangunan`
--
ALTER TABLE `aset_bangunan`
  ADD CONSTRAINT `aset_bangunan_ibfk_1` FOREIGN KEY (`id_tanah`) REFERENCES `aset_tanah` (`id_tanah`) ON DELETE SET NULL;

--
-- Constraints for table `aset_tanah`
--
ALTER TABLE `aset_tanah`
  ADD CONSTRAINT `aset_tanah_ibfk_1` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi` (`id_lokasi`) ON DELETE SET NULL;

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`) ON DELETE CASCADE;

--
-- Constraints for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_ibfk_1` FOREIGN KEY (`nomor_peminjaman`) REFERENCES `peminjaman` (`nomor_peminjaman`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_peminjaman_ibfk_2` FOREIGN KEY (`kode_barang`) REFERENCES `aset` (`kode_barang`) ON DELETE CASCADE;

--
-- Constraints for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  ADD CONSTRAINT `detail_pengadaan_ibfk_1` FOREIGN KEY (`nomor_pengadaan`) REFERENCES `pengadaan` (`nomor_pengadaan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pengadaan_ibfk_2` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`) ON DELETE CASCADE;

--
-- Constraints for table `detail_permintaan`
--
ALTER TABLE `detail_permintaan`
  ADD CONSTRAINT `detail_permintaan_ibfk_1` FOREIGN KEY (`nomor_permintaan`) REFERENCES `permintaan` (`nomor_permintaan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_permintaan_ibfk_2` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_rombel`) REFERENCES `rombel` (`id_rombel`) ON DELETE CASCADE;

--
-- Constraints for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD CONSTRAINT `kerusakan_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `aset` (`kode_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `kerusakan_ibfk_2` FOREIGN KEY (`id_pelapor`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;

--
-- Constraints for table `master_barang`
--
ALTER TABLE `master_barang`
  ADD CONSTRAINT `master_barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL,
  ADD CONSTRAINT `master_barang_ibfk_2` FOREIGN KEY (`id_merek`) REFERENCES `merek` (`id_merek`) ON DELETE SET NULL,
  ADD CONSTRAINT `master_barang_ibfk_3` FOREIGN KEY (`id_satuan`) REFERENCES `satuan` (`id_satuan`) ON DELETE SET NULL;

--
-- Constraints for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD CONSTRAINT `mutasi_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `aset` (`kode_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `mutasi_ibfk_2` FOREIGN KEY (`id_ruang_asal`) REFERENCES `ruang` (`id_ruang`) ON DELETE RESTRICT,
  ADD CONSTRAINT `mutasi_ibfk_3` FOREIGN KEY (`id_ruang_tujuan`) REFERENCES `ruang` (`id_ruang`) ON DELETE RESTRICT,
  ADD CONSTRAINT `mutasi_ibfk_4` FOREIGN KEY (`id_penanggung_jawab`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Constraints for table `opname_aset`
--
ALTER TABLE `opname_aset`
  ADD CONSTRAINT `opname_aset_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `aset` (`kode_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `opname_aset_ibfk_2` FOREIGN KEY (`id_pemeriksa`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Constraints for table `opname_stok`
--
ALTER TABLE `opname_stok`
  ADD CONSTRAINT `opname_stok_ibfk_1` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `opname_stok_ibfk_2` FOREIGN KEY (`id_pemeriksa`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_peminjam`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Constraints for table `pengadaan`
--
ALTER TABLE `pengadaan`
  ADD CONSTRAINT `pengadaan_ibfk_1` FOREIGN KEY (`nomor_permintaan`) REFERENCES `permintaan` (`nomor_permintaan`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengadaan_ibfk_2` FOREIGN KEY (`id_pemasok`) REFERENCES `pemasok` (`id_pemasok`) ON DELETE SET NULL;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`nomor_peminjaman`) REFERENCES `peminjaman` (`nomor_peminjaman`) ON DELETE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id_peran`) REFERENCES `peran` (`id_peran`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pengguna_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengguna_ibfk_3` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id_mapel`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengguna_ibfk_4` FOREIGN KEY (`id_unit`) REFERENCES `unit` (`id_unit`) ON DELETE SET NULL;

--
-- Constraints for table `penghapusan_aset`
--
ALTER TABLE `penghapusan_aset`
  ADD CONSTRAINT `penghapusan_aset_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `aset` (`kode_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `penghapusan_aset_ibfk_2` FOREIGN KEY (`id_penyetuju`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Constraints for table `peran_akses`
--
ALTER TABLE `peran_akses`
  ADD CONSTRAINT `peran_akses_ibfk_1` FOREIGN KEY (`id_peran`) REFERENCES `peran` (`id_peran`) ON DELETE CASCADE,
  ADD CONSTRAINT `peran_akses_ibfk_2` FOREIGN KEY (`id_akses`) REFERENCES `akses` (`id_akses`) ON DELETE CASCADE;

--
-- Constraints for table `perbaikan`
--
ALTER TABLE `perbaikan`
  ADD CONSTRAINT `perbaikan_ibfk_1` FOREIGN KEY (`id_kerusakan`) REFERENCES `kerusakan` (`id_kerusakan`) ON DELETE CASCADE;

--
-- Constraints for table `permintaan`
--
ALTER TABLE `permintaan`
  ADD CONSTRAINT `permintaan_ibfk_1` FOREIGN KEY (`id_pemohon`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE,
  ADD CONSTRAINT `permintaan_ibfk_2` FOREIGN KEY (`id_penyetuju`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL;

--
-- Constraints for table `rombel`
--
ALTER TABLE `rombel`
  ADD CONSTRAINT `rombel_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON DELETE CASCADE;

--
-- Constraints for table `ruang`
--
ALTER TABLE `ruang`
  ADD CONSTRAINT `ruang_ibfk_1` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi` (`id_lokasi`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
