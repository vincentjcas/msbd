-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_simak_smk
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `level_akses` enum('super_admin','admin') DEFAULT 'admin',
  `alamat` text,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nip` (`nip`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,1,'198001012005011001','Administrator Sistem','super_admin',NULL,NULL,'2025-10-28 03:43:02');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang`
--

DROP TABLE IF EXISTS `barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang` (
  `id_barang` int NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `jumlah_total` int NOT NULL DEFAULT '0',
  `jumlah_tersedia` int NOT NULL DEFAULT '0',
  `kondisi` enum('baik','rusak ringan','rusak berat') DEFAULT 'baik',
  `lokasi_penyimpanan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_barang`),
  KEY `idx_kategori` (`kategori`),
  KEY `idx_kondisi` (`kondisi`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang`
--

LOCK TABLES `barang` WRITE;
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
INSERT INTO `barang` VALUES (1,'Laptop Asus','Alat Praktik',20,20,'baik','Lab Komputer 1','2025-10-28 03:51:01'),(2,'Crimping Tool','Alat Praktik',15,15,'baik','Lab Jaringan','2025-10-28 03:51:01'),(3,'Kabel UTP Cat6','Alat Praktik',100,100,'baik','Gudang Alat','2025-10-28 03:51:01'),(4,'Proyektor','Peralatan Kelas',8,8,'baik','Ruang Penyimpanan','2025-10-28 03:51:01'),(5,'Bola Voli','Peralatan Olahraga',10,10,'baik','Gudang Olahraga','2025-10-28 03:51:01'),(6,'Bola Basket','Peralatan Olahraga',8,8,'baik','Gudang Olahraga','2025-10-28 03:51:01');
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guru`
--

DROP TABLE IF EXISTS `guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guru` (
  `id_guru` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `mata_pelajaran` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `alamat` text,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_guru`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nip` (`nip`),
  CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guru`
--

LOCK TABLES `guru` WRITE;
/*!40000 ALTER TABLE `guru` DISABLE KEYS */;
/*!40000 ALTER TABLE `guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `izin`
--

DROP TABLE IF EXISTS `izin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `izin` (
  `id_izin` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `tanggal` date NOT NULL,
  `alasan` text NOT NULL,
  `bukti_file` varchar(255) DEFAULT NULL,
  `status_approval` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `disetujui_oleh` int DEFAULT NULL,
  `catatan_approval` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_izin`),
  KEY `disetujui_oleh` (`disetujui_oleh`),
  KEY `idx_user` (`id_user`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_status` (`status_approval`),
  CONSTRAINT `izin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `izin_ibfk_2` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `izin`
--

LOCK TABLES `izin` WRITE;
/*!40000 ALTER TABLE `izin` DISABLE KEYS */;
/*!40000 ALTER TABLE `izin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal_pelajaran`
--

DROP TABLE IF EXISTS `jadwal_pelajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jadwal_pelajaran` (
  `id_jadwal` int NOT NULL AUTO_INCREMENT,
  `id_kelas` int NOT NULL,
  `id_guru` int NOT NULL,
  `mata_pelajaran` varchar(100) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwal`),
  KEY `idx_kelas` (`id_kelas`),
  KEY `idx_guru` (`id_guru`),
  KEY `idx_hari` (`hari`),
  CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_pelajaran`
--

LOCK TABLES `jadwal_pelajaran` WRITE;
/*!40000 ALTER TABLE `jadwal_pelajaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `jadwal_pelajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kegiatan_sekolah`
--

DROP TABLE IF EXISTS `kegiatan_sekolah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kegiatan_sekolah` (
  `id_kegiatan` int NOT NULL AUTO_INCREMENT,
  `nama_kegiatan` varchar(200) NOT NULL,
  `deskripsi` text,
  `tanggal` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `tempat` varchar(100) DEFAULT NULL,
  `status` enum('dijadwalkan','berlangsung','selesai','ditunda','dibatalkan') DEFAULT 'dijadwalkan',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kegiatan`),
  KEY `created_by` (`created_by`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_status` (`status`),
  CONSTRAINT `kegiatan_sekolah_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatan_sekolah`
--

LOCK TABLES `kegiatan_sekolah` WRITE;
/*!40000 ALTER TABLE `kegiatan_sekolah` DISABLE KEYS */;
/*!40000 ALTER TABLE `kegiatan_sekolah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) NOT NULL,
  `tingkat` int NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kelas`),
  KEY `idx_tingkat` (`tingkat`),
  KEY `idx_tahun` (`tahun_ajaran`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (1,'X-TKJ-1',10,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(2,'X-TKJ-2',10,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(3,'X-TKJ-3',10,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(4,'X-TKJ-4',10,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(5,'XI-TKJ-1',11,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(6,'XI-TKJ-2',11,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(7,'XI-TKJ-3',11,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(8,'XI-TKJ-4',11,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(9,'XII-TKJ-1',12,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(10,'XII-TKJ-2',12,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(11,'XII-TKJ-3',12,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(12,'XII-TKJ-4',12,'Teknik Komputer dan Jaringan','2024/2025','2025-10-28 03:47:06'),(13,'X-TKRO-1',10,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(14,'X-TKRO-2',10,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(15,'X-TKRO-3',10,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(16,'X-TKRO-4',10,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(17,'XI-TKRO-1',11,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(18,'XI-TKRO-2',11,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(19,'XI-TKRO-3',11,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(20,'XI-TKRO-4',11,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(21,'XII-TKRO-1',12,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(22,'XII-TKRO-2',12,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(23,'XII-TKRO-3',12,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25'),(24,'XII-TKRO-4',12,'Teknik Kendaraan Ringan dan Otomotif','2024/2025','2025-10-28 03:50:25');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kepala_sekolah`
--

DROP TABLE IF EXISTS `kepala_sekolah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kepala_sekolah` (
  `id_kepsek` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `alamat` text,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kepsek`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nip` (`nip`),
  CONSTRAINT `kepala_sekolah_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kepala_sekolah`
--

LOCK TABLES `kepala_sekolah` WRITE;
/*!40000 ALTER TABLE `kepala_sekolah` DISABLE KEYS */;
/*!40000 ALTER TABLE `kepala_sekolah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materi`
--

DROP TABLE IF EXISTS `materi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materi` (
  `id_materi` int NOT NULL AUTO_INCREMENT,
  `id_guru` int NOT NULL,
  `id_kelas` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_materi`),
  KEY `idx_guru` (`id_guru`),
  KEY `idx_kelas` (`id_kelas`),
  KEY `idx_uploaded_at` (`uploaded_at`),
  CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `materi_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materi`
--

LOCK TABLES `materi` WRITE;
/*!40000 ALTER TABLE `materi` DISABLE KEYS */;
/*!40000 ALTER TABLE `materi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembina`
--

DROP TABLE IF EXISTS `pembina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembina` (
  `id_pembina` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `alamat` text,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembina`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nip` (`nip`),
  CONSTRAINT `pembina_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembina`
--

LOCK TABLES `pembina` WRITE;
/*!40000 ALTER TABLE `pembina` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman_barang`
--

DROP TABLE IF EXISTS `peminjaman_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peminjaman_barang` (
  `id_peminjaman` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `id_peminjam` int NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_actual` date DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','dipinjam','dikembalikan') DEFAULT 'pending',
  `disetujui_oleh` int DEFAULT NULL,
  `kondisi_barang` enum('baik','rusak') DEFAULT 'baik',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_peminjaman`),
  KEY `disetujui_oleh` (`disetujui_oleh`),
  KEY `idx_peminjam` (`id_peminjam`),
  KEY `idx_barang` (`id_barang`),
  KEY `idx_status` (`status`),
  KEY `idx_tgl_pinjam` (`tgl_pinjam`),
  CONSTRAINT `peminjaman_barang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `peminjaman_barang_ibfk_2` FOREIGN KEY (`id_peminjam`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `peminjaman_barang_ibfk_3` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman_barang`
--

LOCK TABLES `peminjaman_barang` WRITE;
/*!40000 ALTER TABLE `peminjaman_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `peminjaman_barang` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_peminjaman_approved` AFTER UPDATE ON `peminjaman_barang` FOR EACH ROW BEGIN

    IF OLD.status = 'pending' AND NEW.status = 'disetujui' THEN

        UPDATE barang 

        SET jumlah_tersedia = jumlah_tersedia - 1 

        WHERE id_barang = NEW.id_barang;

    END IF;

    

    IF OLD.status = 'dipinjam' AND NEW.status = 'dikembalikan' THEN

        UPDATE barang 

        SET jumlah_tersedia = jumlah_tersedia + 1 

        WHERE id_barang = NEW.id_barang;

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `pengumpulan_tugas`
--

DROP TABLE IF EXISTS `pengumpulan_tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pengumpulan_tugas` (
  `id_pengumpulan` int NOT NULL AUTO_INCREMENT,
  `id_tugas` int NOT NULL,
  `id_siswa` int NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `waktu_submit` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nilai` decimal(5,2) DEFAULT NULL,
  `feedback` text,
  `status` enum('terlambat','tepat_waktu') DEFAULT 'tepat_waktu',
  PRIMARY KEY (`id_pengumpulan`),
  UNIQUE KEY `unique_pengumpulan` (`id_tugas`,`id_siswa`),
  KEY `idx_tugas` (`id_tugas`),
  KEY `idx_siswa` (`id_siswa`),
  KEY `idx_waktu_submit` (`waktu_submit`),
  CONSTRAINT `pengumpulan_tugas_ibfk_1` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengumpulan_tugas_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengumpulan_tugas`
--

LOCK TABLES `pengumpulan_tugas` WRITE;
/*!40000 ALTER TABLE `pengumpulan_tugas` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengumpulan_tugas` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `before_insert_pengumpulan_tugas` BEFORE INSERT ON `pengumpulan_tugas` FOR EACH ROW BEGIN

    DECLARE deadline_tugas DATETIME;

    

    SELECT deadline INTO deadline_tugas 

    FROM tugas 

    WHERE id_tugas = NEW.id_tugas;

    

    IF NEW.waktu_submit > deadline_tugas THEN

        SET NEW.status = 'terlambat';

    ELSE

        SET NEW.status = 'tepat_waktu';

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `presensi`
--

DROP TABLE IF EXISTS `presensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presensi` (
  `id_presensi` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpha') DEFAULT 'hadir',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_presensi`),
  UNIQUE KEY `unique_presensi` (`id_user`,`tanggal`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_user_tanggal` (`id_user`,`tanggal`),
  CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presensi`
--

LOCK TABLES `presensi` WRITE;
/*!40000 ALTER TABLE `presensi` DISABLE KEYS */;
/*!40000 ALTER TABLE `presensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presensi_siswa`
--

DROP TABLE IF EXISTS `presensi_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presensi_siswa` (
  `id_presensi_siswa` int NOT NULL AUTO_INCREMENT,
  `id_siswa` int NOT NULL,
  `id_jadwal` int NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpha') DEFAULT 'hadir',
  `keterangan` text,
  `diinput_oleh` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_presensi_siswa`),
  UNIQUE KEY `unique_presensi_siswa` (`id_siswa`,`id_jadwal`,`tanggal`),
  KEY `diinput_oleh` (`diinput_oleh`),
  KEY `idx_siswa` (`id_siswa`),
  KEY `idx_jadwal` (`id_jadwal`),
  KEY `idx_tanggal` (`tanggal`),
  CONSTRAINT `presensi_siswa_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `presensi_siswa_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_pelajaran` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `presensi_siswa_ibfk_3` FOREIGN KEY (`diinput_oleh`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presensi_siswa`
--

LOCK TABLES `presensi_siswa` WRITE;
/*!40000 ALTER TABLE `presensi_siswa` DISABLE KEYS */;
/*!40000 ALTER TABLE `presensi_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `alamat` text,
  `tanggal_lahir` date DEFAULT NULL,
  `nama_ortu` varchar(100) DEFAULT NULL,
  `no_hp_ortu` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_siswa`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nis` (`nis`),
  UNIQUE KEY `nisn` (`nisn`),
  KEY `idx_kelas` (`id_kelas`),
  CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswa`
--

LOCK TABLES `siswa` WRITE;
/*!40000 ALTER TABLE `siswa` DISABLE KEYS */;
/*!40000 ALTER TABLE `siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staf`
--

DROP TABLE IF EXISTS `staf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staf` (
  `id_staf` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `unit_kerja` varchar(50) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `alamat` text,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_staf`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nip` (`nip`),
  CONSTRAINT `staf_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staf`
--

LOCK TABLES `staf` WRITE;
/*!40000 ALTER TABLE `staf` DISABLE KEYS */;
/*!40000 ALTER TABLE `staf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tugas`
--

DROP TABLE IF EXISTS `tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tugas` (
  `id_tugas` int NOT NULL AUTO_INCREMENT,
  `id_guru` int NOT NULL,
  `id_kelas` int NOT NULL,
  `judul_tugas` varchar(200) NOT NULL,
  `deskripsi` text,
  `deadline` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tugas`),
  KEY `idx_guru` (`id_guru`),
  KEY `idx_kelas` (`id_kelas`),
  KEY `idx_deadline` (`deadline`),
  CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tugas_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tugas`
--

LOCK TABLES `tugas` WRITE;
/*!40000 ALTER TABLE `tugas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tugas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kepala_sekolah','pembina','guru','staf','siswa') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_role` (`role`),
  KEY `idx_status` (`status_aktif`),
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_created_at_users` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin123','admin','Administrator','admin@smkyapim.sch.id','081234567890',1,'2025-10-28 03:42:36','2025-10-28 03:42:36');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `v_rekap_presensi_guru_staf`
--

DROP TABLE IF EXISTS `v_rekap_presensi_guru_staf`;
/*!50001 DROP VIEW IF EXISTS `v_rekap_presensi_guru_staf`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_rekap_presensi_guru_staf` AS SELECT
 1 AS `id_user`,
  1 AS `nama_lengkap`,
  1 AS `role`,
  1 AS `bulan`,
  1 AS `tahun`,
  1 AS `total_hari`,
  1 AS `hadir`,
  1 AS `izin`,
  1 AS `sakit`,
  1 AS `alpha`,
  1 AS `persentase_kehadiran` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_rekap_presensi_siswa`
--

DROP TABLE IF EXISTS `v_rekap_presensi_siswa`;
/*!50001 DROP VIEW IF EXISTS `v_rekap_presensi_siswa`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_rekap_presensi_siswa` AS SELECT
 1 AS `id_siswa`,
  1 AS `nama_lengkap`,
  1 AS `nis`,
  1 AS `nama_kelas`,
  1 AS `bulan`,
  1 AS `tahun`,
  1 AS `total_pertemuan`,
  1 AS `hadir`,
  1 AS `izin`,
  1 AS `sakit`,
  1 AS `alpha`,
  1 AS `persentase_kehadiran` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_tugas_status`
--

DROP TABLE IF EXISTS `v_tugas_status`;
/*!50001 DROP VIEW IF EXISTS `v_tugas_status`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_tugas_status` AS SELECT
 1 AS `id_tugas`,
  1 AS `judul_tugas`,
  1 AS `deadline`,
  1 AS `id_guru`,
  1 AS `nama_guru`,
  1 AS `nama_kelas`,
  1 AS `total_siswa`,
  1 AS `sudah_mengumpulkan`,
  1 AS `belum_mengumpulkan` */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `v_rekap_presensi_guru_staf`
--

/*!50001 DROP VIEW IF EXISTS `v_rekap_presensi_guru_staf`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_rekap_presensi_guru_staf` AS select `u`.`id_user` AS `id_user`,`u`.`nama_lengkap` AS `nama_lengkap`,`u`.`role` AS `role`,month(`p`.`tanggal`) AS `bulan`,year(`p`.`tanggal`) AS `tahun`,count(0) AS `total_hari`,sum((case when (`p`.`status` = 'hadir') then 1 else 0 end)) AS `hadir`,sum((case when (`p`.`status` = 'izin') then 1 else 0 end)) AS `izin`,sum((case when (`p`.`status` = 'sakit') then 1 else 0 end)) AS `sakit`,sum((case when (`p`.`status` = 'alpha') then 1 else 0 end)) AS `alpha`,round(((sum((case when (`p`.`status` = 'hadir') then 1 else 0 end)) / count(0)) * 100),2) AS `persentase_kehadiran` from (`users` `u` left join `presensi` `p` on((`u`.`id_user` = `p`.`id_user`))) where (`u`.`role` in ('guru','staf','admin','kepala_sekolah','pembina')) group by `u`.`id_user`,month(`p`.`tanggal`),year(`p`.`tanggal`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_rekap_presensi_siswa`
--

/*!50001 DROP VIEW IF EXISTS `v_rekap_presensi_siswa`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_rekap_presensi_siswa` AS select `s`.`id_siswa` AS `id_siswa`,`u`.`nama_lengkap` AS `nama_lengkap`,`s`.`nis` AS `nis`,`k`.`nama_kelas` AS `nama_kelas`,month(`ps`.`tanggal`) AS `bulan`,year(`ps`.`tanggal`) AS `tahun`,count(0) AS `total_pertemuan`,sum((case when (`ps`.`status` = 'hadir') then 1 else 0 end)) AS `hadir`,sum((case when (`ps`.`status` = 'izin') then 1 else 0 end)) AS `izin`,sum((case when (`ps`.`status` = 'sakit') then 1 else 0 end)) AS `sakit`,sum((case when (`ps`.`status` = 'alpha') then 1 else 0 end)) AS `alpha`,round(((sum((case when (`ps`.`status` = 'hadir') then 1 else 0 end)) / count(0)) * 100),2) AS `persentase_kehadiran` from (((`siswa` `s` join `users` `u` on((`s`.`id_user` = `u`.`id_user`))) join `kelas` `k` on((`s`.`id_kelas` = `k`.`id_kelas`))) left join `presensi_siswa` `ps` on((`s`.`id_siswa` = `ps`.`id_siswa`))) group by `s`.`id_siswa`,month(`ps`.`tanggal`),year(`ps`.`tanggal`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_tugas_status`
--

/*!50001 DROP VIEW IF EXISTS `v_tugas_status`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_tugas_status` AS select `t`.`id_tugas` AS `id_tugas`,`t`.`judul_tugas` AS `judul_tugas`,`t`.`deadline` AS `deadline`,`g`.`id_guru` AS `id_guru`,`ug`.`nama_lengkap` AS `nama_guru`,`k`.`nama_kelas` AS `nama_kelas`,count(distinct `s`.`id_siswa`) AS `total_siswa`,count(distinct `pt`.`id_siswa`) AS `sudah_mengumpulkan`,(count(distinct `s`.`id_siswa`) - count(distinct `pt`.`id_siswa`)) AS `belum_mengumpulkan` from (((((`tugas` `t` join `guru` `g` on((`t`.`id_guru` = `g`.`id_guru`))) join `users` `ug` on((`g`.`id_user` = `ug`.`id_user`))) join `kelas` `k` on((`t`.`id_kelas` = `k`.`id_kelas`))) left join `siswa` `s` on((`s`.`id_kelas` = `k`.`id_kelas`))) left join `pengumpulan_tugas` `pt` on(((`t`.`id_tugas` = `pt`.`id_tugas`) and (`s`.`id_siswa` = `pt`.`id_siswa`)))) group by `t`.`id_tugas` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-28 17:26:24
