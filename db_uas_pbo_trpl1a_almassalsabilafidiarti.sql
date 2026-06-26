-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2026 at 12:50 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_trpl1a_almassalsabilafidiarti`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_mahasiswa`
--

CREATE TABLE `tabel_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `semester` int NOT NULL,
  `tarif_ukt_nominal` decimal(15,2) NOT NULL,
  `jenis_pembiayaan` enum('mandiri','bidikmisi','prestasi') NOT NULL,
  `golongan_ukt` int DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `nomor_kip_kuliah` varchar(50) DEFAULT NULL,
  `dana_saku_subsidi` decimal(15,2) DEFAULT NULL,
  `nama_instansi_beasiswa` varchar(100) DEFAULT NULL,
  `minimal_ipk_syarat` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_mahasiswa`
--

INSERT INTO `tabel_mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `semester`, `tarif_ukt_nominal`, `jenis_pembiayaan`, `golongan_ukt`, `nama_wali`, `nomor_kip_kuliah`, `dana_saku_subsidi`, `nama_instansi_beasiswa`, `minimal_ipk_syarat`) VALUES
(1, 'Andi Susanto', 'TRPL23001', 3, '5000000.00', 'mandiri', 5, 'Budi Susanto', NULL, NULL, NULL, NULL),
(2, 'Bela Saphira', 'TRPL23002', 3, '6000000.00', 'mandiri', 6, 'Herman Saphira', NULL, NULL, NULL, NULL),
(3, 'Cahyo Utomo', 'TRPL22001', 5, '4000000.00', 'mandiri', 4, 'Bambang Utomo', NULL, NULL, NULL, NULL),
(4, 'Diana Fitri', 'TRPL22002', 5, '7500000.00', 'mandiri', 7, 'Joko Santoso', NULL, NULL, NULL, NULL),
(5, 'Eko Prasetyo', 'TRPL21001', 7, '3000000.00', 'mandiri', 3, 'Agus Prasetyo', NULL, NULL, NULL, NULL),
(6, 'Fina Amelia', 'TRPL24001', 1, '5000000.00', 'mandiri', 5, 'Ridwan Syah', NULL, NULL, NULL, NULL),
(7, 'Gilang Ramadhan', 'TRPL24002', 1, '4000000.00', 'mandiri', 4, 'Surya Ramadhan', NULL, NULL, NULL, NULL),
(8, 'Hana Pertiwi', 'TRPL23003', 3, '0.00', 'bidikmisi', NULL, 'Kasman', 'KIP12345678', '950000.00', NULL, '3.00'),
(9, 'Irfan Hakim', 'TRPL23004', 3, '0.00', 'bidikmisi', NULL, 'Sugeng', 'KIP12345679', '950000.00', NULL, '3.00'),
(10, 'Jihan Nabila', 'TRPL22003', 5, '0.00', 'bidikmisi', NULL, 'Rahmat', 'KIP87654321', '950000.00', NULL, '3.00'),
(11, 'Kiki Amalia', 'TRPL22004', 5, '0.00', 'bidikmisi', NULL, 'Yusuf', 'KIP87654322', '950000.00', NULL, '3.00'),
(12, 'Lutfi Hasan', 'TRPL21002', 7, '0.00', 'bidikmisi', NULL, 'Suparman', 'KIP11223344', '950000.00', NULL, '3.00'),
(13, 'Mila Karmila', 'TRPL24003', 1, '0.00', 'bidikmisi', NULL, 'Tarjo', 'KIP44332211', '950000.00', NULL, '3.00'),
(14, 'Nando Saputra', 'TRPL24004', 1, '0.00', 'bidikmisi', NULL, 'Mulyadi', 'KIP99887766', '950000.00', NULL, '3.00'),
(15, 'Olivia Wijaya', 'TRPL23005', 3, '2500000.00', 'prestasi', NULL, NULL, NULL, '1500000.00', 'Djarum Beasiswa Plus', '3.50'),
(16, 'Putra Pratama', 'TRPL23006', 3, '0.00', 'prestasi', NULL, NULL, NULL, '2000000.00', 'Bank Indonesia', '3.25'),
(17, 'Qori Mubarok', 'TRPL22005', 5, '0.00', 'prestasi', NULL, NULL, NULL, '1000000.00', 'BSI Scholarship', '3.30'),
(18, 'Rina Nose', 'TRPL22006', 5, '2000000.00', 'prestasi', NULL, NULL, NULL, '1200000.00', 'Tanoto Foundation', '3.25'),
(19, 'Siska Anggraini', 'TRPL21003', 7, '0.00', 'prestasi', NULL, NULL, NULL, '1500000.00', 'Djarum Beasiswa Plus', '3.50'),
(20, 'Tio Nugroho', 'TRPL24005', 1, '0.00', 'prestasi', NULL, NULL, NULL, '2000000.00', 'Bakti BCA', '3.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
