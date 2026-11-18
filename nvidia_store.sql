-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 17, 2026 at 05:53 PM
-- Server version: 8.0.46
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nvidia_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `first_name`, `last_name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(1, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:00:25'),
(2, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:01:35'),
(3, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:01:41'),
(4, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:01:49'),
(5, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:02:09'),
(6, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:02:17'),
(7, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:06:45'),
(8, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:07:19'),
(9, 'elad', 'marcel', 'eladmarcel13@gmail.com', 'Laptop', 'Please help me :(', '2026-11-11 15:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `badge` varchar(50) NOT NULL DEFAULT 'NONE',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `category`, `description`, `price`, `old_price`, `image`, `stock`, `created_at`, `badge`) VALUES
(1, 'ASUS Prime Radeon RX 9070 XT', 'ASUS', 'GPU', '16GB GDDR6, PCIe 5.0, High Performance Gaming Graphics Card', 899.00, 999.00, 'images/Screenshot 2026-10-26 141345.png', 10, '2026-11-11 12:27:35', 'BEST SELLER'),
(2, 'ASUS ROG Astral GeForce RTX 5080 OC Graphics Card,', 'ASUS', 'GPU', 'ASUS ROG Astral GeForce RTX 5080 OC Graphics Card, 16GB GDDR7 256-Bit Memory, 10752 CUDA Cores,', 1628.23, 2678.63, 'images/Asus.png', 3, '2026-11-11 12:27:35', 'SALE'),
(3, 'ASUS TUF Gaming F16 FX608JMR Laptop,', 'ASUS', 'Gaming Laptop', 'ASUS TUF Gaming F16 FX608JMR Laptop, 16\" WUXGA IPS 165Hz Display,', 1612.14, 1862.74, 'images/Tuff_laptop.png', 6, '2026-11-11 12:41:29', 'SALE'),
(4, 'Gigabyte AORUS GeForce GTX 1070 8GB Graphic Cards OC', 'Gigabyte', 'GPU', 'Gigabyte AORUS GeForce GTX 1070 8GB Graphic Cards OC Mode RGB fusion Nvdia chipset', 246.35, NULL, 'images/GTX1070.png', 7, '2026-11-11 12:41:29', 'NONE'),
(6, 'NEXUS Gaming PC', 'NEXUS', 'Gaming PC', 'NEXUS Gaming PC - Intel Core Ultra 9 285K 24 Cores 24 Threads, NVIDIA RTX 5090 32GB, 32GB DDR5 RAM 6000MHz, 2TB SSD Gen5, 1200W 80 Plus PSU, 360mm Liquid Cooler, Wi-Fi + BT', 12876.67, 13292.33, 'images/white.png', 2, '2026-11-14 08:03:50', 'TOP PICK'),
(7, 'MSI Crosshair', 'MSI', 'Gaming Laptop', 'MSI Crosshair 16 HX E14WFK Gaming Laptop, 16\" QHD+ IPS 240Hz Display, Intel Core i9-14900HX, 16GB RAM, 512B SSD, GeForce RTX 5060 8GB GPU,', 1805.48, NULL, 'images/crosshair.png', 3, '2026-11-14 11:08:36', 'LIMITED OFFER'),
(8, 'Lenovo ThinkPad P16', 'Lenovo', 'workstation', 'Lenovo ThinkPad P16 Gen 3, 16\" 3.2K OLED 120Hz Touch Display, Intel Core Ultra 9 275HX, 96GB RAM, 2TB SSD, NVIDIA RTX Pro 5000 Blackwell 24GB,', 11868.22, NULL, 'images/thinpad-p16.png', 65, '2026-11-14 11:16:17', 'NEW'),
(9, 'ASUS T1 GeForce RTX 5060 Ti OC Edition Graphics Card,', 'ASUS', 'GPU', 'ASUS T1 GeForce RTX 5060 Ti OC Edition Graphics Card, 8GB GDDR7 128-Bit Memory, 2632 MHz Boost Clock,', 632.88, 700.08, 'images/Screenshot 2026-11-17 203905.png', 46, '2026-11-17 16:47:05', 'SALE'),
(10, 'Lenovo Legion 5 15IRX10 Gaming Laptop,', 'Lenovo', 'Gaming Laptop', 'Lenovo Legion 5 15IRX10 Gaming Laptop, 15.1\" WQXGA OLED 165Hz Display, Intel Core i7-14700HX', 1862.74, 0.00, 'images/Screenshot 2026-11-17 204747.png', 12, '2026-11-17 16:49:28', 'NEW'),
(11, 'HP Z2 Tower G1i Workstation Desktop Computer,', 'HP', 'workstation', 'HP Z2 Tower G1i Workstation Desktop Computer, Intel Core Ultra 9 285K, 32GB RAM,', 3363.56, 4563.46, 'images/Screenshot 2026-11-17 205036.png', 5, '2026-11-17 16:53:06', 'LIMITED OFFER'),
(12, 'Dell Pro Max Tower T2 Workstation', 'Dell', 'workstation', 'Dell Pro Max Tower T2 Workstation Desktop Computer, Intel Core Ultra 9 285K, 64GB RAM,', 4587.95, 0.00, 'images/Screenshot 2026-11-17 205404.png', 3, '2026-11-17 16:55:29', 'NONE'),
(13, 'HP ZBook X G1i 16 Mobile Workstation Laptop,', 'HP', 'workstation', 'HP ZBook X G1i 16 Mobile Workstation Laptop, 16\" WUXGA 60Hz Display, Intel Core Ultra 7 255H, 32GB RAM,', 2928.49, 0.00, 'images/Screenshot 2026-11-17 205709.png', 6, '2026-11-17 16:58:32', 'TOP PICK'),
(14, 'Dell Precision 5860 Tower Workstation,', 'Dell', 'workstation', 'Dell Precision 5860 Tower Workstation, Intel Xeon W3-2423 Processor, 64GB RAM, 2TB SSD,', 9451.78, NULL, 'images/Screenshot 2026-11-17 205859.png', 38, '2026-11-17 17:00:07', 'NEW'),
(15, 'Nvidia CMP 70HX 8GB GDDR6X Graphics Card,', 'Nvidia', 'GPU', 'Nvidia CMP 70HX 8GB GDDR6X Graphics Card, 256 bits, 1365Mhz Core Clock, 19Gbps, 2x 8-Pin Connector, 608 (GB/Sec)', 2648.97, 0.00, 'images/Screenshot 2026-11-17 210331.png', 9, '2026-11-17 17:05:31', 'TOP PICK'),
(16, 'NVIDIA RTX 5000 Ada Generation Graphics Card,', 'NVIDIA', 'GPU', 'NVIDIA RTX 5000 Ada Generation Graphics Card, 32GB GDDR6 256-bit Memory, 576 GHz Memory Bandwidth, 12800 CUDA Cores,', 5178.08, 0.00, 'images/Screenshot 2026-11-17 210543.png', 36, '2026-11-17 17:06:56', 'NEW'),
(17, 'ASUS ROG Edition 20 PC', 'ASUS', 'Gaming PC', 'ASUS ROG Edition 20 PC - AMD Ryzen 9 9950X3D2 16 Cores 32 Threads, NVIDIA RTX 5090 32GB,', 25135.07, 0.00, 'images/Screenshot 2026-11-17 210800.png', 4, '2026-11-17 17:09:36', 'LIMITED OFFER'),
(18, 'AORUS Gaming PC - AMD Ryzen 9 9950X3D', 'AORUS', 'Gaming PC', 'AORUS Gaming PC - AMD Ryzen 9 9950X3D 16 Cores 32 Threads, NVIDIA RTX 5080 WATERFORCE 16GB, 64GB DDR5 RAM 6000MHz,', 6259.15, 0.00, 'images/Screenshot 2026-11-17 210956.png', 2, '2026-11-17 17:11:21', 'NONE'),
(19, 'MSI Draco Epic Gaming PC - AMD Ryzen 9 9950X3D2', 'MSI', 'Gaming PC', 'MSI Draco Epic Gaming PC - AMD Ryzen 9 9950X3D2 16 Cores 32 Threads, NVIDIA RTX 5080 16GB, 64GB DDR5 RAM', 9151.87, 0.00, 'images/Screenshot 2026-11-17 211213.png', 14, '2026-11-17 17:13:58', 'NONE'),
(20, 'Hatsune Miku Edition Gaming PC - AMD Ryzen 7 9800X3D', 'ASUS', 'Gaming Laptop', 'Hatsune Miku Edition Gaming PC - AMD Ryzen 7 9800X3D (8C/16T), NVIDIA RTX 5080 16GB, 32GB DDR5 RAM 6000MHz,', 7123.72, 0.00, 'images/Screenshot 2026-11-17 211419.png', 3, '2026-11-17 17:16:12', 'NONE'),
(21, 'HydraX Gaming PC - AMD Ryzen 9 9950X3D', 'HydraX', 'Gaming PC', 'HydraX Gaming PC - AMD Ryzen 9 9950X3D 16 Cores 32 Threads, NVIDIA RTX 5090 32GB, 64GB DDR5 RAM 6000MHz,', 14050.50, 0.00, 'images/Screenshot 2026-11-17 211626.png', 12, '2026-11-17 17:17:31', 'NONE'),
(22, 'Vision Gaming PC - Intel Core Ultra 9 285K 24 Cores 24', 'Vision', 'Gaming PC', 'Vision Gaming PC - Intel Core Ultra 9 285K 24 Cores 24 Threads, NVIDIA RTX 5090 32GB, 32GB DDR5 RAM 6000MHz,', 13034.11, 0.00, 'images/Screenshot 2026-11-17 211819.png', 5, '2026-11-17 17:19:34', 'NONE'),
(23, 'MSI Crosshair 16 HX AI D2XWGKG Gaming Laptop,', 'MSI', 'Gaming Laptop', 'MSI Crosshair 16 HX AI D2XWGKG Gaming Laptop, 16\" QHD+ IPS 240Hz Display, Intel Core Ultra 7 255HX, 16GB RAM,', 2207.95, 0.00, 'images/Screenshot 2026-11-17 212143.png', 54, '2026-11-17 17:22:54', 'NONE');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`) VALUES
(4, 'elad', 'marcel', 'eladmarcel20@gmail.com', '$2y$10$S3RH4r4/s2YaF48yDF0QCOGIipHY5XM9cQQOXmiLBjqSohlkQ6Kpu', '2026-11-13 18:31:27'),
(5, 'elad', 'marcel', 'eladmarcel13@gmail.com', '$2y$10$pjCiv7.3aMLX7mseHPIerOpoBAK9ELd3Tt9jx5z58gAyhtz1tm3NG', '2026-11-13 19:13:03');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
