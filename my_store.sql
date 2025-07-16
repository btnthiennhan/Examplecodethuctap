-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 16, 2025 lúc 10:26 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `my_store`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Samsung', ''),
(2, 'Apple', ''),
(3, 'Oppo', ''),
(4, 'Poco', 'Cap nhat'),
(6, 'hahaha', 'daw'),
(7, 'oke', 'oke'),
(11, 'Electronics', 'All electronic devices');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order`
--

INSERT INTO `order` (`id`, `user_id`, `total_price`, `shipping_address`, `notes`, `payment_method`, `status`, `order_id`) VALUES
(1, 2, 2500.00, 'dawdawdadawd', 'unknown', 'cash', 'paid', ''),
(2, 2, 1300.00, 'aaa', 'unknown', 'cash', 'paid', ''),
(3, 2, 3900.00, 'ddddddd', 'unknown', 'cash', 'paid', ''),
(4, 2, 1300.00, 'xinchao', 'unknown', 'cash', 'paid', ''),
(5, 2, 1300.00, 'ad', 'unknown', 'cash', 'paid', ''),
(6, 2, 3700.00, 'ddd', 'unknown', 'cash', 'paid', ''),
(7, 2, 34400.00, 'aaa', 'unknown', 'cash', 'paid', ''),
(8, 2, 4900.00, 'd', 'unknown', 'cash', 'paid', ''),
(9, 2, 1300.00, 'dawd', 'unknown', 'cash', 'paid', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `user_id`, `total_price`, `shipping_address`, `notes`, `payment_method`, `status`) VALUES
(1, '12345', 1, 500000, '123 Đường ABC, Quận 1, TP.HCM', 'Giao hàng nhanh', 'cash', 'paid'),
(2, '68183452ec40c', 2, 1200, 'test thử cái mới', 'test', 'cash', 'paid'),
(3, '681836d597b4c', 2, 1300, 'v1', 'v1', 'cash', 'paid'),
(4, '68183be3a1a65', 2, 1300, 'v1-2', 'v1-2', 'cash', 'paid'),
(5, '68183c08e8c17', 2, 1200, 'v1-3', 'v1-3', 'cash', 'paid'),
(6, '68184437d9a9e', 2, 1300, 'test cũ', 'cũ', 'cash', 'paid'),
(7, '68185ca179b44', 2, 1300, 'thu nodejs', 'a', 'cash', 'paid'),
(8, '68185ea659c1b', 2, 1300, 'test thử cái mới 2', 'a', 'cash', 'paid'),
(9, '68185fa258df0', 2, 3700, 'test thử cái mới 3', 'dadwawd', 'cash', 'paid'),
(10, '68186402af1b6', 2, 9600, 'data mới', 'a', 'cash', 'paid'),
(11, '686ce8e009e91', 2, 5000, 'dawadw', 'adwdawd', 'cash', 'paid'),
(12, '68776122013e5', 2, 13000, '4234234', 'aefwefwef', 'cash', 'paid'),
(13, '687761a94422f', 2, 13000, '31231231233123133', '3123123123123', 'cash', 'paid'),
(14, '687761e74a559', 2, 2500, '54654654', '654654654654', 'cash', 'paid');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `orderId`) VALUES
(3, '68183452ec40c', 11, 1, 1200, 2),
(4, '681836d597b4c', 8, 1, 1300, 3),
(5, '68183be3a1a65', 8, 1, 1300, 4),
(6, '68183c08e8c17', 9, 1, 1200, 5),
(7, '68184437d9a9e', 8, 1, 1300, 6),
(8, '68185ca179b44', 8, 1, 1300, 7),
(9, '68185ea659c1b', 14, 1, 1300, 8),
(10, '68185fa258df0', 14, 1, 1300, 9),
(11, '68185fa258df0', 9, 1, 1200, 9),
(12, '68185fa258df0', 11, 1, 1200, 9),
(13, '68186402af1b6', 9, 8, 1200, 10),
(14, '686ce8e009e91', 8, 2, 1300, 11),
(15, '686ce8e009e91', 9, 2, 1200, 11),
(16, '68776122013e5', 8, 10, 1300, 12),
(17, '687761a94422f', 8, 10, 1300, 13),
(18, '687761e74a559', 8, 1, 1300, 14),
(19, '687761e74a559', 9, 1, 1200, 14);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
(8, 'ss a 16', 's24', 1300.00, 'uploads/68020760ad4e6.jpg', 3),
(9, 'Oppo a5', 'adwad1', 1200.00, 'uploads/68020d80c8fb2.jpg', 3),
(11, 'Sản phẩm mới', 'Mô tả sản phẩm', 1200.00, 'uploads/68020ea449040.png', 2),
(14, 'ss a 16', 's24', 1300.00, 'uploads/68020eb26838d.png', 3),
(19, 'aa', 'aaaa', 1200.00, NULL, 4),
(24, 'iPhone 15', 'New iPhone', 1500.00, 'uploads/iphone14.jpg', 3),
(29, 'Áo thun nam', 'Áo thun 100% cotton', 299000.00, 'https://example.com/image.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `username`, `password_hash`, `auth_key`, `access_token`, `created_at`, `updated_at`) VALUES
(2, 'admin2', '$2y$13$rHsOZlYuV9zw4kv7rU4xSucH7e9pVPerjYtEcexybKxYXGtYp1WQe', 'GMuJszfkIxqOwVgBPRz6L0d_Zb6FYtZM', 'Tc0oZnpyyhWZk3mXgYCiM8hEc4S-PB9J', '0000-00-00 00:00:00', '2025-06-09 10:58:15'),
(3, 'admin5', '$2y$13$JztaEiWedsCczvNWPWbUjuKO5Pj1PftKuvUhaplFaiQ2fs4UOyn5y', 'BIImjnt_v37RP39so3J7VR43eddnYJG6', '9izctqVOMGD8KR9xUeIOGB9DrVFenqTb', '0000-00-00 00:00:00', '2025-04-23 16:19:59'),
(4, 'nhan2', '$2y$13$UrJqtTC9O/Cm/DTdn687O.mwuXSfHr6CsoKHo22I.zg1L6qKT4gpm', 'sEz5BNrYEaBowvrwrxxsmfFtvpLsIgOU', 'npP1COS6eI5JWbRUzK5qRBSSu1YCXFCq', '2025-04-21 11:41:48', '2025-04-21 11:42:19'),
(5, 'test', '$2y$13$mDIRr2kvy0V4brMDSv04oO2pRtblq7dS2HVzfcMCjQS3a/SRA5OPi', 'dnpkIFkWP3LjfT7j40ym4qJZvXEVALco', 'UTi9XNzt0lVo7YIAd9a8GLlTzQy00n18', '2025-04-21 11:44:19', '2025-04-21 11:44:19'),
(6, 'test2', '$2y$13$1QHvtKplB1KmkzRF1Z1Rle496O8AXyuEPRYMRM/C3GrnTMn17zfv.', '-MDjJphJ9ORNXzNYHbWC0MIOXObXg0lY', 'WbYPN44FxjUzP5iAw5pxHQIVnmcE0oSH', '2025-04-21 11:44:34', '2025-04-21 11:44:34'),
(7, 'test3', '$2y$13$izzBhfElARMTWQ6kLKHZf.AzZnHmJKpIfU8aNf0YcD7IOsFn3X5Xe', 'p0fgQV0oZovm9m3mrTN8bsZ4mnvnngaM', 'oPLqDTKZ7cm2CfFg3Yalt0j0zER-Q4bE', '2025-06-09 10:57:44', '2025-06-09 10:57:44');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_cad55b3cb25b38be94d2ce831d` (`order_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_147bc15de4304f89a93c7eee969` (`orderId`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `FK_147bc15de4304f89a93c7eee969` FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
