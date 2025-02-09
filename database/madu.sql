-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for manajemen_madu
CREATE DATABASE IF NOT EXISTS `manajemen_madu` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `manajemen_madu`;

-- Dumping structure for table manajemen_madu.kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.kategori: ~0 rows (approximately)
INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
	(1, 'Hutan');

-- Dumping structure for table manajemen_madu.log_aktivitas
CREATE TABLE IF NOT EXISTS `log_aktivitas` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `aksi` text NOT NULL,
  `tanggal_aksi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_pengguna` (`id_pengguna`),
  CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.log_aktivitas: ~0 rows (approximately)

-- Dumping structure for table manajemen_madu.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` int NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(100) NOT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `alamat` text,
  `kota` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.pelanggan: ~1 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `kontak`, `alamat`, `kota`, `provinsi`, `created_at`, `updated_at`) VALUES
	(1, 'Budi', '08786', 'Jl.Pramuka No 44', 'Banjarmasin', 'Kalimantan Selatan', '2025-02-08 06:33:48', '2025-02-08 06:33:48');

-- Dumping structure for table manajemen_madu.pengguna
CREATE TABLE IF NOT EXISTS `pengguna` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `role` enum('admin','karyawan') NOT NULL,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.pengguna: ~0 rows (approximately)
INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `nama_lengkap`, `email`, `kontak`, `role`) VALUES
	(1, 'admin', '$2y$10$4XAZcOa.PgnSaFxVS8QoWebIv42L3mbUIA04ueAc/aZ4gpH0e39lG', 'Admin 1', 'admin1@example.com', '081234567890', 'admin');

-- Dumping structure for table manajemen_madu.penjualan
CREATE TABLE IF NOT EXISTS `penjualan` (
  `id_penjualan` int NOT NULL AUTO_INCREMENT,
  `id_produk` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `tanggal_penjualan` date NOT NULL,
  `jumlah_terjual` int NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penjualan`),
  KEY `id_produk` (`id_produk`),
  KEY `id_pelanggan` (`id_pelanggan`),
  CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  CONSTRAINT `penjualan_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.penjualan: ~0 rows (approximately)
INSERT INTO `penjualan` (`id_penjualan`, `id_produk`, `id_pelanggan`, `tanggal_penjualan`, `jumlah_terjual`, `total_harga`, `created_at`, `updated_at`) VALUES
	(9, 1, 1, '2025-02-06', 5, 250000.00, '2025-02-08 08:27:38', '2025-02-08 08:27:38'),
	(11, 1, 1, '2025-01-27', 1, 50000.00, '2025-02-08 08:38:54', '2025-02-08 08:48:26');

-- Dumping structure for table manajemen_madu.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` int NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi` text,
  `id_kategori` int NOT NULL,
  `id_satuan` int NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `stok` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_satuan` (`id_satuan`),
  CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`id_satuan`) REFERENCES `satuan` (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.produk: ~1 rows (approximately)
INSERT INTO `produk` (`id_produk`, `nama_produk`, `deskripsi`, `id_kategori`, `id_satuan`, `harga`, `stok`, `created_at`, `updated_at`) VALUES
	(1, 'Madu Hutan', 'Madu Asli Dari Hutan', 1, 3, 50000, 50, '2025-02-08 06:32:48', '2025-02-08 06:32:48');

-- Dumping structure for table manajemen_madu.restock
CREATE TABLE IF NOT EXISTS `restock` (
  `id_restock` int NOT NULL AUTO_INCREMENT,
  `id_produk` int NOT NULL,
  `id_supplier` int NOT NULL,
  `tanggal_restock` datetime NOT NULL,
  `jumlah_ditambahkan` int NOT NULL,
  `harga_per_unit` decimal(10,2) NOT NULL,
  `total_biaya` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_restock`),
  KEY `id_produk` (`id_produk`),
  KEY `id_supplier` (`id_supplier`),
  CONSTRAINT `restock_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  CONSTRAINT `restock_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.restock: ~0 rows (approximately)
INSERT INTO `restock` (`id_restock`, `id_produk`, `id_supplier`, `tanggal_restock`, `jumlah_ditambahkan`, `harga_per_unit`, `total_biaya`, `created_at`, `updated_at`) VALUES
	(2, 1, 1, '2024-12-18 00:00:00', 1000, 40000.00, 40000000.00, '2025-02-08 10:36:31', '2025-02-08 10:36:31'),
	(4, 1, 1, '2025-01-29 17:57:00', 1000, 1000.00, 1000000.00, '2025-02-08 10:57:39', '2025-02-08 10:57:39');

-- Dumping structure for table manajemen_madu.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `id_satuan` int NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(50) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.satuan: ~5 rows (approximately)
INSERT INTO `satuan` (`id_satuan`, `nama_satuan`, `deskripsi`, `created_at`, `updated_at`) VALUES
	(1, '50 ml', 'Botol kecil ukuran 50 ml', '2025-02-08 06:28:25', '2025-02-08 06:28:25'),
	(2, '100 ml', 'Botol sedang ukuran 100 ml', '2025-02-08 06:28:25', '2025-02-08 06:28:25'),
	(3, '250 ml', 'Botol besar ukuran 250 ml', '2025-02-08 06:28:25', '2025-02-08 06:28:25'),
	(4, '500 ml', 'Botol ukuran 500 ml', '2025-02-08 06:28:25', '2025-02-08 06:28:25'),
	(5, '1 liter', 'Botol ukuran 1 liter', '2025-02-08 06:28:25', '2025-02-08 06:28:25');

-- Dumping structure for table manajemen_madu.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id_supplier` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kontak` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.supplier: ~1 rows (approximately)
INSERT INTO `supplier` (`id_supplier`, `nama`, `kontak`, `alamat`, `created_at`, `updated_at`) VALUES
	(1, 'Dandi', '01282', 'Banjarmasin', '2025-02-08 06:33:13', '2025-02-08 06:33:13');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
