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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.kategori: ~5 rows (approximately)
INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
	(1, 'Hutan'),
	(2, 'Kelulut'),
	(3, 'Randu'),
	(4, 'Bunga'),
	(7, 'Minuman Sehat Banget'),
	(12, 'Produk Organik 1');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.pelanggan: ~4 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `kontak`, `alamat`, `kota`, `provinsi`, `created_at`, `updated_at`) VALUES
	(1, 'John Doe', '081234567899', 'Jalan Raya No. 123', 'Jakarta', 'DKI Jakarta', '2025-01-02 09:25:44', '2025-01-02 09:25:44'),
	(2, 'Jane Smith', '081234567898', 'Jalan Melati No. 456', 'Bandung', 'Jawa Barat', '2025-01-02 09:25:44', '2025-01-02 09:25:44'),
	(3, 'Michael Johnson', '081234567897', 'Jalan Mawar No. 789', 'Surabaya', 'Jawa Timur', '2025-01-02 09:25:44', '2025-01-02 09:25:44'),
	(4, 'Emily Davis', '081234567896', 'Jalan Anggrek No. 321', 'Medan', 'Sumatera Utara', '2025-01-02 09:25:44', '2025-01-02 09:25:44'),
	(9, 'Budi', '02598498451', 'Jl.Pramuka No 44', 'Banjarmasin', 'Kalimantan Selatan', '2025-01-03 21:34:24', '2025-01-03 21:39:15');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.penjualan: ~0 rows (approximately)

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.produk: ~3 rows (approximately)

-- Dumping structure for table manajemen_madu.restock
CREATE TABLE IF NOT EXISTS `restock` (
  `id_restock` int NOT NULL AUTO_INCREMENT,
  `id_produk` int NOT NULL,
  `id_supplier` int NOT NULL,
  `tanggal_restock` date NOT NULL,
  `jumlah_ditambahkan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_restock`),
  KEY `id_produk` (`id_produk`),
  KEY `id_supplier` (`id_supplier`),
  CONSTRAINT `restock_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  CONSTRAINT `restock_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.restock: ~0 rows (approximately)

-- Dumping structure for table manajemen_madu.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `id_satuan` int NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(50) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.satuan: ~0 rows (approximately)

-- Dumping structure for table manajemen_madu.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id_supplier` int NOT NULL AUTO_INCREMENT,
  `nama_supplier` varchar(100) NOT NULL,
  `kontak_supplier` varchar(20) DEFAULT NULL,
  `alamat_supplier` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_madu.supplier: ~3 rows (approximately)
INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `kontak_supplier`, `alamat_supplier`, `created_at`, `updated_at`) VALUES
	(1, 'budi', '02065001', 'Jl.Api', '2025-01-03 22:19:14', '2025-01-03 22:19:14'),
	(2, 'Randi', '5848945515', 'Jl. Air', '2025-01-04 03:31:23', '2025-01-04 03:31:23'),
	(3, 'adi', '55165156158', 'Jl. AB', '2025-01-04 03:34:08', '2025-01-04 03:34:08'),
	(4, 'Juan', '1315212', 'Banjarmasin', '2025-01-09 01:11:40', '2025-01-09 01:11:40');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
