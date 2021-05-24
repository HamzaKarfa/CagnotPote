-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jun 20, 2019 at 02:20 PM
-- Server version: 10.3.4-MariaDB
-- PHP Version: 7.2.15
 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
 
 
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
 
--
-- Database: `payetonpote`
--
CREATE DATABASE IF NOT EXISTS `payetonpote` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `payetonpote`;
 
-- --------------------------------------------------------
 
--
-- Table structure for table `compagnie`
--
 
CREATE TABLE `compagnie` (
  `id` VARCHAR(32) NOT NULL,
  `title` VARCHAR(150) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `goal` INT(11) DEFAULT NULL,
  `name` VARCHAR(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Table structure for table `candidat`
--
 
CREATE TABLE `candidat` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(200) DEFAULT NULL,
  `email` VARCHAR(200) DEFAULT NULL,
  `campaign_id` VARCHAR(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
-- --------------------------------------------------------
 
--
-- Table structure for table `client`
--
 
CREATE TABLE `user` (
  `id` INT(11) NOT NULL,
  `role` VARCHAR(150) DEFAULT "ROLE_USER",

  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `participant_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
 
--
-- Table structure for table `pivot`
--
 
CREATE TABLE `pivot` (
  `id` INT(11) NOT NULL,
  `campaign_id` VARCHAR(32) NOT NULL
  `payment_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
--
-- Indexes for dumped tables
--
 
--
-- Indexes for table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`id`);
 
 
--
-- Indexes for table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_participant_campaign1_idx` (`campaign_id`);
 
--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_participant1_idx` (`participant_id`);
 
--
-- AUTO_INCREMENT for dumped tables
--
 
--
-- AUTO_INCREMENT for table `participant`
--
ALTER TABLE `participant`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
 
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
 
--
-- Constraints for dumped tables
--
 
--
-- Constraints for table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `fk_participant_campaign1_idx` FOREIGN KEY (`campaign_id`) REFERENCES `campaign` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
 
--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_participant1` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;
 
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;