-- phpMyAdmin SQL Dump
-- version 4.0.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2013 at 03:26 PM
-- Server version: 5.5.30-MariaDB
-- PHP Version: 5.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eve-odyssey`
--

-- --------------------------------------------------------

--
-- Table structure for table `kgasSites`
--

CREATE TABLE IF NOT EXISTS `kgasSites` (
  `name` varchar(255) NOT NULL,
  `typeID` int(10) NOT NULL,
  `qty` int(10) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kgasSites`
--

INSERT INTO `kgasSites` (`name`, `typeID`, `qty`) VALUES
('Bandit Nebula', 25268, 500),
('Blackeye Nebula', 28699, 3000),
('Boisterous Nebula', 25278, 1000),
('Bright Nebula', 28701, 1000),
('Calabash Nebula', 28696, 1000),
('Cheetah Nebula', 25277, 1000),
('Cobra Nebula', 25276, 250),
('Crab spider Nebula', 25276, 750),
('Crimson Nebula', 25276, 500),
('Dewy Nebula', 25268, 3000),
('Diablo Nebula', 28694, 3000),
('Eagle Nebula', 28695, 3000),
('Flame Nebula', 28700, 1000),
('Flowing Nebula', 25275, 1000),
('Forgotten Nebula', 25274, 500),
('Ghost Nebula', 28695, 1000),
('Glass Nebula', 28696, 3000),
('Goose Nebula', 25274, 1000),
('Hazy Nebula', 25276, 9000),
('Helix Nebula', 28698, 3000),
('Hidden Nebula', 25276, 3000),
('Massive Nebula', 25275, 9000),
('Peacock Nebula', 25275, 3000),
('Phoenix Nebula', 25275, 500),
('Pipe Nebula', 28700, 3000),
('Polar bear Nebula', 25274, 9000),
('Rapture Nebula', 25279, 500),
('Rimy Nebula', 25277, 3000),
('Ring Nebula', 28697, 3000),
('Saintly Nebula', 25278, 500),
('Shimmering Nebula', 25268, 9000),
('Sister Nebula', 28698, 1000),
('Small Nebula', 25277, 1000),
('Smoking Nebula', 28697, 1000),
('Spacious Nebula', 25268, 3000),
('Sunspark Nebula', 28694, 1000),
('Swarm Nebula', 25273, 1000),
('Thick Nebula', 25275, 1000),
('Vaporous Nebula', 25278, 1000),
('Whirling Nebula', 25276, 3000),
('Wild Nebula', 28699, 1000);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
