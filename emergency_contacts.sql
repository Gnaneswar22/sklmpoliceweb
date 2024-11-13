-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 06:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emergency_contacts`
--

-- --------------------------------------------------------

--
-- Table structure for table `emergency_numbers`
--

CREATE TABLE `emergency_numbers` (
  `id` int(11) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_numbers`
--

INSERT INTO `emergency_numbers` (`id`, `service_name`, `phone_number`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Police Emergency', '100', 'Emergency police response services', 1, '2024-11-11 15:56:10', '2024-11-11 15:56:10'),
(2, 'Fire Services', '101', 'Fire emergency and rescue services', 1, '2024-11-11 15:56:10', '2024-11-11 15:56:10'),
(3, 'Ambulance', '108', 'Emergency medical services', 1, '2024-11-11 15:56:10', '2024-11-11 15:56:10'),
(4, 'Women\'s Helpline', '181', 'Emergency helpline for women in distress', 1, '2024-11-11 15:56:10', '2024-11-11 15:56:10'),
(5, 'Child Helpline', '1098', 'Emergency helpline for children in need', 1, '2024-11-11 15:56:10', '2024-11-11 15:56:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emergency_numbers`
--
ALTER TABLE `emergency_numbers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emergency_numbers`
--
ALTER TABLE `emergency_numbers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
