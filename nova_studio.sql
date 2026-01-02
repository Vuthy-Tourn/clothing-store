-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 02, 2026 at 12:12 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `outfit_818_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carousels`
--

CREATE TABLE `carousels` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `button_link` varchar(2048) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousels`
--

INSERT INTO `carousels` (`id`, `title`, `description`, `image_path`, `button_text`, `button_link`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Summer Collection', 'Discover our new summer collections', 'carousels/d1X94Z6oSWsrLUBt2TXg6VjfwudArUIWFj5hsGk8.jpg', 'Shop Now', 'https://github.com/Vuthy-Tourn/clothing-store/blob/main/resources/views/admin/products/scripts.blade.php', 1, 0, '2025-12-12 18:18:21', '2025-12-30 16:24:39'),
(2, 'Winter Sale', 'Up to 50% off on winter wear', 'carousels/ZhOyK2AT3jHWFXAo8cH0JkwHDA7UkCYl8sCRbrin.jpg', 'Grab Deals', 'https://winter-sale', 1, 1, '2025-12-12 18:18:21', '2025-12-30 16:24:48');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
(16, 3, 3, 2, '2026-01-01 17:22:00', '2026-01-02 00:01:04'),
(17, 3, 5, 1, '2026-01-01 17:22:05', '2026-01-01 17:22:05');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` enum('men','women','unisex','kids') COLLATE utf8mb4_general_ci DEFAULT 'unisex',
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `gender`, `slug`, `image`, `description`, `parent_id`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(6, 'Pants', 'men', 'mens-pants', 'pants-men.jpg', NULL, NULL, 'active', 10, '2025-12-20 09:57:37', '2025-12-21 06:48:42'),
(7, 'Shirts', 'men', 'mens-shirts', 'shirts-men.jpg', NULL, NULL, 'active', 9, '2025-12-20 09:57:44', '2025-12-21 06:48:42'),
(8, 'T-Shirts', 'unisex', 'womens-t-shirts', 'tshirts-women.jpg', NULL, NULL, 'active', 7, '2025-12-20 09:58:07', '2025-12-21 06:48:42'),
(9, 'Dresses', 'women', 'womens-dresses', 'dresses-women.jpg', NULL, NULL, 'active', 8, '2025-12-20 09:57:51', '2025-12-21 06:48:42'),
(10, 'Tops', 'women', 'womens-tops', 'tops-women.jpg', NULL, 10, 'active', 6, '2025-12-20 09:57:58', '2025-12-21 06:48:42');

-- --------------------------------------------------------

--
-- Table structure for table `featured_products`
--

CREATE TABLE `featured_products` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tagline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discounted_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `button_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"9e40af6d-e73e-4eaf-9dbb-2a248c7648c0\",\"displayName\":\"App\\\\Mail\\\\AdminBulkEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:23:\\\"App\\\\Mail\\\\AdminBulkEmail\\\":4:{s:11:\\\"subjectLine\\\";s:16:\\\"HELLO new update\\\";s:11:\\\"bodyMessage\\\";s:10:\\\"come to us\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"xawet22315@lawior.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1765612570,\"delay\":null}', 0, NULL, 1765612570, 1765612570),
(2, 'default', '{\"uuid\":\"55fc1b5f-33bb-40f6-94c8-f4c85a4eb3a1\",\"displayName\":\"App\\\\Mail\\\\AdminBulkEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:23:\\\"App\\\\Mail\\\\AdminBulkEmail\\\":4:{s:11:\\\"subjectLine\\\";s:16:\\\"HELLO new update\\\";s:11:\\\"bodyMessage\\\";s:23:\\\"Come to us for shopping\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"xawet22315@lawior.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1765612625,\"delay\":null}', 0, NULL, 1765612625, 1765612625);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2025_07_11_170823_create_cache_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscriptions`
--

CREATE TABLE `newsletter_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `is_subscribed` tinyint(1) DEFAULT '1',
  `subscribed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_subscriptions`
--

INSERT INTO `newsletter_subscriptions` (`id`, `email`, `user_id`, `is_subscribed`, `subscribed_at`, `unsubscribed_at`, `created_at`, `updated_at`) VALUES
(1, 'xawet22315@lawior.com', 3, 1, '2025-12-13 06:19:50', NULL, '2025-12-13 06:19:50', '2025-12-13 06:19:50'),
(2, 'gedena7312@mekuron.com', 4, 1, '2025-12-19 08:38:14', NULL, '2025-12-19 08:38:14', '2025-12-19 08:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `order_status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `shipping_amount` decimal(10,2) DEFAULT '0.00',
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `shipping_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tracking_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `shipping_address_id` bigint UNSIGNED DEFAULT NULL,
  `billing_address_id` bigint UNSIGNED DEFAULT NULL,
  `customer_notes` text COLLATE utf8mb4_general_ci,
  `admin_notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `order_status`, `subtotal`, `tax_amount`, `shipping_amount`, `discount_amount`, `total_amount`, `payment_method`, `payment_status`, `payment_id`, `payment_date`, `shipping_method`, `tracking_number`, `estimated_delivery`, `delivered_at`, `shipping_address_id`, `billing_address_id`, `customer_notes`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 3, 'ORD-20251213-693D469B91D33', 'pending', 220.00, 17.60, 10.00, 0.00, 247.60, 'stripe', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-12-13 09:27:31', '2025-12-13 09:27:31'),
(2, 3, 'ORD-20251213-693D4702E90AF', 'pending', 220.00, 17.60, 10.00, 0.00, 247.60, 'stripe', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, 2, 2, NULL, NULL, '2025-12-13 09:29:14', '2025-12-13 09:29:14'),
(3, 3, 'ORD-20251213-693D47609625E', 'pending', 220.00, 17.60, 10.00, 0.00, 247.60, 'stripe', 'pending', 'cs_test_b15RNDIxD5LnmWgGeVKlnV4vzb3QDR7XI3O8IlfE9BOss0WFfc4MM1gTcm', NULL, NULL, NULL, NULL, NULL, 3, 3, NULL, NULL, '2025-12-13 09:30:48', '2025-12-13 09:30:49'),
(4, 3, 'ORD-20251213-693D487B3BF24', 'confirmed', 220.00, 17.60, 10.00, 0.00, 247.60, 'stripe', 'paid', 'cs_test_b1eZlFJrEAkB5roBaHT1h1snu9Un6ToFy8W4d2aXjsYR5mvxN1QsePr2wp', '2025-12-13 09:38:54', NULL, NULL, NULL, NULL, 4, 4, NULL, NULL, '2025-12-13 09:35:31', '2025-12-20 06:22:18'),
(5, 3, 'ORD-20251223-694A1C4CB6643', 'pending', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'pending', 'cs_test_b1ZfTojauUuGyZwXgpSbk9ehmuhHTPlsoUa5eAYrfVWYCpzVfPa4W9QpfQ', NULL, NULL, NULL, NULL, NULL, 5, 5, 'Anim placeat volupt', NULL, '2025-12-23 03:06:28', '2025-12-23 03:06:29'),
(6, 3, 'ORD-20251223-694A1C57A99B0', 'pending', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'pending', 'cs_test_b19HGzkuhP5wxmR2w40cL5x9s9raudhmE0OQrkgQtVLR2ZvJLGRzPNfCjs', NULL, NULL, NULL, NULL, NULL, 1, 1, 'Anim placeat volupt', NULL, '2025-12-23 03:06:39', '2025-12-23 03:06:40'),
(7, 3, 'ORD-20251223-694A1C584FE0F', 'pending', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'pending', 'cs_test_b1ACbPV3LLlWqanIWamPdJtvbPlB5cmH6utDuDRzeifMuw5LE3lBAINs0l', NULL, NULL, NULL, NULL, NULL, 1, 1, 'Anim placeat volupt', NULL, '2025-12-23 03:06:40', '2025-12-23 03:06:40'),
(8, 3, 'ORD-20251223-694A1C58EAC3A', 'pending', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'pending', 'cs_test_b1WZKt3hzrmfKBl8oWgPvxcL2YnWWHHLTbFz8aPErK5VDlilRo7YTgnfXs', NULL, NULL, NULL, NULL, NULL, 1, 1, 'Anim placeat volupt', NULL, '2025-12-23 03:06:40', '2025-12-23 03:06:41'),
(9, 3, 'ORD-20251223-694A1C599C64D', 'pending', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'pending', 'cs_test_b1D5ngd8Xl1YHalnuJYBqblUUte9kc5u1ztbtiUCieAGVekU2OOoYT9Z88', NULL, NULL, NULL, NULL, NULL, 1, 1, 'Anim placeat volupt', NULL, '2025-12-23 03:06:41', '2025-12-23 03:06:42'),
(10, 3, 'ORD-20251223-694A1C5A4CF53', 'pending', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'pending', 'cs_test_b1hvAc1RqKqoxzFvtsSyPQ124J3C6dMO362RArRSNwXRyQXjga8yEyRBCl', NULL, NULL, NULL, NULL, NULL, 1, 1, 'Anim placeat volupt', NULL, '2025-12-23 03:06:42', '2025-12-23 03:06:42'),
(11, 3, 'ORD-20251223-694A1D4DD0E16', 'confirmed', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'paid', 'cs_test_b1IhXPTXK4H7t7GQUUydQOmxTskbPndYCXVzJd2JNNmtsdEZJQ1RPzeJ64', '2026-01-01 10:02:48', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-12-23 03:10:45', '2026-01-01 10:02:48'),
(12, 3, 'ORD-20260102-695721005A714', 'pending', 208.00, 16.64, 0.00, 0.00, 224.64, 'stripe', 'pending', 'cs_test_b1pKughhhL98dpcpUaAkHGZXh4RrC5P8GZJj9Bvt4vD8GlZ6r8kf0c15n3', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2026-01-02 00:06:00', '2026-01-02 00:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `variant_details` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_variant_id`, `product_name`, `variant_details`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Cruz Hendrix', '\"{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Quia esse sed libero-V1\\\"}\"', 1, 220.00, 220.00, '2025-12-13 09:27:31', '2025-12-13 09:27:31'),
(2, 2, 2, 'Cruz Hendrix', '\"{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Quia esse sed libero-V1\\\"}\"', 1, 220.00, 220.00, '2025-12-13 09:29:14', '2025-12-13 09:29:14'),
(3, 3, 2, 'Cruz Hendrix', '\"{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Quia esse sed libero-V1\\\"}\"', 1, 220.00, 220.00, '2025-12-13 09:30:48', '2025-12-13 09:30:48'),
(4, 4, 2, 'Cruz Hendrix', '\"{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Quia esse sed libero-V1\\\"}\"', 1, 220.00, 220.00, '2025-12-13 09:35:31', '2025-12-13 09:35:31'),
(5, 5, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:06:28', '2025-12-23 03:06:28'),
(6, 6, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:06:39', '2025-12-23 03:06:39'),
(7, 7, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:06:40', '2025-12-23 03:06:40'),
(8, 8, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:06:40', '2025-12-23 03:06:40'),
(9, 9, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:06:41', '2025-12-23 03:06:41'),
(10, 10, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:06:42', '2025-12-23 03:06:42'),
(11, 11, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:10:45', '2025-12-23 03:10:45'),
(12, 12, 3, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-V1\\\",\\\"original_price\\\":\\\"77.00\\\",\\\"discounted_price\\\":77,\\\"savings\\\":0,\\\"has_discount\\\":false,\\\"discount_percentage\\\":0}\"', 2, 77.00, 154.00, '2026-01-02 00:06:00', '2026-01-02 00:06:00'),
(13, 12, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\",\\\"original_price\\\":\\\"60.00\\\",\\\"discounted_price\\\":54,\\\"savings\\\":6,\\\"has_discount\\\":true,\\\"discount_percentage\\\":10}\"', 1, 54.00, 54.00, '2026-01-02 00:06:00', '2026-01-02 00:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('xawet22315@lawior.com', '$2y$12$6BveBGB5SazSSIUd6pPCHeSCSr2oJFkMeyfTS1OlZFw3sTYdCAKcW', '2025-12-28 10:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `brand` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `material` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','inactive','draft') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_new` tinyint(1) DEFAULT '0',
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_start` datetime DEFAULT NULL,
  `discount_end` datetime DEFAULT NULL,
  `has_discount` tinyint(1) DEFAULT '0',
  `rating_cache` decimal(3,2) DEFAULT '0.00',
  `review_count` int DEFAULT '0',
  `view_count` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `description`, `brand`, `material`, `status`, `is_featured`, `is_new`, `discount_type`, `discount_value`, `discount_start`, `discount_end`, `has_discount`, `rating_cache`, `review_count`, `view_count`, `created_at`, `updated_at`) VALUES
(2, 'Cruz Hendrix', 'cruz-hendrix', 7, 'Dolorem suscipit acc', 'Aperiam iste autem v', NULL, 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 5.00, 1, 152, '2025-12-13 07:16:08', '2026-01-02 01:11:00'),
(3, 'Janna Mcfarland', 'janna-mcfarland', 7, 'Perferendis quod rei', 'Adipisicing voluptat', 'cotton', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, 206, '2025-12-13 11:12:42', '2026-01-02 10:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `alt_text`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
(20, 3, 'products/images/69UaOWYV0fqbVpwbHceBw5A4mDFFH8eXIjiJWLio.webp', NULL, 1, 1, '2025-12-28 12:08:50', '2026-01-02 01:07:43'),
(21, 3, 'products/images/4Cvtv0aX2zZUf851pSvpg7LyNBiFAosMLBWRXNUJ.webp', NULL, 0, 1, '2025-12-28 12:09:07', '2026-01-02 01:07:43'),
(22, 3, 'products/images/8VkN5wBiH7OkmnXXjGmR7NrIHMA2365chMgBGvul.webp', NULL, 0, 2, '2025-12-28 12:09:07', '2026-01-02 01:07:43'),
(23, 2, 'products/images/nlgTaoSS6zBgCyYvoZfzM1i2HR5XnTdRIjPMJHkt.webp', NULL, 1, 1, '2025-12-28 12:18:01', '2026-01-02 01:11:00'),
(28, 2, 'products/images/HFiUjOB5JHn29Zkf9ML90b9uUy2bF2np4INURQUI.webp', NULL, 0, 1, '2026-01-02 01:11:00', '2026-01-02 01:11:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `is_approved` tinyint(1) DEFAULT '0',
  `helpful_count` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `comment`, `is_approved`, `helpful_count`, `created_at`, `updated_at`) VALUES
(1, 2, 3, NULL, 5, NULL, 'best material', 1, 0, '2025-12-23 10:10:15', '2025-12-23 10:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `size` enum('XS','S','M','L','XL','XXL','XXXL','FREE') COLLATE utf8mb4_general_ci NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `color_code` varchar(7) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_start` datetime DEFAULT NULL,
  `discount_end` datetime DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `stock_alert` int DEFAULT '10',
  `weight` decimal(8,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `has_discount` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `size`, `color`, `color_code`, `price`, `sale_price`, `discount_type`, `discount_value`, `discount_start`, `discount_end`, `cost_price`, `stock`, `stock_alert`, `weight`, `is_active`, `created_at`, `updated_at`, `has_discount`) VALUES
(2, 2, 'Quia esse sed libero-V1', 'L', 'White', '#000000', 220.00, NULL, NULL, NULL, NULL, NULL, NULL, 9, 10, NULL, 1, '2025-12-13 07:16:08', '2026-01-01 08:27:11', 0),
(3, 3, 'Non culpa do iste qu-V1', 'L', 'Black', '#000000', 77.00, NULL, NULL, NULL, NULL, NULL, NULL, 27, 10, NULL, 1, '2025-12-13 11:12:42', '2026-01-02 01:06:59', 0),
(4, 3, 'Non culpa do iste qu-V2', 'XL', 'White', '#ffffff', 80.00, NULL, NULL, NULL, NULL, NULL, NULL, 10, 10, NULL, 1, '2025-12-13 11:12:42', '2026-01-02 01:06:59', 0),
(5, 3, 'Non culpa do iste qu-S-WHI', 'S', 'Black', '#000000', 60.00, NULL, 'percentage', 10.00, '2025-12-29 14:31:00', '2026-01-11 14:31:00', NULL, 20, 10, NULL, 1, '2025-12-13 11:36:55', '2026-01-02 01:07:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Bi4tDYXin8uATnzhXU2FCT6kwGTQ2l2E2AvNhyNL', 3, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid25Za2VQSGc3Rm1xbXRKQXQ0bWRMT0tuQ1VBTnlscGlhZ2lzWmtpRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vcmRlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1767355678);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `account_type` enum('customer','vendor','admin','staff') COLLATE utf8mb4_general_ci DEFAULT 'customer',
  `user_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `customer_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `loyalty_points` int DEFAULT '0',
  `newsletter_opt_in` tinyint(1) DEFAULT '1',
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `default_address_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `otp_code`, `account_type`, `user_type`, `customer_id`, `loyalty_points`, `newsletter_opt_in`, `phone`, `profile_picture`, `dob`, `gender`, `is_verified`, `is_active`, `last_login_at`, `default_address_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'team.novastudio@gmail.com', '2025-12-22 16:37:27', '$2y$12$JyHZKpSMqCCGlGJgNFVBjOFTEpvZ8HzDy.KSzL.9kukXOuTgRxg56', NULL, NULL, 'admin', 'admin', NULL, 0, 1, '1234567890', NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-12-12 18:18:21', '2025-12-12 18:18:21'),
(2, 'Leon Lii', 'xawet22315@lawior.com', '2025-12-30 16:27:25', '$2y$12$xC4R3YF0zGTX/hqWn8uAF.9f6klG6j3a6EdAspRNhxyUkaVqbnYpa', NULL, NULL, 'customer', 'user', NULL, 0, 1, '012300104', NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-12-28 11:29:49', '2025-12-30 16:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('shipping','billing') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'shipping',
  `address_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `address_line1` text COLLATE utf8mb4_general_ci NOT NULL,
  `address_line2` text COLLATE utf8mb4_general_ci,
  `city` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'United States',
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `type`, `address_name`, `full_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `zip_code`, `country`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 3, 'shipping', 'Primary Address', 'Vuthy', '012300203', 'Phnom Penh 168', NULL, 'phnom penh', 'PP', '120102', 'Cambodia', 1, '2025-12-13 09:27:31', '2025-12-30 15:58:54'),
(2, 3, 'shipping', 'Home', 'admin', '012300203', 'Phnom Penh 123', NULL, 'phnom penh', 'PP', '120101', 'Cambodia', 0, '2025-12-13 09:29:14', '2025-12-30 15:58:54'),
(3, 3, 'shipping', 'Home', 'admin', '012300203', 'Phnom Penh 123', NULL, 'phnom penh', 'PP', '120101', 'Cambodia', 0, '2025-12-13 09:30:48', '2025-12-30 15:58:54'),
(4, 3, 'shipping', 'Home', 'admin', '012300203', 'Phnom Penh 123', NULL, 'phnom penh', 'PP', '120101', 'Cambodia', 0, '2025-12-13 09:35:31', '2025-12-30 15:58:54'),
(5, 3, 'shipping', 'Home', 'Olga Dickson', '012993744', 'Proident incididunt', 'Et minim qui omnis l', 'Ut delectus est vo', 'At accusantium enim', '98509', 'Australia', 0, '2025-12-23 03:06:28', '2025-12-30 15:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `carousels`
--
ALTER TABLE `carousels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carousels_is_active_index` (`is_active`),
  ADD KEY `carousels_sort_order_index` (`sort_order`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_product_unique` (`user_id`,`product_variant_id`),
  ADD KEY `cart_items_user_id_index` (`user_id`),
  ADD KEY `cart_items_product_variant_id_index` (`product_variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_index` (`parent_id`),
  ADD KEY `categories_gender_index` (`gender`),
  ADD KEY `categories_gender_parent_index` (`gender`,`parent_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscriptions`
--
ALTER TABLE `newsletter_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsletter_subscriptions_email_unique` (`email`),
  ADD KEY `newsletter_subscriptions_user_id_index` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_index` (`user_id`),
  ADD KEY `orders_order_status_index` (`order_status`),
  ADD KEY `orders_payment_status_index` (`payment_status`),
  ADD KEY `orders_created_at_index` (`created_at`),
  ADD KEY `orders_shipping_address_id_foreign` (`shipping_address_id`),
  ADD KEY `orders_billing_address_id_foreign` (`billing_address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_product_variant_id_index` (`product_variant_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_index` (`category_id`),
  ADD KEY `products_is_featured_index` (`is_featured`),
  ADD KEY `products_status_index` (`status`),
  ADD KEY `products_brand_index` (`brand`),
  ADD KEY `products_discount_active_index` (`discount_start`,`discount_end`,`has_discount`,`status`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_index` (`product_id`),
  ADD KEY `product_images_is_primary_index` (`is_primary`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_reviews_user_product_order_unique` (`user_id`,`product_id`,`order_id`),
  ADD KEY `product_reviews_product_id_index` (`product_id`),
  ADD KEY `product_reviews_user_id_index` (`user_id`),
  ADD KEY `product_reviews_rating_index` (`rating`),
  ADD KEY `product_reviews_is_approved_index` (`is_approved`),
  ADD KEY `product_reviews_order_id_foreign` (`order_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD UNIQUE KEY `product_variants_unique_variant` (`product_id`,`size`,`color`),
  ADD KEY `product_variants_product_id_index` (`product_id`),
  ADD KEY `product_variants_discount_active_index` (`discount_start`,`discount_end`,`is_active`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_customer_id_unique` (`customer_id`),
  ADD KEY `users_account_type_index` (`account_type`),
  ADD KEY `users_default_address_id_index` (`default_address_id`),
  ADD KEY `users_is_active_index` (`is_active`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_addresses_user_id_index` (`user_id`),
  ADD KEY `user_addresses_is_default_index` (`is_default`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_product_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_user_id_index` (`user_id`),
  ADD KEY `wishlists_product_id_index` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carousels`
--
ALTER TABLE `carousels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `newsletter_subscriptions`
--
ALTER TABLE `newsletter_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `newsletter_subscriptions`
--
ALTER TABLE `newsletter_subscriptions`
  ADD CONSTRAINT `newsletter_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_billing_address_id_foreign` FOREIGN KEY (`billing_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_shipping_address_id_foreign` FOREIGN KEY (`shipping_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_default_address_id_foreign` FOREIGN KEY (`default_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
