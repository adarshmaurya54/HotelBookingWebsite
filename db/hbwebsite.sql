-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2023 at 08:09 AM
-- Server version: 8.0.33
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hbwebsite1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int NOT NULL,
  `admin_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `admin_name`, `password`) VALUES
(1, 'admin', '$2y$10$bbuK86uHLi4IFLEzMSDuluCBJbHgw.OujCRNaArRipu94u88zuIFq');

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `room_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `total_pay` int NOT NULL,
  `room_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phonenum` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_order`
--

CREATE TABLE `booking_order` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `arrival` tinyint NOT NULL DEFAULT '0',
  `refund` int DEFAULT NULL,
  `booking_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `trans_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trans_amount` int DEFAULT NULL,
  `trans_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `trans_resp_msg` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `review_and_rating` int DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carousels`
--

CREATE TABLE `carousels` (
  `id` int NOT NULL,
  `picture` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousels`
--

INSERT INTO `carousels` (`id`, `picture`) VALUES
(1, 'IMG_20230829_185451.jpg'),
(2, 'IMG_20230829_191032.jpg'),
(3, 'IMG_20230829_191038.jpg'),
(4, 'IMG_20230829_191049.jpg'),
(5, 'IMG_20230829_191122.jpg'),
(8, 'IMG_20230829_192519.jpg'),
(9, 'IMG_20230829_192527.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_settings`
--

CREATE TABLE `contact_settings` (
  `id` int NOT NULL,
  `address` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gmap` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ph1` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ph2` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `iframe` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_settings`
--

INSERT INTO `contact_settings` (`id`, `address`, `gmap`, `ph1`, `ph2`, `email`, `iframe`) VALUES
(1, 'Delhi', 'https://goo.gl/maps/T52nymq3iC25Qe22A', '7778889991', '8889990001', 'youremail@gmail.com', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d896389.6398288388!2d77.093231!3d28.644084!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x37205b715389640!2sDelhi!5e0!3m2!1sen!2sin!4v1680511178261!5m2!1sen!2sin');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `picture` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `picture`, `description`) VALUES
(4, 'Air Conditioner', 'IMG_20230830_140541.svg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam, soluta voluptatum alias nam quae in molestiae facilis.'),
(6, 'Wifi', 'IMG_20230830_141753.svg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam, soluta voluptatum alias nam quae in molestiae facilis.'),
(9, 'Geyser', 'IMG_20230830_142523.svg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam, soluta voluptatum alias nam quae in molestiae facilis.'),
(10, 'Spa', 'IMG_20230830_142541.svg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam, soluta voluptatum alias nam quae in molestiae facilis.'),
(11, 'Heater', 'IMG_20230830_142606.svg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam, soluta voluptatum alias nam quae in molestiae facilis.'),
(12, 'Television', 'IMG_20230831_111500.svg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam, soluta voluptatum alias nam quae in molestiae facilis.');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int NOT NULL,
  `feature_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `feature_name`) VALUES
(5, 'Balcony'),
(4, 'Bedroom'),
(11, 'Kitchen'),
(12, 'Sofa');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int NOT NULL,
  `site_title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `site_desc` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `shutdown` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_title`, `site_desc`, `shutdown`) VALUES
(1, 'HB Website', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam, soluta voluptatum alias nam quae in molestiae facilis.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews_and_ratings`
--

CREATE TABLE `reviews_and_ratings` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `rating` int NOT NULL,
  `review` varchar(1600) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `seen` tinyint NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `area` int NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `adult` int NOT NULL,
  `children` int NOT NULL,
  `description` varchar(1050) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `removed` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `area`, `price`, `quantity`, `adult`, `children`, `description`, `status`, `removed`) VALUES
(8, 'Simple Room', 200, 300, 5, 4, 2, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam.', 1, 0),
(9, 'Deluxe Room', 320, 500, 4, 8, 4, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam', 1, 0),
(10, 'Luxury Room', 400, 700, 2, 9, 5, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam', 1, 0),
(11, 'Supreme Deluxe', 500, 1200, 12, 12, 6, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiamLorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiamLorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiamLorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiamLorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi expedita error neque beatae velit! Corporis ut vitae possimus provident incidunt veniam aperiam', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_facilities`
--

CREATE TABLE `room_facilities` (
  `id` int NOT NULL,
  `room_id` int NOT NULL,
  `facilities_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_facilities`
--

INSERT INTO `room_facilities` (`id`, `room_id`, `facilities_id`) VALUES
(104, 8, 4),
(105, 8, 6),
(106, 8, 12),
(107, 9, 4),
(108, 9, 6),
(109, 9, 9),
(110, 9, 11),
(111, 9, 12),
(112, 10, 4),
(113, 10, 6),
(114, 10, 9),
(115, 10, 11),
(116, 10, 12),
(117, 11, 4),
(118, 11, 6),
(119, 11, 9),
(120, 11, 10),
(121, 11, 11),
(122, 11, 12);

-- --------------------------------------------------------

--
-- Table structure for table `room_features`
--

CREATE TABLE `room_features` (
  `id` int NOT NULL,
  `room_id` int NOT NULL,
  `feature_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_features`
--

INSERT INTO `room_features` (`id`, `room_id`, `feature_id`) VALUES
(76, 8, 5),
(77, 8, 4),
(78, 8, 12),
(79, 9, 5),
(80, 9, 4),
(81, 9, 11),
(82, 9, 12),
(83, 10, 5),
(84, 10, 4),
(85, 10, 11),
(86, 10, 12),
(87, 11, 5),
(88, 11, 4),
(89, 11, 11),
(90, 11, 12);

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `id` int NOT NULL,
  `room_id` int NOT NULL,
  `room_img` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `thumbnail` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`id`, `room_id`, `room_img`, `thumbnail`) VALUES
(16, 8, 'IMG_20230831_185649.jpg', 1),
(17, 8, 'IMG_20230831_185754.png', 0),
(18, 8, 'IMG_20230831_185830.png', 0),
(19, 9, 'IMG_20230831_185917.png', 1),
(20, 9, 'IMG_20230831_185929.png', 0),
(21, 10, 'IMG_20230831_185938.png', 0),
(22, 10, 'IMG_20230831_185945.png', 1),
(24, 11, 'IMG_20230831_221834.jpg', 0),
(25, 11, 'IMG_20230831_221838.jpg', 1),
(26, 11, 'IMG_20230831_221842.jpg', 0),
(27, 11, 'IMG_20230831_221846.jpg', 0),
(28, 11, 'IMG_20230831_221851.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `social_link` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'none',
  `icon_class_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'bi-arrow-right-square-fill'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `name`, `social_link`, `icon_class_name`) VALUES
(1, 'Facebook', 'https://www.facebook.com/', 'bi-facebook'),
(2, 'Twitter', 'https://www.twitter.com/', 'bi-twitter'),
(3, 'LinkedIn', 'https://www.linkedin.com/', 'bi-linkedin'),
(4, 'Instagram', 'https://www.instagram.com/', 'bi-instagram');

-- --------------------------------------------------------

--
-- Table structure for table `team_details`
--

CREATE TABLE `team_details` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `picture` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_cred`
--

CREATE TABLE `user_cred` (
  `id` int NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `profile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pincode` int NOT NULL,
  `dob` date NOT NULL,
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `token` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_expire` date DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_queries`
--

CREATE TABLE `user_queries` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` varchar(2300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_name` (`admin_name`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_order`
--
ALTER TABLE `booking_order`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `carousels`
--
ALTER TABLE `carousels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_settings`
--
ALTER TABLE `contact_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `feature_name` (`feature_name`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews_and_ratings`
--
ALTER TABLE `reviews_and_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facilities id` (`facilities_id`),
  ADD KEY `room id` (`room_id`);

--
-- Indexes for table `room_features`
--
ALTER TABLE `room_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `features id` (`feature_id`),
  ADD KEY `room id in features` (`room_id`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room id img` (`room_id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_details`
--
ALTER TABLE `team_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_cred`
--
ALTER TABLE `user_cred`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_queries`
--
ALTER TABLE `user_queries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_order`
--
ALTER TABLE `booking_order`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `carousels`
--
ALTER TABLE `carousels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_settings`
--
ALTER TABLE `contact_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews_and_ratings`
--
ALTER TABLE `reviews_and_ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `room_facilities`
--
ALTER TABLE `room_facilities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `room_features`
--
ALTER TABLE `room_features`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `team_details`
--
ALTER TABLE `team_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_cred`
--
ALTER TABLE `user_cred`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_queries`
--
ALTER TABLE `user_queries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `booking_details_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `booking_order`
--
ALTER TABLE `booking_order`
  ADD CONSTRAINT `booking_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booking_order_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `reviews_and_ratings`
--
ALTER TABLE `reviews_and_ratings`
  ADD CONSTRAINT `reviews_and_ratings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `reviews_and_ratings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `reviews_and_ratings_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD CONSTRAINT `facilities id` FOREIGN KEY (`facilities_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_features`
--
ALTER TABLE `room_features`
  ADD CONSTRAINT `features id` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room id in features` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room id img` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
