-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql102.infinityfree.com
-- Generation Time: Feb 13, 2025 at 11:55 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37708294_titan_cink`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#6c757d',
  `company_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`, `company_id`) VALUES
(1, 'Silver 50 Mbules', '#5c5c5c', 1),
(2, 'Sivler 50 Fasad', '#adadad', 1),
(3, 'Antracid 50 Mbules', '#016e8f', 1),
(4, 'Antracid 50 Fasad', '#008cb4', 1),
(5, 'Silver 30 Mbules', '#5c5c5c', 1),
(6, 'Silver 30 Fasad', '#adadad', 1),
(7, 'Antracid 30 Fasad', '#008cb4', 1),
(14, 'Silver 0.45   Mbules', '#6c757d', 1);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `color`) VALUES
(1, 'Emante', '#6c757d'),
(2, 'Other', '#6c757d');

-- --------------------------------------------------------

--
-- Table structure for table `inventorysummary`
--

CREATE TABLE `inventorysummary` (
  `product_id` int(11) NOT NULL,
  `current_qty` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventorysummary`
--

INSERT INTO `inventorysummary` (`product_id`, `current_qty`) VALUES
(10, 36),
(11, 3),
(12, 2),
(13, 3),
(14, 15),
(15, 35),
(16, 34),
(17, 33),
(18, 18),
(19, 4),
(20, 28),
(21, 16),
(22, 1),
(23, 14),
(24, 22),
(25, 13),
(26, 12),
(27, 1),
(28, 0),
(30, 24),
(31, 13),
(32, 12),
(33, 16),
(34, 1),
(35, 4),
(36, 19),
(37, 7),
(38, 5),
(40, 16),
(41, 11),
(42, 3),
(43, 10),
(44, 14),
(45, 3),
(46, 8),
(47, 8),
(48, 8),
(49, 8),
(50, 8),
(51, 12),
(52, 18),
(53, 6),
(54, 6),
(55, 15),
(56, 12),
(57, 12),
(58, 8),
(59, 8),
(60, 8),
(61, 12),
(62, 2),
(63, 9),
(64, 2),
(65, 8),
(66, 8),
(67, 8),
(68, 8),
(69, 8),
(70, 12),
(71, 1),
(72, 6),
(73, 1),
(74, 13),
(75, 11),
(76, 9),
(77, 9);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `length` varchar(15) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `length`, `category_id`) VALUES
(10, '0.35', '15.00', 1),
(11, '0.35', '14.50', 1),
(12, '0.35', '14.00', 1),
(13, '0.35', '13.50', 1),
(14, '0.35', '13.00', 1),
(15, '0.35', '12.50', 1),
(16, '0.35', '12.00', 1),
(17, '0.35', '11.50', 1),
(18, '0.35', '11.00', 1),
(19, '0.35', '10.50', 1),
(20, '0.35', '10.00', 1),
(21, '0.35', '15.35', 1),
(22, '0.35', '9.50', 1),
(23, '0.35', '9.00', 1),
(24, '0.35', '8.50', 1),
(25, '0.35', '8.00', 1),
(26, '0.35', '7.50', 1),
(27, '0.35', '7.00', 1),
(28, '0.35', '6.50', 1),
(30, '0.35', '6.00', 1),
(31, '0.35', '5.50', 1),
(32, '0.35', '5.00', 1),
(33, '0.35', '4.50', 1),
(34, '0.35', '4.00', 1),
(35, '0.35', '3.50', 1),
(36, '0.35', '3.00', 1),
(37, '0.35', '2.50', 1),
(38, 'FASAD', '14.00', 2),
(40, 'FASAD', '12.00', 2),
(41, 'FASAD', '11.00', 2),
(42, 'FASAD', '10.00', 2),
(43, 'FASAD', '9.00', 2),
(44, 'Mbules 50', '14.00', 3),
(45, 'Mbules 30', '14.00', 5),
(46, 'Fasad 30', '14.00', 7),
(47, 'Fasad 50', '14.00', 4),
(48, 'Fasad 30', '13.50', 7),
(49, 'Fasad 50', '13.50', 4),
(50, 'Mbules 30', '13.50', 5),
(51, 'Mbules 50', '13.50', 3),
(52, 'Mbules 50', '13.00', 3),
(53, 'Mbules 30', '13.00', 5),
(54, 'Fasad 30', '13.00', 7),
(55, 'Fasad 30', ' 11.00', 6),
(56, '0.45', '8.00', 14),
(57, '0.45', '7.50', 14),
(58, 'Fasad 30', '12.50', 7),
(59, 'Fasad 30', '12.00', 7),
(60, 'Fasad 30', '11.50', 7),
(61, 'Mbules 50', '12.50', 3),
(62, 'Mbules 30', '12.50', 5),
(63, 'Mbules 50', '12.00', 3),
(64, 'Fasad 30', '12.00', 6),
(65, 'Fasad 50', '12.00', 4),
(66, 'Fasad 50', '11.50', 4),
(67, 'Fasad 30', '11.00', 7),
(68, 'Fasad 30', '10.50', 7),
(69, 'Fasad 30', '10.00', 7),
(70, 'Mbules 50', '11.00', 3),
(71, '0.45', '7.00', 14),
(72, 'Mbules 50', '10.50', 3),
(73, '0.45', '11.50', 14),
(74, 'Mbules 30', '10.50', 5),
(75, 'Fasad 30', '9.00', 6),
(76, 'Fasad 30', '10.00', 6),
(77, 'Mbules 50', '9.00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sale_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `quantity`, `sale_date`) VALUES
(2, 16, 11, '2024-12-19'),
(3, 15, 9, '2024-12-20'),
(4, 14, 1, '2024-12-20'),
(5, 32, 1, '2024-12-20'),
(6, 16, 2, '2024-12-20'),
(7, 23, 4, '2024-12-23'),
(8, 25, 8, '2024-12-23'),
(9, 28, 7, '2025-01-02'),
(10, 14, 3, '2025-01-02');

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `bearer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`bearer`) VALUES
('EAAOkIcIDeIoBOzH9vZAZBZBAvhSnILn740tNZAgtDZBmKoGFQwGo8ZCKUhGo0nSfwZB9IXGZA1RlyBYXRQmvF5Jo8mCyfufugGs1MrJ2JHvLITjVJVaZAac9AjuOeD5MqCXnco3EqHDK0SfbemupZB7nsyTO2xSBIIBc0SeOtMwU5GnD9z2RqlMeWoRZAZAwNcTGVo9UZAQZDZD');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('incoming','outgoing') NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `product_id`, `type`, `quantity`, `date`) VALUES
(23, 16, 'outgoing', 11, '2024-12-19 07:45:30'),
(24, 15, 'outgoing', 9, '2024-12-20 22:42:24'),
(25, 14, 'outgoing', 1, '2024-12-20 22:42:36'),
(26, 32, 'outgoing', 1, '2024-12-20 22:43:03'),
(27, 16, 'outgoing', 2, '2024-12-20 22:43:30'),
(28, 23, 'outgoing', 4, '2024-12-23 03:15:14'),
(29, 25, 'outgoing', 8, '2024-12-23 03:15:42'),
(30, 28, 'outgoing', 7, '2025-01-02 06:52:33'),
(31, 14, 'outgoing', 3, '2025-01-02 06:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(65) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('client','admin') NOT NULL,
  `login_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullname`, `email`, `password`, `phone`, `role`, `login_token`) VALUES
(7, 'Asaf Rushiti', 'asaf@gmail.com', '$2y$10$T9lfWvVA.BEsf/.U.NBWDOQtuiaSCtj8/dmlSgG9Ef5BqRusi.kO2', '000111222', 'admin', '$2y$10$YkD3xJISR3Yd3kluGjAOMecLm190.WmPdn/Bwk3UDANW/pHWSXwdW'),
(8, 'Emir', 'ilo_shabani@live.com', '$2y$10$8PBl.8WB7X0l.W04Zh7qcOD5VrHeNchPGHTm85h.fKoLbBt1XHlPW', '070395888', 'admin', '$2y$10$ydckCHoLwMZc3mYEh9r9g.LWql1bTMZk2mpuHlkDAWVzsbZdmlbqG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventorysummary`
--
ALTER TABLE `inventorysummary`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventorysummary`
--
ALTER TABLE `inventorysummary`
  ADD CONSTRAINT `inventorysummary_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
