-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 23, 2026 at 05:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticketdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ticket_id` int(11) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `topic` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticket_id`, `owner`, `department`, `status`, `date_created`, `topic`, `message`) VALUES
(1920, 'gbenga.atanda@gmail.com', 'IT Support', 'Open', '2026-10-30', 'Hardware', 'Bad hARDWARE'),
(2220, 'emmanuelolulana1@gmail.com', 'Accounts', 'Open', '2026-10-30', 'Printer', 'Bad printer.'),
(3250, 'emmanuelolulana1@gmail.com', 'Administration', 'Open', '2026-10-30', 'Security', 'Help fix my Password.'),
(4460, 'emmanuelolulana1@gmail.com', 'Accounts', 'Open', '2026-10-30', 'Email', 'Bad Account.'),
(5599, 'emmanuelolulana1@gmail.com', 'IT Support', 'Closed', '2026-11-23', 'Software', 'Internet Solving Request'),
(9518, 'emmanuelolulana1@gmail.com', 'Accounts', 'Open', '2026-10-30', 'Other', 'Pls Work.'),
(9868, 'emmanuelolulana1@gmail.com', 'Administration', 'Open', '2026-10-30', 'Security', 'He\'s Dead.');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `sender_type` enum('customer','staff') NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_messages`
--

INSERT INTO `ticket_messages` (`id`, `ticket_id`, `sender`, `sender_type`, `message`, `sent_at`) VALUES
(1, 8024, 'janesmith', 'staff', 'I\'m working on it.', '2026-10-30 04:02:46'),
(2, 8024, 'emmanuelolulana1@gmail.com', 'customer', 'Ok thanks.', '2026-10-30 04:14:53'),
(3, 4930, 'tbromania', 'staff', 'Working ON it', '2026-10-30 09:43:05'),
(4, 5599, 'emmanuelolulana1@gmail.com', 'customer', 'Hi, I\'ve been having some internet problems for weeks. I need help in getting running back again.', '2026-11-23 03:41:31'),
(5, 5599, 'tbromania', 'staff', 'It will be fixed in two days', '2026-11-23 03:50:34'),
(6, 5599, 'emmanuelolulana1@gmail.com', 'customer', 'Alright', '2026-11-23 03:52:57'),
(7, 5599, 'tbromania', 'staff', 'Done. The internet has been fixed.', '2026-11-23 03:54:17');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_staff`
--

CREATE TABLE `ticket_staff` (
  `staff_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_staff`
--

INSERT INTO `ticket_staff` (`staff_id`, `username`, `fullname`, `department`) VALUES
(1, 'tbromania', 'Temiloluwa Olulana', 'IT Support'),
(2, 'janesmith', 'Jane Smith', 'Human Resources');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_staff_login`
--

CREATE TABLE `ticket_staff_login` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_staff_login`
--

INSERT INTO `ticket_staff_login` (`username`, `password`) VALUES
('janesmith', '8765'),
('tbromania', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_user`
--

CREATE TABLE `ticket_user` (
  `username` varchar(255) NOT NULL,
  `fullname` text NOT NULL,
  `number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_user`
--

INSERT INTO `ticket_user` (`username`, `fullname`, `number`) VALUES
('emmanuelolulana1@gmail.com', 'Temiloluwa Olulana', '2147483647'),
('gbenga.atanda@gmail.com', 'John Smith', '09030903895'),
('olulanafavour2010@gmail.com', 'Favour olulana', '2147483647');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `owner` (`owner`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_staff`
--
ALTER TABLE `ticket_staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `ticket_staff_login`
--
ALTER TABLE `ticket_staff_login`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `ticket_user`
--
ALTER TABLE `ticket_user`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ticket_staff`
--
ALTER TABLE `ticket_staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `ticket_user` (`username`);

--
-- Constraints for table `ticket_staff`
--
ALTER TABLE `ticket_staff`
  ADD CONSTRAINT `ticket_staff_ibfk_1` FOREIGN KEY (`username`) REFERENCES `ticket_staff_login` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
