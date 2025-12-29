-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 11:38 AM
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
-- Database: `happ`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `answer_text` text NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `next_question_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `body_sections`
--

CREATE TABLE `body_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `tag` varchar(255) DEFAULT NULL,
  `iscritical` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `body_sections`
--

INSERT INTO `body_sections` (`id`, `name`, `is_active`, `tag`, `iscritical`, `created_at`, `updated_at`) VALUES
(1, 'Head and Neck', 1, 'Upper Body', 1, '2025-12-23 01:10:48', '2025-12-23 01:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `stateid` bigint(20) UNSIGNED NOT NULL,
  `countryid` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `stateid`, `countryid`, `created_at`, `updated_at`) VALUES
(1, 'Thiruvananthapuram', 12, 74, NULL, NULL),
(2, 'Kochi', 12, 74, NULL, NULL),
(3, 'Kozhikode', 12, 74, NULL, NULL),
(4, 'Kollam', 12, 74, NULL, NULL),
(5, 'Thrissur', 12, 74, NULL, NULL),
(6, 'Alappuzha', 12, 74, NULL, NULL),
(7, 'Palakkad', 12, 74, NULL, NULL),
(8, 'Kannur', 12, 74, NULL, NULL),
(9, 'Malappuram', 12, 74, NULL, NULL),
(10, 'Kottayam', 12, 74, NULL, NULL),
(11, 'Pathanamthitta', 12, 74, NULL, NULL),
(12, 'Idukki', 12, 74, NULL, NULL),
(13, 'Ernakulam', 12, 74, NULL, NULL),
(14, 'Wayanad', 12, 74, NULL, NULL),
(15, 'Kasaragod', 12, 74, NULL, NULL),
(16, 'Thalassery', 12, 74, NULL, NULL),
(17, 'Manjeri', 12, 74, NULL, NULL),
(18, 'Tirur', 12, 74, NULL, NULL),
(19, 'Kanhangad', 12, 74, NULL, NULL),
(20, 'Payyanur', 12, 74, NULL, NULL),
(21, 'Koyilandy', 12, 74, NULL, NULL),
(22, 'Parappanangadi', 12, 74, NULL, NULL),
(23, 'Kalamassery', 12, 74, NULL, NULL),
(24, 'Neyyattinkara', 12, 74, NULL, NULL),
(25, 'Tanur', 12, 74, NULL, NULL),
(26, 'Kayamkulam', 12, 74, NULL, NULL),
(27, 'Thrippunithura', 12, 74, NULL, NULL),
(28, 'Aluva', 12, 74, NULL, NULL),
(29, 'Attingal', 12, 74, NULL, NULL),
(30, 'Adoor', 12, 74, NULL, NULL),
(31, 'Ponnani', 12, 74, NULL, NULL),
(32, 'Vatakara', 12, 74, NULL, NULL),
(33, 'Cherthala', 12, 74, NULL, NULL),
(34, 'Paravur', 12, 74, NULL, NULL),
(35, 'Pathanapuram', 12, 74, NULL, NULL),
(36, 'Changanassery', 12, 74, NULL, NULL),
(37, 'Perinthalmanna', 12, 74, NULL, NULL),
(38, 'Mattanur', 12, 74, NULL, NULL),
(39, 'Punalur', 12, 74, NULL, NULL),
(40, 'Nilambur', 12, 74, NULL, NULL),
(41, 'Cherpulassery', 12, 74, NULL, NULL),
(42, 'Pandalam', 12, 74, NULL, NULL),
(43, 'Manjeshwar', 12, 74, NULL, NULL),
(44, 'Ottappalam', 12, 74, NULL, NULL),
(45, 'Thodupuzha', 12, 74, NULL, NULL),
(46, 'Perumbavoor', 12, 74, NULL, NULL),
(47, 'Chalakudy', 12, 74, NULL, NULL),
(48, 'Payyoli', 12, 74, NULL, NULL),
(49, 'Kodungallur', 12, 74, NULL, NULL),
(50, 'Chittur-Thathamangalam', 12, 74, NULL, NULL),
(51, 'Muvattupuzha', 12, 74, NULL, NULL),
(52, 'Adimali', 12, 74, NULL, NULL),
(53, 'Ramanattukara', 12, 74, NULL, NULL),
(54, 'Koothattukulam', 12, 74, NULL, NULL),
(55, 'Pandikkad', 12, 74, NULL, NULL),
(56, 'Wadakkancherry', 12, 74, NULL, NULL),
(57, 'Mumbai', 14, 74, NULL, NULL),
(58, 'Pune', 14, 74, NULL, NULL),
(59, 'Nagpur', 14, 74, NULL, NULL),
(60, 'Thane', 14, 74, NULL, NULL),
(61, 'Nashik', 14, 74, NULL, NULL),
(62, 'Aurangabad', 14, 74, NULL, NULL),
(63, 'Solapur', 14, 74, NULL, NULL),
(64, 'Navi Mumbai', 14, 74, NULL, NULL),
(65, 'Kolhapur', 14, 74, NULL, NULL),
(66, 'Bangalore', 11, 74, NULL, NULL),
(67, 'Mysore', 11, 74, NULL, NULL),
(68, 'Hubli', 11, 74, NULL, NULL),
(69, 'Mangalore', 11, 74, NULL, NULL),
(70, 'Belgaum', 11, 74, NULL, NULL),
(71, 'Gulbarga', 11, 74, NULL, NULL),
(72, 'New Delhi', 32, 74, NULL, NULL),
(73, 'Delhi', 32, 74, NULL, NULL),
(74, 'Dwarka', 32, 74, NULL, NULL),
(75, 'Rohini', 32, 74, NULL, NULL),
(76, 'Chennai', 23, 74, NULL, NULL),
(77, 'Coimbatore', 23, 74, NULL, NULL),
(78, 'Madurai', 23, 74, NULL, NULL),
(79, 'Tiruchirappalli', 23, 74, NULL, NULL),
(80, 'Salem', 23, 74, NULL, NULL),
(81, 'Tirunelveli', 23, 74, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Afghanistan', NULL, NULL),
(2, 'Albania', NULL, NULL),
(3, 'Algeria', NULL, NULL),
(4, 'Andorra', NULL, NULL),
(5, 'Angola', NULL, NULL),
(6, 'Antigua and Barbuda', NULL, NULL),
(7, 'Argentina', NULL, NULL),
(8, 'Armenia', NULL, NULL),
(9, 'Australia', NULL, NULL),
(10, 'Austria', NULL, NULL),
(11, 'Azerbaijan', NULL, NULL),
(12, 'Bahamas', NULL, NULL),
(13, 'Bahrain', NULL, NULL),
(14, 'Bangladesh', NULL, NULL),
(15, 'Barbados', NULL, NULL),
(16, 'Belarus', NULL, NULL),
(17, 'Belgium', NULL, NULL),
(18, 'Belize', NULL, NULL),
(19, 'Benin', NULL, NULL),
(20, 'Bhutan', NULL, NULL),
(21, 'Bolivia', NULL, NULL),
(22, 'Bosnia and Herzegovina', NULL, NULL),
(23, 'Botswana', NULL, NULL),
(24, 'Brazil', NULL, NULL),
(25, 'Brunei', NULL, NULL),
(26, 'Bulgaria', NULL, NULL),
(27, 'Burkina Faso', NULL, NULL),
(28, 'Burundi', NULL, NULL),
(29, 'Cambodia', NULL, NULL),
(30, 'Cameroon', NULL, NULL),
(31, 'Canada', NULL, NULL),
(32, 'Cape Verde', NULL, NULL),
(33, 'Central African Republic', NULL, NULL),
(34, 'Chad', NULL, NULL),
(35, 'Chile', NULL, NULL),
(36, 'China', NULL, NULL),
(37, 'Colombia', NULL, NULL),
(38, 'Comoros', NULL, NULL),
(39, 'Congo', NULL, NULL),
(40, 'Costa Rica', NULL, NULL),
(41, 'Croatia', NULL, NULL),
(42, 'Cuba', NULL, NULL),
(43, 'Cyprus', NULL, NULL),
(44, 'Czech Republic', NULL, NULL),
(45, 'Denmark', NULL, NULL),
(46, 'Djibouti', NULL, NULL),
(47, 'Dominica', NULL, NULL),
(48, 'Dominican Republic', NULL, NULL),
(49, 'Ecuador', NULL, NULL),
(50, 'Egypt', NULL, NULL),
(51, 'El Salvador', NULL, NULL),
(52, 'Equatorial Guinea', NULL, NULL),
(53, 'Eritrea', NULL, NULL),
(54, 'Estonia', NULL, NULL),
(55, 'Ethiopia', NULL, NULL),
(56, 'Fiji', NULL, NULL),
(57, 'Finland', NULL, NULL),
(58, 'France', NULL, NULL),
(59, 'Gabon', NULL, NULL),
(60, 'Gambia', NULL, NULL),
(61, 'Georgia', NULL, NULL),
(62, 'Germany', NULL, NULL),
(63, 'Ghana', NULL, NULL),
(64, 'Greece', NULL, NULL),
(65, 'Grenada', NULL, NULL),
(66, 'Guatemala', NULL, NULL),
(67, 'Guinea', NULL, NULL),
(68, 'Guinea-Bissau', NULL, NULL),
(69, 'Guyana', NULL, NULL),
(70, 'Haiti', NULL, NULL),
(71, 'Honduras', NULL, NULL),
(72, 'Hungary', NULL, NULL),
(73, 'Iceland', NULL, NULL),
(74, 'India', NULL, NULL),
(75, 'Indonesia', NULL, NULL),
(76, 'Iran', NULL, NULL),
(77, 'Iraq', NULL, NULL),
(78, 'Ireland', NULL, NULL),
(79, 'Israel', NULL, NULL),
(80, 'Italy', NULL, NULL),
(81, 'Jamaica', NULL, NULL),
(82, 'Japan', NULL, NULL),
(83, 'Jordan', NULL, NULL),
(84, 'Kazakhstan', NULL, NULL),
(85, 'Kenya', NULL, NULL),
(86, 'Kiribati', NULL, NULL),
(87, 'Kosovo', NULL, NULL),
(88, 'Kuwait', NULL, NULL),
(89, 'Kyrgyzstan', NULL, NULL),
(90, 'Laos', NULL, NULL),
(91, 'Latvia', NULL, NULL),
(92, 'Lebanon', NULL, NULL),
(93, 'Lesotho', NULL, NULL),
(94, 'Liberia', NULL, NULL),
(95, 'Libya', NULL, NULL),
(96, 'Liechtenstein', NULL, NULL),
(97, 'Lithuania', NULL, NULL),
(98, 'Luxembourg', NULL, NULL),
(99, 'Macedonia', NULL, NULL),
(100, 'Madagascar', NULL, NULL),
(101, 'Malawi', NULL, NULL),
(102, 'Malaysia', NULL, NULL),
(103, 'Maldives', NULL, NULL),
(104, 'Mali', NULL, NULL),
(105, 'Malta', NULL, NULL),
(106, 'Marshall Islands', NULL, NULL),
(107, 'Mauritania', NULL, NULL),
(108, 'Mauritius', NULL, NULL),
(109, 'Mexico', NULL, NULL),
(110, 'Micronesia', NULL, NULL),
(111, 'Moldova', NULL, NULL),
(112, 'Monaco', NULL, NULL),
(113, 'Mongolia', NULL, NULL),
(114, 'Montenegro', NULL, NULL),
(115, 'Morocco', NULL, NULL),
(116, 'Mozambique', NULL, NULL),
(117, 'Myanmar', NULL, NULL),
(118, 'Namibia', NULL, NULL),
(119, 'Nauru', NULL, NULL),
(120, 'Nepal', NULL, NULL),
(121, 'Netherlands', NULL, NULL),
(122, 'New Zealand', NULL, NULL),
(123, 'Nicaragua', NULL, NULL),
(124, 'Niger', NULL, NULL),
(125, 'Nigeria', NULL, NULL),
(126, 'North Korea', NULL, NULL),
(127, 'Norway', NULL, NULL),
(128, 'Oman', NULL, NULL),
(129, 'Pakistan', NULL, NULL),
(130, 'Palau', NULL, NULL),
(131, 'Palestine', NULL, NULL),
(132, 'Panama', NULL, NULL),
(133, 'Papua New Guinea', NULL, NULL),
(134, 'Paraguay', NULL, NULL),
(135, 'Peru', NULL, NULL),
(136, 'Philippines', NULL, NULL),
(137, 'Poland', NULL, NULL),
(138, 'Portugal', NULL, NULL),
(139, 'Qatar', NULL, NULL),
(140, 'Romania', NULL, NULL),
(141, 'Russia', NULL, NULL),
(142, 'Rwanda', NULL, NULL),
(143, 'Saint Kitts and Nevis', NULL, NULL),
(144, 'Saint Lucia', NULL, NULL),
(145, 'Saint Vincent and the Grenadines', NULL, NULL),
(146, 'Samoa', NULL, NULL),
(147, 'San Marino', NULL, NULL),
(148, 'Sao Tome and Principe', NULL, NULL),
(149, 'Saudi Arabia', NULL, NULL),
(150, 'Senegal', NULL, NULL),
(151, 'Serbia', NULL, NULL),
(152, 'Seychelles', NULL, NULL),
(153, 'Sierra Leone', NULL, NULL),
(154, 'Singapore', NULL, NULL),
(155, 'Slovakia', NULL, NULL),
(156, 'Slovenia', NULL, NULL),
(157, 'Solomon Islands', NULL, NULL),
(158, 'Somalia', NULL, NULL),
(159, 'South Africa', NULL, NULL),
(160, 'South Korea', NULL, NULL),
(161, 'South Sudan', NULL, NULL),
(162, 'Spain', NULL, NULL),
(163, 'Sri Lanka', NULL, NULL),
(164, 'Sudan', NULL, NULL),
(165, 'Suriname', NULL, NULL),
(166, 'Swaziland', NULL, NULL),
(167, 'Sweden', NULL, NULL),
(168, 'Switzerland', NULL, NULL),
(169, 'Syria', NULL, NULL),
(170, 'Taiwan', NULL, NULL),
(171, 'Tajikistan', NULL, NULL),
(172, 'Tanzania', NULL, NULL),
(173, 'Thailand', NULL, NULL),
(174, 'Timor-Leste', NULL, NULL),
(175, 'Togo', NULL, NULL),
(176, 'Tonga', NULL, NULL),
(177, 'Trinidad and Tobago', NULL, NULL),
(178, 'Tunisia', NULL, NULL),
(179, 'Turkey', NULL, NULL),
(180, 'Turkmenistan', NULL, NULL),
(181, 'Tuvalu', NULL, NULL),
(182, 'Uganda', NULL, NULL),
(183, 'Ukraine', NULL, NULL),
(184, 'United Arab Emirates', NULL, NULL),
(185, 'United Kingdom', NULL, NULL),
(186, 'United States', NULL, NULL),
(187, 'Uruguay', NULL, NULL),
(188, 'Uzbekistan', NULL, NULL),
(189, 'Vanuatu', NULL, NULL),
(190, 'Vatican City', NULL, NULL),
(191, 'Venezuela', NULL, NULL),
(192, 'Vietnam', NULL, NULL),
(193, 'Yemen', NULL, NULL),
(194, 'Zambia', NULL, NULL),
(195, 'Zimbabwe', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_reasons`
--

CREATE TABLE `health_reasons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `health_reasons`
--

INSERT INTO `health_reasons` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Fever', 1, '2025-12-23 02:24:00', '2025-12-23 02:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_09_29_074450_create_countries_table', 1),
(6, '2025_09_29_074451_create_states_table', 1),
(7, '2025_09_29_074452_create_cities_table', 1),
(8, '2025_09_29_074454_create_programs_table', 1),
(9, '2025_09_29_074455_create_specialities_table', 1),
(10, '2025_09_29_074457_create_health_reasons_table', 1),
(11, '2025_09_29_074502_create_body_sections_table', 1),
(12, '2025_09_29_074514_create_patients_table', 1),
(13, '2025_09_29_074515_create_projects_table', 1),
(14, '2025_09_29_074532_create_records_table', 1),
(15, '2025_09_29_074533_create_record_diagnoses_table', 1),
(16, '2025_09_29_074535_create_record_comments_table', 1),
(17, '2025_09_29_074536_create_record_prescriptions_table', 1),
(18, '2025_09_29_074537_create_scheduled_calls_table', 1),
(19, '2025_09_29_074539_create_user_schedules_table', 1),
(20, '2025_09_29_074545_create_symptoms_table', 1),
(21, '2025_09_29_074551_create_questions_table', 1),
(22, '2025_09_29_074552_create_answers_table', 1),
(23, '2025_09_29_175026_create_permission_tables', 1),
(24, '2025_12_25_125356_update_patient_uploads_to_text', 2),
(25, '2025_12_25_125649_2025_12_25_125356_update_patient_uploads_to_text', 2),
(26, '2025_12_26_052533_add_symptom_ids_and_question_summary_to_records_table', 2),
(27, '2025_12_26_054205_add_symptom_fields_to_records_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `filenumber` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `height` double(8,2) DEFAULT NULL,
  `heightunit` text DEFAULT NULL,
  `weight` double(8,2) DEFAULT NULL,
  `weightunit` text DEFAULT NULL,
  `smoke` tinyint(1) NOT NULL DEFAULT 0,
  `drinkalcohol` tinyint(1) NOT NULL DEFAULT 0,
  `generalhealth` text DEFAULT NULL,
  `generalhealthupload` text DEFAULT NULL,
  `reasonvisit` text DEFAULT NULL,
  `bp` varchar(255) DEFAULT NULL,
  `heartrate` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `profile` varchar(255) DEFAULT NULL,
  `reasontovisit` text DEFAULT NULL,
  `medication` text DEFAULT NULL,
  `medicationupload` text DEFAULT NULL,
  `familyhealthreason` text DEFAULT NULL,
  `malariatest` text DEFAULT NULL,
  `malariatestupload` text DEFAULT NULL,
  `hivtest` text DEFAULT NULL,
  `hivtestupload` text DEFAULT NULL,
  `preferredphysician` varchar(255) DEFAULT NULL,
  `oxygensaturation` double(8,2) DEFAULT NULL,
  `temperature` double(8,2) DEFAULT NULL,
  `additionalcomment` text DEFAULT NULL,
  `programid` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `filenumber`, `email`, `mobile`, `dob`, `height`, `heightunit`, `weight`, `weightunit`, `smoke`, `drinkalcohol`, `generalhealth`, `generalhealthupload`, `reasonvisit`, `bp`, `heartrate`, `occupation`, `gender`, `is_active`, `profile`, `reasontovisit`, `medication`, `medicationupload`, `familyhealthreason`, `malariatest`, `malariatestupload`, `hivtest`, `hivtestupload`, `preferredphysician`, `oxygensaturation`, `temperature`, `additionalcomment`, `programid`, `created_at`, `updated_at`) VALUES
(1, 'Mayuresh Mhatre', 'IN1465', 'mayuresh.mhatre@conexus-ns.com', '08329013368', '2025-12-01', 173.00, 'CM', 70.00, 'KG', 0, 0, 'Test', NULL, 'Test', '90-140', '76', 'Web Developer', 'Male', 1, NULL, NULL, 'Test', NULL, 'Test', 'Test', NULL, 'Test', NULL, 'Male', 80.00, 90.00, 'Test', 1, '2025-12-23 23:44:38', '2025-12-23 23:44:38'),
(2, 'DIGMBAR SHINGATE', 'IN555', 'shingatedigu@gmail.com', '1234567890', '2002-06-12', 143.00, 'CM', 60.00, 'KG', 0, 0, 'NA', '01KDADS63BZCEK3EQQJZ3CKZSA.png', 'Test reson', '80/120', '122', 'mer', 'Male', 1, NULL, NULL, 'NA', '01KDADS65KMXDJ2RN9RPKB447W.png', 'test', 'yes', '01KDADS65NYA3CBA5QTBJKJWDN.png', 'no', NULL, 'Male', 96.00, 96.00, NULL, 1, '2025-12-25 04:05:01', '2025-12-25 04:05:01'),
(3, 'Amit Sharma', 'IN3995', 'amit.sharma@example.com', '9123456789', '1985-08-20', 175.00, 'CM', 75.00, 'KG', 0, 0, 'Generally healthy, mild back pain occasionally', '[\"slide2.png\",\"slide3.png\"]', 'Annual health camp checkup', '130/85', '78', 'Teacher', 'Male', 1, 'slide2.png', NULL, 'None', '[\"slide3.png\",\"slide2.png\"]', 'Mother has diabetes', 'Negative', '[\"slide2.png\",\"slide3.png\"]', 'Negative', '[\"slide2.png\",\"slide3.png\"]', 'Dr. Patel', 97.00, 98.40, 'No known allergies', 1, '2025-12-25 07:38:14', '2025-12-25 07:38:14'),
(4, 'Dinesh Mehta', 'IN7363', 'dinu@example.com', '9123456789', '1985-08-20', 175.00, 'CM', 75.00, 'KG', 0, 0, 'Generally healthy, mild back pain occasionally', '[\"slide2.png\",\"slide3.png\"]', 'Annual health camp checkup', '130/85', '78', 'Teacher', 'Male', 1, 'slide2.png', NULL, 'None', '[\"slide3.png\",\"slide2.png\"]', 'Mother has diabetes', 'Negative', '[\"slide2.png\",\"slide3.png\"]', 'Negative', '[\"slide2.png\",\"slide3.png\"]', 'Dr. Patel', 97.00, 98.40, 'No known allergies', 1, '2025-12-26 04:00:25', '2025-12-26 04:00:25'),
(5, 'Saad Mulla', 'IN5567', 'saad@example.com', '9123456789', '1985-08-20', 175.00, 'CM', 75.00, 'KG', 0, 0, 'Generally healthy, mild back pain occasionally', '[\"slide2.png\",\"slide3.png\"]', 'Annual health camp checkup', '130/85', '78', 'Teacher', 'Male', 1, 'slide2.png', NULL, 'None', '[\"slide3.png\",\"slide2.png\"]', 'Mother has diabetes', 'Negative', '[\"slide2.png\",\"slide3.png\"]', 'Negative', '[\"slide2.png\",\"slide3.png\"]', 'Dr. Patel', 97.00, 98.40, 'No known allergies', 1, '2025-12-26 04:19:35', '2025-12-26 04:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(2, 'view_any_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(3, 'create_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(4, 'update_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(5, 'restore_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(6, 'restore_any_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(7, 'replicate_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(8, 'reorder_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(9, 'delete_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(10, 'delete_any_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(11, 'force_delete_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(12, 'force_delete_any_speciality', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(13, 'view_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(14, 'view_any_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(15, 'create_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(16, 'update_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(17, 'restore_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(18, 'restore_any_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(19, 'replicate_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(20, 'reorder_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(21, 'delete_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(22, 'delete_any_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(23, 'force_delete_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(24, 'force_delete_any_program', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(25, 'view_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(26, 'view_any_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(27, 'create_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(28, 'update_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(29, 'restore_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(30, 'restore_any_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(31, 'replicate_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(32, 'reorder_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(33, 'delete_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(34, 'delete_any_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(35, 'force_delete_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(36, 'force_delete_any_project', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(37, 'view_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(38, 'view_any_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(39, 'create_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(40, 'update_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(41, 'restore_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(42, 'restore_any_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(43, 'replicate_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(44, 'reorder_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(45, 'delete_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(46, 'delete_any_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(47, 'force_delete_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(48, 'force_delete_any_health_reason', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(49, 'view_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(50, 'view_any_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(51, 'create_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(52, 'update_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(53, 'restore_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(54, 'restore_any_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(55, 'replicate_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(56, 'reorder_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(57, 'delete_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(58, 'delete_any_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(59, 'force_delete_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(60, 'force_delete_any_patient', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(61, 'view_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(62, 'view_any_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(63, 'create_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(64, 'update_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(65, 'restore_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(66, 'restore_any_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(67, 'replicate_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(68, 'reorder_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(69, 'delete_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(70, 'delete_any_symptom', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(71, 'force_delete_symptom', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(72, 'force_delete_any_symptom', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(73, 'view_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(74, 'view_any_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(75, 'create_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(76, 'update_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(77, 'restore_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(78, 'restore_any_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(79, 'replicate_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(80, 'reorder_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(81, 'delete_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(82, 'delete_any_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(83, 'force_delete_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(84, 'force_delete_any_body_section', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(85, 'view_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(86, 'view_any_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(87, 'create_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(88, 'update_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(89, 'restore_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(90, 'restore_any_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(91, 'replicate_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(92, 'reorder_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(93, 'delete_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(94, 'delete_any_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(95, 'force_delete_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(96, 'force_delete_any_question', 'web', '2025-12-23 01:09:05', '2025-12-23 01:09:05'),
(97, 'view_role', 'web', '2025-12-23 01:09:37', '2025-12-23 01:09:37'),
(98, 'view_any_role', 'web', '2025-12-23 01:09:37', '2025-12-23 01:09:37'),
(99, 'create_role', 'web', '2025-12-23 01:09:37', '2025-12-23 01:09:37'),
(100, 'update_role', 'web', '2025-12-23 01:09:37', '2025-12-23 01:09:37'),
(101, 'delete_role', 'web', '2025-12-23 01:09:37', '2025-12-23 01:09:37'),
(102, 'delete_any_role', 'web', '2025-12-23 01:09:37', '2025-12-23 01:09:37'),
(103, 'view_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(104, 'view_any_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(105, 'create_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(106, 'update_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(107, 'restore_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(108, 'restore_any_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(109, 'replicate_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(110, 'reorder_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(111, 'delete_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(112, 'delete_any_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(113, 'force_delete_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(114, 'force_delete_any_answer', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(115, 'view_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(116, 'view_any_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(117, 'create_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(118, 'update_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(119, 'restore_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(120, 'restore_any_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(121, 'replicate_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(122, 'reorder_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(123, 'delete_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(124, 'delete_any_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(125, 'force_delete_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(126, 'force_delete_any_body::section', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(127, 'view_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(128, 'view_any_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(129, 'create_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(130, 'update_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(131, 'restore_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(132, 'restore_any_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(133, 'replicate_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(134, 'reorder_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(135, 'delete_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(136, 'delete_any_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(137, 'force_delete_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(138, 'force_delete_any_health::reason', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(139, 'view_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(140, 'view_any_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(141, 'create_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(142, 'update_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(143, 'restore_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(144, 'restore_any_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(145, 'replicate_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(146, 'reorder_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(147, 'delete_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(148, 'delete_any_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(149, 'force_delete_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(150, 'force_delete_any_record', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(151, 'view_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(152, 'view_any_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(153, 'create_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(154, 'update_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(155, 'restore_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(156, 'restore_any_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(157, 'replicate_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(158, 'reorder_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(159, 'delete_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(160, 'delete_any_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(161, 'force_delete_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(162, 'force_delete_any_scheduled::call', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(163, 'view_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(164, 'view_any_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(165, 'create_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(166, 'update_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(167, 'restore_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(168, 'restore_any_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(169, 'replicate_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(170, 'reorder_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(171, 'delete_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(172, 'delete_any_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(173, 'force_delete_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(174, 'force_delete_any_user', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55'),
(175, 'widget_PatientsPerProgram', 'web', '2025-12-23 01:09:55', '2025-12-23 01:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(12, 'App\\Models\\User', 4, 'postman', '39f120def650591dbce2d53b91966290bdf8331f4e9bdc495b1ee51b0ac8610a', '[\"*\"]', '2025-12-24 06:53:27', NULL, '2025-12-24 04:47:32', '2025-12-24 06:53:27'),
(24, 'App\\Models\\User', 6, 'android', '587b0c2ab95dcb52c5d0b7d41ed1b531fb6f27c2f673713737e2ec2b2da56303', '[\"*\"]', '2025-12-25 05:44:39', NULL, '2025-12-25 05:42:58', '2025-12-25 05:44:39'),
(27, 'App\\Models\\User', 5, 'android', 'dbd027193c97215251461d6de24be53f5fb40414bb39ed215c702bd8750073a1', '[\"*\"]', '2025-12-26 04:59:25', NULL, '2025-12-26 00:50:00', '2025-12-26 04:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Blood Donation', NULL, 1, '2025-12-23 02:21:14', '2025-12-23 02:21:14'),
(2, 'Test', 'Program Description', 1, '2025-12-24 06:53:27', '2025-12-24 06:53:27'),
(3, 'Panvel Camp', 'test', 1, '2025-12-25 04:01:53', '2025-12-25 04:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cityid` bigint(20) UNSIGNED NOT NULL,
  `gpid` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gpid`)),
  `volunteerid` bigint(20) UNSIGNED DEFAULT NULL,
  `stateid` bigint(20) UNSIGNED NOT NULL,
  `countryid` bigint(20) UNSIGNED NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `budget` double DEFAULT NULL,
  `programid` bigint(20) UNSIGNED DEFAULT NULL,
  `othercity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `cityid`, `gpid`, `volunteerid`, `stateid`, `countryid`, `isactive`, `startdate`, `enddate`, `budget`, `programid`, `othercity`, `created_at`, `updated_at`) VALUES
(1, 'Camp in Navi Mubai', 'Testing ', 64, '[\"3\"]', 5, 14, 74, 1, '2025-12-01', '2026-01-31', 60000, 1, 'Kharghar', '2025-12-23 02:23:43', '2025-12-23 02:23:43'),
(2, 'Second Program', 'test', 57, '[\"3\"]', 5, 14, 74, 1, '2025-12-27', '2026-01-01', 80000, 3, 'panvel', '2025-12-25 04:01:24', '2025-12-25 04:02:29');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `symptomid` bigint(20) UNSIGNED DEFAULT NULL,
  `question_text` text NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `question_index` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `symptomid`, `question_text`, `answers`, `question_index`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, '<p>Are any of these true for you?</p><p>&nbsp;-&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Have you ever left the house for a familiar destination and gotten lost on the way there or when returning home?</p><p>&nbsp;-&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Have you ever forgotten how to do a familiar activity, such as doing the laundry or cooking dinner?</p><p>&nbsp;-&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Do you sometimes repeat the same stories in the same conversation ?</p><p>&nbsp;-&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Is your memory very much worse than it was one year ago?</p>', '[{\"answer\":\"Yes, at least one of these is true for me. \",\"next_question_id\":\"false\"},{\"answer\":\"No, none of these are true for me. \",\"next_question_id\":\"false\"}]', '2', 1, '2025-12-23 01:43:14', '2025-12-23 01:43:14'),
(2, 2, '<p>Other than forgetting people’s names, words for things, and where you left your keys, is your memory generally okay?</p>', '[{\"answer\":\"Yes, my memory is generally okay. \",\"next_question_id\":false},{\"answer\": \"No, I am more forgetful than this. \",\"next_question_id\":1}]', '1', 1, '2025-12-23 01:59:45', '2025-12-23 02:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patientid` bigint(20) UNSIGNED NOT NULL,
  `doctorid` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`doctorid`)),
  `gpid` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gpid`)),
  `volunteerid` bigint(20) UNSIGNED DEFAULT NULL,
  `projectid` bigint(20) UNSIGNED DEFAULT NULL,
  `programid` bigint(20) UNSIGNED DEFAULT NULL,
  `record_type` varchar(255) DEFAULT NULL,
  `symptom_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`symptom_ids`)),
  `question_summary` longtext DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `status` enum('draft','submitted','reviewed') NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `patientid`, `doctorid`, `gpid`, `volunteerid`, `projectid`, `programid`, `record_type`, `symptom_ids`, `question_summary`, `notes`, `attachments`, `status`, `submitted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '[\"2\"]', '[\"3\"]', 5, 1, 1, 'tett', NULL, NULL, 'test', '[]', 'draft', NULL, '2025-12-25 02:30:52', '2025-12-25 02:30:52'),
(2, 2, '[\"2\"]', '[\"3\"]', 5, 2, 3, 'tett', NULL, NULL, 'test', '[]', 'draft', NULL, '2025-12-25 04:06:01', '2025-12-25 04:06:01'),
(3, 3, '[\"2\"]', '[\"3\"]', 5, 2, 3, 'Consultation', '[\"1\",\"2\"]', '[{\"question_id\":1,\"question_text\":\"Do you have headache?\",\"selected_answer\":\"Yes\"}]', 'Patient reported mild headache for 2 days.', '[\"records\\/attachments\\/hpezu56I9QPiRuCQIrT0ImU6M5Yh9Dx8CCqLKvZM.png\"]', 'draft', NULL, '2025-12-26 01:03:39', '2025-12-26 01:03:39'),
(4, 5, '[\"2\"]', '[\"3\"]', 5, 2, 1, 'Consultation', '[\"1\",\"2\"]', '[{\"question_id\":1,\"question_text\":\"Do you have headache?\",\"selected_answer\":\"Yes\"}]', 'Patient reported mild headache for 2 days.', '[\"records\\/attachments\\/X8Ys7mFUJe43mfoqVc4JchstHTbwoEQ6LYeD1bur.png\"]', 'draft', NULL, '2025-12-26 04:21:24', '2025-12-26 04:21:24');

-- --------------------------------------------------------

--
-- Table structure for table `record_comments`
--

CREATE TABLE `record_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `recordid` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `record_diagnoses`
--

CREATE TABLE `record_diagnoses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recordid` bigint(20) UNSIGNED NOT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `record_prescriptions`
--

CREATE TABLE `record_prescriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recordid` bigint(20) UNSIGNED NOT NULL,
  `medicine_name` varchar(255) DEFAULT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(2, 'admin', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(3, 'doctor', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(4, 'gp', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04'),
(5, 'volunteer', 'web', '2025-12-23 01:09:04', '2025-12-23 01:09:04');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(34, 2),
(35, 1),
(35, 2),
(36, 1),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(49, 1),
(49, 2),
(50, 1),
(50, 2),
(51, 1),
(51, 2),
(52, 1),
(52, 2),
(53, 1),
(53, 2),
(54, 1),
(54, 2),
(55, 1),
(55, 2),
(56, 1),
(56, 2),
(57, 1),
(57, 2),
(58, 1),
(58, 2),
(59, 1),
(59, 2),
(60, 1),
(60, 2),
(61, 1),
(61, 2),
(62, 1),
(62, 2),
(63, 1),
(63, 2),
(64, 1),
(64, 2),
(65, 1),
(65, 2),
(66, 1),
(66, 2),
(67, 1),
(67, 2),
(68, 1),
(68, 2),
(69, 1),
(69, 2),
(70, 1),
(70, 2),
(71, 1),
(71, 2),
(72, 1),
(72, 2),
(73, 2),
(74, 2),
(75, 2),
(76, 2),
(77, 2),
(78, 2),
(79, 2),
(80, 2),
(81, 2),
(82, 2),
(83, 2),
(84, 2),
(85, 1),
(85, 2),
(86, 1),
(86, 2),
(87, 1),
(87, 2),
(88, 1),
(88, 2),
(89, 1),
(89, 2),
(90, 1),
(90, 2),
(91, 1),
(91, 2),
(92, 1),
(92, 2),
(93, 1),
(93, 2),
(94, 1),
(94, 2),
(95, 1),
(95, 2),
(96, 1),
(96, 2),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_calls`
--

CREATE TABLE `scheduled_calls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recordid` bigint(20) UNSIGNED NOT NULL,
  `volunteer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_gp_doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `schedule_start_time` time NOT NULL,
  `schedule_end_time` time NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scheduled_calls`
--

INSERT INTO `scheduled_calls` (`id`, `recordid`, `volunteer_id`, `assigned_gp_doctor_id`, `schedule_date`, `schedule_start_time`, `schedule_end_time`, `room_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 3, '2025-12-26', '14:56:23', '15:56:26', '101', 'scheduled', '2025-12-25 03:56:38', '2025-12-25 03:56:38'),
(2, 2, 5, 3, '2025-12-25', '15:06:46', '16:06:49', '102', 'completed', '2025-12-25 04:07:00', '2025-12-25 04:07:00'),
(3, 3, 5, 2, '2025-12-27', '12:54:54', '13:54:56', '105', 'scheduled', '2025-12-26 01:55:07', '2025-12-26 01:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `specialities`
--

CREATE TABLE `specialities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specialities`
--

INSERT INTO `specialities` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Neurologist', 1, '2025-12-23 02:20:54', '2025-12-23 02:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `countryid` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `countryid`, `created_at`, `updated_at`) VALUES
(1, 'Andhra Pradesh', 74, NULL, NULL),
(2, 'Arunachal Pradesh', 74, NULL, NULL),
(3, 'Assam', 74, NULL, NULL),
(4, 'Bihar', 74, NULL, NULL),
(5, 'Chhattisgarh', 74, NULL, NULL),
(6, 'Goa', 74, NULL, NULL),
(7, 'Gujarat', 74, NULL, NULL),
(8, 'Haryana', 74, NULL, NULL),
(9, 'Himachal Pradesh', 74, NULL, NULL),
(10, 'Jharkhand', 74, NULL, NULL),
(11, 'Karnataka', 74, NULL, NULL),
(12, 'Kerala', 74, NULL, NULL),
(13, 'Madhya Pradesh', 74, NULL, NULL),
(14, 'Maharashtra', 74, NULL, NULL),
(15, 'Manipur', 74, NULL, NULL),
(16, 'Meghalaya', 74, NULL, NULL),
(17, 'Mizoram', 74, NULL, NULL),
(18, 'Nagaland', 74, NULL, NULL),
(19, 'Odisha', 74, NULL, NULL),
(20, 'Punjab', 74, NULL, NULL),
(21, 'Rajasthan', 74, NULL, NULL),
(22, 'Sikkim', 74, NULL, NULL),
(23, 'Tamil Nadu', 74, NULL, NULL),
(24, 'Telangana', 74, NULL, NULL),
(25, 'Tripura', 74, NULL, NULL),
(26, 'Uttar Pradesh', 74, NULL, NULL),
(27, 'Uttarakhand', 74, NULL, NULL),
(28, 'West Bengal', 74, NULL, NULL),
(29, 'Andaman and Nicobar Islands', 74, NULL, NULL),
(30, 'Chandigarh', 74, NULL, NULL),
(31, 'Dadra and Nagar Haveli and Daman and Diu', 74, NULL, NULL),
(32, 'Delhi', 74, NULL, NULL),
(33, 'Jammu and Kashmir', 74, NULL, NULL),
(34, 'Ladakh', 74, NULL, NULL),
(35, 'Lakshadweep', 74, NULL, NULL),
(36, 'Puducherry', 74, NULL, NULL),
(37, 'Alabama', 186, NULL, NULL),
(38, 'Alaska', 186, NULL, NULL),
(39, 'Arizona', 186, NULL, NULL),
(40, 'Arkansas', 186, NULL, NULL),
(41, 'California', 186, NULL, NULL),
(42, 'Colorado', 186, NULL, NULL),
(43, 'Connecticut', 186, NULL, NULL),
(44, 'Delaware', 186, NULL, NULL),
(45, 'Florida', 186, NULL, NULL),
(46, 'Georgia', 186, NULL, NULL),
(47, 'Hawaii', 186, NULL, NULL),
(48, 'Idaho', 186, NULL, NULL),
(49, 'Illinois', 186, NULL, NULL),
(50, 'Indiana', 186, NULL, NULL),
(51, 'Iowa', 186, NULL, NULL),
(52, 'Kansas', 186, NULL, NULL),
(53, 'Kentucky', 186, NULL, NULL),
(54, 'Louisiana', 186, NULL, NULL),
(55, 'Maine', 186, NULL, NULL),
(56, 'Maryland', 186, NULL, NULL),
(57, 'Massachusetts', 186, NULL, NULL),
(58, 'Michigan', 186, NULL, NULL),
(59, 'Minnesota', 186, NULL, NULL),
(60, 'Mississippi', 186, NULL, NULL),
(61, 'Missouri', 186, NULL, NULL),
(62, 'Montana', 186, NULL, NULL),
(63, 'Nebraska', 186, NULL, NULL),
(64, 'Nevada', 186, NULL, NULL),
(65, 'New Hampshire', 186, NULL, NULL),
(66, 'New Jersey', 186, NULL, NULL),
(67, 'New Mexico', 186, NULL, NULL),
(68, 'New York', 186, NULL, NULL),
(69, 'North Carolina', 186, NULL, NULL),
(70, 'North Dakota', 186, NULL, NULL),
(71, 'Ohio', 186, NULL, NULL),
(72, 'Oklahoma', 186, NULL, NULL),
(73, 'Oregon', 186, NULL, NULL),
(74, 'Pennsylvania', 186, NULL, NULL),
(75, 'Rhode Island', 186, NULL, NULL),
(76, 'South Carolina', 186, NULL, NULL),
(77, 'South Dakota', 186, NULL, NULL),
(78, 'Tennessee', 186, NULL, NULL),
(79, 'Texas', 186, NULL, NULL),
(80, 'Utah', 186, NULL, NULL),
(81, 'Vermont', 186, NULL, NULL),
(82, 'Virginia', 186, NULL, NULL),
(83, 'Washington', 186, NULL, NULL),
(84, 'West Virginia', 186, NULL, NULL),
(85, 'Wisconsin', 186, NULL, NULL),
(86, 'Wyoming', 186, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `body_section_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `tag` varchar(255) DEFAULT NULL,
  `iscritical` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`id`, `name`, `body_section_id`, `is_active`, `tag`, `iscritical`, `created_at`, `updated_at`) VALUES
(1, 'Headache', 1, 1, 'Upper Body', 1, '2025-12-23 01:11:21', '2025-12-23 01:11:21'),
(2, 'Forgetfulness Memory Loss', 1, 1, 'Upper Body', 1, '2025-12-23 01:27:15', '2025-12-23 01:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `nextkin` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `nlnumber` varchar(255) DEFAULT NULL,
  `proofid` varchar(255) DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `nextkin`, `mobile`, `nlnumber`, `proofid`, `isactive`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$12$Av.5iejd6IKeDtS.atvgQenPUiKq11D5MwOS05P3wiUgBEicmt.Lu', NULL, NULL, NULL, NULL, NULL, 1, NULL, 'Oy4GSztYRjgTt9qtRD5qqTHpwngIj4hjY3l0eZp9oDmoRhJtXDtpmUggPwl3', '2025-12-23 01:09:05', '2025-12-23 01:09:05', NULL),
(2, 'doctor', 'doctor@example.com', '$2y$12$J5b1ipVUfv2BSpRlYFGeYegdgYCqMEC6j5JOW1r/iPlkW6ddTl7iC', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-12-23 01:09:05', '2025-12-23 01:09:05', NULL),
(3, 'gp', 'gp@example.com', '$2y$12$49WHe0b9.dyQFlELmQEV5.O/vZcS08yXu1PqheFyhSglu1jRJYjKG', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-12-23 01:09:06', '2025-12-23 01:09:06', NULL),
(4, 'Sakshi Dige', 'sakshi.dige@conexus-ns.com', '$2y$12$LRxqWZpA9cWxnvX/Yi8T/.YeEWMYcSFb0JxcVUT9aYGel2DCq50oW', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-12-23 02:22:34', '2025-12-23 02:22:34', NULL),
(5, 'Mayuresh Mhatre', 'mayuresh.mhatre@conexus-ns.com', '$2y$12$ddX4ADzqEVPhjJrvRO0NNeu/lqJE42p6WYXE.zQvOMCZVj/fI0PD6', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-12-25 01:41:03', '2025-12-25 01:41:03', NULL),
(6, 'DIGMBAR SHINGATE', 'digu@gmail.com', '$2y$12$S10OyFn8vV//Syj0PliBnOIXncpHSg7e07XTzmSdD2id5nE5qcKN2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2025-12-25 03:34:56', '2025-12-25 03:34:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_schedules`
--

CREATE TABLE `user_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `weekname` varchar(255) NOT NULL,
  `ismorning` tinyint(1) NOT NULL DEFAULT 0,
  `isafternoon` tinyint(1) NOT NULL DEFAULT 0,
  `isevening` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_question_id_foreign` (`question_id`),
  ADD KEY `answers_next_question_id_foreign` (`next_question_id`);

--
-- Indexes for table `body_sections`
--
ALTER TABLE `body_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_stateid_foreign` (`stateid`),
  ADD KEY `cities_countryid_foreign` (`countryid`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `health_reasons`
--
ALTER TABLE `health_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_filenumber_unique` (`filenumber`),
  ADD KEY `patients_programid_foreign` (`programid`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_cityid_foreign` (`cityid`),
  ADD KEY `projects_volunteerid_foreign` (`volunteerid`),
  ADD KEY `projects_stateid_foreign` (`stateid`),
  ADD KEY `projects_countryid_foreign` (`countryid`),
  ADD KEY `projects_programid_foreign` (`programid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_symptomid_foreign` (`symptomid`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `records_patientid_foreign` (`patientid`),
  ADD KEY `records_volunteerid_foreign` (`volunteerid`),
  ADD KEY `records_projectid_foreign` (`projectid`),
  ADD KEY `records_programid_foreign` (`programid`);

--
-- Indexes for table `record_comments`
--
ALTER TABLE `record_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_comments_userid_foreign` (`userid`),
  ADD KEY `record_comments_recordid_foreign` (`recordid`);

--
-- Indexes for table `record_diagnoses`
--
ALTER TABLE `record_diagnoses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_diagnoses_recordid_foreign` (`recordid`);

--
-- Indexes for table `record_prescriptions`
--
ALTER TABLE `record_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_prescriptions_recordid_foreign` (`recordid`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `scheduled_calls`
--
ALTER TABLE `scheduled_calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheduled_calls_recordid_foreign` (`recordid`),
  ADD KEY `scheduled_calls_volunteer_id_foreign` (`volunteer_id`),
  ADD KEY `scheduled_calls_assigned_gp_doctor_id_foreign` (`assigned_gp_doctor_id`);

--
-- Indexes for table `specialities`
--
ALTER TABLE `specialities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `states_countryid_foreign` (`countryid`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `symptoms_body_section_id_foreign` (`body_section_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_schedules`
--
ALTER TABLE `user_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_schedules_userid_foreign` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `body_sections`
--
ALTER TABLE `body_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `health_reasons`
--
ALTER TABLE `health_reasons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `record_comments`
--
ALTER TABLE `record_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `record_diagnoses`
--
ALTER TABLE `record_diagnoses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `record_prescriptions`
--
ALTER TABLE `record_prescriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `scheduled_calls`
--
ALTER TABLE `scheduled_calls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `specialities`
--
ALTER TABLE `specialities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_schedules`
--
ALTER TABLE `user_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_next_question_id_foreign` FOREIGN KEY (`next_question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_countryid_foreign` FOREIGN KEY (`countryid`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `cities_stateid_foreign` FOREIGN KEY (`stateid`) REFERENCES `states` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_programid_foreign` FOREIGN KEY (`programid`) REFERENCES `programs` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_cityid_foreign` FOREIGN KEY (`cityid`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `projects_countryid_foreign` FOREIGN KEY (`countryid`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `projects_programid_foreign` FOREIGN KEY (`programid`) REFERENCES `programs` (`id`),
  ADD CONSTRAINT `projects_stateid_foreign` FOREIGN KEY (`stateid`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `projects_volunteerid_foreign` FOREIGN KEY (`volunteerid`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_symptomid_foreign` FOREIGN KEY (`symptomid`) REFERENCES `symptoms` (`id`);

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `records_patientid_foreign` FOREIGN KEY (`patientid`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `records_programid_foreign` FOREIGN KEY (`programid`) REFERENCES `programs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `records_projectid_foreign` FOREIGN KEY (`projectid`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `records_volunteerid_foreign` FOREIGN KEY (`volunteerid`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `record_comments`
--
ALTER TABLE `record_comments`
  ADD CONSTRAINT `record_comments_recordid_foreign` FOREIGN KEY (`recordid`) REFERENCES `records` (`id`),
  ADD CONSTRAINT `record_comments_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `record_diagnoses`
--
ALTER TABLE `record_diagnoses`
  ADD CONSTRAINT `record_diagnoses_recordid_foreign` FOREIGN KEY (`recordid`) REFERENCES `records` (`id`);

--
-- Constraints for table `record_prescriptions`
--
ALTER TABLE `record_prescriptions`
  ADD CONSTRAINT `record_prescriptions_recordid_foreign` FOREIGN KEY (`recordid`) REFERENCES `records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scheduled_calls`
--
ALTER TABLE `scheduled_calls`
  ADD CONSTRAINT `scheduled_calls_assigned_gp_doctor_id_foreign` FOREIGN KEY (`assigned_gp_doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scheduled_calls_recordid_foreign` FOREIGN KEY (`recordid`) REFERENCES `records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scheduled_calls_volunteer_id_foreign` FOREIGN KEY (`volunteer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_countryid_foreign` FOREIGN KEY (`countryid`) REFERENCES `countries` (`id`);

--
-- Constraints for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD CONSTRAINT `symptoms_body_section_id_foreign` FOREIGN KEY (`body_section_id`) REFERENCES `body_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_schedules`
--
ALTER TABLE `user_schedules`
  ADD CONSTRAINT `user_schedules_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
