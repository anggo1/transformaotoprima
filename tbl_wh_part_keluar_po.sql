-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2026 at 10:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_transforma_oto_prima`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wh_part_keluar_po`
--

CREATE TABLE `tbl_wh_part_keluar_po` (
  `id` int(11) NOT NULL,
  `kode_keluar` varchar(25) NOT NULL,
  `no_po` varchar(35) NOT NULL,
  `kode_po` varchar(35) NOT NULL,
  `tgl_po` date NOT NULL,
  `kode_cus` varchar(25) NOT NULL,
  `nama_cus` varchar(150) NOT NULL,
  `no_sj` varchar(50) NOT NULL,
  `lokasi` varchar(35) NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  `pengguna` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_wh_part_keluar_po`
--
ALTER TABLE `tbl_wh_part_keluar_po`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_wh_part_keluar_po`
--
ALTER TABLE `tbl_wh_part_keluar_po`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
