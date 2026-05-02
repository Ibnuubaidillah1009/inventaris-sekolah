-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250718.d42db65a1e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 02, 2026 at 01:14 PM
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
(21, 'penghapusan_aset', 1, 1, 1, 1),
(22, 'kondisi', 1, 1, 1, 1),
(23, 'status_barang', 1, 1, 1, 1),
(24, 'opname_aset', 1, 1, 1, 1),
(25, 'opname_aset', 1, 1, 1, 1),
(26, 'pengaturan', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `kode_barang` varchar(50) NOT NULL,
  `id_master_barang` int NOT NULL,
  `id_ruang` int DEFAULT NULL,
  `tanggal_registrasi` date NOT NULL,
  `nilai_residu` decimal(15,2) DEFAULT '0.00',
  `gambar` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `id_status` int DEFAULT NULL,
  `id_kondisi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aset`
--

INSERT INTO `aset` (`kode_barang`, `id_master_barang`, `id_ruang`, `tanggal_registrasi`, `nilai_residu`, `gambar`, `keterangan`, `id_status`, `id_kondisi`) VALUES
('ASET-DUMMY-1', 1, 3, '2026-03-23', 3490819.00, NULL, NULL, 1, 1),
('ASET-DUMMY-2', 1, 3, '2026-04-13', 1898008.00, NULL, NULL, 2, 2),
('ASET-DUMMY-3', 2, 3, '2026-04-02', 4476376.00, NULL, NULL, 2, 2);

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

--
-- Dumping data for table `aset_bangunan`
--

INSERT INTO `aset_bangunan` (`id_bangunan`, `nama_bangunan`, `id_tanah`, `luas_bangunan`, `tahun_bangun`, `kondisi_bangunan`, `konstruksi_bertingkat`, `konstruksi_beton`, `nilai_aset`, `keterangan`) VALUES
(1, 'Bangunan Dummy 1', 1, 433, '2018', 'Baik', 'Tidak', 'Ya', 274246852.00, NULL),
(2, 'Bangunan Dummy 2', 1, 128, '2019', 'Baik', 'Tidak', 'Ya', 121432671.00, NULL),
(3, 'Bangunan Dummy 3', 1, 370, '2014', 'Baik', 'Tidak', 'Ya', 243324979.00, NULL);

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

--
-- Dumping data for table `aset_tanah`
--

INSERT INTO `aset_tanah` (`id_tanah`, `nama_tanah`, `id_lokasi`, `luas_tanah`, `tahun_pengadaan`, `alamat_lokasi`, `nomor_sertifikat`, `status_hak`, `nilai_aset`, `keterangan`) VALUES
(1, 'Tanah Dummy 1', 2, 287, '2010', 'Alamat Dummy Tanah 1', 'SRT-TNH-1', 'Hak Milik', 56114308.00, NULL),
(2, 'Tanah Dummy 2', 1, 413, '2023', 'Alamat Dummy Tanah 2', 'SRT-TNH-2', 'Hak Milik', 97701354.00, NULL),
(3, 'Tanah Dummy 3', 1, 932, '2024', 'Alamat Dummy Tanah 3', 'SRT-TNH-3', 'Hak Milik', 54637484.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_barang_keluar` int NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `id_master_barang` int NOT NULL,
  `jumlah_keluar` int NOT NULL,
  `keterangan` text,
  `kode_gudang` varchar(20) DEFAULT NULL,
  `id_ruang` int DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_barang_keluar`, `tanggal_keluar`, `id_master_barang`, `jumlah_keluar`, `keterangan`, `kode_gudang`, `id_ruang`, `nama_penerima`) VALUES
(1, '2026-04-17', 4, 3, 'Barang Keluar Dummy 1', '1', 1, 'contoh'),
(2, '2026-04-17', 2, 3, 'Barang Keluar Dummy 2', NULL, NULL, NULL),
(3, '2026-04-17', 1, 2, 'Barang Keluar Dummy 3', NULL, NULL, NULL);

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

--
-- Dumping data for table `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail_pinjam`, `nomor_peminjaman`, `kode_barang`) VALUES
(1, 'PJM-DUMMY-1', 'ASET-DUMMY-1'),
(2, 'PJM-DUMMY-2', 'ASET-DUMMY-2'),
(3, 'PJM-DUMMY-3', 'ASET-DUMMY-2');

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

--
-- Dumping data for table `detail_pengadaan`
--

INSERT INTO `detail_pengadaan` (`id_detail_pengadaan`, `nomor_pengadaan`, `id_master_barang`, `jumlah_masuk`, `harga_satuan`) VALUES
(1, 'PGD-DUMMY-1', 4, 3, 19269.00),
(2, 'PGD-DUMMY-2', 2, 5, 21211.00),
(3, 'PGD-DUMMY-3', 1, 5, 30890.00);

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

--
-- Dumping data for table `detail_permintaan`
--

INSERT INTO `detail_permintaan` (`id_detail_permintaan`, `nomor_permintaan`, `id_master_barang`, `jumlah_diminta`, `alasan_kebutuhan`) VALUES
(1, 'PRM-DUMMY-1', 4, 5, 'Alasan Dummy 1'),
(2, 'PRM-DUMMY-2', 2, 8, 'Alasan Dummy 2'),
(3, 'PRM-DUMMY-3', 1, 10, 'Alasan Dummy 3');

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
-- Table structure for table `gudang`
--

CREATE TABLE `gudang` (
  `kode_gudang` varchar(20) NOT NULL,
  `nama_gudang` varchar(100) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gudang`
--

INSERT INTO `gudang` (`kode_gudang`, `nama_gudang`, `keterangan`) VALUES
('1', 'gudang 1', 'contoh gudang 1');

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

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(1, 'Jurusan Dummy 1'),
(2, 'Jurusan Dummy 2'),
(3, 'Jurusan Dummy 3');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `keterangan`) VALUES
(1, 'Laptop', NULL),
(2, 'Kategori Dummy 1', NULL),
(3, 'Kategori Dummy 2', NULL),
(4, 'Kategori Dummy 3', NULL),
(5, 'Elektronik', NULL),
(6, 'Furnitur', NULL),
(8, 'contoh2', 'contoh');

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

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `id_rombel`, `nama_kelas`, `tahun_ajaran`) VALUES
(1, 1, 'Kelas 1', '2025/2026'),
(2, 2, 'Kelas 2', '2025/2026'),
(3, 2, 'Kelas 3', '2025/2026');

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

--
-- Dumping data for table `kerusakan`
--

INSERT INTO `kerusakan` (`id_kerusakan`, `kode_barang`, `tanggal_lapor`, `id_pelapor`, `deskripsi_kerusakan`, `tingkat_kerusakan`, `status_kerusakan`) VALUES
(1, 'ASET-DUMMY-1', '2026-04-17', 4, 'Kerusakan Dummy 1', 'Ringan', 'Menunggu Pemeriksaan'),
(2, 'ASET-DUMMY-2', '2026-04-17', 4, 'Kerusakan Dummy 2', 'Ringan', 'Menunggu Pemeriksaan'),
(3, 'ASET-DUMMY-2', '2026-04-17', 3, 'Kerusakan Dummy 3', 'Ringan', 'Menunggu Pemeriksaan'),
(4, 'ASET-DUMMY-1', '2026-04-16', 1, 'Layar monitor berkedip-kedip', 'Ringan', 'Sedang Diperbaiki');

-- --------------------------------------------------------

--
-- Table structure for table `kondisi`
--

CREATE TABLE `kondisi` (
  `id_kondisi` int NOT NULL,
  `nama_kondisi` varchar(50) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kondisi`
--

INSERT INTO `kondisi` (`id_kondisi`, `nama_kondisi`, `keterangan`) VALUES
(1, 'Rusak Berat', NULL),
(2, 'Baik', NULL),
(4, 'Rusak', 'Kondisi barang baik dan layak pakai');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id_lokasi` int NOT NULL,
  `nama_lokasi` varchar(100) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id_lokasi`, `nama_lokasi`, `keterangan`) VALUES
(1, 'Lokasi Dummy 1', NULL),
(2, 'Lokasi Dummy 2', NULL),
(3, 'Lokasi Dummy 3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int NOT NULL,
  `nama_mapel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `nama_mapel`) VALUES
(1, 'Mapel Dummy 1'),
(2, 'Mapel Dummy 2'),
(3, 'Mapel Dummy 3');

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
  `stok_aktual` int DEFAULT '0',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `master_barang`
--

INSERT INTO `master_barang` (`id_master_barang`, `nama_barang`, `id_kategori`, `id_merek`, `id_satuan`, `jenis_barang`, `stok_minimal`, `stok_aktual`, `keterangan`) VALUES
(1, 'contoh', 1, 1, 1, 'Inventaris', 0, 0, NULL),
(2, 'Proyektor Epson EB-X51', 1, 1, 1, 'Inventaris', 0, 0, NULL),
(3, 'Barang Dummy 2', 1, 1, 4, 'Inventaris', 5, 10, NULL),
(4, 'Barang Dummy 3', 4, 4, 2, 'Inventaris', 5, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `merek`
--

CREATE TABLE `merek` (
  `id_merek` int NOT NULL,
  `nama_merek` varchar(100) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `merek`
--

INSERT INTO `merek` (`id_merek`, `nama_merek`, `keterangan`) VALUES
(1, 'Asus', NULL),
(2, 'Merek Dummy 1', NULL),
(3, 'Merek Dummy 2', NULL),
(4, 'Merek Dummy 3', NULL);

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

--
-- Dumping data for table `mutasi`
--

INSERT INTO `mutasi` (`id_mutasi`, `kode_barang`, `tanggal_mutasi`, `id_ruang_asal`, `id_ruang_tujuan`, `alasan_mutasi`, `id_penanggung_jawab`) VALUES
(1, 'ASET-DUMMY-1', '2026-04-17', 1, 1, 'Alasan Mutasi Dummy 1', 4),
(2, 'ASET-DUMMY-2', '2026-04-17', 2, 2, 'Alasan Mutasi Dummy 2', 4),
(3, 'ASET-DUMMY-2', '2026-04-17', 2, 2, 'Alasan Mutasi Dummy 3', 3);

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

--
-- Dumping data for table `opname_aset`
--

INSERT INTO `opname_aset` (`id_opname_aset`, `kode_barang`, `tanggal_opname`, `kondisi_ditemukan`, `keterangan`, `id_pemeriksa`) VALUES
(1, 'ASET-DUMMY-1', '2026-04-17', 'Baik', 'Opname Aset Dummy 1', 4),
(2, 'ASET-DUMMY-2', '2026-04-17', 'Baik', 'Opname Aset Dummy 2', 4),
(3, 'ASET-DUMMY-2', '2026-04-17', 'Baik', 'Opname Aset Dummy 3', 3),
(4, 'ASET-DUMMY-1', '2026-04-27', 'Baik', 'Sesuai data', 1);

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

--
-- Dumping data for table `opname_stok`
--

INSERT INTO `opname_stok` (`id_opname_stok`, `id_master_barang`, `tanggal_opname`, `stok_sistem`, `stok_fisik`, `selisih`, `keterangan`, `id_pemeriksa`) VALUES
(1, 4, '2026-04-17', 10, 10, 0, 'Opname Stok Dummy 1', 4),
(2, 2, '2026-04-17', 10, 10, 0, 'Opname Stok Dummy 2', 1),
(3, 1, '2026-04-17', 10, 10, 0, 'Opname Stok Dummy 3', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE `pemasok` (
  `id_pemasok` int NOT NULL,
  `nama_pemasok` varchar(150) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `alamat` text,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pemasok`
--

INSERT INTO `pemasok` (`id_pemasok`, `nama_pemasok`, `nomor_telepon`, `alamat`, `keterangan`) VALUES
(1, 'Pemasok Dummy 1', '08123456781', 'Alamat Pemasok 1', NULL),
(2, 'Pemasok Dummy 2', '08123456782', 'Alamat Pemasok 2', NULL),
(3, 'Pemasok Dummy 3', '08123456783', 'Alamat Pemasok 3', NULL);

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
('', '2026-04-17', 1, '089509242323', 10, 'contoh', 'Sedang Dipinjam'),
('PJM-DUMMY-1', '2026-04-07', 4, '08988771', 14, 'Peminjaman Dummy 1', 'Dikembalikan'),
('PJM-DUMMY-2', '2026-04-07', 4, '08988772', 14, 'Peminjaman Dummy 2', 'Dikembalikan'),
('PJM-DUMMY-3', '2026-04-07', 3, '08988773', 14, 'Peminjaman Dummy 3', 'Dikembalikan');

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
  `keterangan` text,
  `kode_gudang` varchar(20) DEFAULT NULL,
  `jumlah_pengadaan` int DEFAULT NULL,
  `id_satuan` int DEFAULT NULL,
  `id_master_barang` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengadaan`
--

INSERT INTO `pengadaan` (`nomor_pengadaan`, `tanggal_pengadaan`, `nomor_permintaan`, `id_pemasok`, `total_harga`, `keterangan`, `kode_gudang`, `jumlah_pengadaan`, `id_satuan`, `id_master_barang`) VALUES
('PGD-DUMMY-1', '2026-04-17', 'PRM-DUMMY-1', 2, 355478.00, 'Keterangan Dummy Pengadaan 1', '1', 10, 2, 3),
('PGD-DUMMY-2', '2026-04-17', 'PRM-DUMMY-2', 1, 498442.00, 'Keterangan Dummy Pengadaan 2', NULL, NULL, NULL, NULL),
('PGD-DUMMY-3', '2026-04-17', 'PRM-DUMMY-3', 1, 427970.00, 'Keterangan Dummy Pengadaan 3', NULL, NULL, NULL, NULL);

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
(1, 'SMKN 1 Bangil', NULL, NULL, 'default_wallpaper.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Instansi Dummy 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Instansi Dummy 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Instansi Dummy 3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'SMK Negeri 1 Contoh Updated', NULL, NULL, NULL, NULL, NULL, NULL, 'Jakarta', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int NOT NULL,
  `nomor_peminjaman` varchar(50) NOT NULL,
  `tanggal_kembali` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`id_pengembalian`, `nomor_peminjaman`, `tanggal_kembali`) VALUES
(1, 'PJM-DUMMY-1', '2026-04-17'),
(2, 'PJM-DUMMY-2', '2026-04-17'),
(3, 'PJM-DUMMY-3', '2026-04-17');

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
(1, 'admin', '$2a$12$aUxVp8g3vj/kwS2co.98VOSkTxuzLUS1ziJT4jWQiKUK8CqzgdEle', 1, NULL, NULL, NULL),
(2, 'user_dummy_1', '$2y$12$N3LZo/PIpXST1oPooKAkzeKjQdC1v1.gr4FCcIeLHSxfA3LomC5E6', 3, 1, 3, 1),
(3, 'user_dummy_2', '$2y$12$utCBKmEvHtDuDm3fT6aYiuXwRWmrlwIgNQXV4jVQAsilgWjOlhL2G', 4, 1, 2, 3),
(4, 'admin2', '$2y$12$9nCKqOXUH1aZHiNgO/2juegf5G.yOOr9u3Aot/mMzBVDJiPGB9Y7K', 1, 1, 3, 1),
(5, 'guru', '$2y$12$iYz61SEOcIst3GpI/c6n3eP9nPbUsB5xm8nNJmx2f45J8qA7mNY6G', 2, NULL, 3, NULL),
(6, 'contoh', '$2y$12$b0jZY1pRmMfQhrvt8f0tx.GkOoSndu9OZY4ZlxRum9dFw4bwidiUy', 5, NULL, NULL, NULL);

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

--
-- Dumping data for table `penghapusan_aset`
--

INSERT INTO `penghapusan_aset` (`id_penghapusan`, `kode_barang`, `tanggal_hapus`, `alasan_hapus`, `id_penyetuju`) VALUES
(1, 'ASET-DUMMY-1', '2026-04-17', 'Penghapusan Dummy 1', 1),
(2, 'ASET-DUMMY-2', '2026-04-17', 'Penghapusan Dummy 2', 1),
(3, 'ASET-DUMMY-2', '2026-04-17', 'Penghapusan Dummy 3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `peran`
--

CREATE TABLE `peran` (
  `id_peran` int NOT NULL,
  `nama_peran` varchar(50) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peran`
--

INSERT INTO `peran` (`id_peran`, `nama_peran`, `keterangan`) VALUES
(1, 'Admin', NULL),
(2, 'Peran Dummy 1', NULL),
(3, 'Peran Dummy 2', NULL),
(4, 'Peran Dummy 3', NULL),
(5, 'Toolman', 'contoh');

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
(22, 1, 21),
(23, 4, 22),
(24, 1, 22),
(25, 1, 23),
(26, 1, 24),
(27, 1, 24),
(28, 1, 26);

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

--
-- Dumping data for table `perbaikan`
--

INSERT INTO `perbaikan` (`id_perbaikan`, `id_kerusakan`, `tanggal_perbaikan`, `teknisi`, `biaya_perbaikan`, `tindakan_perbaikan`) VALUES
(1, 1, '2026-04-17', 'Teknisi Dummy 1', 79863.00, 'Tindakan Dummy 1'),
(2, 2, '2026-04-17', 'Teknisi Dummy 2', 147573.00, 'Tindakan Dummy 2'),
(3, 3, '2026-04-17', 'Teknisi Dummy 3', 147241.00, 'Tindakan Dummy 3'),
(4, 4, '2026-04-17', 'CV Teknik Jaya', 500000.00, 'Ganti layar LCD');

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

--
-- Dumping data for table `permintaan`
--

INSERT INTO `permintaan` (`nomor_permintaan`, `tanggal_permintaan`, `id_pemohon`, `keterangan_keperluan`, `status_persetujuan`, `tanggal_persetujuan`, `id_penyetuju`) VALUES
('PRM-DUMMY-1', '2026-04-16', 4, 'Keperluan Dummy 1', 'Disetujui', NULL, 1),
('PRM-DUMMY-2', '2026-04-11', 1, 'Keperluan Dummy 2', 'Disetujui', NULL, 1),
('PRM-DUMMY-3', '2026-04-13', 3, 'Keperluan Dummy 3', 'Disetujui', NULL, 1);

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
(10, 'App\\Models\\Pengguna', 1, 'auth_token', '554abb5696ccf4ecf248a5810b89390bffb723643e5f436569e9a8d53cb44efd', '[\"*\"]', '2026-04-16 20:55:02', NULL, '2026-04-16 20:48:56', '2026-04-16 20:55:02'),
(11, 'App\\Models\\Pengguna', 1, 'auth_token', '24db8714ed3a5d17586243cb0b6084e112585b880bb69caf591bb7eae97f0541', '[\"*\"]', '2026-04-16 22:36:15', NULL, '2026-04-16 21:09:22', '2026-04-16 22:36:15'),
(12, 'App\\Models\\Pengguna', 1, 'auth_token', '1c30045f29ae0b99f0a4c869766655cfab47d76892b2f36851c393d6973ad6fd', '[\"*\"]', '2026-04-17 07:35:37', NULL, '2026-04-17 07:22:44', '2026-04-17 07:35:37'),
(13, 'App\\Models\\Pengguna', 1, 'auth_token', '4046f65d617ccadfbb65a66ab8af4cf814d2b0c053b33e94c162a0f60035bfe5', '[\"*\"]', '2026-04-19 07:19:19', NULL, '2026-04-19 06:58:28', '2026-04-19 07:19:19'),
(14, 'App\\Models\\Pengguna', 1, 'auth_token', 'ea1e9ae0696e13c7771e3e9947b4b52a3f667d2d8d6aa9ad4a3bfe3545ad8b19', '[\"*\"]', '2026-04-19 08:01:30', NULL, '2026-04-19 08:01:21', '2026-04-19 08:01:30'),
(15, 'App\\Models\\Pengguna', 1, 'auth_token', '5365011b1ff44f9cc5f90d80710edaa6397ce3136ed03757d004cd6281f86a21', '[\"*\"]', NULL, NULL, '2026-04-19 08:19:42', '2026-04-19 08:19:42'),
(16, 'App\\Models\\Pengguna', 1, 'auth_token', 'c5f72d551df4c04c723835e9f028883b5efe9b5c15950643d0377e738da02004', '[\"*\"]', NULL, NULL, '2026-04-19 19:07:03', '2026-04-19 19:07:03'),
(17, 'App\\Models\\Pengguna', 1, 'auth_token', 'f4bc1df587423afd6add51eb12214d786732f4d46f056932487bff86a50fa256', '[\"*\"]', NULL, NULL, '2026-04-19 19:07:56', '2026-04-19 19:07:56'),
(18, 'App\\Models\\Pengguna', 1, 'auth_token', '911ceb54cc159c807c89e536d620eebdca9494c839465030f58f444c8baa824f', '[\"*\"]', NULL, NULL, '2026-04-19 19:08:05', '2026-04-19 19:08:05'),
(19, 'App\\Models\\Pengguna', 1, 'auth_token', '95fadd18ae066c53265255c5df8f6f5602b8d2e7ba4b23faa0d993c4d8d50783', '[\"*\"]', NULL, NULL, '2026-04-19 19:09:55', '2026-04-19 19:09:55'),
(20, 'App\\Models\\Pengguna', 1, 'auth_token', '21105da6cb72d20da4f18189b71f1754aa4fcecb9614a37f8366c1418f3aa540', '[\"*\"]', NULL, NULL, '2026-04-19 19:10:02', '2026-04-19 19:10:02'),
(21, 'App\\Models\\Pengguna', 1, 'auth_token', '4166f2380fe04a665e6c63298278c8c67621576b42e3ef927b394035d1db0de2', '[\"*\"]', NULL, NULL, '2026-04-19 19:10:10', '2026-04-19 19:10:10'),
(22, 'App\\Models\\Pengguna', 1, 'auth_token', '6c059445cc53adc112810d093316b845974de6ae00336d0c1bf8bbef4bebfdee', '[\"*\"]', '2026-04-19 19:16:34', NULL, '2026-04-19 19:13:15', '2026-04-19 19:16:34'),
(23, 'App\\Models\\Pengguna', 1, 'auth_token', 'e287ff33edde49ff118eae069d41e798b44e9241f94d54a561bac8748046666c', '[\"*\"]', NULL, NULL, '2026-04-19 19:17:05', '2026-04-19 19:17:05'),
(24, 'App\\Models\\Pengguna', 1, 'auth_token', '3831029fa144ad3902aa50e9633900c33c75c99d39d5fcf2e349c16599db1733', '[\"*\"]', NULL, NULL, '2026-04-19 19:17:53', '2026-04-19 19:17:53'),
(25, 'App\\Models\\Pengguna', 1, 'auth_token', '8181c25d6fc617bc664584280ed3227dfcdce061991b513f8b183deb34405189', '[\"*\"]', NULL, NULL, '2026-04-19 19:25:45', '2026-04-19 19:25:45'),
(27, 'App\\Models\\Pengguna', 1, 'auth_token', 'b831510aeaa68b6b03a3e80763accd53e38d7d483dacc7fed4593c588a3523fd', '[\"*\"]', '2026-04-19 19:27:41', NULL, '2026-04-19 19:27:30', '2026-04-19 19:27:41'),
(28, 'App\\Models\\Pengguna', 1, 'auth_token', '8e461a865576ccdeb56b973b33df4f5dc16683bd36cd31e111fb3435993fb2e5', '[\"*\"]', NULL, NULL, '2026-04-19 19:28:11', '2026-04-19 19:28:11'),
(31, 'App\\Models\\Pengguna', 1, 'auth_token', 'cedeeb2ce67f8483121fb584c693652e229615ee40e3501723ad5ebcd5d7a4d7', '[\"*\"]', '2026-04-19 20:53:32', NULL, '2026-04-19 19:44:39', '2026-04-19 20:53:32'),
(33, 'App\\Models\\Pengguna', 1, 'auth_token', '717aa706636e1bb8133ebec016c967a21e0d327fc4f0bdceb3ea98910ab9c7dd', '[\"*\"]', NULL, NULL, '2026-04-19 20:11:35', '2026-04-19 20:11:35'),
(34, 'App\\Models\\Pengguna', 1, 'auth_token', 'e7763ef3cc1eb1a76574cd4518d875789bfb12568e23506fcebf10917f25ca4c', '[\"*\"]', NULL, NULL, '2026-04-19 20:23:55', '2026-04-19 20:23:55'),
(36, 'App\\Models\\Pengguna', 1, 'auth_token', '00e0e8e182fff487b5c77b2b135454028a09dbc263aa4db47af7a0004423dccd', '[\"*\"]', NULL, NULL, '2026-04-19 20:27:12', '2026-04-19 20:27:12'),
(37, 'App\\Models\\Pengguna', 1, 'auth_token', '190100ca52b1cb130458a3a9ba96de3537a8bb3c49188d7e629f9f27ecf0bba1', '[\"*\"]', '2026-04-19 20:33:36', NULL, '2026-04-19 20:31:10', '2026-04-19 20:33:36'),
(38, 'App\\Models\\Pengguna', 2, 'auth_token', '61b81fb1025569917a17c12cabb4d720730d42d02a9e7fc5c2a7fab359dea57a', '[\"*\"]', NULL, NULL, '2026-04-19 20:45:09', '2026-04-19 20:45:09'),
(39, 'App\\Models\\Pengguna', 4, 'auth_token', '55b94db5eb15cfac02a9b5e03ada944493715f4caa79447398dc77dbba6369fc', '[\"*\"]', '2026-04-19 20:56:02', NULL, '2026-04-19 20:55:46', '2026-04-19 20:56:02'),
(40, 'App\\Models\\Pengguna', 4, 'auth_token', '69c9be3b677fdd8889bf224b196fbcaebe663a5f8eddcba22c2f8387a9251e46', '[\"*\"]', NULL, NULL, '2026-04-19 20:56:32', '2026-04-19 20:56:32'),
(43, 'App\\Models\\Pengguna', 4, 'auth_token', '1be262e13fd8dd438a2542923fbed8dcfc95e6f4b950a08f61426216bf9f033c', '[\"*\"]', '2026-04-20 08:46:30', NULL, '2026-04-20 08:46:15', '2026-04-20 08:46:30'),
(44, 'App\\Models\\Pengguna', 4, 'auth_token', '0374676596733970ad34e3004a3bc1c3e23c55b508f2e3838808b3ac5c2717f2', '[\"*\"]', '2026-04-21 09:41:53', NULL, '2026-04-21 08:24:41', '2026-04-21 09:41:53'),
(45, 'App\\Models\\Pengguna', 4, 'auth_token', 'e34251da385037387cf55a04ac92a08d91937133a4c591f7baf1e4d20cfdfae7', '[\"*\"]', '2026-04-21 18:58:37', NULL, '2026-04-21 17:22:23', '2026-04-21 18:58:37'),
(46, 'App\\Models\\Pengguna', 4, 'auth_token', '6a29eaf27f2cd530e7ce1f56fbd6f64db606dc9e961902e4d403fd46973afb66', '[\"*\"]', '2026-04-21 20:56:30', NULL, '2026-04-21 20:54:25', '2026-04-21 20:56:30'),
(47, 'App\\Models\\Pengguna', 4, 'auth_token', 'cf693ff46ed65b7678fe69d1a7ce99d99fecda8eddf36e5f6542ae92b49bdc5c', '[\"*\"]', '2026-04-22 08:08:47', NULL, '2026-04-22 07:59:26', '2026-04-22 08:08:47'),
(48, 'App\\Models\\Pengguna', 4, 'auth_token', '1faf26e04cf33f8f06fc7bc2c96c1dc560a6044e3ce1d9868b1e1bab5d97de4b', '[\"*\"]', '2026-04-25 18:16:19', NULL, '2026-04-22 08:09:58', '2026-04-25 18:16:19'),
(49, 'App\\Models\\Pengguna', 4, 'auth_token', '0080b2c7b072e58950866cb7fe5f4c30c52e731b6d93949a345e73e8b98ec7b1', '[\"*\"]', '2026-04-22 18:28:49', NULL, '2026-04-22 18:01:37', '2026-04-22 18:28:49'),
(50, 'App\\Models\\Pengguna', 4, 'auth_token', '46743be10579a4fd5fb883083ed14478cbd0a30b1948202224a6918eae404c21', '[\"*\"]', '2026-04-25 18:33:48', NULL, '2026-04-25 18:16:23', '2026-04-25 18:33:48'),
(51, 'App\\Models\\Pengguna', 4, 'auth_token', 'dcab1788603a0b309a86d55fad53cdfec16e2c6fd8cafbb7b2a8a97065eea60e', '[\"*\"]', '2026-04-26 09:30:42', NULL, '2026-04-26 09:05:30', '2026-04-26 09:30:42'),
(52, 'App\\Models\\Pengguna', 4, 'auth_token', '3974e2fa2e5eab2f1337d1366fb693122aaf65f15274b3a29f3938fd7e5e27b9', '[\"*\"]', '2026-04-27 08:54:12', NULL, '2026-04-27 07:44:45', '2026-04-27 08:54:12'),
(53, 'App\\Models\\Pengguna', 4, 'auth_token', '5b70717873d35825304d0f2e409ba92b3470395095af21c9fdb18adf340ec93d', '[\"*\"]', '2026-04-27 08:21:57', NULL, '2026-04-27 08:17:56', '2026-04-27 08:21:57'),
(54, 'App\\Models\\Pengguna', 4, 'auth_token', '618c270bb3c54358e8b48cd75abac759e85a3f58d209be9cd637ac4503f7c4fd', '[\"*\"]', NULL, NULL, '2026-04-27 09:28:55', '2026-04-27 09:28:55'),
(55, 'App\\Models\\Pengguna', 4, 'auth_token', '966b1e76375b28d9ee8d69c6b2663fd6934622f73d8e8a2eb05206b912ae77a7', '[\"*\"]', '2026-05-02 03:08:55', NULL, '2026-05-02 03:07:01', '2026-05-02 03:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `rombel`
--

CREATE TABLE `rombel` (
  `id_rombel` int NOT NULL,
  `id_jurusan` int NOT NULL,
  `nama_rombel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rombel`
--

INSERT INTO `rombel` (`id_rombel`, `id_jurusan`, `nama_rombel`) VALUES
(1, 2, 'Rombel Dummy 1'),
(2, 3, 'Rombel Dummy 2'),
(3, 3, 'Rombel Dummy 3');

-- --------------------------------------------------------

--
-- Table structure for table `ruang`
--

CREATE TABLE `ruang` (
  `id_ruang` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `nama_ruang` varchar(100) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ruang`
--

INSERT INTO `ruang` (`id_ruang`, `id_lokasi`, `nama_ruang`, `keterangan`) VALUES
(1, 3, 'Ruang Dummy 1', NULL),
(2, 1, 'Ruang Dummy 2', NULL),
(3, 1, 'Ruang Dummy 3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id_satuan` int NOT NULL,
  `nama_satuan` varchar(50) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id_satuan`, `nama_satuan`, `keterangan`) VALUES
(1, 'contoh', NULL),
(2, 'Satuan 1', NULL),
(3, 'Satuan 2', NULL),
(4, 'Satuan 3', NULL);

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
('aqXvy0PHGxPnfkh6FxdNGxI42wV9u2P5RxBtCfKc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJmTXRXcmZPUjJLY3B2Y0w2VTN6akJoT25Oa1ZMUTRaZnlZZm5qSWhFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776435743),
('AwqdCfH616JzmU1e7zKru7C6uybAxST1eL3dQpiU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJLZHQ5Z3VBWVdRZ3lSbzRKd2hqd0hFNURjU2k0Z1ZFNzVyb09uVlUxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1777256618),
('CVIAv6syMI7pPU6Shok9CRTy4MSdTwZqMnWtGxCK', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJXQTI2eGxucnhvTlZBTm1mOXJlWjNqYXo1ZzVkZGV6NTl0U3pZZ20xIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvSW52ZW50b3J5X3Nla29sYWhcL0FQSVwvcHVibGljIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776905170),
('CXqoXtRDBu8ZFlKacdijXTMqgoMBa7nC7ddTfHe6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJod0RXalIzWUd1WWNpSGxueXZwVjlxNFpFMmN6d3FZb2padHBMRk1pIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776308179),
('DRd6v1fqnxlQiitu33vTo9eHpFzP55ooS7zF5spE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIzU0pWZE1RYWo1ZWZlRDNnNVJqTzg2bUpUNTZycU5IVmM0V1kzZEVaIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776905254),
('HUtLNzqAFdUEa0k5KfkmboW3NYUUM2bBmQ8BBkOg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJOemlzMERGNnZWVXV4RmZxQTdVWVhicWRYdVVlY2c0bjJWdlp3elVkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776355782),
('l87XAVYgEJAJXJ6jr4EINLNfeazIXm50NFkR71RH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIzUGk1MXN4RFJxejZRV2hpcFAzZ2FMNVR5aVFxcGp3YUdtZkNwUnZiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776403152),
('pc4xjIVbD7YQlgdI9s0wNSoHpaG1Oc0dYHuy2L0G', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJzSms0UGNJUjBCWHhySDFqUHV4MFMwbThBWVZJcWtGQUtrU1BTYTZxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1777298309),
('Qe1B9RC2idhRqiUL7S8rZFa1CJ97qWUyDlnHYpfQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJFemoxcUlTTW1jZGlwdWNCazFCZUpYT2pPWGt4VDlDcHJMRFQ2cFZ4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1777716606),
('QMmgL5SzLKS5L74gnhgqzKGSzofoJKPSPu8sohGD', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJxdWFPRjVBSWNxcktOZjZDcnV2ZUlHQWY4Y01rbVNmNExVWWdGNGlUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776323165),
('qwYTarSPH1klOeAfIamvKLobJocx1IbRcDW9s4MI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIyQndnaTJuaHJSSU03cjh4S2ZTRW1oTWlTOTdiWlFVZEJyNHR3QjloIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1777221143),
('RfDKGtvFFyhJhMQJ9Gigpslun9UXa1aFwM71qVNJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ0VE84aDl4cm83RXNRQnRqdE93RTVaQ3B6Sm0zSHlJdHJxY0pBTEdIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776652077),
('shtYmS02LbqCaubQ8z6gwJOi9RGATF6YsZkq0iU1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqSmVIc0hIY2ZwdlM0UnFVQmtIUENzSU1MRGx5MHB0a0x1UlpqZFdWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776309127),
('TdZjvg25aVZgW9NGrBEAqD9G5DUKBcccT3avq8op', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJoT0FVRHF5OXFHTHM5OWJXWWE2amJtSFJzdmVwWm5JcENHTlBXeXZ3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776608227),
('ToUGpXFHD7PGGlAvJwfAA6SrQiRirxO3QlknhfHw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJpVnhab0ZSWHpTcTBMOVJSeXYzUk1EaXB5STUxWHNZdGtpVkVKaWNNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776830049);

-- --------------------------------------------------------

--
-- Table structure for table `status_barang`
--

CREATE TABLE `status_barang` (
  `id_status` int NOT NULL,
  `nama_status` varchar(50) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status_barang`
--

INSERT INTO `status_barang` (`id_status`, `nama_status`, `keterangan`) VALUES
(1, 'Non-Aktif', NULL),
(2, 'Tersedia', NULL),
(4, 'Dihapus', 'Barang yang sudah di hapus dari aset');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id_unit` int NOT NULL,
  `nama_unit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id_unit`, `nama_unit`) VALUES
(1, 'Unit Dummy 1'),
(2, 'Unit Dummy 2'),
(3, 'Unit Dummy 3');

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
  ADD KEY `id_ruang` (`id_ruang`),
  ADD KEY `fk_status` (`id_status`),
  ADD KEY `fk_kondisi` (`id_kondisi`);

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
  ADD KEY `id_master_barang` (`id_master_barang`),
  ADD KEY `fk_barang_keluar_gudang` (`kode_gudang`),
  ADD KEY `fk_barang_keluar_ruang` (`id_ruang`);

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
-- Indexes for table `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`kode_gudang`);

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
-- Indexes for table `kondisi`
--
ALTER TABLE `kondisi`
  ADD PRIMARY KEY (`id_kondisi`);

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
  ADD KEY `id_pemasok` (`id_pemasok`),
  ADD KEY `fk_pengadaan_gudang` (`kode_gudang`),
  ADD KEY `fk_pengadaan_satuan` (`id_satuan`),
  ADD KEY `fk_pengadaan_master_barang` (`id_master_barang`);

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
-- Indexes for table `status_barang`
--
ALTER TABLE `status_barang`
  ADD PRIMARY KEY (`id_status`);

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
  MODIFY `id_akses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `aset_bangunan`
--
ALTER TABLE `aset_bangunan`
  MODIFY `id_bangunan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `aset_tanah`
--
ALTER TABLE `aset_tanah`
  MODIFY `id_tanah` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_barang_keluar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail_pinjam` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  MODIFY `id_detail_pengadaan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_permintaan`
--
ALTER TABLE `detail_permintaan`
  MODIFY `id_detail_permintaan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id_jurusan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `id_kerusakan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kondisi`
--
ALTER TABLE `kondisi`
  MODIFY `id_kondisi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id_lokasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_barang`
--
ALTER TABLE `master_barang`
  MODIFY `id_master_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `merek`
--
ALTER TABLE `merek`
  MODIFY `id_merek` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mutasi`
--
ALTER TABLE `mutasi`
  MODIFY `id_mutasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `opname_aset`
--
ALTER TABLE `opname_aset`
  MODIFY `id_opname_aset` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `opname_stok`
--
ALTER TABLE `opname_stok`
  MODIFY `id_opname_stok` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemasok`
--
ALTER TABLE `pemasok`
  MODIFY `id_pemasok` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `penghapusan_aset`
--
ALTER TABLE `penghapusan_aset`
  MODIFY `id_penghapusan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `peran`
--
ALTER TABLE `peran`
  MODIFY `id_peran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `peran_akses`
--
ALTER TABLE `peran_akses`
  MODIFY `id_peran_akses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `perbaikan`
--
ALTER TABLE `perbaikan`
  MODIFY `id_perbaikan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `rombel`
--
ALTER TABLE `rombel`
  MODIFY `id_rombel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ruang`
--
ALTER TABLE `ruang`
  MODIFY `id_ruang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id_satuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status_barang`
--
ALTER TABLE `status_barang`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id_unit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `aset_ibfk_2` FOREIGN KEY (`id_ruang`) REFERENCES `ruang` (`id_ruang`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_kondisi` FOREIGN KEY (`id_kondisi`) REFERENCES `kondisi` (`id_kondisi`),
  ADD CONSTRAINT `fk_status` FOREIGN KEY (`id_status`) REFERENCES `status_barang` (`id_status`);

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
  ADD CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_barang_keluar_gudang` FOREIGN KEY (`kode_gudang`) REFERENCES `gudang` (`kode_gudang`),
  ADD CONSTRAINT `fk_barang_keluar_ruang` FOREIGN KEY (`id_ruang`) REFERENCES `ruang` (`id_ruang`);

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
  ADD CONSTRAINT `fk_pengadaan_gudang` FOREIGN KEY (`kode_gudang`) REFERENCES `gudang` (`kode_gudang`),
  ADD CONSTRAINT `fk_pengadaan_master_barang` FOREIGN KEY (`id_master_barang`) REFERENCES `master_barang` (`id_master_barang`),
  ADD CONSTRAINT `fk_pengadaan_satuan` FOREIGN KEY (`id_satuan`) REFERENCES `satuan` (`id_satuan`),
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
