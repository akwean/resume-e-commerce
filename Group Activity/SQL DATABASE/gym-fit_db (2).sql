-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 02:40 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

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
(1, 'Kai Cenat', 'M', 1),
(2, 'IShowSpeed', 'M', 2),
(3, 'KSI', 'M', 3),
(4, 'PewDiePie', 'M', 4),
(5, 'MrBeast', 'M', 5),
(6, 'CongTV', 'M', 6),
(7, 'Batman', 'M', 7),
(8, 'Spooder-man', 'M', 8),
(9, 'Deadpool', 'M', 9),
(10, 'WonderWoman', 'F', 10),
(11, 'Mo Jojo', 'M', 11);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(55) NOT NULL,
  `item_desc` varchar(255) NOT NULL,
  `item_price` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_desc`, `item_price`) VALUES
(1, 'Standard Cast Iron Dumbbells', 'These traditional dumbbells are made from solid cast iron and come in various fixed weights. They are durable and perfect for general strength training.', '800.00'),
(2, 'Kettlebells', 'A versatile weight for strength training, endurance, and flexibility, suitable for full-body workouts.', '1000.00'),
(3, 'Yoga Mat', 'A cushioned surface for yoga, stretching, and floor exercises, providing grip and comfort.', '500.00'),
(4, 'Resistance Bands', 'Versatile bands for strength training, stretching, and rehabilitation exercises, available in various resistance levels.', '500.00'),
(5, 'Treadmill', 'A popular cardio machine for walking, jogging, or running indoors.', '9000.00'),
(6, 'Fitness Ball', 'A large inflatable ball used for core strengthening, stability exercises, and rehabilitation.', '300.00'),
(7, 'Cable Machine', 'A multi-functional machine with adjustable cables for various strength training exercises.', '9500.00'),
(8, 'Whey Protein Powder', 'A high-quality protein source derived from milk, ideal for muscle recovery and growth after workouts.', '1500.00'),
(9, 'Leg Press Machine', 'A strength training machine that targets the legs, allowing for controlled leg presses.', '8500.00'),
(10, ' Standard Barbell', 'A straight, solid metal bar used for basic weightlifting exercises like squats, bench presses, and deadlifts. ', '1500.00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `item_id`, `item_qty`, `cus_id`, `Date`) VALUES
(1, 1, 2, 1, '2024-10-18 10:22:02'),
(2, 2, 1, 1, '2024-10-18 10:22:02'),
(3, 6, 5, 6, '2024-10-18 10:22:02'),
(4, 4, 2, 1, '2024-10-18 10:22:02'),
(5, 7, 5, 7, '2024-10-18 10:22:02'),
(6, 8, 2, 1, '2024-10-18 10:22:02'),
(7, 8, 3, 7, '2024-10-18 10:22:02'),
(8, 5, 1, 1, '2024-10-18 10:22:02'),
(9, 10, 4, 1, '2024-10-18 10:22:02'),
(10, 9, 2, 1, '2024-10-18 10:22:02'),
(11, 1, 4, 5, '2024-10-18 10:22:02'),
(12, 7, 1, 6, '2024-10-18 10:22:02'),
(13, 8, 4, 11, '2024-10-18 10:22:02'),
(14, 1, 1, 3, '2024-10-18 10:23:24'),
(16, 1, 1, 2, '2024-10-09 10:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `rent`
--

CREATE TABLE `rent` (
  `rent_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rent`
--

INSERT INTO `rent` (`rent_id`, `trainer_id`, `cus_id`) VALUES
(61, 1, 1),
(62, 1, 3),
(63, 1, 9),
(64, 1, 1),
(65, 1, 1),
(66, 1, 1),
(67, 1, 7),
(68, 10, 7),
(69, 5, 10),
(70, 1, 1),
(71, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE `trainer` (
  `trainer_id` int(11) NOT NULL,
  `trainer_name` varchar(55) NOT NULL,
  `trainer_info` varchar(255) NOT NULL,
  `trainer_rate` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`trainer_id`, `trainer_name`, `trainer_info`, `trainer_rate`) VALUES
(1, 'Baki Hanma', 'Monday, Wednesday, Friday (Morning)\r\n\r\nBaki offers a blend of martial arts and strength training, helping you build endurance and total body fitness.', '100.00'),
(2, 'Yujiro Hanma ', 'Monday, Wednesday, Friday (Evening)\r\n\r\nTrain for raw power and dominance with Yujiro. Expect intense strength and mental toughness training.', '200.00'),
(3, 'Guts – Relentless Warrior', 'Tuesday, Thursday, Saturday (Morning)\r\n\r\nGuts will push you to your limits with weightlifting and endurance workouts focused on sheer willpower.', '500.00'),
(4, 'Son Goku ', 'Tuesday, Thursday, Saturday (Afternoon)\r\n\r\nHigh-energy sessions combining strength, agility, and martial arts, perfect for speed and stamina building.', '1000.00'),
(5, 'Toji Fushiguro', 'Monday, Wednesday, Friday (Afternoon)\r\n\r\nIntense combat-focused training, combining strength, agility, and stealth-based routines.', '250.00'),
(6, 'Toguro Ani', 'Tuesday, Thursday, Saturday (Evening)\r\n\r\nFocus on building massive muscle and durability through heavy lifting and bodybuilding routines.', '100.00'),
(7, 'Saitama ', 'Sunday (Full-Day Special)\r\n\r\nSaitama’s basic yet effective routine is perfect for those who want a simple, consistent workout plan.', '9999.00'),
(8, 'Mahoraga ', 'Monday, Wednesday, Friday (Morning)\r\nMahoraga’s training focuses on adaptability and resilience. Expect dynamic workouts that evolve to match your growth, challenging both your body and mind.', '75.00'),
(9, 'Roronoa Zoro ', 'Monday, Wednesday, Friday (Evening)\r\n\r\nZoro’s intense training combines strength, stamina,  Perfect for those looking to build muscle while sharpening mental focus and discipline.', '5.00'),
(10, 'Gorlack the Destroyer', 'Gorlack\'s training sessions are all about raw, primal power. Expect grueling strength workouts that will push your body to its limits, focusing on building massive muscle and unstoppable force.', '50.00');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `ua_id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`ua_id`, `username`, `password`) VALUES
(1, 'Kai_Cenat', 'Iam_nigg'),
(2, 'IShowSpeed', 'SpeedyGaming123$'),
(3, 'KSI', 'BoxingKing987@'),
(4, 'PewDiePie', 'BroFist2024@'),
(5, 'MrBeast', 'ChallengeMaster456#'),
(6, ' CongTV', 'ViyIsLife2024@'),
(7, 'ImBatman', 'DarkKnight456@\r\n'),
(8, 'Spooder-man', 'WebSlinger789#'),
(9, 'Deadpool', 'MercWithMouth789#'),
(10, 'WonderWoman', 'AmazonianStrength123$'),
(11, 'Mo JOJO', 'ProfessorIhateyou');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `item-constraint` (`item_id`),
  ADD KEY `customer-constraint` (`cus_id`);

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
  ADD PRIMARY KEY (`ua_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer_contact_info`
--
ALTER TABLE `customer_contact_info`
  MODIFY `cci_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_profile`
--
ALTER TABLE `customer_profile`
  MODIFY `cus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rent`
--
ALTER TABLE `rent`
  MODIFY `rent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `trainer`
--
ALTER TABLE `trainer`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `ua_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `customer-constraint` FOREIGN KEY (`cus_id`) REFERENCES `customer_profile` (`cus_id`),
  ADD CONSTRAINT `item-constraint` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

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
