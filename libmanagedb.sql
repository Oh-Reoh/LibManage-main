-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 04:29 PM
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
  `issueddate` varchar(155) NOT NULL,
  `returndate` varchar(155) NOT NULL,
  `borrowedby` varchar(155) NOT NULL,
  `returnedby` varchar(155) NOT NULL,
  `bookisinuse` int(155) NOT NULL,
  `requestby` varchar(155) NOT NULL,
  `isrequest` int(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bookinfo_logs`
--

INSERT INTO `tbl_bookinfo_logs` (`id`, `bookname`, `author`, `issueddate`, `returndate`, `borrowedby`, `returnedby`, `bookisinuse`, `requestby`, `isrequest`) VALUES
(1, 'Murder on the Orient Express', 'Agatha Christie', '2024-11-28', '', 'Reader1', '', 1, 'Reader1', 1),
(2, 'Murder on the Orient Express', 'Agatha Christie', '2024-11-28', '', 'Reader1', '', 1, 'Reader1', 1),
(3, 'Murder on the Orient Express', 'Agatha Christie', '', '2024-11-28', 'Reader1', '', 0, 'Reader1', 0),
(4, 'Murder on the Orient Express', 'Agatha Christie', '', '2024-11-28', 'Reader1', '', 0, 'Reader1', 0),
(5, 'Murder on the Orient Express', 'Agatha Christie', '2024-11-28', '', 'ethan 1', '', 1, 'ethan 1', 1),
(6, 'Murder on the Orient Express', 'Agatha Christie', '', '2024-11-28', 'ethan 1', '', 0, 'ethan 1', 0);

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
(1, 'user1', 'user1@gmail.com', '$2y$10$4yuPGB6zyTss/OHSqsZ1OOqYYvsspMP1FsM.lKm4TGgNrqk2Fk3MO', 'IT', 'regular', 'images/default.jpg', ''),
(2, 'jay', 'jay@gmail.com', '$2y$10$amtSxTfU/1B9/dLOKMdVt.nHSKBuT1eVr5SV.urRPYnztDyPir54a', 'Engineering', 'regular', 'images/default.jpg', ''),
(3, 'John', 'John@gmail.com', '$2y$10$/D4b/kd.eXpE00IkZO/WJ.Jo7pERQwcMvT9wraQ/yIr3/lfjqsVTy', 'BSIT', 'regular', 'images/default.jpg', ''),
(5, 'regular1', 'regular@gmail.com', '$2y$10$ShyuS9i3UPOByOnvuY8WB.fZAEWI1gq7ZdGp0lSy40f0UNjPED3tS', 'BSIT', 'regular', 'images/default.jpg', ''),
(6, 'admin', 'admin@gmail.com', '$2y$10$WXZ9.7bFiULsRDd.ok2lluGcZ1lFM1azU6oMQg1D5/x/zskAQFTja', 'Admin', 'librarian', 'profilePictures/674d76b7c1488.jpg', 'John Admin'),
(7, 'Reader1', 'Reader@gmail.com', '$2y$10$RN8shrsIXgSMYakwDMas7OuQdX0P2lVi/dPnGGWSUc4wbjFCZySNO', 'BSIT', 'regular', 'images/default.jpg', ''),
(8, 'admin', 'admin@gmail.com', '$2y$10$rIUYE9Jm/he8C4Sn.AObr.44CC5jy.hgnp24YV4QfA6W3wjEtsPnq', 'Admin', 'regular', 'images/default.jpg', 'John Admin');

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
  MODIFY `id` int(155) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_bookinfo_logs`
--
ALTER TABLE `tbl_bookinfo_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_genres`
--
ALTER TABLE `tbl_genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_userinfo`
--
ALTER TABLE `tbl_userinfo`
  MODIFY `id` int(155) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
