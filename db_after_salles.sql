-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 08:52 AM
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
-- Table structure for table `db_after_salles`
--

CREATE TABLE `tbl_after_salles` (
  `id` int(11) NOT NULL,
  `wo_no` int(9) NOT NULL,
  `claim_no` varchar(25) NOT NULL,
  `vin` varchar(35) NOT NULL,
  `fin` varchar(35) NOT NULL,
  `engine_no` varchar(35) NOT NULL,
  `no_pol` varchar(35) NOT NULL,
  `date_open_wo` date NOT NULL,
  `clockin` time NOT NULL,
  `date_close_wo` date NOT NULL,
  `clockout` time NOT NULL,
  `mileage` varchar(25) NOT NULL,
  `model` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `remarks` varchar(25) NOT NULL,
  `customer_complain` varchar(35) NOT NULL,
  `category` varchar(35) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `sold_labor_time` varchar(15) NOT NULL,
  `productive_hour_a` varchar(15) NOT NULL,
  `productive_hour_b` varchar(15) NOT NULL,
  `amount_labor` decimal(10,0) NOT NULL,
  `part_turn_over` decimal(10,0) NOT NULL,
  `technical_1` varchar(35) NOT NULL,
  `technical_2` varchar(35) NOT NULL,
  `note` varchar(25) NOT NULL,
  `ket` varchar(50) NOT NULL,
  `time_work` varchar(5) NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  `catatan` varchar(150) NOT NULL,
  `no_inv` varchar(35) NOT NULL,
  `tgl_fu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_after_salles`
--
ALTER TABLE `tbl_after_salles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `db_after_salles`
--
ALTER TABLE `db_after_salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
