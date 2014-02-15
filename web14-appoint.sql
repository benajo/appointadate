-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2014 at 03:02 PM
-- Server version: 5.5.35
-- PHP Version: 5.3.10-1ubuntu3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `web14-appoint`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE IF NOT EXISTS `appointment` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `length` int(11) NOT NULL COMMENT 'in minutes',
  `accepted` int(1) NOT NULL DEFAULT '0',
  `cancelled` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`appointment_id`),
  KEY `customer_id` (`customer_id`),
  KEY `staff_id` (`staff_id`),
  KEY `business_id` (`business_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `business_id`, `customer_id`, `staff_id`, `datetime`, `length`, `accepted`, `cancelled`, `created`, `updated`) VALUES
(1, 1, 1, NULL, '2013-11-30 10:00:00', 60, 0, 0, '2013-11-30 11:02:50', '2013-11-30 11:02:50'),
(2, 1, 2, NULL, '2013-11-30 11:00:00', 30, 0, 0, '2013-11-30 11:03:47', '2013-11-30 11:03:47'),
(3, 1, 3, NULL, '2013-11-30 12:00:00', 30, 0, 0, '2013-11-30 11:05:14', '2013-11-30 11:05:14'),
(4, 1, 4, NULL, '2013-11-30 12:30:00', 60, 0, 0, '2013-11-30 11:05:35', '2013-11-30 11:05:35'),
(5, 1, 5, NULL, '2013-11-30 14:00:00', 30, 0, 0, '2013-11-30 11:06:21', '2013-11-30 11:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

CREATE TABLE IF NOT EXISTS `business` (
  `business_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address_line_1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address_line_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `county` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `longitude` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `contact_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `contact_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`business_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`business_id`, `name`, `image`, `address_line_1`, `address_line_2`, `city`, `county`, `postcode`, `latitude`, `longitude`, `contact_name`, `contact_phone`, `contact_email`, `created`, `updated`) VALUES
(1, 'Alpha Studios', '', '204 Meston', 'King''s College', 'Aberdeen', 'Aberdeen City', 'AB238JP', '57.165140', '-2.104861', 'Ben Jovanic', '01234567899', 'ben.jovanic.11@aberdeen.ac.uk', '2013-11-30 09:17:43', '2013-11-30 09:17:43'),
(2, 'Anderson & Anderson', '', '', '', '', '', '', '', '', '', '', '', '2014-02-14 12:00:36', '2014-02-14 12:00:36'),
(3, 'Essence Hair and Beauty', '', '', '', '', '', '', '', '', '', '', '', '2014-02-14 12:03:48', '2014-02-14 12:03:48'),
(4, 'Doctor', '', '', '', '', '', '', '', '', '', '', '', '2014-02-14 12:03:48', '2014-02-14 12:03:48'),
(5, 'Peak Physiotherapist', '', '', '', '', '', '', '', '', '', '', '', '2014-02-14 12:03:48', '2014-02-14 12:03:48');

-- --------------------------------------------------------

--
-- Table structure for table `business_type`
--

CREATE TABLE IF NOT EXISTS `business_type` (
  `business_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`business_id`,`type_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pass_hint` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address_line_1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address_line_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `county` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone`, `email`, `password`, `pass_hint`, `address_line_1`, `address_line_2`, `city`, `county`, `postcode`, `created`, `updated`) VALUES
(1, 'Jesse', 'Pinkman', '', '', '', '', '', '', '', '', '', '2013-11-30 09:20:35', '2013-11-30 09:20:35'),
(2, 'Josh', 'Kelly', '', '', '', '', '', '', '', '', '', '2013-11-30 09:25:19', '2013-11-30 09:25:19'),
(3, 'Kate', 'Smith', '', '', '', '', '', '', '', '', '', '2013-11-30 09:25:19', '2013-11-30 09:25:19'),
(4, 'Martin', 'McDonald', '', '', '', '', '', '', '', '', '', '2013-11-30 09:26:22', '2013-11-30 09:26:22'),
(5, 'Olivia', 'Jameson', '', '', '', '', '', '', '', '', '', '2013-11-30 09:26:22', '2013-11-30 09:26:22');

-- --------------------------------------------------------

--
-- Table structure for table `customer_pref_business`
--

CREATE TABLE IF NOT EXISTS `customer_pref_business` (
  `customer_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`,`business_id`),
  KEY `business_id` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `noticeboard`
--

CREATE TABLE IF NOT EXISTS `noticeboard` (
  `noticeboard_id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`noticeboard_id`),
  KEY `business_id` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_meta`
--

CREATE TABLE IF NOT EXISTS `page_meta` (
  `page` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `page_meta`
--

INSERT INTO `page_meta` (`page`, `title`, `keywords`, `description`) VALUES
('/customer/create_appointment.php', 'Create Appointment', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `review` text COLLATE utf8_unicode_ci NOT NULL,
  `reply` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`review_id`),
  KEY `appointment_id` (`appointment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review_helpful`
--

CREATE TABLE IF NOT EXISTS `review_helpful` (
  `review_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `helpful` int(1) NOT NULL,
  PRIMARY KEY (`review_id`,`customer_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) NOT NULL,
  `first_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pass_hint` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`staff_id`),
  KEY `business_id` (`business_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `business_id`, `first_name`, `last_name`, `email`, `password`, `pass_hint`, `admin`, `created`, `updated`) VALUES
(1, 1, 'Walter', 'White', 'walter.white@gmail.com', '', '', 1, '2013-11-30 09:21:17', '2013-11-30 09:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='types to be used in `business_type`' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`type_id`, `name`, `created`, `updated`) VALUES
(1, 'Barber', '2013-11-17 13:28:51', '2013-11-17 13:28:51'),
(2, 'Yoga', '2013-11-17 13:28:51', '2013-11-17 13:28:51'),
(3, 'Nail Salon', '2013-11-17 13:29:57', '2013-11-17 13:29:57'),
(4, 'Hair Salon', '2013-11-17 13:29:57', '2013-11-17 13:29:57');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `business` (`business_id`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `business_type`
--
ALTER TABLE `business_type`
  ADD CONSTRAINT `business_type_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `business` (`business_id`),
  ADD CONSTRAINT `business_type_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`);

--
-- Constraints for table `customer_pref_business`
--
ALTER TABLE `customer_pref_business`
  ADD CONSTRAINT `customer_pref_business_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `customer_pref_business_ibfk_2` FOREIGN KEY (`business_id`) REFERENCES `business` (`business_id`);

--
-- Constraints for table `noticeboard`
--
ALTER TABLE `noticeboard`
  ADD CONSTRAINT `noticeboard_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `business` (`business_id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`appointment_id`);

--
-- Constraints for table `review_helpful`
--
ALTER TABLE `review_helpful`
  ADD CONSTRAINT `review_helpful_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `review` (`review_id`),
  ADD CONSTRAINT `review_helpful_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `business` (`business_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
