-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2024 at 03:06 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real_framp`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `name`, `price`, `quantity`, `image`) VALUES
(62, 1, 3, 'Candle', 10, 100, '2.jpg'),
(69, 9, 2, 'Wallet', 5, 1, '1.jpg'),
(70, 9, 9, 'Perfume', 10, 1, '8.jpg'),
(71, 9, 5, 'Keychain', 6, 10, '4.jpg'),
(72, 8, 3, 'Candle', 11, 1, '2.jpg'),
(73, 11, 2, 'Wallet', 5, 1, '1.jpg'),
(133, 12, 0, 'Keychain', 6, 1, '4.jpg'),
(134, 12, 0, 'Wallet', 5, 2, '1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(10, 1, 'Monica Angel', 'monicaangel48@gmail.com', '7980', 'Thank you for the good merchandise'),
(11, 6, 'Raihan Almi', 'raihan@gmail.com', '01273981', 'owahs');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `request` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `request`) VALUES
(32, 12, 'aldes', '76', 'aldes@gmail.com', 'cash on delivery', 'uy, hh, hhh - 67', 'Keychain (1) , Lanyard  (1) ', 11, '06-Jul-2024', 'completed', 'h'),
(33, 12, 'aldes', '77', 'aldes@gmail.com', 'cash on delivery', 'j, u, u - 88', 'Pouch (1) , Eating Utensils (1) ', 11, '06-Jul-2024', 'completed', 'u'),
(34, 12, 'aldes', '76', 'aldes@gmail.com', 'cash on delivery', 'hg, ff, tr - 76', 'Wallet (1) ', 5, '06-Jul-2024', 'completed', 'hf'),
(35, 13, 'fira', '98', 'fira@gmail.com', 'cash on delivery', 'kj, Ciks, j - 88', 'Wallet (1) , Candle (1) , Eating Utensils (1) , Keychain (1) ', 25, '06-Jul-2024', 'completed', 'kj'),
(36, 14, 'ipri', '0', 'ipri@gmail.com', 'cash on delivery', 'o, o, o - 0', 'Wallet (1) , Candle (1) , Eating Utensils (1) , Mug (1) ', 29, '06-Jul-2024', 'completed', 'o'),
(37, 12, 'aldes', '7654', 'aldes@gmail.com', 'cash on delivery', 'h, g, g - 65', 'Keychain (2) , Lanyard  (2) ', 22, '07-Jul-2024', 'completed', 'f'),
(38, 12, '2_Monica Angel', '98698789789', 'monicaangel48@gmail.com', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, BEKASI, Indonesia - 17610', 'Wallet (1) , Candle (1) , Eating Utensils (1) , Lanyard  (1) ', 24, '07-Jul-2024', 'completed', 'jwasdjasdas'),
(39, 12, 'quiuiqeyqweoqweqweywq', '0327120321', 'monicaangel@aiesec.net', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, wiiq, Indonesia - 293792132', 'Wallet (5) , Candle (6) ', 91, '07-Jul-2024', 'pending', 'jhsdashodsa'),
(40, 12, 'quiuiqeyqweoqweqweywq', '0327120321', 'monicaangel@aiesec.net', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, wiiq, Indonesia - 092309213', 'Wallet (5) , Candle (6) , Lanyard  (1) ', 96, '07-Jul-2024', 'pending', 'jhsdashodsa'),
(41, 12, 'quiuiqeyqweoqweqweywq', '0327120321', 'monicaangel@aiesec.net', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, wiiq, Indonesia - 17610', 'Wallet (5) , Candle (6) , Lanyard  (1) ', 96, '07-Jul-2024', 'pending', 'jhsdashodsa'),
(42, 12, 'quiuiqeyqweoqweqweywq', '0327120321', 'monicaangel@aiesec.net', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, wiiq, Indonesia - 17610', 'Keychain (1) ', 6, '07-Jul-2024', 'pending', 'jhsdashodsa'),
(43, 12, '2_Monica Angel', '989', 'monicaangel48@gmail.com', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, BEKASI, Indonesia - 17610', 'Keychain (1) ', 6, '07-Jul-2024', 'pending', 'ijabdahdiqdqdq'),
(44, 12, 'Monica Angel', '2312321', 'monicaangel@aiesec.net', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, BEKASI, Indonesia - 17610', 'Keychain (1) , Wallet (2) ', 16, '07-Jul-2024', 'pending', 'jhsdashodsa'),
(45, 12, '2_Monica Angel', '983792131', 'monicaangel48@gmail.com', 'cash on delivery', 'Pondok Ungu Permai Blok AL 3/12, Bekasi Utara, BEKASI, Indonesia - 17610', 'Keychain (1) , Wallet (2) ', 16, '07-Jul-2024', 'pending', 'zhdasdosdad');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(5, 32, 5, 1, '6.00'),
(6, 32, 6, 1, '5.00'),
(7, 33, 7, 1, '8.00'),
(8, 33, 4, 1, '3.00'),
(9, 34, 2, 1, '5.00'),
(10, 35, 2, 1, '5.00'),
(11, 35, 3, 1, '11.00'),
(12, 35, 4, 1, '3.00'),
(13, 35, 5, 1, '6.00'),
(14, 36, 2, 1, '5.00'),
(15, 36, 3, 1, '11.00'),
(16, 36, 4, 1, '3.00'),
(17, 36, 8, 1, '10.00'),
(18, 37, 5, 2, '6.00'),
(19, 37, 6, 2, '5.00'),
(20, 38, 2, 1, '5.00'),
(21, 38, 3, 1, '11.00'),
(22, 38, 4, 1, '3.00'),
(23, 38, 6, 1, '5.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `qty` int(250) NOT NULL,
  `image` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `qty`, `image`, `description`) VALUES
(2, 'Wallet', 5, 1000, '1.jpg', 'A good wallet from France\r\n'),
(3, 'Candle', 11, 20, '2.jpg', ''),
(4, 'Eating Utensils', 3, 100, '3.jpg', ''),
(5, 'Keychain', 6, 10, '4.jpg', ''),
(6, 'Lanyard ', 5, 250, '5.jpg', ''),
(7, 'Pouch', 8, 2000, '6.jpg', ''),
(8, 'Mug', 10, 117, '7.jpg', ''),
(9, 'Perfume', 10, 2192, '8.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `user_id`, `order_id`, `product_id`, `rating`, `review`, `created_at`) VALUES
(2, 12, 33, 7, 3, 'ww', '2024-07-06 19:35:33'),
(3, 12, 33, 4, 4, 'hh', '2024-07-06 19:35:33'),
(4, 12, 32, 5, 3, 'mayan', '2024-07-06 19:45:49'),
(5, 12, 32, 6, 5, 'wow', '2024-07-06 19:45:49'),
(6, 13, 35, 2, 3, 'mejik', '2024-07-06 21:14:50'),
(7, 13, 35, 3, 5, 'wow apinya ga mati2', '2024-07-06 21:14:50'),
(8, 13, 35, 4, 4, '2 bulan udah naik haji', '2024-07-06 21:14:50'),
(9, 13, 35, 5, 5, 'kiw', '2024-07-06 21:14:50'),
(10, 14, 36, 2, 4, 'om telolet om', '2024-07-06 21:39:17'),
(11, 14, 36, 3, 5, 'rit eling rit', '2024-07-06 21:39:17'),
(12, 14, 36, 4, 2, 'b aja', '2024-07-06 21:39:17'),
(13, 14, 36, 8, 1, 'pecah bangg', '2024-07-06 21:39:17'),
(14, 12, 34, 2, 4, 'wow', '2024-07-07 07:04:55'),
(15, 12, 37, 5, 5, 'Wow', '2024-07-07 07:37:32'),
(16, 12, 37, 6, 5, 'KEREN', '2024-07-07 07:37:32'),
(17, 12, 38, 2, 5, 'good', '2024-07-07 12:13:03'),
(18, 12, 38, 3, 5, 'wow', '2024-07-07 12:13:03'),
(19, 12, 38, 4, 4, 'nice', '2024-07-07 12:13:03'),
(20, 12, 38, 6, 5, 'aaaa', '2024-07-07 12:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(2, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(6, 'user', 'user@gmail.com', '42810cb02db3bb2cbb428af0d8b0376e', 'user'),
(7, 'prianka', 'prianka@gmail.com', 'e00cf25ad42683b3df678c61f42c6bda', 'admin'),
(8, 'halmeera', 'booyaa.kkukku@gmail.com', '90537ffda9e256f00ce0c78f20cdbbe1', 'user'),
(9, 'raden', 'raden@gmail.com', 'c399440fe7440b7a33e8de0cdcd7f015', 'user'),
(10, 'admin2', 'admin2@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin'),
(11, 'jennie', 'jennie@gmail.com', '202cb962ac59075b964b07152d234b70', 'user'),
(12, 'aldes', 'aldes@gmail.com', '202cb962ac59075b964b07152d234b70', 'user'),
(13, 'fira', 'fira@gmail.com', 'd57d8d5422365e4295153b987f907c5e', 'user'),
(14, 'ipri', 'ipri@gmail.com', '83797b96e840507ed060a49e0c73022a', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
