-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 15, 2023 at 05:08 AM
-- Server version: 8.0.33-0ubuntu0.20.04.2
-- PHP Version: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parkings`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin', 'kasir') NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `loginStatus` tinyint(1) DEFAULT NULL,
  `lastLogin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint NOT NULL,
  `type` enum('RODA DUA','RODA EMPAT') DEFAULT NULL,
  `harga` DECIMAL(10,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `type`, `harga`) VALUES
(1, 'RODA DUA', 2.000),
(2, 'RODA EMPAT', 5.000);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint NOT NULL,
  `vehicle_id` bigint DEFAULT NULL,
  `slot_id` bigint DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `paymentAmount` decimal(10,3) DEFAULT NULL,
  `paymentDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('AVAILABLE','NOT AVAILABLE') NOT NULL DEFAULT 'AVAILABLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`id`, `name`, `status`) VALUES
(1, 'slot 1', 'AVAILABLE'),
(2, 'slot 2', 'AVAILABLE'),
(3, 'slot 3', 'AVAILABLE'),
(4, 'slot 4', 'AVAILABLE'),
(5, 'slot 5', 'AVAILABLE'),
(6, 'slot 6', 'AVAILABLE'),
(7, 'slot 7', 'AVAILABLE'),
(8, 'slot 8', 'AVAILABLE'),
(9, 'slot 9', 'AVAILABLE'),
(10, 'slot 10', 'AVAILABLE'),
(11, 'slot 11', 'AVAILABLE'),
(12, 'slot 12', 'AVAILABLE'),
(13, 'slot 13', 'AVAILABLE'),
(14, 'slot 14', 'AVAILABLE'),
(15, 'slot 15', 'AVAILABLE'),
(16, 'slot 16', 'AVAILABLE'),
(17, 'slot 17', 'AVAILABLE'),
(18, 'slot 18', 'AVAILABLE'),
(19, 'slot 19', 'AVAILABLE'),
(20, 'slot 20', 'AVAILABLE'),
(21, 'slot 21', 'AVAILABLE'),
(22, 'slot 22', 'AVAILABLE'),
(23, 'slot 23', 'AVAILABLE'),
(24, 'slot 24', 'AVAILABLE');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `vehicle_id` bigint DEFAULT NULL,
  `slot_id` bigint DEFAULT NULL,
  `startedAt` timestamp NULL DEFAULT NULL,
  `endedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`);
COMMIT;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
