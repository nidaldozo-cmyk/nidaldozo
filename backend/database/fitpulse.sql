-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2026 at 01:37 PM
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
-- Database: `fitpulse`
--
-- WARNING: This dump drops and recreates the tables below, replacing their
-- existing data with the sample records in this file. Back up data first.
CREATE DATABASE IF NOT EXISTS `fitpulse` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fitpulse`;

-- Drop dependent tables before the tables they reference.
DROP TABLE IF EXISTS `workout_logs`;
DROP TABLE IF EXISTS `goals`;
DROP TABLE IF EXISTS `workouts`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `exercises`;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE IF NOT EXISTS `exercises` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `muscle_group` varchar(100) NOT NULL,
  `equipment` varchar(100) DEFAULT NULL,
  `external_api_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exercises_name` (`name`),
  KEY `idx_exercises_muscle_group` (`muscle_group`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `name`, `muscle_group`, `equipment`, `external_api_id`) VALUES
(1, 'Bench press', 'Chest', 'Barbell', NULL),
(2, 'Barbell squat', 'Legs', 'Barbell', NULL),
(3, 'Pull-up', 'Back', 'Bodyweight', NULL),
(4, 'Overhead press', 'Shoulders', 'Barbell', NULL),
(5, 'Deadlift', 'Back', 'Barbell', NULL),
(6, 'Biceps curl', 'Arms', 'Dumbbell', NULL),
(7, 'Plank', 'Core', 'Bodyweight', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE IF NOT EXISTS `goals` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `goal_type` varchar(100) NOT NULL,
  `target_value` decimal(10,2) NOT NULL,
  `current_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deadline` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goals_user` (`user_id`),
  KEY `idx_goals_exercise` (`exercise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `exercise_id`, `goal_type`, `target_value`, `current_value`, `deadline`) VALUES
(1, 2, 1, 'Strength', 80.00, 70.00, '2026-12-31'),
(2, 2, NULL, 'Body weight', 75.00, 79.00, '2027-03-15'),
(3, 3, 2, 'Strength', 60.00, 40.00, '2027-01-31'),
(4, 4, NULL, 'Cardio', 25.00, 31.00, '2027-02-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin@fitpulse.com', '$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C', 'admin', '2026-10-30 11:53:08'),
(2, 'Amar Hodzic', 'amar@fitpulse.com', '$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C', 'user', '2026-10-30 11:53:08'),
(3, 'Lejla Kovac', 'lejla@fitpulse.com', '$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C', 'user', '2026-10-30 11:53:08'),
(4, 'Tarik Begic', 'tarik@fitpulse.com', '$2y$10$3ndkWhMtNwVoxAYlU2dInOytuoIYfObD1g2X6txJNgcTl0a7bVk3C', 'user', '2026-10-30 11:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE IF NOT EXISTS `workouts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `workout_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_workouts_user_date` (`user_id`,`workout_date`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workouts`
--

INSERT INTO `workouts` (`id`, `user_id`, `title`, `workout_date`, `notes`) VALUES
(1, 2, 'Push day', '2026-10-26', 'Felt strong'),
(2, 2, 'Leg day', '2026-10-28', NULL),
(3, 3, 'Full body', '2026-10-27', 'First week back'),
(4, 4, 'Pull day', '2026-10-29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workout_logs`
--

CREATE TABLE IF NOT EXISTS `workout_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `workout_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `sets` smallint(5) UNSIGNED NOT NULL,
  `reps` smallint(5) UNSIGNED NOT NULL,
  `weight` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_workout_logs_workout` (`workout_id`),
  KEY `idx_workout_logs_exercise` (`exercise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workout_logs`
--

INSERT INTO `workout_logs` (`id`, `workout_id`, `exercise_id`, `sets`, `reps`, `weight`) VALUES
(1, 1, 1, 4, 8, 70.00),
(2, 1, 4, 3, 10, 40.00),
(3, 2, 2, 4, 6, 90.00),
(4, 2, 5, 3, 5, 110.00),
(5, 3, 2, 3, 10, 40.00),
(6, 3, 7, 3, 45, 0.00),
(7, 4, 3, 4, 8, 0.00),
(8, 4, 6, 3, 12, 14.00);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `fk_goals_exercise` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_goals_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workouts`
--
ALTER TABLE `workouts`
  ADD CONSTRAINT `fk_workouts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workout_logs`
--
ALTER TABLE `workout_logs`
  ADD CONSTRAINT `fk_workout_logs_exercise` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_workout_logs_workout` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
