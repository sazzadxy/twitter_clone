-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 16, 2020 at 02:12 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tweety`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentID` int(11) NOT NULL,
  `comment` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `commentOn` int(11) NOT NULL,
  `commentBy` int(11) NOT NULL,
  `commentAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentID`, `comment`, `commentOn`, `commentBy`, `commentAt`) VALUES
(3, 'ss', 15, 11, '2020-08-21 15:03:41'),
(6, 'hey!', 15, 15, '2020-08-23 15:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `followID` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `followOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`followID`, `sender`, `receiver`, `followOn`) VALUES
(28, 11, 15, '2020-09-02 14:07:58'),
(38, 15, 11, '2020-09-03 06:20:12'),
(41, 13, 11, '2020-09-03 14:32:36'),
(49, 15, 12, '2020-09-12 15:33:03');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `likeID` int(11) NOT NULL,
  `likeBy` int(11) NOT NULL,
  `likeOn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`likeID`, `likeBy`, `likeOn`) VALUES
(10, 11, 48),
(11, 11, 49),
(12, 15, 55),
(13, 11, 72),
(14, 11, 73),
(15, 15, 78),
(16, 15, 15),
(17, 11, 104),
(18, 11, 109),
(19, 12, 109),
(21, 15, 107),
(22, 15, 111),
(23, 11, 111),
(24, 11, 98),
(25, 11, 98);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messageID` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `messageTo` int(11) NOT NULL,
  `messageFrom` int(11) NOT NULL,
  `messageOn` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageID`, `message`, `messageTo`, `messageFrom`, `messageOn`, `status`) VALUES
(2, 'ok', 12, 11, '2020-09-06 04:13:10', 1),
(3, 'www', 15, 11, '2020-09-09 05:50:20', 1),
(4, 'd', 12, 15, '2020-09-13 05:50:32', 1),
(5, 'c', 15, 12, '2020-09-13 05:50:59', 1),
(6, 'keep it up', 15, 11, '2020-09-14 06:35:08', 1),
(7, 'ok', 11, 15, '2020-09-14 06:35:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `ID` int(11) NOT NULL,
  `notificationFor` int(11) NOT NULL,
  `notificationFrom` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `type` enum('follow','retweet','like','mention') COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`ID`, `notificationFor`, `notificationFrom`, `target`, `type`, `time`, `status`) VALUES
(1, 15, 11, 111, 'follow', '2020-09-12 05:53:42', 1),
(2, 15, 11, 123, 'mention', '2020-09-13 14:34:12', 1),
(3, 15, 11, 98, 'retweet', '2020-09-13 14:52:33', 1),
(4, 15, 11, 98, 'like', '2020-09-13 14:52:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `trends`
--

CREATE TABLE `trends` (
  `trendID` int(11) NOT NULL,
  `hashtag` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `createdOn` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trends`
--

INSERT INTO `trends` (`trendID`, `hashtag`, `createdOn`) VALUES
(6, 'tag', '2020-09-13 13:08:06'),
(9, 'nojob', '2020-09-07 20:38:54'),
(10, '039', '2020-09-03 21:22:24'),
(11, 'programming', '2020-09-13 13:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `tweetID` int(11) NOT NULL,
  `status` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `tweetBy` int(11) NOT NULL,
  `retweetID` int(11) NOT NULL,
  `retweetBy` int(11) NOT NULL,
  `tweetImage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `likesCount` bigint(11) NOT NULL,
  `retweetCount` bigint(11) NOT NULL,
  `postedOn` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `retweetMsg` varchar(140) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tweets`
--

INSERT INTO `tweets` (`tweetID`, `status`, `tweetBy`, `retweetID`, `retweetBy`, `tweetImage`, `likesCount`, `retweetCount`, `postedOn`, `retweetMsg`) VALUES
(45, '#tag solved', 11, 0, 0, '', 0, 13, '2020-08-03 00:00:00', ''),
(66, '@riho', 11, 33, 11, '', 0, 2, '2020-08-16 20:23:59', 'done'),
(67, 'https://gamil.com', 13, 41, 15, '', 1, 15, '2020-08-02 00:00:00', 'nnnnnn'),
(68, 'https://www.google.com', 11, 30, 15, '', 1, 1, '2020-08-02 00:00:00', 'nbvn'),
(70, '#nojob #tag', 11, 69, 15, '', 0, 18, '2020-08-16 21:17:17', 'dfdfdfdfd'),
(85, '', 11, 15, 15, 'users/VFVY9b1.JPG', 0, 1, '2020-08-18 21:28:03', 'good'),
(86, '', 11, 15, 13, 'users/VFVY9b1.JPG', 0, 2, '2020-08-19 19:11:49', 'ok'),
(87, '@sharp94', 11, 54, 15, '', 0, 3, '2020-08-19 19:20:17', 'kkk'),
(88, '', 11, 15, 15, 'users/VFVY9b1.JPG', 1, 3, '2020-08-19 20:07:43', 'ok'),
(89, '@sharp94', 11, 54, 15, '', 0, 4, '2020-08-19 20:30:49', 'lllll'),
(92, 'ggh', 11, 0, 0, '', 0, 1, '2020-08-27 07:03:03', ''),
(93, '@sharp94', 11, 54, 11, '', 0, 5, '2020-08-27 13:08:59', 'mnm'),
(94, 'https://gamil.com', 13, 67, 11, '', 1, 15, '2020-08-02 00:00:00', 'testing'),
(95, '#tag', 11, 0, 0, '', 0, 1, '2020-09-01 15:54:17', ''),
(98, 'what\'s up', 15, 0, 0, '', 1, 1, '2020-09-03 15:30:30', ''),
(99, '#tag  ccc', 15, 0, 0, '', 0, 1, '2020-09-05 14:15:20', ''),
(100, '#tag', 12, 0, 0, '', 0, 0, '2020-09-07 14:37:34', ''),
(102, '#tag problem solved by upsert  :-)', 12, 0, 0, '', 0, 0, '2020-09-07 15:06:53', ''),
(103, '#programming', 15, 0, 0, 'users/programming.jpg', 0, 1, '2020-09-08 14:35:24', ''),
(104, '#programming', 15, 103, 15, 'users/programming.jpg', 1, 2, '2020-09-08 14:35:24', 'good!'),
(105, '#tag  ccc', 15, 99, 11, '', 0, 1, '2020-09-09 21:06:06', 'ok'),
(106, '#tag', 11, 95, 12, '', 0, 1, '2020-09-09 21:08:17', 'ok'),
(107, 'ggh', 11, 92, 15, '', 1, 1, '2020-09-09 23:16:23', 'mm'),
(108, '#programming', 15, 104, 11, 'users/programming.jpg', 1, 2, '2020-09-10 11:49:54', 'nice'),
(111, '@sharp94 nice work', 12, 0, 0, '', 2, 2, '2020-09-12 05:53:42', ''),
(112, '@sharp94 nice work', 12, 111, 11, '', 0, 1, '2020-09-12 05:53:42', '\\//'),
(113, '@riho, hi bro.', 15, 0, 0, '', 0, 0, '2020-09-12 15:33:37', ''),
(114, 'sd', 15, 0, 0, '', 0, 0, '2020-09-12 15:39:42', ''),
(116, '@sharp94 nice work', 12, 111, 15, '', 1, 2, '2020-09-12 05:53:42', 'keep it up'),
(118, '@riho', 15, 0, 0, '', 0, 0, '2020-09-13 05:51:41', ''),
(121, '#tag', 12, 0, 0, '', 0, 0, '2020-09-13 07:08:06', ''),
(123, '@sazu nice', 11, 0, 0, '', 0, 0, '2020-09-13 14:34:12', ''),
(124, 'what\'s up', 15, 98, 11, '', 0, 1, '2020-09-03 15:30:30', 'mess up'),
(129, '', 12, 0, 0, 'users/49625805_1995274623843717_5630145969740840960_n.jpg', 0, 0, '2020-09-16 06:59:49', ''),
(134, 'hola', 14, 0, 0, '', 0, 0, '2020-09-16 07:17:10', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `screenName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `profileImage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profileCover` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `following` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `bio` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `screenName`, `profileImage`, `profileCover`, `following`, `followers`, `bio`, `country`, `website`) VALUES
(11, 'sharp94', 'sharp@mail.com', '$2y$10$29dVsuPLPju8OEY8e7KaNuBq7om4CuCXDLaFzsD9wU8i2yMct5BKW', 'Sharp', 'users/51461783_807884676229765_9026121068170969088_n.jpg', 'users/Screenshot (979).png', 1, 2, 'Mysterious Single', 'Bangladesh', 'www.iloveu.com'),
(12, 'dude', 'duke@mail.com', '$2y$10$h.NooB1.bT0xQOEEnQ8lRuZ9.Gx1763N7Zw6WrrtqjlBWOtQdL1vq', 'duke', 'users/7889719_2e2ef7fea9bf4ef6b6305d9a0b59af19.jpg', 'users/15977114_715075645322435_6283606381791070911_n.jpg', 0, 3, '', '', ''),
(13, 'maria', 'maria@mail.com', '$2y$10$4ouMETjcDZQBe6taWQ7p0.LqyhBomr4ZiHVawdAFgailZobWBXr46', 'maria', 'users/Screenshot (3647).png', 'users/Screenshot (3646).png', 1, 1, '', '', ''),
(14, 'claudia', 'claudia@mail.com', '$2y$10$5cvMr.ynkoYivCeALjmmlu9pKGM.kiUqXPb8gw7cCIbLZncJKoR/i', 'claudia', 'users/Screenshot (3646).png', 'users/Screenshot (3655).png', 0, 0, '', '', ''),
(15, 'bigbro', 'bigbro@mail.com', '$2y$10$nQXnYvDLjbGhMwiQArWFxOXgEE5Y9k0ZjqDLkTwt5UO3yoU7zawbG', 'sazubro', 'users/71670489_477619416417120_2395201547563696128_n.jpg', 'users/83706370_156276489150295_4121378143963447296_n.jpg', 2, 1, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`followID`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageID`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `trends`
--
ALTER TABLE `trends`
  ADD PRIMARY KEY (`trendID`),
  ADD UNIQUE KEY `hashtag` (`hashtag`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`tweetID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `followID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trends`
--
ALTER TABLE `trends`
  MODIFY `trendID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `tweetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
