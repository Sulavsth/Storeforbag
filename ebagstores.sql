-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 30, 2024 at 07:37 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebagstores`
--

-- --------------------------------------------------------

--
-- Table structure for table `bags`
--

CREATE TABLE `bags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `video_url` varchar(255) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bags`
--

INSERT INTO `bags` (`id`, `name`, `description`, `price`, `image_url`, `category_id`, `stock_quantity`, `created_at`, `updated_at`, `video_url`, `material`, `dimensions`, `weight`, `color`) VALUES
(12, 'star', '', 3200.00, NULL, 3, 6, '2024-09-14 05:28:30', '2024-09-14 05:28:30', NULL, NULL, NULL, NULL, NULL),
(13, 'Moderns', '', 1000.00, NULL, 6, 7, '2024-09-14 06:27:29', '2024-09-14 06:27:29', NULL, NULL, NULL, NULL, NULL),
(14, 'hj', 'gvhjkl', 2500.00, NULL, 3, 8, '2024-09-14 06:29:47', '2024-09-14 06:29:47', NULL, NULL, NULL, NULL, NULL),
(16, 'Cluctch', 'dsafsa\r\nfs\r\nsads\r\n\r\ndsa\r\nds', 2000.00, NULL, 5, 43, '2024-09-18 06:40:29', '2024-09-18 06:40:29', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bag_images`
--

CREATE TABLE `bag_images` (
  `id` int(11) NOT NULL,
  `bag_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bag_images`
--

INSERT INTO `bag_images` (`id`, `bag_id`, `image_url`) VALUES
(5, 12, '../uploads/1.jpeg'),
(6, 12, '../uploads/3.jpeg'),
(7, 12, '../uploads/4.jpeg'),
(8, 12, '../uploads/5.jpeg'),
(9, 13, '../uploads/1 (1).jpg'),
(10, 13, '../uploads/2 - Copy.jpeg'),
(11, 13, '../uploads/2.jpeg'),
(12, 14, '../uploads/a-stunning-and-sophisticated-image-of-a-group-of-a-LR_VkH_RTxyNP9qKOzCGUw-xNh5Y6HWQOCUYFay3tDGow.jpeg'),
(13, 14, '../uploads/a-captivating-photo-of-three-asian-girls-radiating-fhDReSONSeaVFJ39IcJDcg-XuaJtMqfSKCkF1gf_7ymcw.jpeg'),
(14, 14, '../uploads/an-exquisite-and-sophisticated-logo-design-for-eba-15V2tG8mRQSvQz3Vwjfs2Q-nJA3gw4dQXSrns9jelFyDQ.jpeg'),
(15, 14, '../uploads/a-stylish-and-luxurious-logo-design-for-ebag-store-27GvW2wbRgK7945gwBdAcg-X1drFfJnQTyEgAKsRaWUgQ.jpeg'),
(16, 14, '../uploads/a-captivating-and-elegant-logo-design-for-ebag-sto-sOcu6Z0VTT6ebSW5CX0ONg-JZPqoTiiRWy6X9vMEn6k_A.jpeg'),
(17, 14, '../uploads/a-sleek-and-contemporary-logo-design-for-ebag-stor-TqWE6IMtTlGbfU1Z29k_dw-PYOflAzgQiu31Z2NL6Gs3g.jpeg'),
(18, 14, '../uploads/a-captivating-watercolor-illustration-of-a-bag-pai-dZYyEKWUT6yIyrEGhiUAug-CeMDCWX_RVSu5P9rzuMLCw.jpeg'),
(19, 14, '../uploads/20240403_100304.jpg'),
(20, 14, '../uploads/[s_1332861158]-[gs_9]-[is_30]-[u_1]-[istr_0.6]-[oi_0]-[m_kandinsky-22]-modern_logo.jpeg'),
(21, 14, '../uploads/Designer.png'),
(32, 16, '../uploads/an-enthralling-book-cover-image-for-classroom-of-t-3eGKZEyKRwWnxQj-K-PiIA-3t5rdLZtTlCVZEo4q3i39Q.jpeg'),
(33, 16, '../uploads/login.png'),
(34, 16, '../uploads/cover.png'),
(35, 16, '../uploads/cover.jpg'),
(36, 16, '../uploads/20240407_094724.png');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(2, 'Premium Bags', ''),
(3, 'Backpack', ''),
(5, 'Luxury Bags', ''),
(6, 'Duffle Bags', '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_evidence` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `shipping`, `status`, `created_at`, `payment_method`, `payment_evidence`) VALUES
(92, 4, 50.00, 50.00, 'delivered', '2024-09-18 06:45:46', 'cash', NULL),
(93, 4, 2600.00, 100.00, 'delivered', '2024-09-18 06:45:46', 'cash', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`) VALUES
(26, 93, 14, 'hj', 1, 2500.00, 2500.00);

-- --------------------------------------------------------

--
-- Table structure for table `persistent_cart`
--

CREATE TABLE `persistent_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bag_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone_number`, `password`, `is_admin`, `created_at`) VALUES
(1, 'Sulav', 'suljh0000@gmail.com', NULL, '$2y$10$fqPmMWx8.cliAOqOElR/N.XthGpjkxvMn3g8ZpN83VD.pdtwtDcJW', 0, '2024-09-13 07:53:31'),
(4, 'Sky', 'Sulavshrestha202@gmail.com', '9825199825', '$2y$10$0a7bculLblIS3oJR32LTWOz7YusAgjAr9gt9DjvVxVcpU7iNxwjsu', 0, '2024-09-18 06:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `user_cart`
--

CREATE TABLE `user_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bags`
--
ALTER TABLE `bags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `bag_images`
--
ALTER TABLE `bag_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bag_id` (`bag_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `persistent_cart`
--
ALTER TABLE `persistent_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bag_id` (`bag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bags`
--
ALTER TABLE `bags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bag_images`
--
ALTER TABLE `bag_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `persistent_cart`
--
ALTER TABLE `persistent_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bags`
--
ALTER TABLE `bags`
  ADD CONSTRAINT `bags_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bag_images`
--
ALTER TABLE `bag_images`
  ADD CONSTRAINT `bag_images_ibfk_1` FOREIGN KEY (`bag_id`) REFERENCES `bags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `persistent_cart`
--
ALTER TABLE `persistent_cart`
  ADD CONSTRAINT `persistent_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `persistent_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`bag_id`) REFERENCES `bags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
