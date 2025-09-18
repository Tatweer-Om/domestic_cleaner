-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 01:13 PM
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
-- Database: `cleaning_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_branch` varchar(255) DEFAULT NULL,
  `account_no` varchar(255) DEFAULT NULL,
  `opening_balance` decimal(50,3) DEFAULT NULL,
  `commission` decimal(50,2) DEFAULT NULL,
  `account_type` int(11) DEFAULT NULL COMMENT '1 : Normal Account 2 : Saving Account',
  `notes` text DEFAULT NULL,
  `account_status` int(11) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `booking_no` varchar(255) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `visits_count` int(11) DEFAULT NULL,
  `visits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`visits`)),
  `status` varchar(32) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `worker_id`, `package_id`, `booking_no`, `location_id`, `start_date`, `duration`, `visits_count`, `visits`, `status`, `created_at`, `updated_at`, `customer_id`, `added_by`, `updated_by`) VALUES
(1, 4, 1, 1, 'B2025-1', 1, '2025-09-20', 4, 4, '[{\"index\":1,\"date\":\"2025-09-20\",\"shift\":\"morning\",\"duration\":4},{\"index\":2,\"date\":\"2025-09-21\",\"shift\":\"morning\",\"duration\":4},{\"index\":3,\"date\":\"2025-09-22\",\"shift\":\"morning\",\"duration\":4},{\"index\":4,\"date\":\"2025-09-23\",\"shift\":\"morning\",\"duration\":4}]', '1', '2025-09-13 01:46:51', '2025-09-13 01:47:04', '1', 'adil', NULL),
(2, 6, 1, 1, 'B2025-2', 1, '2025-09-14', 4, 4, '[{\"index\":1,\"date\":\"2025-09-14\",\"shift\":\"morning\",\"duration\":4},{\"index\":2,\"date\":\"2025-09-15\",\"shift\":\"morning\",\"duration\":4},{\"index\":3,\"date\":\"2025-09-16\",\"shift\":\"morning\",\"duration\":4},{\"index\":4,\"date\":\"2025-09-17\",\"shift\":\"morning\",\"duration\":4}]', '1', '2025-09-13 05:00:28', '2025-09-13 05:00:44', '2', 'qadeer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_payments`
--

CREATE TABLE `booking_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_payment_expenses`
--

CREATE TABLE `booking_payment_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `phone_number`, `user_id`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'adil', '73839393', '4', '4', NULL, '2025-09-13 01:47:03', '2025-09-13 01:47:03'),
(2, 'qadeer', '91937980', '6', '6', NULL, '2025-09-13 05:00:42', '2025-09-13 05:00:42');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `driver_user_id` varchar(255) DEFAULT NULL,
  `whatsapp_notification` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `driver_image` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `location_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `driver_name`, `phone`, `driver_user_id`, `whatsapp_notification`, `notes`, `driver_image`, `shift`, `location_id`, `user_id`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'driver', '91937980', '7', '1', NULL, '1757758710.jpg', '1', '1', '1', 'system', NULL, '2025-09-13 05:18:30', '2025-09-13 05:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `expensecats`
--

CREATE TABLE `expensecats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_category_name` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_id` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `expense_name` varchar(255) DEFAULT NULL,
  `amount` decimal(50,2) DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `expense_date` varchar(255) DEFAULT NULL,
  `expense_type` varchar(255) DEFAULT NULL,
  `recurring_frequency` varchar(255) DEFAULT NULL,
  `expense_image` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `worker_id` varchar(255) DEFAULT NULL,
  `rating` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `booking_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `record_id` int(11) NOT NULL,
  `function` varchar(255) NOT NULL,
  `function_status` varchar(255) NOT NULL COMMENT '1 for update, 2 for delete',
  `branch_id` int(11) NOT NULL,
  `previous_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`previous_data`)),
  `updated_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`updated_data`)),
  `added_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`added_data`)),
  `added_by` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `table_name`, `record_id`, `function`, `function_status`, `branch_id`, `previous_data`, `updated_data`, `added_data`, `added_by`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'users', 1, 'update', '1', 1, '{\"user_name\":\"haseeb\",\"user_email\":\"safirai26@gmail.com\",\"user_phone\":\"919370980\",\"permissions\":\"1,2,3,4,5,6,7\",\"user_image\":\"1757718112.jpg\",\"user_type\":\"1\",\"notes\":null,\"user_id\":\"1\",\"added_by\":\"system\",\"created_at\":\"2025-09-12T23:01:53.000000Z\"}', '{\"user_name\":\"haseeb\",\"user_email\":\"safirai26@gmail.com\",\"user_phone\":\"919370980\",\"permissions\":\"1,2,3,4,5,6,7\",\"user_image\":\"1757745730.jpg\",\"user_type\":null,\"notes\":null,\"user_id\":1,\"added_by\":\"system\"}', NULL, 'system', 1, '2025-09-13 01:42:11', '2025-09-13 01:42:11'),
(2, 'users', 1, 'update', '1', 1, '{\"user_name\":\"haseeb\",\"user_email\":\"safirai26@gmail.com\",\"user_phone\":\"919370980\",\"permissions\":\"1,2,3,4,5,6,7\",\"user_image\":\"1757745730.jpg\",\"user_type\":null,\"notes\":null,\"user_id\":\"1\",\"added_by\":\"system\",\"created_at\":\"2025-09-12T23:01:53.000000Z\"}', '{\"user_name\":\"haseeb\",\"user_email\":\"safirai26@gmail.com\",\"user_phone\":\"919370980\",\"permissions\":\"1,2,3,4,5,6,7\",\"user_image\":\"1757745730.jpg\",\"user_type\":null,\"notes\":null,\"user_id\":1,\"added_by\":\"system\"}', NULL, 'system', 1, '2025-09-13 01:42:20', '2025-09-13 01:42:20'),
(3, 'users', 1, 'update', '1', 1, '{\"user_name\":\"haseeb\",\"user_email\":\"safirai26@gmail.com\",\"user_phone\":\"919370980\",\"permissions\":\"1,2,3,4,5,6,7\",\"user_image\":\"1757745730.jpg\",\"user_type\":null,\"notes\":null,\"user_id\":\"1\",\"added_by\":\"system\",\"created_at\":\"2025-09-12T23:01:53.000000Z\"}', '{\"user_name\":\"haseeb\",\"user_email\":\"safirai26@gmail.com\",\"user_phone\":\"919370980\",\"permissions\":\"1,2,3,4,5,6,7\",\"user_image\":\"1757745730.jpg\",\"user_type\":\"1\",\"notes\":null,\"user_id\":1,\"added_by\":\"system\"}', NULL, 'system', 1, '2025-09-13 01:42:35', '2025-09-13 01:42:35'),
(4, 'users', 2, 'update', '1', 1, '{\"user_name\":\"worker22\",\"user_email\":\"safirai36@gmail.com\",\"user_phone\":\"536373893\",\"permissions\":\"\",\"user_image\":\"1757742242.jpg\",\"user_type\":\"4\",\"notes\":null,\"user_id\":\"1\",\"added_by\":\"system\",\"created_at\":\"2025-09-13T05:44:03.000000Z\"}', '{\"user_name\":\"worker22\",\"user_email\":\"safirai36@gmail.com\",\"user_phone\":\"536373893\",\"permissions\":null,\"user_image\":\"1757745788.jpg\",\"user_type\":\"4\",\"notes\":null,\"user_id\":1,\"added_by\":\"system\"}', NULL, 'system', 2, '2025-09-13 01:43:08', '2025-09-13 01:43:08'),
(5, 'users', 7, 'update', '1', 1, '{\"user_name\":\"driver\",\"user_email\":\"hase@gmail.com\",\"user_phone\":\"91937980\",\"permissions\":\"\",\"user_image\":\"1757758408.jpg\",\"user_type\":\"3\",\"notes\":null,\"user_id\":\"1\",\"added_by\":\"system\",\"created_at\":\"2025-09-13T10:13:29.000000Z\"}', '{\"user_name\":\"driver\",\"user_email\":\"hase@gmail.com\",\"user_phone\":\"91937980\",\"permissions\":null,\"user_image\":\"1757758408.jpg\",\"user_type\":null,\"notes\":null,\"user_id\":1,\"added_by\":\"system\"}', NULL, 'system', 7, '2025-09-13 05:56:34', '2025-09-13 05:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_fare` varchar(255) DEFAULT NULL,
  `driver_availabe` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`, `location_fare`, `driver_availabe`, `notes`, `user_id`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'location', '120', '1', 'hello', '1', '1', NULL, '2025-09-13 01:44:40', '2025-09-13 01:44:40');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_22_175900_create_accounts_table', 1),
(5, '2025_02_22_175942_create_expensecats_table', 1),
(6, '2025_02_22_175948_create_expenses_table', 1),
(7, '2025_07_27_094139_create_locations_table', 1),
(8, '2025_07_27_094149_create_drivers_table', 1),
(9, '2025_07_27_094205_create_workers_table', 1),
(10, '2025_07_27_100422_create_histories_table', 1),
(11, '2025_08_02_193608_create_vouchers_table', 1),
(12, '2025_08_02_194319_create_packages_table', 1),
(13, '2025_08_03_073832_create_bookings_table', 1),
(14, '2025_08_04_122633_create_customers_table', 1),
(15, '2025_08_04_123946_create_booking_payments_table', 1),
(16, '2025_08_04_123958_create_booking_payment_expenses_table', 1),
(17, '2025_08_11_055300_create_services_table', 1),
(18, '2025_08_21_083842_create_visits_table', 1),
(19, '2025_08_25_072257_create_feedback_table', 1),
(20, '2025_09_09_000000_add_status_to_workers_table', 1),
(21, '2025_09_10_204825_add_location_id_to_visits_table', 1),
(22, '2025_09_11_021210_add_driver_id_to_visits_table', 1),
(23, '2025_09_11_023129_add_driver_status_to_visits_table', 1),
(24, '2025_06_30_161034_create_s_m_s_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `sessions` varchar(255) DEFAULT NULL,
  `package_price_4` varchar(255) DEFAULT NULL,
  `package_price_5` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `package_type` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package_name`, `sessions`, `package_price_4`, `package_price_5`, `notes`, `user_id`, `package_type`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'package 1 day a month', '4', '120', '130', NULL, '1', '2', 'SYSTEM', NULL, '2025-09-13 01:46:22', '2025-09-13 01:46:22');

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
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_fee` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `service_image` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('gfXsdkclCFemKcb4MGYywt2ZnsKrDWlacRboh63m', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRUdmamVrVEdxakxjem1FaGZGOXhjR2N6ZWNQcDlKUFFSaTdOYzlTSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kcml2ZXJfcGFnZS8xIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1757761924);

-- --------------------------------------------------------

--
-- Table structure for table `s_m_s`
--

CREATE TABLE `s_m_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sms` longtext NOT NULL,
  `sms_status` int(11) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `s_m_s`
--

INSERT INTO `s_m_s` (`id`, `sms`, `sms_status`, `added_by`, `updated_by`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '2LnZhdmK2YTZhtinINin2YTYudiy2YrYsiB7Y3VzdG9tZXJfbmFtZX3YjA0KDQrZhdmGINi02LHZg9ipIFNDUlVCINmE2KrZiNmB2YrYsSDYp9mE2LnZhdin2YTYqSDYp9mE2YXZhtiy2YTZitip2Iwg2YbZiNivINiq2LLZiNmK2K/Zg9mFINio2KrZgdin2LXZitmEINin2YTYrdis2LIg2KfZhNiu2KfYtdipINio2YPZhToNCg0K2KrYp9ix2YrYriDYp9mE2K3YrNiyOiB7Ym9va2luZ19kYXRlfQ0KDQrYsdmC2YUg2KfZhNit2KzYsjoge2Jvb2tpbmdfbm99DQoNCtin2LPZhSDYp9mE2LnYp9mF2YTYqToge3dvcmtlcl9uYW1lfQ0KDQrYqtin2LHZitiuINij2YjZhCDYstmK2KfYsdipOiB7dmlzaXRfZGF0ZX0NCg0K2KfZhNio2KfZgtipOiB7cGFja2FnZX0NCg0K2KfZhNmF2YjZgti5OiB7bG9jYXRpb259DQoNCti52K/YryDYp9mE2LLZitin2LHYp9iqOiB7dG90YWxfdmlzaXRzfQ0KDQrYp9mE2YXYr9ipOiB7ZHVyYXRpb259', NULL, 'haseeb', 'haseeb', '1', '2025-09-12 18:16:24', '2025-09-12 18:16:55'),
(2, '2LnZhdmK2YTZhtinINin2YTYudiy2YrYsiB7Y3VzdG9tZXJfbmFtZX3YjA0KDQrZhdmGINi02LHZg9ipIFNDUlVCINmE2KrZiNmB2YrYsSDYp9mE2LnZhdin2YTYqSDYp9mE2YXZhtiy2YTZitip2Iwg2YbZiNivINiq2LLZiNmK2K/Zg9mFINio2KrZgdin2LXZitmEINin2YTYrdis2LIg2KfZhNiu2KfYtdipINio2YPZhToNCg0K2KrYp9ix2YrYriDYp9mE2K3YrNiyOiB7Ym9va2luZ19kYXRlfQ0KDQrYsdmC2YUg2KfZhNit2KzYsjoge2Jvb2tpbmdfbm99DQoNCtin2LPZhSDYp9mE2LnYp9mF2YTYqToge3dvcmtlcl9uYW1lfQ0KDQrYqtin2LHZitiuINij2YjZhCDYstmK2KfYsdipOiB7dmlzaXRfZGF0ZX0NCg0K2KfZhNio2KfZgtipOiB7cGFja2FnZX0NCg0K2KfZhNmF2YjZgti5OiB7bG9jYXRpb259DQoNCti52K/YryDYp9mE2LLZitin2LHYp9iqOiB7dG90YWxfdmlzaXRzfQ0KDQrYp9mE2YXYr9ipOiB7ZHVyYXRpb259', 1, 'haseeb', 'haseeb', '1', '2025-09-12 18:33:54', '2025-09-12 18:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `permissions` text DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `user_email`, `user_phone`, `password`, `permissions`, `notes`, `user_image`, `user_type`, `user_id`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'haseeb', 'safirai26@gmail.com', '919370980', '$2y$12$KitNkqMtj7DfQRe5r5kM0eZ78cR7ETNJbAC4MtksWumfO0N0erBFy', '1,2,3,4,5,6,7', NULL, '1757745730.jpg', '1', '1', 'system', NULL, '2025-09-12 18:01:53', '2025-09-13 01:42:35'),
(2, 'worker22', 'safirai36@gmail.com', '536373893', '$2y$12$4xD3/Qa07i3fyTD3awdTuexuobcrZTFM7aWCY.TSGErh0TAyU0xpK', NULL, NULL, '1757745788.jpg', '4', '1', 'system', NULL, '2025-09-13 00:44:03', '2025-09-13 01:43:08'),
(4, 'adil', NULL, '73839393', '$2y$12$LurPsa7pkj1q7YA9I/4Vxe7v8TEryX2YLVKsHlP57sLzgo2YdREEm', '500', NULL, NULL, '10', NULL, 'adil', NULL, '2025-09-13 01:47:03', '2025-09-13 01:47:03'),
(5, 'admin', 'admin@gmail.com', '16178292', '$2y$12$ESp2UslDgyuSaN.JQV.7CuL7rSvkisUv1pGbPbVNpt2ojpxEmujd2', '1,2,3,4,5,6,7,8,9,10,11', NULL, '1757753170.jpg', NULL, '1', 'system', NULL, '2025-09-13 03:46:11', '2025-09-13 03:46:11'),
(6, 'qadeer', NULL, '91937980', '$2y$12$16us88AD/IWBMA6JFzLoleaYBMmvzyc7t0JhLjeJN/0FNVuR/f5Ca', '500', NULL, NULL, '10', NULL, 'qadeer', NULL, '2025-09-13 05:00:42', '2025-09-13 05:00:42'),
(7, 'driver', 'hase@gmail.com', '91937980', '$2y$12$FVuCbvKsTIVoNXTLvuCWbeSQ0i8um3yNfPA6xvdVbb3XHGnWpIbda', NULL, NULL, '1757758408.jpg', NULL, '1', 'system', NULL, '2025-09-13 05:13:29', '2025-09-13 05:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `location_id` varchar(255) DEFAULT NULL,
  `driver_id` varchar(255) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `visit_name` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `driver_status` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `booking_id`, `location_id`, `driver_id`, `worker_id`, `visit_date`, `shift`, `duration`, `visit_name`, `customer_id`, `added_by`, `updated_by`, `status`, `driver_status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '1', NULL, 1, '2025-09-20', 'morning', '4', 'B2025-1-v1', '1', 'adil', NULL, '1', '1', '4', '2025-09-13 01:46:51', '2025-09-13 01:47:04'),
(2, 1, '1', NULL, 1, '2025-09-21', 'morning', '4', 'B2025-1-v2', '1', 'adil', NULL, '1', '1', '4', '2025-09-13 01:46:51', '2025-09-13 01:47:04'),
(3, 1, '1', NULL, 1, '2025-09-22', 'morning', '4', 'B2025-1-v3', '1', 'adil', NULL, '1', '1', '4', '2025-09-13 01:46:51', '2025-09-13 01:47:04'),
(4, 1, '1', NULL, 1, '2025-09-23', 'morning', '4', 'B2025-1-v4', '1', 'adil', NULL, '1', '1', '4', '2025-09-13 01:46:51', '2025-09-13 01:47:04'),
(5, 2, '1', NULL, 1, '2025-09-14', 'Morning', '5', 'B2025-2-v1', '2', 'qadeer', NULL, '1', '1', '6', '2025-09-13 05:00:28', '2025-09-13 05:07:44'),
(6, 2, '1', NULL, 1, '2025-09-15', 'morning', '4', 'B2025-2-v2', '2', 'qadeer', NULL, '1', '1', '6', '2025-09-13 05:00:28', '2025-09-13 05:00:44'),
(7, 2, '1', NULL, 1, '2025-09-16', 'morning', '4', 'B2025-2-v3', '2', 'qadeer', NULL, '1', '1', '6', '2025-09-13 05:00:28', '2025-09-13 05:00:44'),
(8, 2, '1', NULL, 1, '2025-09-17', 'morning', '4', 'B2025-2-v4', '2', 'qadeer', NULL, '1', '1', '6', '2025-09-13 05:00:28', '2025-09-13 05:00:44');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_name` varchar(255) NOT NULL,
  `voucher_price` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `voucher_type` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `worker_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `worker_user_id` varchar(255) DEFAULT NULL,
  `location_id` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `worker_image` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `user_id` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `worker_name`, `phone`, `worker_user_id`, `location_id`, `notes`, `worker_image`, `shift`, `status`, `user_id`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'worker1', '73883393', '2', '1', NULL, '1757745926.jpg', '1', 'available', '1', 'system', NULL, '2025-09-13 01:45:26', '2025-09-13 01:45:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_worker_id_location_id_package_id_index` (`user_id`,`worker_id`,`location_id`,`package_id`),
  ADD KEY `bookings_status_index` (`status`);

--
-- Indexes for table `booking_payments`
--
ALTER TABLE `booking_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_payment_expenses`
--
ALTER TABLE `booking_payment_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expensecats`
--
ALTER TABLE `expensecats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `s_m_s`
--
ALTER TABLE `s_m_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_email_unique` (`user_email`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_payments`
--
ALTER TABLE `booking_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_payment_expenses`
--
ALTER TABLE `booking_payment_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expensecats`
--
ALTER TABLE `expensecats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `s_m_s`
--
ALTER TABLE `s_m_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
