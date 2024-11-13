-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 06:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srikakulam_police`
--
CREATE DATABASE IF NOT EXISTS `srikakulam_police` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `srikakulam_police`;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_highlights`
--

DROP TABLE IF EXISTS `gallery_highlights`;
CREATE TABLE IF NOT EXISTS `gallery_highlights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `gallery_highlights`
--

TRUNCATE TABLE `gallery_highlights`;
--
-- Dumping data for table `gallery_highlights`
--

INSERT DELAYED IGNORE INTO `gallery_highlights` VALUES
(1, 'images/Picsart_24-10-25_15-40-26-620.jpg', 'Event Title 1', '2024-11-11 14:09:03'),
(2, 'images/Picsart_24-10-25_15-40-26-620.jpg', 'Event Title 2', '2024-11-11 14:09:03'),
(3, 'images/Picsart_24-10-25_15-40-26-620.jpg', 'Event Title 3', '2024-11-11 14:09:03'),
(4, 'images/station.jpg', 'Event Title 4', '2024-11-11 14:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `helplines`
--

DROP TABLE IF EXISTS `helplines`;
CREATE TABLE IF NOT EXISTS `helplines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_toll_free` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `helplines`
--

TRUNCATE TABLE `helplines`;
--
-- Dumping data for table `helplines`
--

INSERT DELAYED IGNORE INTO `helplines` VALUES
(1, 'Child Line Day/Night', '1098', 'Child Line Day/Night', 1),
(2, 'Anti Bank Fraud Helpline', '8585063104', 'Anti Bank Fraud Helpline', 0),
(3, 'Cyber PS', '033-2214 3000', 'Cyber Police Station', 0),
(4, 'Cyber PS', '98365 13000', 'Cyber Police Station', 0),
(5, 'Control Room', '100', 'Control Room', 1),
(6, 'Control Room', '1090', 'Control Room', 1),
(7, 'Traffic', '1073', 'Traffic', 1),
(8, 'Women in Need Call', '1091', 'Women in Need Call', 1);

-- --------------------------------------------------------

--
-- Table structure for table `initiatives`
--

DROP TABLE IF EXISTS `initiatives`;
CREATE TABLE IF NOT EXISTS `initiatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `initiatives`
--

TRUNCATE TABLE `initiatives`;
--
-- Dumping data for table `initiatives`
--

INSERT DELAYED IGNORE INTO `initiatives` VALUES
(1, 'Community Outreach', 'Building Trust through Engagement', 'images/1.jpg'),
(2, 'Safety Programs', 'Road Safety and Accident Prevention', 'images/2.jpg'),
(3, 'Women’s Safety', 'Programs for Protecting Women', 'images/3.jpg'),
(4, 'Anti-Drug Campaign', 'Awareness Programs Against Substance Abuse', 'images/sp.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `news`
--

TRUNCATE TABLE `news`;
--
-- Dumping data for table `news`
--

INSERT DELAYED IGNORE INTO `news` VALUES
(1, 'Instructions to Submit Event Permission Application', 'Data+Science_Academy+Curriculum.pdf', 'For Download Form Click Here', '2024-11-11 11:24:50'),
(2, 'Application For The Grant of Licence to Run Place of Entertainment', 'citizen-services/APPLICATION FOR THE GRANT OF LICENCE TO RUN PLACE OF ENTERTAINMENT(FUNCTION HALLCONVENTION CENTER).pdf', 'For Download Form Click Here', '2024-11-11 11:24:50'),
(3, 'Notification for Registration of Women Hostel in IT Corridor', 'WomensHostelRegistration.html', '', '2024-11-11 11:24:50'),
(4, 'Visit to Women in Distress Website', 'https://www.ncwwomenhelpline.in/', 'helpline: 7827 170 170', '2024-11-11 11:24:50'),
(5, 'Srikakulam Police To Auction 665 Abandoned / Unclaimed Vehicles', 'press-release/Abandoned Vehicles 2023 (665).pdf', 'For Vehicle List Click Here', '2024-11-11 11:24:50');

-- --------------------------------------------------------

--
-- Table structure for table `news_articles`
--

DROP TABLE IF EXISTS `news_articles`;
CREATE TABLE IF NOT EXISTS `news_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `news_articles`
--

TRUNCATE TABLE `news_articles`;
--
-- Dumping data for table `news_articles`
--

INSERT DELAYED IGNORE INTO `news_articles` VALUES
(1, 'Instructions to Submit Event Permission Application', 'Data+Science_Academy+Curriculum.pdf', 'For Download Form Click Here', '2024-11-11 10:48:39'),
(2, 'Application For The Grant of Licence to Run Place of Entertainment', 'citizen-services/APPLICATION FOR THE GRANT OF LICENCE TO RUN PLACE OF ENTERTAINMENT(FUNCTION HALLCONVENTION CENTER).pdf', 'For Download Form Click Here', '2024-11-11 10:48:39'),
(3, 'Notification for Registration of Women Hostel in IT Corridor', 'WomensHostelRegistration.html', '', '2024-11-11 10:48:39'),
(4, 'Visit to Women in Distress Website', 'https://www.ncwwomenhelpline.in/', 'helpline: 7827 170 170', '2024-11-11 10:48:39'),
(5, 'Srikakulam Police To Auction 665 Abandoned / Unclaimed Vehicles', 'press-release/Abandoned Vehicles 2023 (665).pdf', 'For Vehicle List Click Here', '2024-11-11 10:48:39'),
(6, 'Srikakulam Police To Auction 1000 Abandoned / Unclaimed Vehicles', 'press-release/Abandoned Vehicles 2023 (1000).pdf', 'For Vehicle List Click Here', '2024-11-11 10:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

DROP TABLE IF EXISTS `officers`;
CREATE TABLE IF NOT EXISTS `officers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL,
  `station` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `officers`
--

TRUNCATE TABLE `officers`;
--
-- Dumping data for table `officers`
--

INSERT DELAYED IGNORE INTO `officers` VALUES
(1, 'Sri K.V.Maheswara Reddy', 'Superintendent of Police', NULL, 'images/2.jpg'),
(2, 'Deputy Superintendent', 'Deputy Superintendent of Police', 'Special Branch', 'images/DSP SIR  SB.jpg'),
(3, 'CI PRASAD', 'Circle Inspector', 'Kotturu Circle', 'images/CIS/CI PRASAD- ktohuru Circle.jpg'),
(4, 'Dadi Mohan Rao', 'Circle Inspector', 'Kasibugga PS', 'images/CIS/Dadi Mohan rao-Kasibugga PS.jpeg'),
(5, 'Demullu', 'Circle Inspector', 'Control Room', 'images/CIS/Demullu-CI- Controle Room.jpeg'),
(6, 'K Srinivasa Rao', 'Circle Inspector', 'Tekkali Rural Circle', 'images/CIS/K Srinvasa Rao-CI Tekkali Rural Circle.jpeg'),
(7, 'Mangaraju', 'Circle Inspector', 'Sompeta', 'images/CIS/Mangaraju- CI - Sompeta.jpeg'),
(8, 'Nagaraju', 'Circle Inspector', 'Traffic', 'images/CIS/Nagaraju- CI Traffic.jpeg'),
(9, 'Sai', 'Circle Inspector', 'Pathapatnam', 'images/CIS/Sai-Pathapatnam CI.jpeg'),
(10, 'Satyanarayana', 'Circle Inspector', 'Amadalavalasa', 'images/CIS/Satyanarayana-CI-Amadalavalasa.jpeg'),
(11, 'Srinivasa Rao', 'Circle Inspector', 'Narasannapeta Circle', 'images/CIS/Srinivasa Rao- Narasannapeta Circle.jpeg'),
(12, 'Swamy Naidu', 'Circle Inspector', 'Disha', 'images/CIS/Swamy Naidu-CI- Disha.jpeg'),
(13, 'Umamaheswara Rao', 'Circle Inspector', 'SKL II Town', 'images/CIS/Umamaheswara Rao-CI- SKL II town.jpeg'),
(14, 'Vijay Kumar', 'Circle Inspector', 'General', 'images/CIS/Vijay Kumar- CI.JPG'),
(15, 'M. Hari Krishna', 'Sub-Inspector', 'Srikakulam I Town', 'images/SIS/1- M. Hari Krishna- Srikakulam I Town.jpg'),
(16, 'R. Janardhana Rao', 'Sub-Inspector', 'Gara', 'images/SIS/2- R. Janardhana Rao- Gara.jpg'),
(17, 'Ameer Ali', 'Sub-Inspector', 'VR', 'images/SIS/Ameer Ali -VR.jpg'),
(18, 'Anil Kumar', 'Sub-Inspector', 'Saravakota', 'images/SIS/Anil Kumar- Saravakota.jpeg'),
(19, 'Ashok Babu', 'Sub-Inspector', 'Jalumuru', 'images/SIS/Ashok babu-Jalumuru.JPG'),
(20, 'Aziz Ahammad', 'Sub-Inspector', 'Kotturu', 'images/SIS/Aziz ahammad- Kothuru.jpeg'),
(21, 'Basha', 'Sub-Inspector', 'Mandasa', 'images/SIS/Basha- Mandasa.jpg'),
(22, 'Chandra Kala', 'Sub-Inspector', 'WPS', 'images/SIS/Chandra kala-WPS.jpg'),
(23, 'Chandra Sekhar', 'Sub-Inspector', 'Baruva', 'images/SIS/Chandra Sekhar- Baruva.jpeg'),
(24, 'Chinnamnaidu', 'Sub-Inspector', 'ICP Town', 'images/SIS/Chinnamnaidu-ICP Town SI.jpg'),
(25, 'Chiranjeevi', 'Sub-Inspector', 'Etcherla', 'images/SIS/Chiranjeevi- Etcherla.jpg'),
(26, 'Devadanam', 'Sub-Inspector', 'Traffic', 'images/SIS/Devadanam-Traffic.jpeg'),
(27, 'Durga Prasad', 'Sub-Inspector', 'Narasannapeta', 'images/SIS/DURGA PRASAD- Narasannapeta.jpg'),
(28, 'G. Laxmana Rao', 'Sub-Inspector', 'Laveru', 'images/SIS/G. Laxmana Rao-SI Laveru.jpeg'),
(29, 'G. Rajesh', 'Sub-Inspector', 'Kanchili', 'images/SIS/G. Rajesh- Kanchili.JPG'),
(30, 'Gafuoor', 'Sub-Inspector', 'CCS', 'images/SIS/Gafuoor- SI-CCS.JPG'),
(31, 'Ganesh', 'Sub-Inspector', 'VR', 'images/SIS/Ganesh- VR.jpg'),
(32, 'Hymavathi', 'Sub-Inspector', 'Sompeta', 'images/SIS/Hymavathi - Sompeta.jpg'),
(33, 'Kanakaraju', 'Sub-Inspector', 'Srikakulam II Town', 'images/SIS/Kanakaraju- Srikakulam II Town.jpeg'),
(34, 'Kishore Varma', 'Sub-Inspector', 'SKL Rural', 'images/SIS/Kishore Varma-SKL Rural.jpeg'),
(35, 'Krishna', 'Sub-Inspector', 'Srikakulam I Town', 'images/SIS/Krishna SI- Srikkulam i Town.jpg'),
(36, 'Krishnaprasad', 'Sub-Inspector', 'VR', 'images/SIS/Krishnaprasad- VR.jpeg'),
(37, 'Lakshmi', 'Sub-Inspector', 'Disha', 'images/SIS/Lakshmi- SI- Disha.jpg'),
(38, 'Lakshmi', 'Sub-Inspector', 'Tekkali', 'images/SIS/Lakshmi- Tekkali.jpg'),
(39, 'Lavanya', 'Sub-Inspector', 'Pathapatnam', 'images/SIS/Lavanya- Pathapatnam.jpg'),
(40, 'Madhusudhana Rao', 'Sub-Inspector', 'GSDM', 'images/SIS/Madhusudhana Rao-GSDM.JPG'),
(41, 'Manmadha Rao', 'Sub-Inspector', 'Disha', 'images/SIS/Manmadha Rao-Disha.jpg'),
(42, 'Manmadha', 'Sub-Inspector', 'SB', 'images/SIS/Manmadha SI- SB.jpg'),
(43, 'Mohan Rao', 'Sub-Inspector', 'SB', 'images/SIS/Mohan Rao- SB.JPG'),
(44, 'Narayanaswamy', 'Sub-Inspector', 'Nowpada', 'images/SIS/Narayanaswamy- nowpada.jpeg'),
(45, 'Netaji', 'Sub-Inspector', 'DCRB', 'images/SIS/Netaji- DCRB.jpg'),
(46, 'Nihar', 'Sub-Inspector', 'VKotturu', 'images/SIS/Nihar-VKothru.jpeg'),
(47, 'Prabhakar', 'Sub-Inspector', 'Disha', 'images/SIS/Prabhkar - SI- Disha.jpeg'),
(48, 'Pravallika', 'Sub-Inspector', 'Burja', 'images/SIS/Pravallika- Burja.jpg'),
(49, 'PV Ramna', 'Sub-Inspector', 'CCS', 'images/SIS/PV RAMNA -CCS.jpg'),
(50, 'Ragavendra Rao', 'Sub-Inspector', 'Tekkali', 'images/SIS/Ragavendra Rao - Tekkali.jpg'),
(51, 'Rajesh', 'Sub-Inspector', 'Sarubujjili', 'images/SIS/Rajesh- Sarubujjili.jpeg'),
(52, 'Rama Rao', 'Sub-Inspector', 'CCS', 'images/SIS/RAMA RAO- CCS.jpg'),
(53, 'Rama Rao', 'Sub-Inspector', 'VKTR', 'images/SIS/Rama Rao- VKTR.JPG'),
(54, 'Ramesh Babu', 'Sub-Inspector', 'MPTY', 'images/SIS/Ramesh Babu- MPTY.jpeg'),
(55, 'Ramu', 'Sub-Inspector', 'Disha', 'images/SIS/RAmu-SI- Disha.jpeg'),
(56, 'Revathi', 'Sub-Inspector', 'Kasibugga', 'images/SIS/Revathi- Kasibugga SI.jpg'),
(57, 'Ramana', 'Sub-Inspector', 'Disha', 'images/SIS/Rmana-Disha.jpeg'),
(58, 'Sandeep', 'Sub-Inspector', 'Etcherla', 'images/SIS/Sandeep- Etcherla.JPG'),
(59, 'Satyanarayana', 'Sub-Inspector', 'Kotabommali', 'images/SIS/SATYANARAYANA- Kotabommai.jpg'),
(60, 'Satyanarayana', 'Sub-Inspector', 'Etcherla', 'images/SIS/Satyanarayana-Etcherla.JPG'),
(61, 'Simhachalam', 'Sub-Inspector', 'VR', 'images/SIS/simhachalam -VR.jpeg'),
(62, 'Simhachalam', 'Sub-Inspector', '2 Amadalavalasa', 'images/SIS/Simhachalam- SI -2 Amadalavalasa.jpeg'),
(63, 'SK Mahammad Ali', 'Sub-Inspector', 'Nandigam', 'images/SIS/SK Mahammad ALi- Nandigam-1.jpg'),
(64, 'Somasekhar', 'Sub-Inspector', 'Traffic', 'images/SIS/Somasekhar-Traffic PS.jpeg'),
(65, 'Srinivas', 'Sub-Inspector', 'ICP Rural', 'images/SIS/Srinivas- ICP Rural.jpg'),
(66, 'Sudhakar', 'Sub-Inspector', 'Traffic PS', 'images/SIS/Sudhakar- Traffic PS.jpg'),
(67, 'U. Varadarajulu', 'Sub-Inspector', 'Control Room', 'images/SIS/U. Varadarajulu-Control room.jpeg'),
(68, 'Varadarajulu', 'Sub-Inspector', 'Traffic', 'images/SIS/Varadarajulu- Traffic.jpg'),
(69, 'Venkata Rao', 'Sub-Inspector', 'Disha', 'images/SIS/Venkata Rao- DIsha.jpg'),
(70, 'Venkata Rao', 'Sub-Inspector', 'SI Disha', 'images/SIS/Venkata Rao- SI Disha.jpeg'),
(71, 'Venkatesh', 'Sub-Inspector', 'Tekkali', 'images/SIS/Venkatesh- Tekkali.JPG'),
(72, 'Vijay Kumar', 'Sub-Inspector', 'DCRB SKLM', 'images/SIS/Vijay Kumar- DCRB skl.JPG'),
(73, 'Y. Ravi Kumar', 'Sub-Inspector', 'DPTC', 'images/SIS/Y. Ravi Kumar- DPTC.JPG'),
(74, 'Yaseen', 'Sub-Inspector', 'Hiramandalam', 'images/SIS/Yaseen- Hiramandalam.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `police_stations`
--

DROP TABLE IF EXISTS `police_stations`;
CREATE TABLE IF NOT EXISTS `police_stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `police_stations`
--

TRUNCATE TABLE `police_stations`;
--
-- Dumping data for table `police_stations`
--

INSERT DELAYED IGNORE INTO `police_stations` VALUES
(1, 'Srikakulam I Town', 18.29379415, 83.89546610, '1234567890', 'Srikakulam I Town, Srikakulam District, Andhra Pradesh', 'path/to/image1.jpg', '2024-11-11 16:21:54', '2024-11-11 16:21:54'),
(2, 'Srikakulam II Town', 18.29580307, 83.89439602, '2345678901', 'Srikakulam II Town, Srikakulam District, Andhra Pradesh', 'path/to/image2.jpg', '2024-11-11 16:21:54', '2024-11-11 16:21:54'),
(3, 'Amadalavalasa', 18.41264730, 83.90247140, '3456789012', 'Amadalavalasa, Srikakulam District, Andhra Pradesh', 'path/to/image3.jpg', '2024-11-11 16:21:54', '2024-11-11 16:21:54'),
(4, 'Kasibugga Urban', 18.76040782, 84.41728723, '4567890123', 'Kasibugga Urban, Srikakulam District, Andhra Pradesh', 'path/to/image4.jpg', '2024-11-11 16:21:54', '2024-11-11 16:21:54'),
(5, 'Kasibugga Rural', 18.77157230, 84.41065760, '5678901234', 'Kasibugga Rural, Srikakulam District, Andhra Pradesh', 'path/to/image5.jpg', '2024-11-11 16:21:54', '2024-11-11 16:21:54'),
(6, 'Burja', 18.36929220, 84.10389050, '6309990849', '9494+WJC, SH 92, Polaki, Andhra Pradesh 532429', NULL, '2024-11-12 06:04:21', '2024-11-12 06:04:21');

-- --------------------------------------------------------

--
-- Table structure for table `press_releases`
--

DROP TABLE IF EXISTS `press_releases`;
CREATE TABLE IF NOT EXISTS `press_releases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `press_releases`
--

TRUNCATE TABLE `press_releases`;
--
-- Dumping data for table `press_releases`
--

INSERT DELAYED IGNORE INTO `press_releases` VALUES
(1, 'Srikakulam Police To Auction 1000 Abandoned / Unclaimed Vehicles', 'press-release/Abandoned Vehicles 2023 (1000).pdf', 'For Vehicle List Click Here', '2024-11-11 11:15:18'),
(2, 'Srikakulam Police To Auction 539 Abandoned / Unclaimed Vehicles', 'press-release/Abandoned Vehicles 2023 (539).pdf', 'For Vehicle List Click Here', '2024-11-11 11:15:18'),
(3, 'Srikakulam Police To Auction 1197 Abandoned / Unclaimed Vehicles', 'press-release/Abandoned Vehicles 2023 (1197).pdf', 'For Vehicle List Click Here', '2024-11-11 11:15:18');

-- --------------------------------------------------------

--
-- Table structure for table `recent_activities`
--

DROP TABLE IF EXISTS `recent_activities`;
CREATE TABLE IF NOT EXISTS `recent_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_text` text NOT NULL,
  `activity_link` varchar(255) DEFAULT NULL,
  `is_important` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `recent_activities`
--

TRUNCATE TABLE `recent_activities`;
--
-- Dumping data for table `recent_activities`
--

INSERT DELAYED IGNORE INTO `recent_activities` VALUES
(1, 'welcome to our webpage', NULL, 0, '2024-11-11 08:09:29');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
