-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2024 at 05:01 AM
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
-- Database: `libmanagedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookinfo`
--

CREATE TABLE `tbl_bookinfo` (
  `id` int(155) NOT NULL,
  `bookname` varchar(155) NOT NULL,
  `author` varchar(155) NOT NULL,
  `issueddate` varchar(155) NOT NULL,
  `isinuse` int(155) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'blankimg.png',
  `bookNumber` varchar(155) NOT NULL,
  `publishYear` int(11) NOT NULL,
  `genre` varchar(155) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bookinfo`
--

INSERT INTO `tbl_bookinfo` (`id`, `bookname`, `author`, `issueddate`, `isinuse`, `image`, `bookNumber`, `publishYear`, `genre`, `description`) VALUES
(1, 'Murder on the Orient Express', 'Agatha Christie', '2024-11-28', 0, 'murder-on-the-orient-express.jpg', '1', 1934, 'Novel, Mystery', 'Murder on the Orient Express is undoubtedly one of Agatha Christie&#039;s greatest mystery novels.\r\n\r\nJust after midnight, a snowdrift stops the Orient Express in its tracks. The luxurious train is surprisingly full for the time of the year, but by the morning it is one passenger fewer. An American tycoon lies dead in his compartment, stabbed a dozen times, his door locked from the inside. Isolated and with a killer in their midst, detective Hercule Poirot must identify the murderer – in case he or she decides to strike again.'),
(2, 'Harry Potter and the Goblet of Fire', 'J.K Rowling', '2024-11-28', 0, 'Harry_Potter_and_the_Goblet_of_Fire_cover.png', '2', 2005, 'Novel, Fantasy', 'Harry Potter and the Goblet of Fire is a fantasy novel written by the British author J. K. Rowling. It is the fourth novel in the Harry Potter series'),
(3, 'Life of Pi', 'Yann Martel', '2024-11-28', 0, 'life of pi.jpg', '3', 2001, 'Novel, Adventure', 'Life of Pi is a Canadian philosophical novel by Yann Martel published in 2001. The protagonist is Piscine Molitor &quot;Pi&quot; Patel, an Indian boy from Pondicherry, India, who explores issues of spirituality and metaphysics from an early age.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookinfo_logs`
--

CREATE TABLE `tbl_bookinfo_logs` (
  `id` int(11) NOT NULL,
  `bookname` varchar(155) NOT NULL,
  `author` varchar(155) NOT NULL,
  `issueddate` date DEFAULT NULL,
  `returndate` date DEFAULT NULL,
  `borrowedby` varchar(255) DEFAULT NULL,
  `returnedby` varchar(255) DEFAULT NULL,
  `bookisinuse` int(155) NOT NULL,
  `requestby` varchar(155) NOT NULL,
  `isrequest` int(155) NOT NULL,
  `islate` tinyint(1) DEFAULT 0,
  `requestdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bookinfo_logs`
--

INSERT INTO `tbl_bookinfo_logs` (`id`, `bookname`, `author`, `issueddate`, `returndate`, `borrowedby`, `returnedby`, `bookisinuse`, `requestby`, `isrequest`, `islate`, `requestdate`) VALUES
(1, 'Murder on the Orient Express', 'Agatha Christie', NULL, NULL, NULL, NULL, 0, 'TestReader1', 3, 0, '2024-12-10'),
(2, 'Murder on the Orient Express', 'Agatha Christie', NULL, NULL, NULL, NULL, 0, 'TestReader1', 3, 0, '2024-12-10'),
(3, 'Murder on the Orient Express', 'Agatha Christie', NULL, NULL, NULL, NULL, 0, 'TestReader1', 3, 0, '2024-12-10'),
(4, 'Murder on the Orient Express', 'Agatha Christie', '2024-12-10', '2024-12-10', 'TestReader1', 'TestReader1', 0, 'TestReader1', 0, 0, '2024-12-10'),
(5, 'Murder on the Orient Express', 'Agatha Christie', '2024-12-10', '2024-12-11', 'TestReader1', 'TestReader1', 0, 'TestReader1', 0, 0, '2024-12-10'),
(6, 'Murder on the Orient Express', 'Agatha Christie', NULL, NULL, NULL, NULL, 0, 'TestReader3', 3, 0, '2024-12-10'),
(7, 'Murder on the Orient Express', 'Agatha Christie', '2024-12-11', '2024-12-11', 'TestReader1', 'TestReader1', 0, 'TestReader1', 0, 0, '2024-12-11'),
(8, 'Murder on the Orient Express', 'Agatha Christie', '2024-12-11', '2024-12-11', 'TestReader1', 'TestReader1', 0, 'TestReader1', 0, 0, '2024-12-11'),
(9, 'Murder on the Orient Express', 'Agatha Christie', '2024-12-11', '2024-12-11', 'TestReader1', 'TestReader1', 0, 'TestReader1', 0, 0, '2024-12-11'),
(10, 'Murder on the Orient Express', 'Agatha Christie', '2024-12-11', '2024-12-11', 'TestReader1', 'TestReader1', 0, 'TestReader1', 0, 0, '2024-12-12'),
(11, 'Harry Potter and the Goblet of Fire', 'J.K Rowling', '2024-12-11', '2024-12-11', 'TestReader2', 'TestReader2', 0, 'TestReader2', 0, 0, '2024-12-12'),
(12, 'Life of Pi', 'Yann Martel', NULL, NULL, NULL, NULL, 0, 'TestReader2', 1, 0, '2024-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_departments`
--

CREATE TABLE `tbl_departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_departments`
--

INSERT INTO `tbl_departments` (`id`, `department_name`) VALUES
(1, 'BSPT'),
(2, 'BSOT'),
(3, 'BSES'),
(4, 'BSCS'),
(5, 'BSIT'),
(6, 'BSEE'),
(7, 'BSCpE'),
(8, 'BSCE'),
(9, 'BSME'),
(10, 'BSIE'),
(11, 'BEE'),
(12, 'BSCHE'),
(13, 'BSED');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_genres`
--

CREATE TABLE `tbl_genres` (
  `id` int(11) NOT NULL,
  `genre_name` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_genres`
--

INSERT INTO `tbl_genres` (`id`, `genre_name`) VALUES
(33, 'Action'),
(32, 'Adventure'),
(26, 'Art & Photography'),
(19, 'Biography/Autobiography'),
(28, 'Business & Economics'),
(16, 'Children\'s'),
(9, 'Contemporary Fiction'),
(25, 'Cookbooks'),
(3, 'Dystopian'),
(1, 'Fantasy'),
(12, 'Graphic Novels'),
(24, 'Health & Wellness'),
(7, 'Historical Fiction'),
(20, 'History'),
(5, 'Horror'),
(31, 'Humor'),
(10, 'Literary Fiction'),
(11, 'Magical Realism'),
(17, 'Middle Grade'),
(4, 'Mystery'),
(35, 'Nature'),
(15, 'New Adult (NA)'),
(18, 'Non-Fiction'),
(37, 'Novel'),
(29, 'Religion & Spirituality'),
(8, 'Romance'),
(36, 'Satire'),
(34, 'Science'),
(21, 'Science & Nature'),
(2, 'Science Fiction'),
(23, 'Self-Help'),
(13, 'Short Stories'),
(22, 'Technology'),
(6, 'Thriller'),
(27, 'Travel'),
(30, 'True Crime'),
(14, 'Young Adult (YA)');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userinfo`
--

CREATE TABLE `tbl_userinfo` (
  `id` int(155) NOT NULL,
  `username` varchar(155) NOT NULL,
  `email` varchar(155) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(155) NOT NULL,
  `role` enum('regular','librarian') NOT NULL DEFAULT 'regular',
  `profile_picture` varchar(255) DEFAULT 'images/default.jpg',
  `full_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_userinfo`
--

INSERT INTO `tbl_userinfo` (`id`, `username`, `email`, `password`, `department`, `role`, `profile_picture`, `full_name`) VALUES
(6, 'admin', 'admin@gmail.com', '$2y$10$AhC2k6no3Mh7EL6tS7nN2uBRlGBJwPCqgCnfWtPfLCR/37x12S9Ge', 'BSIT', 'librarian', 'profilePictures/6757e93e558fd_bear.jpg', 'John Admin'),
(7, 'TestReader1', 'Reader@gmail.com', '$2y$10$Y.f19VoX5NZpJszRZbP0a.MskrcZhdca63ZyiCT/MWkNAXeS6uM42', 'BSIT', 'regular', 'profilePictures/6757de462e0f0_profile1.png', 'John Reader'),
(9, 'TestReader2', 'reader2@gmail.com', '$2y$10$/N5XT81K8YlXaxHo4KNDte8JdPORp.t5s8D.Nx9ns4rxrN3Q957hC', 'BSCS', 'regular', 'profilePictures/675a5e1bc667e_profile5.png', 'Jane Doe'),
(13, 'TestReader3', 'reader3@gmail.com', '$2y$10$3DgIKXpTw7BNUGpcY1EiSexmPcgqNUbE2K1gvCw/y9N3jj5AEUaYW', 'BSIT', 'regular', 'images/default.jpg', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bookinfo`
--
ALTER TABLE `tbl_bookinfo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookNumber` (`bookNumber`);

--
-- Indexes for table `tbl_bookinfo_logs`
--
ALTER TABLE `tbl_bookinfo_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_genres`
--
ALTER TABLE `tbl_genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genre_name` (`genre_name`);

--
-- Indexes for table `tbl_userinfo`
--
ALTER TABLE `tbl_userinfo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bookinfo`
--
ALTER TABLE `tbl_bookinfo`
  MODIFY `id` int(155) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_bookinfo_logs`
--
ALTER TABLE `tbl_bookinfo_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_genres`
--
ALTER TABLE `tbl_genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_userinfo`
--
ALTER TABLE `tbl_userinfo`
  MODIFY `id` int(155) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
