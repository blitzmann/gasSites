-- phpMyAdmin SQL Dump
-- version 4.0.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2013 at 05:03 PM
-- Server version: 5.5.30-MariaDB
-- PHP Version: 5.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eve-odyssey-1.1`
--

-- --------------------------------------------------------

--
-- Table structure for table `gasSites`
--

CREATE TABLE IF NOT EXISTS `gasSites` (
  `name` varchar(255) NOT NULL,
  `typeID` int(10) NOT NULL,
  `qty` int(4) unsigned NOT NULL,
  `typeID2` int(10) DEFAULT NULL,
  `qty2` smallint(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gasSites`
--

INSERT INTO `gasSites` (`name`, `typeID`, `qty`, `typeID2`, `qty2`) VALUES
('Bandit Nebula', 25268, 500, NULL, NULL),
('Barren', 30370, 3000, 30371, 1500),
('Blackeye Nebula', 28699, 3000, NULL, NULL),
('Boisterous Nebula', 25278, 1000, NULL, NULL),
('Bountiful', 30375, 5000, 30376, 1000),
('Bright Nebula', 28701, 1000, NULL, NULL),
('Calabash Nebula', 28696, 1000, NULL, NULL),
('Cheetah Nebula', 25277, 1000, NULL, NULL),
('Cobra Nebula', 25276, 250, NULL, NULL),
('Crab spider Nebula', 25276, 750, NULL, NULL),
('Crimson Nebula', 25276, 500, NULL, NULL),
('Crystal Nebula', 25279, 1000, NULL, NULL),
('Dewy Nebula', 25268, 3000, NULL, NULL),
('Diablo Nebula', 28694, 3000, NULL, NULL),
('Eagle Nebula', 28695, 3000, NULL, NULL),
('Flame Nebula', 28700, 1000, NULL, NULL),
('Flowing Nebula', 25275, 1000, NULL, NULL),
('Forgotten Nebula', 25274, 500, NULL, NULL),
('Ghost Nebula', 28695, 1000, NULL, NULL),
('Glass Nebula', 28696, 3000, NULL, NULL),
('Goose Nebula', 25274, 1000, NULL, NULL),
('Hazy Nebula', 25276, 9000, NULL, NULL),
('Helix Nebula', 28698, 3000, NULL, NULL),
('Hidden Nebula', 25276, 3000, NULL, NULL),
('Instrumental', 30378, 500, 30377, 6000),
('Massive Nebula', 25275, 9000, NULL, NULL),
('Minor', 30372, 3000, 30373, 1500),
('Ordinary', 30373, 3000, 30374, 1500),
('Peacock Nebula', 25275, 3000, NULL, NULL),
('Phoenix Nebula', 25275, 500, NULL, NULL),
('Pipe Nebula', 28700, 3000, NULL, NULL),
('Polar bear Nebula', 25274, 9000, NULL, NULL),
('Rapture Nebula', 25279, 500, NULL, NULL),
('Rimy Nebula', 25277, 3000, NULL, NULL),
('Ring Nebula', 28697, 3000, NULL, NULL),
('Saintly Nebula', 25278, 500, NULL, NULL),
('Shimmering Nebula', 25268, 9000, NULL, NULL),
('Sister Nebula', 28698, 1000, NULL, NULL),
('Sizable', 30374, 3000, 30370, 1500),
('Small Nebula', 25277, 1000, NULL, NULL),
('Smoking Nebula', 28697, 1000, NULL, NULL),
('Spacious Nebula', 25268, 3000, NULL, NULL),
('Sunspark Nebula', 28694, 1000, NULL, NULL),
('Swarm Nebula', 25273, 1000, NULL, NULL),
('Thick Nebula', 25275, 1000, NULL, NULL),
('Token', 30371, 3000, 30372, 1500),
('Vaporous Nebula', 25278, 1000, NULL, NULL),
('Vast', 30375, 1000, 30376, 5000),
('Vital', 30377, 500, 30378, 6000),
('Whirling Nebula', 25276, 3000, NULL, NULL),
('Wild Nebula', 28699, 1000, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
