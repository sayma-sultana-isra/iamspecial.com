-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 03:36 PM
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
-- Database: `autism_support_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessibility_levels`
--

CREATE TABLE `accessibility_levels` (
  `accessibility_id` int(11) NOT NULL,
  `accessibility_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `age_groups`
--

CREATE TABLE `age_groups` (
  `age_group_id` int(11) NOT NULL,
  `age_range` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answer_id`, `question_id`, `user_id`, `answer_text`, `created_at`) VALUES
(1, 1, 5, ' it depends. It depends on the type of therapy and the therapy goals written specifically for your child. Most children with autism do receive therapy, but the type and length of time is highly variable.  ', '2025-01-23 16:38:00'),
(5, 2, 3, 'Remembering to keep some activities simply for fun is important! This helps to give children (and parents) a break and prevent burnout, which is important because when children are fatigued they will often be more frustrated and less engaged when you are practicing new skills at home', '2025-01-23 17:07:01'),
(6, 5, 3, 'Applied Behavior Analysis involves many techniques for understanding and changing behavior. ABA is a flexible treatment:  \r\n\r\nCan be adapted to meet the needs of each unique person\r\nProvided in many different locations – at home, at school, and in the community\r\nTeaches skills that are useful in everyday life\r\nCan involve one-to-one teaching or group instruction\r\n', '2025-01-27 06:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `behavioral_logs`
--

CREATE TABLE `behavioral_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `behavior_type_id` int(11) NOT NULL,
  `occurrence_time` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `intensity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `behavioral_logs`
--

INSERT INTO `behavioral_logs` (`id`, `user_id`, `behavior_type_id`, `occurrence_time`, `notes`, `created_at`, `intensity`) VALUES
(39, 1, 1, '2025-01-18 22:43:00', '', '2025-01-18 16:44:08', 2),
(40, 1, 3, '2025-01-09 22:44:00', '', '2025-01-18 16:44:24', 4),
(41, 1, 1, '2025-01-04 22:44:00', '', '2025-01-18 16:44:41', 1),
(42, 5, 3, '2025-01-07 11:50:00', '', '2025-01-27 05:50:38', 2),
(43, 5, 3, '2025-01-15 11:50:00', '2', '2025-01-27 05:51:00', 2),
(44, 5, 5, '2025-01-17 11:51:00', '', '2025-01-27 05:51:26', 3),
(45, 5, 2, '2025-01-23 13:34:00', '', '2025-01-27 07:34:58', 5),
(46, 5, 2, '2025-01-24 13:35:00', '', '2025-01-27 07:35:12', 3),
(47, 5, 2, '2025-01-27 13:35:00', '', '2025-01-27 07:35:24', 4);

-- --------------------------------------------------------

--
-- Table structure for table `behavior_types`
--

CREATE TABLE `behavior_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `behavior_types`
--

INSERT INTO `behavior_types` (`id`, `type_name`) VALUES
(1, 'Aggression'),
(3, 'Attention Seeking'),
(2, 'Eye Contact'),
(4, 'Repetitive Behavior'),
(5, 'Self-Injury'),
(6, 'Sensory Overload'),
(7, 'Social Withdrawal');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 4, 3, 'this is my first comment', '2025-01-04 19:21:35'),
(2, 4, 3, 'And this one is my second.\n', '2025-01-04 20:31:38'),
(4, 3, 3, 'the previous one is deleted.\n\n', '2025-01-04 20:32:17'),
(7, 4, 3, 'this is a comment\n', '2025-01-05 07:28:20'),
(9, 1, 3, 'haha 0 comment\n', '2025-01-06 16:53:11'),
(12, 13, 4, 'nice picture', '2025-01-08 16:43:33'),
(16, 24, 3, 'lets\'s do a comment\n', '2025-01-23 05:36:18'),
(17, 29, 6, 'great understanding', '2025-01-27 07:13:12'),
(18, 31, 4, 'god bless you', '2025-01-27 07:15:29'),
(19, 31, 4, 'you can try counselling', '2025-01-27 07:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `daily_planner`
--

CREATE TABLE `daily_planner` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `priority` enum('High','Medium','Low') NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `daily_planner`
--

INSERT INTO `daily_planner` (`task_id`, `user_id`, `title`, `description`, `priority`, `deadline`, `status`, `created_at`) VALUES
(0, 3, 'rgf', 'fgfg', 'High', '2025-01-09', 'Completed', '2025-01-19 16:19:44'),
(0, 3, 'rgf', 'fgfg', 'High', '2025-01-09', 'Completed', '2025-01-19 16:19:53'),
(0, 3, 'rgf', 'fgfg', 'High', '2025-01-09', 'Completed', '2025-01-19 16:19:44'),
(0, 3, 'rgf', 'fgfg', 'High', '2025-01-09', 'Completed', '2025-01-19 16:19:53'),
(0, 5, 'playtime', 'play with friends', 'Medium', '2025-01-27', 'Pending', '2025-01-27 05:51:57'),
(0, 5, 'shower', 'take a bath', 'High', '2025-01-28', 'Pending', '2025-01-27 06:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `accessibility_level` varchar(100) DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `organizer_id` int(11) DEFAULT NULL,
  `expert_id` int(11) DEFAULT NULL,
  `attendees_count` int(11) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `event_mode` varchar(50) DEFAULT NULL,
  `parental_guidance_required` tinyint(1) DEFAULT NULL,
  `resources_provided` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `event_id` int(11) NOT NULL,
  `notification_sent` tinyint(1) DEFAULT 0,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`title`, `description`, `location`, `category`, `date`, `accessibility_level`, `event_type`, `age_group`, `organizer_id`, `expert_id`, `attendees_count`, `start_time`, `end_time`, `event_mode`, `parental_guidance_required`, `resources_provided`, `status`, `event_id`, `notification_sent`, `name`) VALUES
('Children\'s Webinar on Mental Health', 'A webinar focused on mental health strategies for children.', 'Online', 'Health', '2025-02-10', 'Medium', 'Webinar', '0-5', NULL, NULL, 150, '10:00:00', '12:00:00', 'Online', NULL, NULL, 'Upcoming', 8, 0, ''),
('Autism Awareness Seminar', 'A seminar to raise awareness about autism and provide support to families.', 'Community Center', 'Awareness', '2025-03-15', 'High', 'Seminar', '6-10', NULL, NULL, 200, '14:00:00', '16:30:00', 'Offline', NULL, NULL, 'Upcoming', 9, 0, ''),
('Parenting Tips Workshop', 'A hands-on workshop for parents to learn effective parenting strategies.', 'Community Hall', 'Education', '2025-04-20', 'Low', 'Workshop', '11-15', NULL, NULL, 50, '09:00:00', '12:00:00', 'Offline', NULL, NULL, 'Upcoming', 10, 0, ''),
('Child Education Online Course', 'An online course for parents on the importance of early childhood education.', 'Online', 'Education', '2025-05-05', 'Medium', 'Webinar', '0-5', NULL, NULL, 100, '11:00:00', '13:00:00', 'Online', NULL, NULL, 'Upcoming', 11, 0, ''),
('Health and Nutrition for Kids', 'A workshop for parents on providing proper health and nutrition for kids.', 'Online', 'Health', '2025-06-12', 'High', 'Workshop', '6-10', NULL, NULL, 80, '15:00:00', '17:00:00', 'Online', NULL, NULL, 'Upcoming', 12, 0, ''),
('Autism Support Group Meeting', 'A support group meeting for parents of children with autism.', 'Local Community Center', 'Awareness', '2025-07-02', 'Low', 'Seminar', '11-15', NULL, NULL, 30, '18:00:00', '20:00:00', 'Offline', NULL, NULL, 'Upcoming', 13, 0, ''),
('Mental Health for Parents Webinar', 'A webinar on coping strategies for parents of children with mental health challenges.', 'Online', 'Health', '2025-08-10', 'Medium', 'Webinar', '0-5', NULL, NULL, 120, '19:00:00', '21:00:00', 'Online', NULL, NULL, 'Upcoming', 14, 0, ''),
('Parenting Tools for Early Childhood', 'A workshop offering tools and techniques for parents to manage early childhood development.', 'Offline', 'Education', '2025-09-18', 'High', 'Workshop', '0-5', NULL, NULL, 60, '10:00:00', '12:00:00', 'Offline', NULL, NULL, 'Upcoming', 15, 0, ''),
('Autism Awareness Month Celebration', 'A community event to celebrate Autism Awareness Month with various activities and talks.', 'Local Park', 'Awareness', '2025-10-05', 'High', 'Seminar', '6-10', NULL, NULL, 500, '09:00:00', '15:00:00', 'Offline', NULL, NULL, 'Upcoming', 16, 0, ''),
('Effective Communication with Kids Webinar', 'An interactive webinar on improving communication with children.', 'Online', 'Education', '2025-11-22', 'Medium', 'Webinar', '6-10', NULL, NULL, 200, '13:00:00', '15:00:00', 'Online', NULL, NULL, 'Completed', 17, 0, ''),
('pitha uthshob', 'Arrange a pitha uthshob\r\n', 'DHANMONDI', 'Others', '2025-01-31', 'Medium', 'Workshop', '11-15', NULL, NULL, 0, '16:25:00', '22:30:00', 'Offline', NULL, NULL, NULL, 18, 0, ''),
('pitha uthshob', 'arrange a pitha uthshob', 'Mohammadpur', 'Others', '2025-01-27', 'High', 'Workshop', '0-5', NULL, NULL, 0, '10:30:00', '22:30:00', 'Offline', NULL, NULL, NULL, 20, 0, ''),
('kdhuwe', 'nfwh', 'nbdhs', 'Health', '2025-01-24', 'Low', 'Workshop', '0-5', NULL, NULL, 0, '20:56:00', '20:59:00', 'Online', NULL, NULL, NULL, 21, 0, ''),
('ksjad', 'ajshdu', 'ash', 'Health', '2025-02-07', 'Low', 'Webinar', '6-10', NULL, NULL, 0, '22:19:00', '13:19:00', 'Online', NULL, NULL, NULL, 22, 0, ''),
('ksjad', 'nb', 'jadah', 'Health', '2025-01-24', 'Low', 'Webinar', '6-10', NULL, 0, 0, '22:46:00', '22:49:00', 'Online', NULL, NULL, '', 23, 0, ''),
('dbshf', 'hgwefy', 'mirpur', 'Health', '2025-01-24', 'High', 'Webinar', '6-10', NULL, NULL, 0, '23:17:00', '23:17:00', 'Offline', NULL, NULL, NULL, 26, 0, ''),
('dbshf', 'hgwefy', 'mirpur', 'Health', '2025-01-24', 'High', 'Webinar', '6-10', NULL, NULL, 0, '23:17:00', '23:17:00', 'Offline', NULL, NULL, NULL, 27, 0, ''),
('nebf', 'fbhe', 'Mohammadpur', 'Education', '2025-01-24', 'Low', 'Webinar', '0-5', NULL, NULL, 0, '23:23:00', '23:23:00', 'Online', NULL, NULL, NULL, 28, 0, ''),
('pitha uthshob', 'ashgdyqwt', 'DHANMONDI', 'Others', '2025-01-31', 'Medium', 'Workshop', '11-15', NULL, NULL, 0, '14:37:00', '15:37:00', 'Offline', NULL, NULL, NULL, 30, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `event_modes`
--

CREATE TABLE `event_modes` (
  `event_mode_id` int(11) NOT NULL,
  `event_mode_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `event_participant_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `status` enum('Interested','Going') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_participants`
--

INSERT INTO `event_participants` (`event_participant_id`, `event_id`, `participant_id`, `status`) VALUES
(2, 16, 5, 'Interested'),
(3, 17, 3, 'Going'),
(4, 16, 3, 'Going'),
(5, 15, 3, 'Going'),
(6, 16, 6, 'Going'),
(7, 17, 6, 'Going'),
(8, 15, 6, 'Going'),
(9, 17, 5, 'Going');

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `event_type_id` int(11) NOT NULL,
  `event_type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experts`
--

CREATE TABLE `experts` (
  `expert_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `contact_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `feedback_score` int(1) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `session_id`, `feedback_score`, `comments`, `created_at`) VALUES
(1, 1, 2, 'he was so caring\r\n', '2025-01-18 18:00:01'),
(2, 1, 3, 'improvised Social anxiety', '2025-01-19 05:43:02'),
(3, 2, 1, 'cool', '2025-01-23 08:01:44');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `follower_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `follower_user_id` int(11) DEFAULT NULL,
  `followed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`follower_id`, `user_id`, `follower_user_id`, `followed_at`) VALUES
(1, 1, 2, '2025-01-04 18:06:25'),
(2, 2, 1, '2025-01-04 18:06:25'),
(10, NULL, 3, '2025-01-06 20:34:14'),
(31, NULL, 3, '2025-01-08 01:51:38'),
(32, NULL, 3, '2025-01-08 01:51:41'),
(33, NULL, 3, '2025-01-08 01:51:42'),
(34, NULL, 3, '2025-01-08 01:51:42'),
(35, NULL, 3, '2025-01-08 01:51:43'),
(36, NULL, 3, '2025-01-08 01:51:44'),
(37, NULL, 3, '2025-01-08 01:51:44'),
(38, NULL, 3, '2025-01-08 01:51:45'),
(39, NULL, 3, '2025-01-08 01:51:45'),
(40, NULL, 3, '2025-01-08 01:51:45'),
(41, NULL, 3, '2025-01-08 01:51:45'),
(42, NULL, 3, '2025-01-08 01:51:46'),
(43, NULL, 3, '2025-01-08 01:51:47'),
(44, NULL, 3, '2025-01-08 01:51:48'),
(45, NULL, 3, '2025-01-08 01:53:36'),
(46, NULL, 3, '2025-01-08 01:53:37'),
(47, NULL, 3, '2025-01-08 01:53:57'),
(48, NULL, 3, '2025-01-08 01:54:00'),
(49, NULL, 3, '2025-01-08 01:54:01'),
(50, NULL, 3, '2025-01-08 01:56:12'),
(51, NULL, 3, '2025-01-08 01:56:13'),
(52, NULL, 3, '2025-01-08 01:56:14'),
(53, NULL, 3, '2025-01-08 01:56:14'),
(54, NULL, 3, '2025-01-08 01:56:14'),
(55, NULL, 3, '2025-01-08 01:56:15'),
(56, NULL, 3, '2025-01-08 01:56:15'),
(57, NULL, 3, '2025-01-08 01:56:15'),
(58, NULL, 3, '2025-01-08 01:56:15'),
(59, NULL, 3, '2025-01-08 01:56:15'),
(60, NULL, 3, '2025-01-08 01:56:16'),
(61, NULL, 3, '2025-01-08 01:56:16'),
(62, NULL, 3, '2025-01-08 01:56:17'),
(65, NULL, 3, '2025-01-08 13:07:26'),
(66, NULL, 3, '2025-01-08 13:07:30'),
(67, NULL, 3, '2025-01-08 13:07:31'),
(68, NULL, 3, '2025-01-08 13:07:31'),
(69, NULL, 3, '2025-01-08 13:07:31'),
(93, 1, 3, '2025-01-08 20:07:52'),
(94, NULL, 3, '2025-01-08 20:19:55'),
(96, NULL, 3, '2025-01-08 20:45:44'),
(97, NULL, 3, '2025-01-08 20:45:45'),
(98, NULL, 3, '2025-01-08 20:56:46'),
(99, NULL, 3, '2025-01-08 20:56:50'),
(100, NULL, 3, '2025-01-08 20:57:40'),
(101, NULL, 3, '2025-01-08 20:57:42'),
(102, NULL, 3, '2025-01-09 10:31:45'),
(103, NULL, 3, '2025-01-09 10:31:46'),
(104, NULL, 3, '2025-01-09 10:31:50'),
(105, NULL, 3, '2025-01-09 10:31:53'),
(106, NULL, 3, '2025-01-09 10:31:54'),
(107, NULL, 3, '2025-01-09 10:36:47'),
(108, NULL, 3, '2025-01-09 10:36:49'),
(109, NULL, 3, '2025-01-09 10:36:52'),
(110, NULL, 3, '2025-01-09 10:43:30'),
(111, NULL, 3, '2025-01-09 10:49:55'),
(112, NULL, 3, '2025-01-09 10:49:57'),
(113, NULL, 3, '2025-01-09 10:49:57'),
(114, NULL, 3, '2025-01-09 10:49:59'),
(136, 4, 3, '2025-01-23 05:35:51'),
(137, 3, 6, '2025-01-27 07:12:53'),
(138, 5, 6, '2025-01-27 07:12:55'),
(139, 6, 4, '2025-01-27 07:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `post_id`, `created_at`) VALUES
(1, 1, 1, '2025-01-04 18:06:25'),
(2, 2, 1, '2025-01-04 18:06:25'),
(72, 3, NULL, '2025-01-06 20:26:32'),
(73, 3, NULL, '2025-01-06 20:27:39'),
(74, 3, NULL, '2025-01-06 20:28:09'),
(75, 3, NULL, '2025-01-06 20:29:13'),
(76, 3, NULL, '2025-01-06 20:32:33'),
(77, 3, NULL, '2025-01-06 20:33:14'),
(78, 3, 3, '2025-01-06 21:08:02'),
(79, 3, NULL, '2025-01-07 14:52:18'),
(80, 3, NULL, '2025-01-07 14:52:38'),
(81, 3, NULL, '2025-01-07 14:55:06'),
(82, 3, NULL, '2025-01-07 14:55:18'),
(83, 3, NULL, '2025-01-07 14:57:32'),
(84, 3, NULL, '2025-01-07 14:57:35'),
(85, 3, NULL, '2025-01-07 16:38:02'),
(86, 3, NULL, '2025-01-07 23:07:47'),
(87, 3, NULL, '2025-01-07 23:07:47'),
(88, 3, NULL, '2025-01-07 23:08:08'),
(89, 3, NULL, '2025-01-07 23:15:45'),
(90, 3, NULL, '2025-01-07 23:16:00'),
(91, 3, NULL, '2025-01-07 23:16:33'),
(94, 4, 10, '2025-01-08 14:51:08'),
(103, 3, 17, '2025-01-09 12:07:55'),
(104, 3, 4, '2025-01-12 05:41:56'),
(105, 3, 1, '2025-01-12 05:42:01'),
(106, 5, 28, '2025-01-27 06:38:08'),
(107, 6, 29, '2025-01-27 07:12:57'),
(108, 6, 30, '2025-01-27 07:12:58'),
(109, 6, 28, '2025-01-27 07:13:17'),
(110, 4, 3, '2025-01-27 07:15:03'),
(111, 4, 30, '2025-01-27 07:15:08'),
(113, 4, 28, '2025-01-27 07:15:15'),
(114, 4, 32, '2025-01-27 07:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `motivationalquotes`
--

CREATE TABLE `motivationalquotes` (
  `id` int(11) NOT NULL,
  `motivationcontent` text DEFAULT NULL,
  `inspirationcontent` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `motivationalquotes`
--

INSERT INTO `motivationalquotes` (`id`, `motivationcontent`, `inspirationcontent`, `url`, `created_at`) VALUES
(1, 'Every step forward is an achievement worth celebrating.', 'Autism is not a limitation; it is a unique perspective.', 'https://www.autismspeaks.org/sites/default/files/2021-03/motivation1.jpg', '2025-01-09 13:22:32'),
(2, 'The smallest progress is still progress. Keep going!', 'Different, not less. Celebrate your uniqueness.', 'https://www.autismsociety.org/wp-content/uploads/2021/04/inspiration1.jpg', '2025-01-09 13:22:32'),
(3, 'You are stronger than you know and loved more than you can imagine.', 'Autism is a journey, not a race. Take it one step at a time.', 'https://www.autism.org.uk/images/steps-of-encouragement.jpg', '2025-01-09 13:22:32'),
(4, 'Patience and understanding are the keys to unlocking potential.', 'The world needs all kinds of minds to thrive.', 'https://www.autismspeaks.org/sites/default/files/2021-03/inclusive-minds.jpg', '2025-01-09 13:22:32'),
(5, 'Believe in your ability to make the world a better place.', 'Autism brings new colors to the world; let’s embrace them.', 'https://www.autismspeaks.org/sites/default/files/2021-03/embrace-difference.jpg', '2025-01-09 13:22:32'),
(6, 'You are not alone on this journey; there is a whole community behind you.', 'Let’s focus on strengths and talents rather than challenges.', 'https://www.autismsociety.org/wp-content/uploads/2021/04/community-support.jpg', '2025-01-09 13:22:32'),
(7, 'Kindness, patience, and love can make extraordinary changes.', 'Autism is about seeing the world through a different lens.', 'https://www.autism.org.uk/images/unique-lens.jpg', '2025-01-09 13:22:32'),
(8, 'Every challenge is an opportunity to learn and grow.', 'Together, we can create an inclusive and supportive world.', 'https://www.autismspeaks.org/sites/default/files/2021-03/inclusion-matters.jpg', '2025-01-09 13:22:32'),
(9, 'Your voice matters. Speak up, and the world will listen.', 'Inclusion starts with understanding and acceptance.', 'https://www.autism.org.uk/images/voice-matters.jpg', '2025-01-09 13:22:32'),
(10, 'Small steps lead to big milestones. Keep moving forward.', 'Autism is not a disorder; it’s a different way of thinking.', 'https://www.autismspeaks.org/sites/default/files/2021-03/think-differently.jpg', '2025-01-09 13:22:32'),
(11, 'Celebrate every success, no matter how small.', 'Everyone deserves a place to belong. Let’s make it happen.', 'https://www.autismsociety.org/wp-content/uploads/2021/04/belonging.jpg', '2025-01-09 13:22:32'),
(12, 'The journey may be challenging, but the rewards are beyond measure.', 'We are all different, but together we are stronger.', 'https://www.autismspeaks.org/sites/default/files/2021-03/stronger-together.jpg', '2025-01-09 13:22:32'),
(13, 'Every individual is unique and important in their own way.', 'Autism is the ability to see the world differently.', 'https://www.autismsociety.org/wp-content/uploads/2021/04/unique-ability.jpg', '2025-01-09 13:22:32'),
(14, 'You are capable, creative, and courageous. Believe in yourself.', 'Let’s celebrate diversity and embrace every perspective.', 'https://www.autismspeaks.org/sites/default/files/2021-03/celebrate-diversity.jpg', '2025-01-09 13:22:32'),
(15, 'Together, we can overcome any obstacle.', 'Autism is not a barrier; it is a different way to innovate.', 'https://www.autism.org.uk/images/innovation.jpg', '2025-01-09 13:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_types`
--

CREATE TABLE `notification_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `message_template` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `participant_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `privacy` enum('public','private','friends-only') NOT NULL DEFAULT 'public',
  `category` varchar(255) DEFAULT 'General',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `user_id`, `content`, `privacy`, `category`, `created_at`, `photo`) VALUES
(1, 1, 'First post about my experience', 'public', NULL, '2025-01-04 18:06:25', NULL),
(2, 2, 'Private thoughts about parenting', 'private', NULL, '2025-01-04 18:06:25', NULL),
(3, 3, 'this is a test post.by sr.', 'public', NULL, '2025-01-04 18:56:39', NULL),
(4, 3, 'A post can be private which only its user and the follower can see.\r\n', 'public', NULL, '2025-01-04 19:17:33', NULL),
(13, 4, 'a post is sharing from this profile\r\n', 'public', NULL, '2025-01-08 16:43:14', '2f6d0c1c4596404a4d97b1029b9e056d.jpg'),
(18, 3, '', '', NULL, '2025-01-08 17:57:29', NULL),
(19, 3, '', '', NULL, '2025-01-08 18:00:44', NULL),
(23, 3, 'can this post be deleted?', 'public', 'general', '2025-01-12 05:42:23', NULL),
(24, 3, 'a post is sharing from this profile', 'public', 'general', '2025-01-12 06:49:46', NULL),
(26, 3, 'Bipolar disorder testing', 'public', 'Bipolar Disorder', '2025-01-23 05:59:33', NULL),
(28, 5, 'autism child\r\n', 'public', 'general', '2025-01-27 06:38:03', 'OIP.jpg'),
(29, 5, 'Managing bipolar disorder is a journey with its ups and downs. How do you maintain balance during mood swings? Let\'s discuss coping strategies and daily routines.', 'public', 'Bipolar Disorder', '2025-01-27 07:06:12', NULL),
(30, 3, 'Anxiety can feel overwhelming, but small steps make a difference. What helps you or your child calm down during anxious moments?', 'public', 'anxiety', '2025-01-27 07:10:26', 'OIP (2).jpg'),
(31, 6, 'Sometimes, depression feels isolating, but reaching out makes a difference. What resources or practices do you find most helpful for support?', 'public', 'depression', '2025-01-27 07:12:46', '6-28-2018-depression-in-teens-with-autism.jpg'),
(32, 4, 'Schizophrenia requires a lot of understanding and care. What therapies or routines have made a positive impact in your life or your loved one’s?', 'public', 'Schizophrenia', '2025-01-27 07:17:00', 'schizophrenia-spectrum-and-types-5193053-FINAL-ff64839e31a64ca293f12f168d488302.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `priority`
--

CREATE TABLE `priority` (
  `priority_id` int(11) NOT NULL,
  `priority_level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `user_id`, `question_text`, `created_at`) VALUES
(1, 3, 'How long will my child need therapy if they get diagnosed with autism spectrum disorder? ', '2025-01-23 16:27:51'),
(2, 5, ' And what can I do at home starting today to help my child with their speech?', '2025-01-23 16:51:24'),
(5, 3, ' What is ABA Therapy? ', '2025-01-24 16:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `resource_type_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `user_id`, `title`, `description`, `resource_type_id`, `created_at`) VALUES
(2, 1, 'number learning', 'They can learn how to count', 2, '2025-01-19 01:18:21'),
(3, 1, 'Social Skills Modules', 'Social interaction can be particularly challenging for individuals with autism. These modules aim to teach crucial social skills such as turn-taking, interpreting facial expressions, understanding social cues, and maintaining conversations. They often use role-playing, video modeling, and social stories to illustrate concepts', 2, '2025-01-19 01:47:13'),
(13, 1, 'Life Skills Modules', 'These focus on developing practical skills necessary for daily living and independence. Topics may include personal hygiene, meal preparation, money management, and time management. These modules are crucial for promoting autonomy and quality of life', 1, '2025-01-19 05:07:33');

-- --------------------------------------------------------

--
-- Table structure for table `resource_details`
--

CREATE TABLE `resource_details` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource_details`
--

INSERT INTO `resource_details` (`id`, `resource_id`, `file_path`, `external_link`) VALUES
(1, 2, NULL, 'https://youtu.be/Ol_5o6_13ko?si=qRTSpDLj4I4VkPmr'),
(2, 3, NULL, 'https://youtu.be/DEqhWMugltk?si=ZlHEkv7b3Txmq9Tm'),
(12, 13, 'uploads/life skill module.txt', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resource_types`
--

CREATE TABLE `resource_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource_types`
--

INSERT INTO `resource_types` (`id`, `type_name`) VALUES
(1, 'document'),
(2, 'video');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

CREATE TABLE `search` (
  `search_id` int(11) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `resource_type` varchar(50) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `search`
--

INSERT INTO `search` (`search_id`, `keywords`, `category`, `resource_type`, `resource_id`, `created_at`) VALUES
(1, 'trying', 'all', NULL, NULL, '2025-01-06 19:54:29'),
(2, 'pink floyd', 'all', NULL, NULL, '2025-01-06 19:54:50'),
(3, 'pink floyd', 'all', NULL, NULL, '2025-01-06 19:54:53'),
(4, 'pink floyd', 'all', NULL, NULL, '2025-01-06 19:54:57'),
(5, 'pink floyd', 'all', NULL, NULL, '2025-01-06 19:55:03'),
(6, 'pink floyd', 'all', NULL, NULL, '2025-01-06 19:55:06'),
(7, 'trying', 'all', NULL, NULL, '2025-01-08 13:56:55'),
(8, 'trying', 'all', NULL, NULL, '2025-01-08 18:53:30'),
(9, 'trying', 'all', NULL, NULL, '2025-01-08 18:54:10'),
(10, 'trying', 'all', NULL, NULL, '2025-01-08 18:54:12'),
(11, 'here', 'all', NULL, NULL, '2025-01-08 18:54:26'),
(12, 'sayem', 'all', NULL, NULL, '2025-01-09 12:21:59'),
(13, 'sayem', 'all', NULL, NULL, '2025-01-09 12:22:17'),
(14, 'sayem', 'all', NULL, NULL, '2025-01-09 12:22:18'),
(15, 'sayem', 'all', NULL, NULL, '2025-01-09 12:22:19'),
(16, 'trying', 'all', NULL, NULL, '2025-01-09 12:22:39'),
(17, 'trying', 'all', NULL, NULL, '2025-01-09 12:22:54'),
(18, 'trying', 'all', NULL, NULL, '2025-01-09 12:22:54'),
(19, 'trying', 'all', NULL, NULL, '2025-01-09 12:22:55'),
(20, 'trying', 'all', NULL, NULL, '2025-01-09 12:27:16'),
(21, 'sayem', 'all', NULL, NULL, '2025-01-09 12:27:30'),
(22, 'trying', 'all', NULL, NULL, '2025-01-09 12:32:59'),
(23, 'trying', 'all', NULL, NULL, '2025-01-09 12:33:55'),
(24, 'trying', 'all', NULL, NULL, '2025-01-09 12:33:57'),
(25, 'trying', 'all', NULL, NULL, '2025-01-09 12:33:58'),
(26, 'here', 'all', NULL, NULL, '2025-01-09 12:34:11'),
(27, 'here', 'all', NULL, NULL, '2025-01-09 12:37:37'),
(28, 'here', 'all', NULL, NULL, '2025-01-09 12:38:40'),
(29, 'here', 'all', NULL, NULL, '2025-01-09 12:38:42'),
(30, 'here', 'all', NULL, NULL, '2025-01-09 12:38:43'),
(31, 'sayem', 'all', NULL, NULL, '2025-01-09 12:38:51'),
(32, 'sayem', 'all', NULL, NULL, '2025-01-09 12:40:51'),
(33, 'sayem', 'all', NULL, NULL, '2025-01-09 12:42:46'),
(34, 'sayem', 'all', NULL, NULL, '2025-01-09 12:42:48'),
(35, 'sayem', 'all', NULL, NULL, '2025-01-09 12:43:31'),
(36, 'sayem', 'all', NULL, NULL, '2025-01-09 12:43:35'),
(37, 'sayem', 'all', NULL, NULL, '2025-01-09 12:43:36'),
(38, 'sayem', 'all', NULL, NULL, '2025-01-09 12:43:38'),
(39, 'this', 'all', NULL, NULL, '2025-01-09 12:43:49'),
(40, 'this', 'all', NULL, NULL, '2025-01-09 12:46:37'),
(41, 'this', 'all', NULL, NULL, '2025-01-09 12:46:58'),
(42, 'this', 'all', NULL, NULL, '2025-01-09 12:47:00'),
(43, 'this', 'all', NULL, NULL, '2025-01-09 12:47:01'),
(44, 'sayem', 'all', NULL, NULL, '2025-01-09 12:47:11'),
(45, 'sayem', 'all', NULL, NULL, '2025-01-09 12:47:20'),
(46, 'sayem', 'all', NULL, NULL, '2025-01-09 12:47:35'),
(47, 'sayem', 'all', NULL, NULL, '2025-01-09 12:47:45'),
(48, 'sayem', 'specific', NULL, NULL, '2025-01-09 12:47:50'),
(49, 'sayem', 'all', NULL, NULL, '2025-01-09 12:51:13'),
(50, 'sayem', 'all', NULL, NULL, '2025-01-09 12:51:20'),
(51, 'sayem', 'all', NULL, NULL, '2025-01-09 12:51:27'),
(52, 'sayem', 'general', NULL, NULL, '2025-01-09 12:51:32'),
(53, 'sayem', 'general', NULL, NULL, '2025-01-09 12:52:39'),
(54, 'sayem', 'general', NULL, NULL, '2025-01-09 12:52:40'),
(55, 'sayem', 'general', NULL, NULL, '2025-01-09 12:52:50'),
(56, 'sayem', 'general', NULL, NULL, '2025-01-09 12:52:54'),
(57, 'sayem', 'general', NULL, NULL, '2025-01-09 13:02:12'),
(58, 'sayem', 'general', NULL, NULL, '2025-01-09 13:02:13'),
(59, 'sayem', 'general', NULL, NULL, '2025-01-09 13:02:16'),
(60, 'sayem', 'general', NULL, NULL, '2025-01-09 13:02:18'),
(61, 'sayem', 'all', NULL, NULL, '2025-01-09 13:02:26'),
(62, 'sayem', 'all', NULL, NULL, '2025-01-09 13:02:28'),
(63, 'sayem', 'all', NULL, NULL, '2025-01-09 13:02:29'),
(64, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:19'),
(65, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:22'),
(66, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:25'),
(67, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:26'),
(68, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:28'),
(69, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:34'),
(70, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:37'),
(71, 'sayem', 'all', NULL, NULL, '2025-01-09 13:05:39'),
(72, 'sayem', 'general', NULL, NULL, '2025-01-09 13:05:46'),
(73, 'sayem', 'general', NULL, NULL, '2025-01-09 13:05:50'),
(74, 'sayem', 'general', NULL, NULL, '2025-01-09 13:05:55'),
(75, 'this', 'all', NULL, NULL, '2025-01-09 13:06:19'),
(76, 'this', 'all', NULL, NULL, '2025-01-09 13:06:24'),
(77, 'this', 'all', NULL, NULL, '2025-01-09 13:06:39'),
(78, 'this', 'all', NULL, NULL, '2025-01-09 13:06:44'),
(79, 'this', 'all', NULL, NULL, '2025-01-09 13:06:46'),
(80, 'this', 'all', NULL, NULL, '2025-01-09 13:06:47'),
(81, 'this', 'all', NULL, NULL, '2025-01-09 13:06:49'),
(82, 'pink floyd', 'all', NULL, NULL, '2025-01-09 13:07:28'),
(83, 'this', 'all', NULL, NULL, '2025-01-09 13:08:01'),
(84, 'this', 'all', NULL, NULL, '2025-01-09 13:08:04'),
(85, 'this', 'all', NULL, NULL, '2025-01-09 13:08:07'),
(86, 'this', 'all', NULL, NULL, '2025-01-09 13:08:08'),
(87, 'this', 'all', NULL, NULL, '2025-01-09 13:08:13'),
(88, 'this', 'all', NULL, NULL, '2025-01-09 13:08:17'),
(89, 'this', 'all', NULL, NULL, '2025-01-09 13:08:21'),
(90, 'this', 'all', NULL, NULL, '2025-01-09 13:08:26'),
(91, 'this', 'specific', NULL, NULL, '2025-01-09 13:08:48'),
(92, 'trying', 'all', NULL, NULL, '2025-01-09 13:26:26'),
(93, 'trying', 'all', NULL, NULL, '2025-01-09 13:26:41'),
(94, 'trying', 'all', NULL, NULL, '2025-01-09 13:26:42'),
(95, 'general', 'all', NULL, NULL, '2025-01-09 13:26:52'),
(96, 'can this', 'all', NULL, NULL, '2025-01-12 05:42:28'),
(97, 'can this', 'all', NULL, NULL, '2025-01-12 05:42:31'),
(98, 'can this', 'all', NULL, NULL, '2025-01-12 05:42:35'),
(99, 'can this', 'all', NULL, NULL, '2025-01-12 05:42:36'),
(100, 'can this', 'all', NULL, NULL, '2025-01-12 05:42:42'),
(101, 'can this', 'all', NULL, NULL, '2025-01-12 05:42:48'),
(102, 'trying', 'all', NULL, NULL, '2025-01-12 06:22:57'),
(103, 'trying', 'all', NULL, NULL, '2025-01-12 06:23:01'),
(104, 'trying', 'all', NULL, NULL, '2025-01-12 06:23:03'),
(105, 'trying', 'all', NULL, NULL, '2025-01-12 06:46:04'),
(106, 'can', 'all', NULL, NULL, '2025-01-12 06:46:34'),
(107, 'can', 'all', NULL, NULL, '2025-01-12 06:48:30'),
(108, 'can', 'all', NULL, NULL, '2025-01-12 06:48:39'),
(109, 'can', 'all', NULL, NULL, '2025-01-12 06:48:41'),
(110, 'can', 'all', NULL, NULL, '2025-01-12 06:48:47'),
(111, 'can', 'all', NULL, NULL, '2025-01-12 06:48:52'),
(112, 'can', 'all', NULL, NULL, '2025-01-12 06:48:58'),
(113, 'can', 'all', NULL, NULL, '2025-01-12 06:49:03'),
(114, 'can', 'all', NULL, NULL, '2025-01-12 06:49:09'),
(115, 'a post is', 'all', NULL, NULL, '2025-01-12 06:49:51'),
(116, 'a post is', 'all', NULL, NULL, '2025-01-12 06:49:56'),
(117, 'a post is', 'general', NULL, NULL, '2025-01-12 06:50:01'),
(118, 'a post is', 'general', NULL, NULL, '2025-01-12 06:50:11'),
(119, 'a post is', 'all', NULL, NULL, '2025-01-12 06:53:38'),
(120, 'a post is', 'all', NULL, NULL, '2025-01-12 06:54:21'),
(121, 'a post is', 'all', NULL, NULL, '2025-01-12 06:54:41'),
(122, 'a post is', 'all', NULL, NULL, '2025-01-12 06:54:52'),
(123, 'a post is', 'all', NULL, NULL, '2025-01-12 06:55:01'),
(124, 'a post is', 'all', NULL, NULL, '2025-01-12 06:55:30'),
(125, 'a post is', 'all', NULL, NULL, '2025-01-12 06:55:54'),
(126, 'a post is', 'all', NULL, NULL, '2025-01-12 06:58:36'),
(127, 'a post is', 'all', NULL, NULL, '2025-01-12 06:58:42'),
(128, 'a post is', 'all', NULL, NULL, '2025-01-12 06:58:50'),
(129, 'a post is', 'all', NULL, NULL, '2025-01-12 06:59:01'),
(130, 'a post is', 'all', NULL, NULL, '2025-01-12 06:59:02'),
(131, 'a post is', 'all', NULL, NULL, '2025-01-12 06:59:10'),
(132, 'a post is', 'all', NULL, NULL, '2025-01-12 06:59:23'),
(133, 'a post is', 'general', NULL, NULL, '2025-01-12 06:59:34'),
(134, 'a post is', 'all', NULL, NULL, '2025-01-12 07:02:10'),
(135, 'a post is', 'all', NULL, NULL, '2025-01-12 07:02:15'),
(136, 'a post is', 'all', NULL, NULL, '2025-01-12 07:02:18'),
(137, 'a post', 'all', NULL, NULL, '2025-01-12 07:02:39'),
(138, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:12'),
(139, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:20'),
(140, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:21'),
(141, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:25'),
(142, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:29'),
(143, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:36'),
(144, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:41'),
(145, 'a post', 'all', NULL, NULL, '2025-01-12 07:10:55'),
(146, 'a post', 'all', NULL, NULL, '2025-01-12 07:11:02'),
(147, 'a post', 'all', NULL, NULL, '2025-01-12 07:11:18'),
(148, 'a post', 'all', NULL, NULL, '2025-01-12 07:15:49'),
(149, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:07'),
(150, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:10'),
(151, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:14'),
(152, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:17'),
(153, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:21'),
(154, 'a post', 'general', NULL, NULL, '2025-01-12 07:16:26'),
(155, 'a post', 'general', NULL, NULL, '2025-01-12 07:16:34'),
(156, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:39'),
(157, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:43'),
(158, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:48'),
(159, 'a post', 'all', NULL, NULL, '2025-01-12 07:16:59'),
(160, 'a post', 'all', NULL, NULL, '2025-01-12 07:36:22'),
(161, 'trying', 'all', NULL, NULL, '2025-01-18 15:51:59'),
(162, 'trying', 'all', NULL, NULL, '2025-01-18 16:14:50'),
(163, 'trying', 'all', NULL, NULL, '2025-01-18 16:42:21'),
(164, 'trying', 'all', NULL, NULL, '2025-01-18 16:46:51'),
(165, 'a post', 'all', NULL, NULL, '2025-01-19 05:29:09'),
(166, 'a post', 'all', NULL, NULL, '2025-01-19 05:29:47'),
(167, 'a post', 'all', NULL, NULL, '2025-01-19 05:29:49'),
(168, 'a post', 'all', NULL, NULL, '2025-01-19 05:29:51'),
(169, 'trying', 'all', NULL, NULL, '2025-01-23 05:43:44'),
(170, 'this', 'all', NULL, NULL, '2025-01-23 05:49:56'),
(171, 'this', 'all', NULL, NULL, '2025-01-23 05:56:42'),
(172, 'this', 'all', NULL, NULL, '2025-01-23 05:56:44'),
(173, 'this', 'all', NULL, NULL, '2025-01-23 05:56:45'),
(174, 'this', 'all', NULL, NULL, '2025-01-23 05:57:50'),
(175, 'this', 'all', NULL, NULL, '2025-01-23 05:57:53'),
(176, 'this', 'all', NULL, NULL, '2025-01-23 05:57:55'),
(177, 'this', 'all', NULL, NULL, '2025-01-23 05:57:57'),
(178, 'this', 'all', NULL, NULL, '2025-01-23 05:57:58'),
(179, 'this', 'all', NULL, NULL, '2025-01-23 05:58:04'),
(180, 'this', 'all', NULL, NULL, '2025-01-23 05:58:10'),
(181, 'this', 'all', NULL, NULL, '2025-01-23 05:58:18'),
(182, 'this', 'all', NULL, NULL, '2025-01-23 05:58:23'),
(183, 'this', 'all', NULL, NULL, '2025-01-23 05:58:27'),
(184, 'this', 'all', NULL, NULL, '2025-01-23 05:58:30'),
(185, 'this', 'all', NULL, NULL, '2025-01-23 05:58:40'),
(186, 'bipolar', 'all', NULL, NULL, '2025-01-23 05:59:41'),
(187, 'bipolar', 'all', NULL, NULL, '2025-01-23 05:59:48'),
(188, 's', 'all', NULL, NULL, '2025-01-23 06:00:17'),
(189, 'this', 'all', NULL, NULL, '2025-01-23 06:33:48'),
(190, 'this', 'all', NULL, NULL, '2025-01-23 06:34:08'),
(191, 'this', 'all', NULL, NULL, '2025-01-23 06:34:14'),
(192, 'this', 'all', NULL, NULL, '2025-01-23 06:34:45'),
(193, 'this', 'general', NULL, NULL, '2025-01-23 06:34:48'),
(194, 'this', 'ADHD', NULL, NULL, '2025-01-23 06:35:13'),
(195, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 06:35:18'),
(196, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 06:38:37'),
(197, 'this', 'general', NULL, NULL, '2025-01-23 06:38:45'),
(198, 'this', 'general', NULL, NULL, '2025-01-23 06:40:00'),
(199, 'this', 'all', NULL, NULL, '2025-01-23 06:40:03'),
(200, 'this', 'all', NULL, NULL, '2025-01-23 06:40:05'),
(201, 'this', 'all', NULL, NULL, '2025-01-23 06:40:09'),
(202, 'this', 'all', NULL, NULL, '2025-01-23 06:40:13'),
(203, 'this', 'all', NULL, NULL, '2025-01-23 06:42:08'),
(204, 'this', 'all', NULL, NULL, '2025-01-23 06:43:13'),
(205, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 06:43:16'),
(206, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 06:47:10'),
(207, 'this', 'all', NULL, NULL, '2025-01-23 06:47:15'),
(208, 'this', 'all', NULL, NULL, '2025-01-23 06:47:19'),
(209, 'this', 'all', NULL, NULL, '2025-01-23 06:49:02'),
(210, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:49:10'),
(211, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:50:38'),
(212, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:50:38'),
(213, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:50:43'),
(214, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:50:45'),
(215, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:51:03'),
(216, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:51:09'),
(217, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:51:10'),
(218, 'bipolar', 'all', NULL, NULL, '2025-01-23 06:51:12'),
(219, 'bipolar', 'epilepsy', NULL, NULL, '2025-01-23 06:51:14'),
(220, 'bipolar', 'general', NULL, NULL, '2025-01-23 06:51:22'),
(221, 'bipolar', 'general', NULL, NULL, '2025-01-23 06:51:25'),
(222, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:06:14'),
(223, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:06:16'),
(224, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:07:30'),
(225, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:07:31'),
(226, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:07:38'),
(227, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:08:30'),
(228, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:08:31'),
(229, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:08:35'),
(230, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:08:59'),
(231, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:10:36'),
(232, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:10:40'),
(233, 'bipolar', 'sleep', NULL, NULL, '2025-01-23 07:10:42'),
(234, 'bipolar', 'sleep', NULL, NULL, '2025-01-23 07:10:44'),
(235, 'bipolar', 'sleep', NULL, NULL, '2025-01-23 07:10:46'),
(236, 'bipolar', 'general', NULL, NULL, '2025-01-23 07:10:48'),
(237, 'bipolar', 'general', NULL, NULL, '2025-01-23 07:10:49'),
(238, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:10:53'),
(239, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:10:55'),
(240, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:10:56'),
(241, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:10:58'),
(242, 'bipolar', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:10:59'),
(243, 'this', 'all', NULL, NULL, '2025-01-23 07:11:17'),
(244, 'this', 'all', NULL, NULL, '2025-01-23 07:11:50'),
(245, 'this', 'all', NULL, NULL, '2025-01-23 07:11:51'),
(246, 'this', 'all', NULL, NULL, '2025-01-23 07:11:55'),
(247, 'this', 'all', NULL, NULL, '2025-01-23 07:12:11'),
(248, 'this', 'all', NULL, NULL, '2025-01-23 07:12:54'),
(249, 'this', 'all', NULL, NULL, '2025-01-23 07:13:00'),
(250, 'this', 'all', NULL, NULL, '2025-01-23 07:13:02'),
(251, 'this', 'all', NULL, NULL, '2025-01-23 07:13:49'),
(252, 'this', 'all', NULL, NULL, '2025-01-23 07:14:04'),
(253, 'this', 'all', NULL, NULL, '2025-01-23 07:14:24'),
(254, 'this', 'all', NULL, NULL, '2025-01-23 07:14:39'),
(255, 'this', 'all', NULL, NULL, '2025-01-23 07:14:58'),
(256, 'this', 'all', NULL, NULL, '2025-01-23 07:15:12'),
(257, 'this', 'all', NULL, NULL, '2025-01-23 07:15:24'),
(258, 'this', 'all', NULL, NULL, '2025-01-23 07:16:59'),
(259, 'this', 'all', NULL, NULL, '2025-01-23 07:20:01'),
(260, 'this', 'all', NULL, NULL, '2025-01-23 07:21:40'),
(261, 'this', 'all', NULL, NULL, '2025-01-23 07:23:41'),
(262, 'this', 'all', NULL, NULL, '2025-01-23 07:26:19'),
(263, 'this', 'all', NULL, NULL, '2025-01-23 07:26:22'),
(264, 'this', 'all', NULL, NULL, '2025-01-23 07:26:28'),
(265, 'this', 'all', NULL, NULL, '2025-01-23 07:26:30'),
(266, 'this', 'all', NULL, NULL, '2025-01-23 07:28:30'),
(267, 'this', 'all', NULL, NULL, '2025-01-23 07:28:38'),
(268, 'this', 'all', NULL, NULL, '2025-01-23 07:28:42'),
(269, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:28:45'),
(270, 'this', 'general', NULL, NULL, '2025-01-23 07:28:53'),
(271, 'this', 'general', NULL, NULL, '2025-01-23 07:28:55'),
(272, 'this', 'general', NULL, NULL, '2025-01-23 07:28:58'),
(273, 'this', 'general', NULL, NULL, '2025-01-23 07:29:02'),
(274, 'this', 'general', NULL, NULL, '2025-01-23 07:29:05'),
(275, 'this', 'general', NULL, NULL, '2025-01-23 07:30:53'),
(276, 'this', 'general', NULL, NULL, '2025-01-23 07:30:54'),
(277, 'this', 'general', NULL, NULL, '2025-01-23 07:30:58'),
(278, 'this', 'general', NULL, NULL, '2025-01-23 07:30:59'),
(279, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:31:02'),
(280, 'this', 'epilepsy', NULL, NULL, '2025-01-23 07:31:10'),
(281, 'this', 'general', NULL, NULL, '2025-01-23 07:31:11'),
(282, 'this', 'general', NULL, NULL, '2025-01-23 07:31:38'),
(283, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:31:42'),
(284, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:31:44'),
(285, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:31:52'),
(286, 'this', 'general', NULL, NULL, '2025-01-23 07:31:55'),
(287, 'this', 'general', NULL, NULL, '2025-01-23 07:34:28'),
(288, 'this', 'general', NULL, NULL, '2025-01-23 07:35:13'),
(289, 'this', 'general', NULL, NULL, '2025-01-23 07:35:14'),
(290, 'this', 'general', NULL, NULL, '2025-01-23 07:35:24'),
(291, 'this', 'general', NULL, NULL, '2025-01-23 07:36:08'),
(292, 'this', 'general', NULL, NULL, '2025-01-23 07:38:47'),
(293, 'this', 'general', NULL, NULL, '2025-01-23 07:38:48'),
(294, 'this', 'general', NULL, NULL, '2025-01-23 07:38:49'),
(295, 'this', 'general', NULL, NULL, '2025-01-23 07:38:50'),
(296, 'bipolar', 'all', NULL, NULL, '2025-01-23 07:39:43'),
(297, 'search', 'all', NULL, NULL, '2025-01-23 07:39:51'),
(298, 'search', 'all', NULL, NULL, '2025-01-23 07:42:23'),
(299, 'this', 'all', NULL, NULL, '2025-01-23 07:42:28'),
(300, 'this', 'all', NULL, NULL, '2025-01-23 07:49:45'),
(301, 'this', 'all', NULL, NULL, '2025-01-23 07:49:51'),
(302, 'this', 'all', NULL, NULL, '2025-01-23 07:52:02'),
(303, 'this', 'all', NULL, NULL, '2025-01-23 07:52:18'),
(304, 'this', 'all', NULL, NULL, '2025-01-23 07:53:02'),
(305, 'this', 'all', NULL, NULL, '2025-01-23 07:53:19'),
(306, 'this', 'all', NULL, NULL, '2025-01-23 07:53:20'),
(307, 'this', 'all', NULL, NULL, '2025-01-23 07:55:13'),
(308, 'this', 'all', NULL, NULL, '2025-01-23 07:55:37'),
(309, 'this', 'all', NULL, NULL, '2025-01-23 07:55:39'),
(310, 'this', 'all', NULL, NULL, '2025-01-23 07:55:40'),
(311, 'this', 'all', NULL, NULL, '2025-01-23 07:55:41'),
(312, 'this', 'all', NULL, NULL, '2025-01-23 07:55:43'),
(313, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:55:44'),
(314, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:55:47'),
(315, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:55:48'),
(316, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:55:49'),
(317, 'this', 'general', NULL, NULL, '2025-01-23 07:55:51'),
(318, 'this', 'general', NULL, NULL, '2025-01-23 07:56:34'),
(319, 'this', 'general', NULL, NULL, '2025-01-23 07:56:43'),
(320, 'this', 'general', NULL, NULL, '2025-01-23 07:56:45'),
(321, 'this', 'general', NULL, NULL, '2025-01-23 07:56:46'),
(322, 'this', 'general', NULL, NULL, '2025-01-23 07:56:54'),
(323, 'this', 'general', NULL, NULL, '2025-01-23 07:56:55'),
(324, 'this', 'all', NULL, NULL, '2025-01-23 07:57:04'),
(325, 'this', 'all', NULL, NULL, '2025-01-23 07:57:24'),
(326, 'this', 'all', NULL, NULL, '2025-01-23 07:57:38'),
(327, 'this', 'all', NULL, NULL, '2025-01-23 07:57:39'),
(328, 'this', 'all', NULL, NULL, '2025-01-23 07:58:02'),
(329, 'this', 'all', NULL, NULL, '2025-01-23 07:58:22'),
(330, 'this', 'all', NULL, NULL, '2025-01-23 07:58:39'),
(331, 'this', 'all', NULL, NULL, '2025-01-23 07:58:54'),
(332, 'this', 'all', NULL, NULL, '2025-01-23 07:59:09'),
(333, 'this', 'all', NULL, NULL, '2025-01-23 07:59:10'),
(334, 'this', 'all', NULL, NULL, '2025-01-23 07:59:28'),
(335, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:59:34'),
(336, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:59:36'),
(337, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:59:37'),
(338, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:59:38'),
(339, 'this', 'Bipolar Disorder', NULL, NULL, '2025-01-23 07:59:38'),
(340, 'this', 'general', NULL, NULL, '2025-01-23 07:59:40'),
(341, 'this', 'all', NULL, NULL, '2025-01-23 08:33:23'),
(342, 'sayem', 'all', NULL, NULL, '2025-01-27 06:18:26'),
(343, 'humayun', 'all', NULL, NULL, '2025-01-27 06:35:44'),
(344, 'humayun', 'all', NULL, NULL, '2025-01-27 06:35:47'),
(345, 'humayun', 'all', NULL, NULL, '2025-01-27 06:35:48'),
(346, 'schizophrenia', 'all', NULL, NULL, '2025-01-27 07:17:11'),
(347, 'schizophrenia', 'depression', NULL, NULL, '2025-01-27 07:17:16'),
(348, 'schizophrenia', 'Schizophrenia', NULL, NULL, '2025-01-27 07:17:20'),
(349, 'schizophrenia', 'Schizophrenia', NULL, NULL, '2025-01-27 07:17:23'),
(350, 'schizophrenia', 'Schizophrenia', NULL, NULL, '2025-01-27 07:17:25'),
(351, 'schizophrenia', 'Schizophrenia', NULL, NULL, '2025-01-27 07:17:26'),
(352, 'schizophrenia', 'Schizophrenia', NULL, NULL, '2025-01-27 07:17:27'),
(353, 'schizophrenia', 'general', NULL, NULL, '2025-01-27 07:17:36'),
(354, 'schizophrenia', 'Schizophrenia', NULL, NULL, '2025-01-27 07:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `therapists`
--

CREATE TABLE `therapists` (
  `therapist_id` int(11) NOT NULL,
  `therapist_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapists`
--

INSERT INTO `therapists` (`therapist_id`, `therapist_name`) VALUES
(2, 'Tahmina'),
(3, 'sanjida');

-- --------------------------------------------------------

--
-- Table structure for table `therapy_sessions`
--

CREATE TABLE `therapy_sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `session_date` datetime NOT NULL,
  `session_type` varchar(255) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapy_sessions`
--

INSERT INTO `therapy_sessions` (`session_id`, `user_id`, `therapist_id`, `session_date`, `session_type`, `duration_minutes`, `notes`) VALUES
(2, 6, 2, '2025-01-30 12:46:00', 'Speech and Language Therapy', 30, ''),
(3, 6, 3, '2025-01-28 12:48:00', 'ABA therapy', 20, '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT 'default-profile.png',
  `cover_photo` varchar(255) DEFAULT 'default-cover.png',
  `location` varchar(100) DEFAULT NULL,
  `children_details` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `email`, `first_name`, `last_name`, `bio`, `profile_photo`, `cover_photo`, `location`, `children_details`, `phone`, `date_of_birth`, `gender`, `created_at`, `updated_at`) VALUES
(3, 'Sayem', '$2y$10$B58/WM/l3lQBoF88A2DQF..AwaIO8Z.Pf8gAMTU3BnorA0rZVM8mW', 'sayemreza64656@gmail.com', 'Sayem1', 'Reza', 'A Father, Creator of this site.', 'profile_3_1736290445_366281411_182972191458640_3184501897832931941_n.jpg', NULL, 'dhaka', 'Two years old, surviving with ADHD', '', '2024-07-09', 'male', '2025-01-07 16:55:10', '2025-01-18 16:17:18'),
(4, 'sayemreza', '$2y$10$VGe2rc2lUFcV19aTRll6nO5ongMFxfK75.LqWU1gPe4y80amw9rBa', 'sayemreza646@gmail.com', NULL, NULL, NULL, 'default-profile.png', 'default-cover.png', NULL, NULL, NULL, NULL, NULL, '2025-01-08 14:49:14', '2025-01-23 05:47:12'),
(5, 'sayma', '$2y$10$5nycevJhiVIdoq3xGtPwF.qZs3g/bsToL85LNxv4oIaBZaBCqowJG', 'saymasultanaisra@gmail.com', '', '', '', NULL, 'default-cover.png', '', '', '', '0000-00-00', '', '2025-01-23 16:31:32', '2025-01-27 07:08:08'),
(6, 'humayun', '$2y$10$oon45cW7fWcT6QsipTfIA.fzn4.acyct5dkxgUQGqoTb93.5VSAH.', 'humayun@gmail.com', 'Humayun', '', '', 'profile_6_1737960060_OIP (1).jpg', 'default-cover.png', 'Dhaka', 'Have 3 children .THe younger one coping with ADHD.', '', '0000-00-00', 'male', '2025-01-27 06:39:29', '2025-01-27 06:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_actions`
--

CREATE TABLE `user_actions` (
  `action_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` enum('like','comment','share') NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `action_description` text DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_interest`
--

CREATE TABLE `user_interest` (
  `user_interest_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessibility_levels`
--
ALTER TABLE `accessibility_levels`
  ADD PRIMARY KEY (`accessibility_id`);

--
-- Indexes for table `age_groups`
--
ALTER TABLE `age_groups`
  ADD PRIMARY KEY (`age_group_id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `behavioral_logs`
--
ALTER TABLE `behavioral_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `behavior_type_id` (`behavior_type_id`);

--
-- Indexes for table `behavior_types`
--
ALTER TABLE `behavior_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_comments_post` (`post_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`event_participant_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `participant_id` (`participant_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`follower_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`follower_user_id`),
  ADD KEY `follower_user_id` (`follower_user_id`),
  ADD KEY `idx_followers_user` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`post_id`),
  ADD KEY `idx_likes_post` (`post_id`);

--
-- Indexes for table `motivationalquotes`
--
ALTER TABLE `motivationalquotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_types`
--
ALTER TABLE `notification_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`participant_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `idx_post_user` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `resource_type_id` (`resource_type_id`);

--
-- Indexes for table `resource_details`
--
ALTER TABLE `resource_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `resource_types`
--
ALTER TABLE `resource_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `search`
--
ALTER TABLE `search`
  ADD PRIMARY KEY (`search_id`),
  ADD KEY `idx_search_keywords` (`keywords`(191));

--
-- Indexes for table `therapists`
--
ALTER TABLE `therapists`
  ADD PRIMARY KEY (`therapist_id`);

--
-- Indexes for table `therapy_sessions`
--
ALTER TABLE `therapy_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`);

--
-- Indexes for table `user_actions`
--
ALTER TABLE `user_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `user_interest`
--
ALTER TABLE `user_interest`
  ADD PRIMARY KEY (`user_interest_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `behavioral_logs`
--
ALTER TABLE `behavioral_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `behavior_types`
--
ALTER TABLE `behavior_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `event_participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `follower_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `motivationalquotes`
--
ALTER TABLE `motivationalquotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `resource_details`
--
ALTER TABLE `resource_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `resource_types`
--
ALTER TABLE `resource_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `search`
--
ALTER TABLE `search`
  MODIFY `search_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT for table `therapists`
--
ALTER TABLE `therapists`
  MODIFY `therapist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `therapy_sessions`
--
ALTER TABLE `therapy_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_actions`
--
ALTER TABLE `user_actions`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_interest`
--
ALTER TABLE `user_interest`
  MODIFY `user_interest_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `therapy_sessions`
--
ALTER TABLE `therapy_sessions`
  ADD CONSTRAINT `therapy_sessions_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`therapist_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_actions`
--
ALTER TABLE `user_actions`
  ADD CONSTRAINT `user_actions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `user_actions_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_actions_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_interest`
--
ALTER TABLE `user_interest`
  ADD CONSTRAINT `user_interest_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_interest_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
