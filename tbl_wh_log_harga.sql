-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2026 at 11:10 AM
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
-- Table structure for table `tbl_wh_log_harga`
--

CREATE TABLE `tbl_wh_log_harga` (
  `id` int(11) NOT NULL,
  `id_part` varchar(25) NOT NULL,
  `no_part` varchar(25) NOT NULL,
  `hrg_net_lama` decimal(10,0) NOT NULL,
  `hrg_net` decimal(10,0) NOT NULL,
  `hrg_price_list_lama` decimal(10,0) NOT NULL,
  `hrg_price_list` decimal(10,0) NOT NULL,
  `diskon` varchar(5) NOT NULL,
  `ppn` varchar(5) NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `tgl_update` date NOT NULL,
  `user` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_wh_log_harga`
--

INSERT INTO `tbl_wh_log_harga` (`id`, `id_part`, `no_part`, `hrg_net_lama`, `hrg_net`, `hrg_price_list_lama`, `hrg_price_list`, `diskon`, `ppn`, `lokasi`, `tgl_update`, `user`) VALUES
(1, '1123', 'QA400 323 08 21          ', 53790, 48900, 53790, 53790, '40', '11', '          ', '2024-06-20', 'Administrator'),
(2, '1123', 'QA400 323 08 21          ', 53790, 48900, 53790, 53790, '40', '11', '          ', '2024-06-20', 'Administrator'),
(3, '1123', 'QA400 323 08 21          ', 53790, 48900, 53790, 53790, '40', '11', '          ', '2024-06-20', 'Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_wh_log_harga`
--
ALTER TABLE `tbl_wh_log_harga`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_wh_log_harga`
--
ALTER TABLE `tbl_wh_log_harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
