-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 03, 2021 at 04:11 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ignite`
--
CREATE DATABASE IF NOT EXISTS `ignite` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ignite`;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `ID` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(700) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`ID`, `sender_id`, `receiver_id`, `message`, `sent_at`, `seen_at`) VALUES
(5, 2, 3, 'Hi Mara ! How are you?', '2021-12-02 02:49:22', '2021-12-02 02:49:28'),
(6, 3, 2, 'I am good. How are you John ?', '2021-12-02 02:49:43', '2021-12-02 02:49:47'),
(7, 2, 3, 'I am good as well', '2021-12-02 02:51:32', '2021-12-02 02:53:04'),
(8, 2, 3, 'are you free tonight?', '2021-12-02 02:52:56', '2021-12-02 02:53:04'),
(9, 2, 3, 'was thinking if we can go out somewhere', '2021-12-02 02:54:03', '2021-12-02 02:54:08'),
(10, 3, 2, 'I\'m Sorry, not today. But I am free tomorrow evening', '2021-12-02 02:55:17', '2021-12-02 02:55:20'),
(11, 3, 2, 'I\'m Sorry, not today. But I am free tomorrow evening', '2021-12-02 02:55:22', '2021-12-02 02:55:51'),
(12, 2, 3, 'Perfect lets meet tomorrow then', '2021-12-02 02:55:51', '2021-12-02 02:55:54'),
(13, 3, 2, 'ok. lemme know the venue', '2021-12-02 02:56:20', '2021-12-02 02:56:56'),
(14, 3, 2, 'and time?', '2021-12-02 02:56:30', '2021-12-02 02:56:56'),
(15, 2, 3, 'Vieux-Port Steakhouse\n', '2021-12-02 15:59:09', '2021-12-02 18:59:22'),
(16, 2, 3, '39 Rue Saint-Paul E, Montréal, QC H2Y 1G2', '2021-12-02 16:00:23', '2021-12-02 18:59:26'),
(17, 2, 3, 'time: 5pm', '2021-12-02 16:01:38', '2021-12-02 18:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `ID` int(11) NOT NULL,
  `path` varchar(256) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`ID`, `path`, `uploaded_at`) VALUES
(1, 'ignite-content/images/profile1.jpeg', '2021-11-26 19:51:08'),
(2, 'ignite-content/images/profile2.jpg', '2021-11-26 19:56:40'),
(3, 'ignite-content/images/profile3.jpg', '2021-11-26 21:06:21'),
(4, 'ignite-content/images/profile4.jpg', '2021-11-26 21:06:21'),
(10, 'ignite-content/images/1638303218_rohith.jpg', '2021-11-30 20:13:38'),
(11, 'ignite-content/images/1638304156_rohith.jpg', '2021-11-30 20:29:16'),
(12, 'ignite-content/images/1638311740_chris-hemsworth.jpg', '2021-11-30 22:35:40'),
(13, 'ignite-content/images/1638311968_emma.jpg', '2021-11-30 22:39:28'),
(14, 'ignite-content/images/1638376493_zendaya.jpg', '2021-12-01 16:34:53'),
(15, 'ignite-content/images/1638400501_Anuj.jpg', '2021-12-01 23:15:01'),
(16, 'ignite-content/images/1638406276_emma.jpg', '2021-12-02 00:51:16'),
(17, 'ignite-content/images/1638406294_emma.jpg', '2021-12-02 00:51:34'),
(18, 'ignite-content/images/1638406308_emma.jpg', '2021-12-02 00:51:48'),
(19, 'ignite-content/images/1638472489_images.jpg', '2021-12-02 19:14:49'),
(20, 'ignite-content/images/1638477453_jennifer.jpg', '2021-12-02 20:37:33'),
(21, 'ignite-content/images/1638483576_Tom.jpg', '2021-12-02 22:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(2) NOT NULL,
  `interested_in` tinyint(2) NOT NULL COMMENT '0-MEN\r\n1-Women\r\n2-Both',
  `profile_photo_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL,
  `is_premium` tinyint(2) NOT NULL DEFAULT 0,
  `about` varchar(500) DEFAULT NULL,
  `profession` varchar(50) NOT NULL,
  `living_in` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `first_name`, `last_name`, `email`, `password`, `age`, `gender`, `interested_in`, `profile_photo_id`, `created_at`, `last_seen_at`, `modified_at`, `is_premium`, `about`, `profession`, `living_in`) VALUES
(2, 'John', 'Dow', 'John@gmail.com', 'John123', 28, 'M', 1, 1, '2021-11-26 19:53:56', NULL, NULL, 0, 'I am a lazy person', 'Artist', 'Montreal'),
(3, 'Mara', 'Chandler', 'Mara@gmail.com', 'Mara123', 25, 'F', 0, 2, '2021-11-26 19:57:44', NULL, NULL, 1, NULL, 'Engineer', 'Toronto'),
(4, 'Wes', 'Chandler', 'Wes@gmail.com', 'Wes123', 25, 'M', 1, 3, '2021-11-26 21:07:18', NULL, NULL, 0, 'Giving ignite a try', 'Politician', 'Calgary'),
(5, 'Izzy', 'Morales', 'Izzy@gmail.com', 'Izzy123', 26, 'F', 0, 4, '2021-11-28 22:56:54', NULL, NULL, 0, NULL, 'Pilot', 'Ottawa'),
(6, 'Rohith', 'Kumar', 'rohith@gmail.com', 'rohith123', 21, 'M', 1, 11, '2021-11-30 20:29:16', NULL, NULL, 1, 'I am looking for a partner', 'Engineer', 'Montreal'),
(7, 'Safaldeep', 'singh', 'safal@gmail.com', '123456', 21, 'M', 1, 12, '2021-11-30 22:35:40', NULL, NULL, 1, 'Looking for love of my life', 'Politician', 'Montreal'),
(8, 'Emma', 'Watson', 'Emma@gmail.com', 'Emma123', 21, 'F', 2, 18, '2021-11-30 22:39:29', NULL, NULL, 1, 'Looking to meet new people', 'Artist', 'Hamilton'),
(9, 'Zendaya', 'Maree', 'zendaya@gmail.com', 'zendaya123', 22, 'F', 1, 14, '2021-12-01 16:34:53', NULL, NULL, 1, 'Looking for love of my life', 'Artist', 'Mississauga'),
(10, 'Anuj', 'Singh', 'anuj@gmail.com', 'anuj123', 30, 'M', 2, 15, '2021-12-01 23:15:01', NULL, NULL, 1, 'I am crazy person, Looking for someone who can watch Netflix with me', 'Student', 'Brampton'),
(11, 'Venkatesh', 'Kumar', 'venky@gmail.com', '123456', 29, 'M', 1, 19, '2021-12-02 19:14:49', NULL, NULL, 1, 'Just want someone to chill with', 'Trading', 'Montreal'),
(12, 'Jennifer', 'Lawrence', 'jenny@gmail.com', 'jenny789', 27, 'F', 0, 20, '2021-12-02 20:37:33', NULL, NULL, 1, 'Angel on Earth', 'Artist', 'Vancouver'),
(13, 'Tom', 'Cruise', 'tom@gmail.com', 'tom123', 25, 'M', 2, 21, '2021-12-02 22:19:36', NULL, NULL, 1, 'I have good sense of humor', 'Pilot', 'Edmonton');

-- --------------------------------------------------------

--
-- Table structure for table `user_favourite`
--

DROP TABLE IF EXISTS `user_favourite`;
CREATE TABLE `user_favourite` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `favourite_user_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen_at` timestamp NULL DEFAULT NULL,
  `is_removed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_favourite`
--

INSERT INTO `user_favourite` (`ID`, `user_id`, `favourite_user_id`, `added_at`, `seen_at`, `is_removed`) VALUES
(1, 3, 2, '2021-12-03 02:40:24', '2021-12-03 02:40:29', 0),
(2, 6, 3, '2021-12-03 03:08:13', '2021-12-03 03:08:20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_photo`
--

DROP TABLE IF EXISTS `user_photo`;
CREATE TABLE `user_photo` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wink`
--

DROP TABLE IF EXISTS `wink`;
CREATE TABLE `wink` (
  `ID` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen_at` timestamp NULL DEFAULT NULL,
  `sender_informed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wink`
--

INSERT INTO `wink` (`ID`, `sender_id`, `receiver_id`, `sent_at`, `seen_at`, `sender_informed`) VALUES
(1, 2, 3, '2021-12-03 02:18:43', '2021-12-03 02:37:15', 1),
(2, 2, 3, '2021-12-03 02:39:06', '2021-12-03 02:39:13', 1),
(3, 3, 2, '2021-12-03 02:40:26', '2021-12-03 02:40:29', 1),
(4, 2, 3, '2021-12-03 03:06:44', '2021-12-03 03:06:46', 0),
(5, 6, 3, '2021-12-03 03:08:40', '2021-12-03 03:08:44', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_sender_id` (`sender_id`),
  ADD KEY `fk_receiver_id` (`receiver_id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_user_profile_photo` (`profile_photo_id`);

--
-- Indexes for table `user_favourite`
--
ALTER TABLE `user_favourite`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_favourite_user_id` (`user_id`),
  ADD KEY `fk_user_favourite_user_id` (`favourite_user_id`);

--
-- Indexes for table `user_photo`
--
ALTER TABLE `user_photo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_photo_id` (`photo_id`),
  ADD KEY `fk_user_photo_user_id` (`user_id`);

--
-- Indexes for table `wink`
--
ALTER TABLE `wink`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_wink_sender_id` (`sender_id`),
  ADD KEY `fk_wink_receiver_id` (`receiver_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_favourite`
--
ALTER TABLE `user_favourite`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_photo`
--
ALTER TABLE `user_photo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wink`
--
ALTER TABLE `wink`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `fk_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `user` (`ID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_profile_photo` FOREIGN KEY (`profile_photo_id`) REFERENCES `photo` (`ID`);

--
-- Constraints for table `user_favourite`
--
ALTER TABLE `user_favourite`
  ADD CONSTRAINT `fk_favourite_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `fk_user_favourite_user_id` FOREIGN KEY (`favourite_user_id`) REFERENCES `user` (`ID`);

--
-- Constraints for table `user_photo`
--
ALTER TABLE `user_photo`
  ADD CONSTRAINT `fk_photo_id` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`ID`),
  ADD CONSTRAINT `fk_user_photo_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`);

--
-- Constraints for table `wink`
--
ALTER TABLE `wink`
  ADD CONSTRAINT `fk_wink_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `fk_wink_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
