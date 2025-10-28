-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 28, 2025 at 03:14 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simak_smk`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `jumlah_total` int(11) NOT NULL DEFAULT 0,
  `jumlah_tersedia` int(11) NOT NULL DEFAULT 0,
  `kondisi` enum('baik','rusak ringan','rusak berat') DEFAULT 'baik',
  `lokasi_penyimpanan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kategori`, `jumlah_total`, `jumlah_tersedia`, `kondisi`, `lokasi_penyimpanan`, `created_at`) VALUES
(1, 'Laptop Asus', 'Alat Praktik', 20, 20, 'baik', 'Lab Komputer 1', '2025-10-28 03:51:01'),
(2, 'Crimping Tool', 'Alat Praktik', 15, 15, 'baik', 'Lab Jaringan', '2025-10-28 03:51:01'),
(3, 'Kabel UTP Cat6', 'Alat Praktik', 100, 100, 'baik', 'Gudang Alat', '2025-10-28 03:51:01'),
(4, 'Proyektor', 'Peralatan Kelas', 8, 8, 'baik', 'Ruang Penyimpanan', '2025-10-28 03:51:01'),
(5, 'Bola Voli', 'Peralatan Olahraga', 10, 10, 'baik', 'Gudang Olahraga', '2025-10-28 03:51:01'),
(6, 'Bola Basket', 'Peralatan Olahraga', 8, 8, 'baik', 'Gudang Olahraga', '2025-10-28 03:51:01');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `mata_pelajaran` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `izin`
--

CREATE TABLE `izin` (
  `id_izin` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `alasan` text NOT NULL,
  `bukti_file` varchar(255) DEFAULT NULL,
  `status_approval` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `disetujui_oleh` int(11) DEFAULT NULL,
  `catatan_approval` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `id_jadwal` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `mata_pelajaran` varchar(100) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_sekolah`
--

CREATE TABLE `kegiatan_sekolah` (
  `id_kegiatan` int(11) NOT NULL,
  `nama_kegiatan` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `tempat` varchar(100) DEFAULT NULL,
  `status` enum('dijadwalkan','berlangsung','selesai','ditunda','dibatalkan') DEFAULT 'dijadwalkan',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `tingkat` int(11) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `tingkat`, `jurusan`, `tahun_ajaran`, `created_at`) VALUES
(1, 'X-TKJ-1', 10, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(2, 'X-TKJ-2', 10, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(3, 'X-TKJ-3', 10, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(4, 'X-TKJ-4', 10, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(5, 'XI-TKJ-1', 11, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(6, 'XI-TKJ-2', 11, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(7, 'XI-TKJ-3', 11, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(8, 'XI-TKJ-4', 11, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(9, 'XII-TKJ-1', 12, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(10, 'XII-TKJ-2', 12, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(11, 'XII-TKJ-3', 12, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(12, 'XII-TKJ-4', 12, 'Teknik Komputer dan Jaringan', '2024/2025', '2025-10-28 03:47:06'),
(13, 'X-TKRO-1', 10, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(14, 'X-TKRO-2', 10, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(15, 'X-TKRO-3', 10, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(16, 'X-TKRO-4', 10, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(17, 'XI-TKRO-1', 11, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(18, 'XI-TKRO-2', 11, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(19, 'XI-TKRO-3', 11, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(20, 'XI-TKRO-4', 11, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(21, 'XII-TKRO-1', 12, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(22, 'XII-TKRO-2', 12, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(23, 'XII-TKRO-3', 12, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25'),
(24, 'XII-TKRO-4', 12, 'Teknik Kendaraan Ringan dan Otomotif', '2024/2025', '2025-10-28 03:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `kepala_sekolah`
--

CREATE TABLE `kepala_sekolah` (
  `id_kepsek` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id_materi` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembina`
--

CREATE TABLE `pembina` (
  `id_pembina` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_barang`
--

CREATE TABLE `peminjaman_barang` (
  `id_peminjaman` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_actual` date DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','dipinjam','dikembalikan') DEFAULT 'pending',
  `disetujui_oleh` int(11) DEFAULT NULL,
  `kondisi_barang` enum('baik','rusak') DEFAULT 'baik',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id_pengumpulan` int(11) NOT NULL,
  `id_tugas` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `waktu_submit` timestamp NULL DEFAULT current_timestamp(),
  `nilai` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `status` enum('terlambat','tepat_waktu') DEFAULT 'tepat_waktu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `pengumpulan_tugas`
--
DELIMITER $$
CREATE TRIGGER `before_insert_pengumpulan_tugas` BEFORE INSERT ON `pengumpulan_tugas` FOR EACH ROW BEGIN

    DECLARE deadline_tugas DATETIME;

    

    SELECT deadline INTO deadline_tugas 

    FROM tugas 

    WHERE id_tugas = NEW.id_tugas;

    

    IF NEW.waktu_submit > deadline_tugas THEN

        SET NEW.status = 'terlambat';

    ELSE

        SET NEW.status = 'tepat_waktu';

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id_presensi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpha') DEFAULT 'hadir',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presensi_siswa`
--

CREATE TABLE `presensi_siswa` (
  `id_presensi_siswa` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpha') DEFAULT 'hadir',
  `keterangan` text DEFAULT NULL,
  `diinput_oleh` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aOr3UvSgV7CHYP5Cn1BBkywxoU7tbVR6b1nTKorG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUNKRWIwbHN0ZW01NGFJMXZjRnRxY3llZDR4Z1ZqRFA0RWxYeGRPbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761663015);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nama_ortu` varchar(100) DEFAULT NULL,
  `no_hp_ortu` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `judul_tugas` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kepala_sekolah','pembina','guru','staf','siswa') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `nama_lengkap`, `email`, `no_hp`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123', 'admin', 'Administrator', 'admin@smkyapim.sch.id', '081234567890', 1, '2025-10-28 03:42:36', '2025-10-28 03:42:36');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_rekap_presensi_guru_staf`
-- (See below for the actual view)
--
CREATE TABLE `v_rekap_presensi_guru_staf` (
`id_user` int(11)
,`nama_lengkap` varchar(100)
,`role` enum('admin','kepala_sekolah','pembina','guru','staf','siswa')
,`bulan` int(3)
,`tahun` int(5)
,`total_hari` bigint(21)
,`hadir` decimal(22,0)
,`izin` decimal(22,0)
,`sakit` decimal(22,0)
,`alpha` decimal(22,0)
,`persentase_kehadiran` decimal(28,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_rekap_presensi_siswa`
-- (See below for the actual view)
--
CREATE TABLE `v_rekap_presensi_siswa` (
`id_siswa` int(11)
,`nama_lengkap` varchar(100)
,`nis` varchar(20)
,`nama_kelas` varchar(20)
,`bulan` int(3)
,`tahun` int(5)
,`total_pertemuan` bigint(21)
,`hadir` decimal(22,0)
,`izin` decimal(22,0)
,`sakit` decimal(22,0)
,`alpha` decimal(22,0)
,`persentase_kehadiran` decimal(28,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_tugas_status`
-- (See below for the actual view)
--
CREATE TABLE `v_tugas_status` (
`id_tugas` int(11)
,`judul_tugas` varchar(200)
,`deadline` datetime
,`id_guru` int(11)
,`nama_guru` varchar(100)
,`nama_kelas` varchar(20)
,`total_siswa` bigint(21)
,`sudah_mengumpulkan` bigint(21)
,`belum_mengumpulkan` bigint(22)
);

-- --------------------------------------------------------

--
-- Structure for view `v_rekap_presensi_guru_staf`
--
DROP TABLE IF EXISTS `v_rekap_presensi_guru_staf`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rekap_presensi_guru_staf`  AS SELECT `u`.`id_user` AS `id_user`, `u`.`nama_lengkap` AS `nama_lengkap`, `u`.`role` AS `role`, month(`p`.`tanggal`) AS `bulan`, year(`p`.`tanggal`) AS `tahun`, count(0) AS `total_hari`, sum(case when `p`.`status` = 'hadir' then 1 else 0 end) AS `hadir`, sum(case when `p`.`status` = 'izin' then 1 else 0 end) AS `izin`, sum(case when `p`.`status` = 'sakit' then 1 else 0 end) AS `sakit`, sum(case when `p`.`status` = 'alpha' then 1 else 0 end) AS `alpha`, round(sum(case when `p`.`status` = 'hadir' then 1 else 0 end) / count(0) * 100,2) AS `persentase_kehadiran` FROM (`users` `u` left join `presensi` `p` on(`u`.`id_user` = `p`.`id_user`)) WHERE `u`.`role` in ('guru','staf','admin','kepala_sekolah','pembina') GROUP BY `u`.`id_user`, month(`p`.`tanggal`), year(`p`.`tanggal`)  ;

-- --------------------------------------------------------

--
-- Structure for view `v_rekap_presensi_siswa`
--
DROP TABLE IF EXISTS `v_rekap_presensi_siswa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rekap_presensi_siswa`  AS SELECT `s`.`id_siswa` AS `id_siswa`, `u`.`nama_lengkap` AS `nama_lengkap`, `s`.`nis` AS `nis`, `k`.`nama_kelas` AS `nama_kelas`, month(`ps`.`tanggal`) AS `bulan`, year(`ps`.`tanggal`) AS `tahun`, count(0) AS `total_pertemuan`, sum(case when `ps`.`status` = 'hadir' then 1 else 0 end) AS `hadir`, sum(case when `ps`.`status` = 'izin' then 1 else 0 end) AS `izin`, sum(case when `ps`.`status` = 'sakit' then 1 else 0 end) AS `sakit`, sum(case when `ps`.`status` = 'alpha' then 1 else 0 end) AS `alpha`, round(sum(case when `ps`.`status` = 'hadir' then 1 else 0 end) / count(0) * 100,2) AS `persentase_kehadiran` FROM (((`siswa` `s` join `users` `u` on(`s`.`id_user` = `u`.`id_user`)) join `kelas` `k` on(`s`.`id_kelas` = `k`.`id_kelas`)) left join `presensi_siswa` `ps` on(`s`.`id_siswa` = `ps`.`id_siswa`)) GROUP BY `s`.`id_siswa`, month(`ps`.`tanggal`), year(`ps`.`tanggal`)  ;

-- --------------------------------------------------------

--
-- Structure for view `v_tugas_status`
--
DROP TABLE IF EXISTS `v_tugas_status`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tugas_status`  AS SELECT `t`.`id_tugas` AS `id_tugas`, `t`.`judul_tugas` AS `judul_tugas`, `t`.`deadline` AS `deadline`, `g`.`id_guru` AS `id_guru`, `ug`.`nama_lengkap` AS `nama_guru`, `k`.`nama_kelas` AS `nama_kelas`, count(distinct `s`.`id_siswa`) AS `total_siswa`, count(distinct `pt`.`id_siswa`) AS `sudah_mengumpulkan`, count(distinct `s`.`id_siswa`) - count(distinct `pt`.`id_siswa`) AS `belum_mengumpulkan` FROM (((((`tugas` `t` join `guru` `g` on(`t`.`id_guru` = `g`.`id_guru`)) join `users` `ug` on(`g`.`id_user` = `ug`.`id_user`)) join `kelas` `k` on(`t`.`id_kelas` = `k`.`id_kelas`)) left join `siswa` `s` on(`s`.`id_kelas` = `k`.`id_kelas`)) left join `pengumpulan_tugas` `pt` on(`t`.`id_tugas` = `pt`.`id_tugas` and `s`.`id_siswa` = `pt`.`id_siswa`)) GROUP BY `t`.`id_tugas``id_tugas`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_kondisi` (`kondisi`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `izin`
--
ALTER TABLE `izin`
  ADD PRIMARY KEY (`id_izin`),
  ADD KEY `disetujui_oleh` (`disetujui_oleh`),
  ADD KEY `idx_user` (`id_user`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_status` (`status_approval`);

--
-- Indexes for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `idx_kelas` (`id_kelas`),
  ADD KEY `idx_guru` (`id_guru`),
  ADD KEY `idx_hari` (`hari`);

--
-- Indexes for table `kegiatan_sekolah`
--
ALTER TABLE `kegiatan_sekolah`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `idx_tingkat` (`tingkat`),
  ADD KEY `idx_tahun` (`tahun_ajaran`);

--
-- Indexes for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  ADD PRIMARY KEY (`id_kepsek`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `idx_guru` (`id_guru`),
  ADD KEY `idx_kelas` (`id_kelas`),
  ADD KEY `idx_uploaded_at` (`uploaded_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembina`
--
ALTER TABLE `pembina`
  ADD PRIMARY KEY (`id_pembina`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `peminjaman_barang`
--
ALTER TABLE `peminjaman_barang`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `disetujui_oleh` (`disetujui_oleh`),
  ADD KEY `idx_peminjam` (`id_peminjam`),
  ADD KEY `idx_barang` (`id_barang`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tgl_pinjam` (`tgl_pinjam`);

--
-- Indexes for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id_pengumpulan`),
  ADD UNIQUE KEY `unique_pengumpulan` (`id_tugas`,`id_siswa`),
  ADD KEY `idx_tugas` (`id_tugas`),
  ADD KEY `idx_siswa` (`id_siswa`),
  ADD KEY `idx_waktu_submit` (`waktu_submit`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id_presensi`),
  ADD UNIQUE KEY `unique_presensi` (`id_user`,`tanggal`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_user_tanggal` (`id_user`,`tanggal`);

--
-- Indexes for table `presensi_siswa`
--
ALTER TABLE `presensi_siswa`
  ADD PRIMARY KEY (`id_presensi_siswa`),
  ADD UNIQUE KEY `unique_presensi_siswa` (`id_siswa`,`id_jadwal`,`tanggal`),
  ADD KEY `diinput_oleh` (`diinput_oleh`),
  ADD KEY `idx_siswa` (`id_siswa`),
  ADD KEY `idx_jadwal` (`id_jadwal`),
  ADD KEY `idx_tanggal` (`tanggal`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD KEY `idx_kelas` (`id_kelas`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `idx_guru` (`id_guru`),
  ADD KEY `idx_kelas` (`id_kelas`),
  ADD KEY `idx_deadline` (`deadline`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_status` (`status_aktif`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_created_at_users` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `izin`
--
ALTER TABLE `izin`
  MODIFY `id_izin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kegiatan_sekolah`
--
ALTER TABLE `kegiatan_sekolah`
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  MODIFY `id_kepsek` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembina`
--
ALTER TABLE `pembina`
  MODIFY `id_pembina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman_barang`
--
ALTER TABLE `peminjaman_barang`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id_pengumpulan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id_presensi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi_siswa`
--
ALTER TABLE `presensi_siswa`
  MODIFY `id_presensi_siswa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `izin`
--
ALTER TABLE `izin`
  ADD CONSTRAINT `izin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `izin_ibfk_2` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kegiatan_sekolah`
--
ALTER TABLE `kegiatan_sekolah`
  ADD CONSTRAINT `kegiatan_sekolah_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  ADD CONSTRAINT `kepala_sekolah_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `materi_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembina`
--
ALTER TABLE `pembina`
  ADD CONSTRAINT `pembina_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `peminjaman_barang`
--
ALTER TABLE `peminjaman_barang`
  ADD CONSTRAINT `peminjaman_barang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_barang_ibfk_2` FOREIGN KEY (`id_peminjam`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_barang_ibfk_3` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_1` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presensi_siswa`
--
ALTER TABLE `presensi_siswa`
  ADD CONSTRAINT `presensi_siswa_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presensi_siswa_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_pelajaran` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presensi_siswa_ibfk_3` FOREIGN KEY (`diinput_oleh`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tugas_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
