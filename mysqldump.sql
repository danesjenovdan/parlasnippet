-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2017 at 03:05 PM
-- Server version: 5.7.11
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `parlatube`
--

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `snippet_order` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `published` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `playlist`
--


-- --------------------------------------------------------

--
-- Table structure for table `shortenedurls`
--

CREATE TABLE `shortenedurls` (
  `id` int(11) UNSIGNED NOT NULL,
  `long_url` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `snippet_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shortenedurls`
--


-- --------------------------------------------------------

--
-- Table structure for table `snippet`
--

CREATE TABLE `snippet` (
  `id` int(11) UNSIGNED NOT NULL,
  `video_id` int(11) UNSIGNED DEFAULT NULL,
  `start_time` bigint(20) DEFAULT NULL,
  `end_time` bigint(20) DEFAULT NULL,
  `extras` text CHARACTER SET utf8,
  `short_url` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `published` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `snippet`
--

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE `video` (
  `id` int(11) UNSIGNED NOT NULL,
  `videoid` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `published` tinyint(1) UNSIGNED DEFAULT NULL,
  `subtitles_url` varchar(191) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`id`, `videoid`, `published`, `subtitles_url`) VALUES
(1, 'R3uQ5SwS3yU', 1, 'https://www.youtube.com/watch?v=R3uQ5SwS3yU');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shortenedurls`
--
ALTER TABLE `shortenedurls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_foreignkey_shortenedurls_snippet` (`snippet_id`);

--
-- Indexes for table `snippet`
--
ALTER TABLE `snippet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_foreignkey_snippet_video` (`video_id`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `shortenedurls`
--
ALTER TABLE `shortenedurls`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `snippet`
--
ALTER TABLE `snippet`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `shortenedurls`
--
ALTER TABLE `shortenedurls`
  ADD CONSTRAINT `c_fk_shortenedurls_snippet_id` FOREIGN KEY (`snippet_id`) REFERENCES `snippet` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `snippet`
--
ALTER TABLE `snippet`
  ADD CONSTRAINT `c_fk_snippet_video_id` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
