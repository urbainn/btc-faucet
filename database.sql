-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 145.239.39.118:3306
-- Generation Time: Mar 06, 2021 at 05:13 PM
-- Server version: 5.5.58-0+deb8u1
-- PHP Version: 5.6.40-0+deb8u11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: ``
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(30) NOT NULL,
  `authorID` int(30) NOT NULL,
  `messageContent` text NOT NULL,
  `topicID` int(30) NOT NULL,
  `publicationTimestamp` varchar(50) NOT NULL DEFAULT '',
  `userType` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_topics`
--

CREATE TABLE `contact_topics` (
  `id` int(10) NOT NULL,
  `topicTitle` varchar(500) NOT NULL,
  `authorID` int(30) NOT NULL,
  `creationTimestamp` int(30) NOT NULL,
  `closed` int(1) NOT NULL,
  `claimedBy` int(30) NOT NULL,
  `supportRead` tinyint(1) NOT NULL DEFAULT '0',
  `userRead` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feature_notifications`
--

CREATE TABLE `feature_notifications` (
  `id` int(255) NOT NULL,
  `userid` int(100) NOT NULL,
  `title` varchar(500) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `anchor` varchar(500) NOT NULL,
  `notificationTimestamp` varchar(150) NOT NULL,
  `icon` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `info_globalStats`
--

CREATE TABLE `info_globalStats` (
  `statID` int(100) NOT NULL,
  `statName` varchar(255) NOT NULL,
  `statValue` int(255) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `info_globalStats`
--

INSERT INTO `info_globalStats` (`statID`, `statName`, `statValue`, `lastUpdate`) VALUES
(1, 'totalWithdraw', 0, '2021-02-21 00:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `info_users`
--

CREATE TABLE `info_users` (
  `id` int(50) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `sats_balance` bigint(30) NOT NULL DEFAULT '100',
  `sessionToken` varchar(90) DEFAULT NULL,
  `bannedTimestamp` int(30) NOT NULL DEFAULT '0',
  `userLevel` int(30) NOT NULL DEFAULT '1',
  `nextLevelXP` int(125) NOT NULL DEFAULT '40',
  `userLevelXP` int(125) NOT NULL DEFAULT '0',
  `gainMultiplier` float NOT NULL DEFAULT '1',
  `faucetTimer` int(20) NOT NULL DEFAULT '600',
  `diceCashback` float NOT NULL DEFAULT '0.05',
  `lastClaimTimestamp` varchar(50) NOT NULL DEFAULT '0',
  `multiplierSatsEarned` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `wageredSinceGift` int(10) NOT NULL DEFAULT '0',
  `wageredTotal` int(20) NOT NULL DEFAULT '0',
  `diceCashbackReward` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `referralID` int(10) DEFAULT NULL,
  `referralIncome` int(10) NOT NULL DEFAULT '0',
  `rank` varchar(5) NOT NULL DEFAULT '1',
  `floodFilter_LastHit` varchar(90) NOT NULL,
  `linkedBtcAddress` varchar(90) NOT NULL,
  `ipAddress` varchar(100) NOT NULL,
  `antiBotInfos` varchar(100) NOT NULL DEFAULT '0,0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs_claims`
--

CREATE TABLE `logs_claims` (
  `id` int(50) NOT NULL,
  `userID` int(50) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `claimAmount` int(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs_withdraws`
--

CREATE TABLE `logs_withdraws` (
  `id` int(100) NOT NULL,
  `userid` varchar(100) NOT NULL,
  `withdrawAmount` varchar(100) NOT NULL,
  `withdrawTimestamp` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_topics`
--
ALTER TABLE `contact_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feature_notifications`
--
ALTER TABLE `feature_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info_globalStats`
--
ALTER TABLE `info_globalStats`
  ADD PRIMARY KEY (`statID`);

--
-- Indexes for table `info_users`
--
ALTER TABLE `info_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `sessionToken` (`sessionToken`);

--
-- Indexes for table `logs_claims`
--
ALTER TABLE `logs_claims`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs_withdraws`
--
ALTER TABLE `logs_withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_topics`
--
ALTER TABLE `contact_topics`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feature_notifications`
--
ALTER TABLE `feature_notifications`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `info_globalStats`
--
ALTER TABLE `info_globalStats`
  MODIFY `statID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `info_users`
--
ALTER TABLE `info_users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_claims`
--
ALTER TABLE `logs_claims`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_withdraws`
--
ALTER TABLE `logs_withdraws`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
