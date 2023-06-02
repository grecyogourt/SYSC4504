-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 11, 2023 at 09:16 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

CREATE DATABASE IF NOT EXISTS sheehan_yu_syscbook;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sheehan_yu_syscbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_address`
--

CREATE TABLE `users_address` (
  `student_ID` int(10) NOT NULL,
  `street_number` int(5) DEFAULT NULL,
  `street_name` varchar(150) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `province` char(2) DEFAULT NULL,
  `postal_code` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_address`
--

INSERT INTO `users_address` (`student_ID`, `street_number`, `street_name`, `city`, `province`, `postal_code`) VALUES
(49, 1, 'raven ', 'ottawa', 'ON', '123456'),
(50, 1, 'raven', 'toronto', 'ON', '123 456'),
(51, 1, 'raven', 'Vancouver ', 'BC', '456 789');

-- --------------------------------------------------------

--
-- Table structure for table `users_avatar`
--

CREATE TABLE `users_avatar` (
  `student_ID` int(10) NOT NULL,
  `avatar` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_avatar`
--

INSERT INTO `users_avatar` (`student_ID`, `avatar`) VALUES
(49, 3),
(50, 2),
(51, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `student_ID` int(10) NOT NULL,
  `student_email` varchar(150) DEFAULT NULL,
  `first_name` varchar(150) DEFAULT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `DOB` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`student_ID`, `student_email`, `first_name`, `last_name`, `DOB`) VALUES
(49, 'sheehanyu@cmail.carleton.ca', 'sheehan', 'yu', '1999-05-20'),
(50, '12345@cmail.carleton.ca', 'Jun', 'Li', '2023-04-05'),
(51, '7890@cmail.carleton.ca', 'Wang', 'Niu', '2023-04-26');

-- --------------------------------------------------------

--
-- Table structure for table `users_passwords`
--

CREATE TABLE `users_passwords` (
  `student_ID` int(10) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_passwords`
--

INSERT INTO `users_passwords` (`student_ID`, `password`) VALUES
(49, '$2y$10$jAQICLe43UOgpVxv22.X0.aoHXhbxlMm6lnw3sJSbMwQlX3pVqsYi'),
(50, '$2y$10$LYAmY4R.ycylK0gQKG9kyechVdIZCxqRIdGwdwSW/Hkha3tcxJeMa'),
(51, '$2y$10$/qvgAYzh9tq1V2VIXg6MV.BySYFVfdRofdAOTmvTJFAnGEmJ6xEUi');

-- --------------------------------------------------------

--
-- Table structure for table `users_permissions`
--

CREATE TABLE `users_permissions` (
  `student_ID` int(10) NOT NULL,
  `account_type` int(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_permissions`
--

INSERT INTO `users_permissions` (`student_ID`, `account_type`) VALUES
(49, 0),
(50, 1),
(51, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_posts`
--

CREATE TABLE `users_posts` (
  `post_ID` int(11) NOT NULL,
  `student_ID` int(10) DEFAULT NULL,
  `new_post` varchar(1000) DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_posts`
--

INSERT INTO `users_posts` (`post_ID`, `student_ID`, `new_post`, `post_date`) VALUES
(1, 49, '1234', '2023-04-11 19:15:31'),
(2, 49, '6789', '2023-04-11 19:15:35'),
(3, 49, '4321', '2023-04-11 19:15:40'),
(4, 49, '5643', '2023-04-11 19:15:44'),
(5, 49, '3456', '2023-04-11 19:15:49'),
(6, 49, '2341', '2023-04-11 19:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `users_program`
--

CREATE TABLE `users_program` (
  `student_ID` int(10) NOT NULL,
  `program` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_program`
--

INSERT INTO `users_program` (`student_ID`, `program`) VALUES
(49, 'Electrical Engineering'),
(50, 'Software Engineering'),
(51, 'Biomedical and Electrical');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users_address`
--
ALTER TABLE `users_address`
  ADD PRIMARY KEY (`student_ID`);

--
-- Indexes for table `users_avatar`
--
ALTER TABLE `users_avatar`
  ADD PRIMARY KEY (`student_ID`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`student_ID`);

--
-- Indexes for table `users_passwords`
--
ALTER TABLE `users_passwords`
  ADD PRIMARY KEY (`student_ID`);

--
-- Indexes for table `users_permissions`
--
ALTER TABLE `users_permissions`
  ADD PRIMARY KEY (`student_ID`);

--
-- Indexes for table `users_posts`
--
ALTER TABLE `users_posts`
  ADD PRIMARY KEY (`post_ID`),
  ADD KEY `student_ID` (`student_ID`);

--
-- Indexes for table `users_program`
--
ALTER TABLE `users_program`
  ADD PRIMARY KEY (`student_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `student_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users_posts`
--
ALTER TABLE `users_posts`
  MODIFY `post_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_address`
--
ALTER TABLE `users_address`
  ADD CONSTRAINT `users_address_ibfk_1` FOREIGN KEY (`student_ID`) REFERENCES `users_info` (`student_ID`);

--
-- Constraints for table `users_avatar`
--
ALTER TABLE `users_avatar`
  ADD CONSTRAINT `users_avatar_ibfk_1` FOREIGN KEY (`student_ID`) REFERENCES `users_info` (`student_ID`);

--
-- Constraints for table `users_posts`
--
ALTER TABLE `users_posts`
  ADD CONSTRAINT `users_posts_ibfk_1` FOREIGN KEY (`student_ID`) REFERENCES `users_info` (`student_ID`);

--
-- Constraints for table `users_program`
--
ALTER TABLE `users_program`
  ADD CONSTRAINT `users_program_ibfk_1` FOREIGN KEY (`student_ID`) REFERENCES `users_info` (`student_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
