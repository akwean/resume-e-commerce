-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 05:06 PM
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
-- Database: `gym-fit_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_title`) VALUES
(1, 'Accessories'),
(2, 'Equipment'),
(3, 'Supplement'),
(4, 'Attire');

-- --------------------------------------------------------

--
-- Table structure for table `customer_contact_info`
--

CREATE TABLE `customer_contact_info` (
  `cci_id` int(11) NOT NULL,
  `address` varchar(55) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `contact_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profile`
--

CREATE TABLE `customer_profile` (
  `cus_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `gender` char(1) NOT NULL,
  `ua_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_profile`
--

INSERT INTO `customer_profile` (`cus_id`, `name`, `gender`, `ua_id`) VALUES
(16, 'Non, Christian Jeric', 'M', 22),
(17, 'Charls Emil Barquin', 'F', 23),
(18, 'Rod B. Rañola', 'M', 24);

-- --------------------------------------------------------

--
-- Table structure for table `featured`
--

CREATE TABLE `featured` (
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured`
--

INSERT INTO `featured` (`item_id`) VALUES
(1),
(2),
(3),
(4);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(55) NOT NULL,
  `item_desc` varchar(255) NOT NULL,
  `item_price` decimal(6,2) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `time_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stock` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_desc`, `item_price`, `product_image`, `time_added`, `stock`, `category_id`) VALUES
(1, 'Standard Cast Iron Dumbbells', 'These traditional dumbbells are made from solid cast iron and come in various fixed weights. They are durable and perfect for general strength training.', 800.00, '../product_img/1.jpg\r\n', '2024-12-09 12:38:01', 0, 2),
(2, 'Kettlebells', 'A versatile weight for strength training, endurance, and flexibility, suitable for full-body workouts.', 1000.00, '../product_img/2.jpg', '2024-12-09 12:38:01', 0, 2),
(3, 'Yoga Mat', 'A cushioned surface for yoga, stretching, and floor exercises, providing grip and comfort.', 500.00, '../product_img/3.jpg', '2024-12-09 12:38:01', 0, 2),
(4, 'Resistance Bands', 'Versatile bands for strength training, stretching, and rehabilitation exercises, available in various resistance levels.', 500.00, '../product_img/4.jpg', '2024-12-09 12:38:01', 0, 2),
(5, 'Treadmill', 'A popular cardio machine for walking, jogging, or running indoors.', 9000.00, '../product_img/5.jpg', '2024-12-09 12:38:01', 0, 2),
(6, 'Fitness Ball', 'A large inflatable ball used for core strengthening, stability exercises, and rehabilitation.', 300.00, '../product_img/6.jpg', '2024-12-09 12:38:01', 0, 2),
(7, 'Cable Machine', 'A multi-functional machine with adjustable cables for various strength training exercises.', 9500.00, '../product_img/7.jpg', '2024-12-09 12:38:01', 0, 2),
(8, 'Whey Protein Powder', 'A high-quality protein source derived from milk, ideal for muscle recovery and growth after workouts.', 1500.00, '../product_img/8.jpg', '2024-12-09 12:38:01', 0, 3),
(9, 'Leg Press Machine', 'A strength training machine that targets the legs, allowing for controlled leg presses.', 8500.00, '../product_img/9.jpg', '2024-12-09 12:38:01', 0, 2),
(10, ' Standard Barbell', 'A straight, solid metal bar used for basic weightlifting exercises like squats, bench presses, and deadlifts. ', 1500.00, '../product_img/10.jpg', '2024-12-09 12:38:01', -7, 2),
(24, 'Jump Rope', 'For Rope Jumping. Very Good. Very nice', 50.00, '../product_img/11.png', '2024-12-09 12:38:01', 0, 2),
(25, 'Resistance Band', 'Good for yoga. Great Condition. Pati scoliosis mo kaya mapa straight', 50.00, '../product_img/12.png', '2024-12-09 12:38:01', -5, 2),
(26, 'Resistance band', 'A variation of resistance band that can target chest and back more specifically ', 100.00, '../product_img/13.png', '2024-12-09 12:38:01', 0, 2),
(27, 'Yoga Mat', 'Mat for yoga obviously.', 150.00, '../product_img/14.png', '2024-12-09 12:38:01', 0, 2),
(28, 'Earpods pro+', 'Apple earpods pro + with 1000 year waranty kahit patay kana pwede mo paring isauli samin.', 1000.00, '../product_img/15.png', '2024-12-09 12:38:01', 0, 1),
(29, 'Watter Flask', 'Would you care for some Bo-oh of wo-oh', 200.00, '../product_img/16.png', '2024-12-09 12:38:01', 0, 2),
(30, 'Yoga Ball', 'Ball for yoga of course. pwede mong i tapon sa petpeve mo HAHAHAHA', 500.00, '../product_img/17.png', '2024-12-09 12:38:01', 0, 2),
(31, 'Metal Plates', 'Plates made of metal. ito itapon mo sa petepeve mo wag na yung yoga ball', 500.00, '../product_img/18.png', '2024-12-09 12:38:01', 0, 2),
(32, 'Smart Watch', 'Mas smart pa sayo', 250.00, '../product_img/19.png', '2024-12-09 12:38:01', 0, 1),
(33, 'Gym Shoes', 'Shoes for gym not for you', 1000.00, '../product_img/20.png', '2024-12-09 12:38:01', 0, 4),
(53, 'Suit', 'Suit TV series', 123.00, '../product_img/Screenshot (21).png', '2024-12-09 13:45:23', 321, 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','confirmed','shipped','completed','canceled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `address` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `courier_service` varchar(50) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rent`
--

CREATE TABLE `rent` (
  `rent_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE `trainer` (
  `trainer_id` int(11) NOT NULL,
  `trainer_name` varchar(55) NOT NULL,
  `trainer_info` varchar(255) NOT NULL,
  `trainer_rate` decimal(6,2) NOT NULL,
  `trainer_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`trainer_id`, `trainer_name`, `trainer_info`, `trainer_rate`, `trainer_img`) VALUES
(1, 'Baki Hanma', 'Monday, Wednesday, Friday (Morning)\r\n\r\nBaki offers a blend of martial arts and strength training, helping you build endurance and total body fitness.', 100.00, '../trainer_img/baki.png'),
(2, 'Yujiro Hanma ', 'Monday, Wednesday, Friday (Evening)\r\n\r\nTrain for raw power and dominance with Yujiro. Expect intense strength and mental toughness training.', 200.00, '../trainer_img/yuujiro.png'),
(3, 'Guts – Relentless Warrior', 'Tuesday, Thursday, Saturday (Morning)\r\n\r\nGuts will push you to your limits with weightlifting and endurance workouts focused on sheer willpower.', 500.00, '../trainer_img/guts.png'),
(4, 'Son Goku ', 'Tuesday, Thursday, Saturday (Afternoon)\r\n\r\nHigh-energy sessions combining strength, agility, and martial arts, perfect for speed and stamina building.', 1000.00, '../trainer_img/goku.png'),
(5, 'Toji Fushiguro', 'Monday, Wednesday, Friday (Afternoon)\r\n\r\nIntense combat-focused training, combining strength, agility, and stealth-based routines.', 250.00, '../trainer_img/toji.png'),
(6, 'Toguro Ani', 'Tuesday, Thursday, Saturday (Evening)\r\n\r\nFocus on building massive muscle and durability through heavy lifting and bodybuilding routines.', 100.00, '../trainer_img/taguro.png'),
(7, 'Saitama ', 'Sunday (Full-Day Special)\r\n\r\nSaitama’s basic yet effective routine is perfect for those who want a simple, consistent workout plan.', 9999.00, '../trainer_img/saitama.png'),
(8, 'Mahoraga ', 'Monday, Wednesday, Friday (Morning)\r\nMahoraga’s training focuses on adaptability and resilience. Expect dynamic workouts that evolve to match your growth, challenging both your body and mind.', 75.00, '../trainer_img/mahoraga.png'),
(9, 'Roronoa Zoro ', 'Monday, Wednesday, Friday (Evening)\r\n\r\nZoro’s intense training combines strength, stamina,  Perfect for those looking to build muscle while sharpening mental focus and discipline.', 5.00, '../trainer_img/zoro.png'),
(10, 'Gorlack the Destroyer', 'Gorlack\'s training sessions are all about raw, primal power. Expect grueling strength workouts that will push your body to its limits, focusing on building massive muscle and unstoppable force.', 50.00, '../trainer_img/gorlock.png'),
(11, 'Jojo', 'The ehd', 34.00, '../trainer_img/banna cake.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `ua_id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `user_priv` char(1) DEFAULT 'u' COMMENT 'a - admin || u - user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`ua_id`, `username`, `password`, `email`, `reset_token`, `user_priv`) VALUES
(22, 'cj1', '$2y$10$HtlfUKDFt5vmqHmNEirPEuhAOu6FYD/5w97RuqKPNHxyKFGaFJl3W', 'regrowth1521@gmail.com', NULL, 'a'),
(23, 'ch', '$2y$10$Yg4nf2DvMHAbz8WIp1KHWOYeZo3YGY1iyQs77L1.UOzpy4kUxzC1G', 'charlsbarquin2@gmail.com', NULL, 'u'),
(24, 'StonedPhilosopher', '$2y$10$ooqtnmVKJyC2jCF0CJLsOuu.2Xi8b3Z/Q9nr9etaPIB//XA31uRz2', 'ranolarod2@gmail.com', NULL, 'u');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer_contact_info`
--
ALTER TABLE `customer_contact_info`
  ADD PRIMARY KEY (`cci_id`),
  ADD KEY `cus1-constraint` (`cus_id`);

--
-- Indexes for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD PRIMARY KEY (`cus_id`),
  ADD KEY `ua2-id` (`ua_id`);

--
-- Indexes for table `featured`
--
ALTER TABLE `featured`
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_id` (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `rent`
--
ALTER TABLE `rent`
  ADD PRIMARY KEY (`rent_id`),
  ADD KEY `trainer-constraint` (`trainer_id`),
  ADD KEY `csm-constraint` (`cus_id`);

--
-- Indexes for table `trainer`
--
ALTER TABLE `trainer`
  ADD PRIMARY KEY (`trainer_id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`ua_id`),
  ADD UNIQUE KEY `username_3` (`username`,`email`),
  ADD KEY `username` (`username`,`email`),
  ADD KEY `username_2` (`username`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `customer_contact_info`
--
ALTER TABLE `customer_contact_info`
  MODIFY `cci_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_profile`
--
ALTER TABLE `customer_profile`
  MODIFY `cus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rent`
--
ALTER TABLE `rent`
  MODIFY `rent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `trainer`
--
ALTER TABLE `trainer`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `ua_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `customer_contact_info`
--
ALTER TABLE `customer_contact_info`
  ADD CONSTRAINT `cus1-constraint` FOREIGN KEY (`cus_id`) REFERENCES `customer_profile` (`cus_id`);

--
-- Constraints for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD CONSTRAINT `ua2-id` FOREIGN KEY (`ua_id`) REFERENCES `user_account` (`ua_id`);

--
-- Constraints for table `featured`
--
ALTER TABLE `featured`
  ADD CONSTRAINT `featured_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `rent`
--
ALTER TABLE `rent`
  ADD CONSTRAINT `csm-constraint` FOREIGN KEY (`cus_id`) REFERENCES `customer_profile` (`cus_id`),
  ADD CONSTRAINT `trainer-constraint` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`trainer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
