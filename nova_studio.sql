-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 07, 2026 at 02:20 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database
CREATE DATABASE IF NOT EXISTS nova_studio_db;
USE nova_studio_db;

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

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` enum('men','women','unisex','kids') COLLATE utf8mb4_general_ci DEFAULT 'unisex',
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
(8, 'T-Shirts', 'kids', 'womens-t-shirts', 'tshirts-women.jpg', NULL, NULL, 'active', 7, '2025-12-20 09:58:07', '2026-01-07 07:56:06'),
(9, 'Dresses', 'women', 'womens-dresses', 'dresses-women.jpg', NULL, NULL, 'active', 8, '2025-12-20 09:57:51', '2025-12-21 06:48:42'),
(10, 'Tops', 'women', 'womens-tops', 'tops-women.jpg', NULL, 10, 'active', 6, '2025-12-20 09:57:58', '2025-12-21 06:48:42'),
(11, 'Jackets', 'men', 'mens-jackets', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800', 'Stylish jackets and outerwear for men', NULL, 'active', 5, '2026-01-06 02:00:00', '2026-01-06 02:00:00'),
(13, 'Skirts', 'women', 'womens-skirts', 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?w=800', 'Elegant and casual skirts for women', NULL, 'active', 3, '2026-01-06 02:10:00', '2026-01-06 02:10:00'),
(15, 'Sweaters', 'men', 'sweaters', 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800', 'Cozy sweaters and knitwear', NULL, 'active', 1, '2026-01-06 02:20:00', '2026-01-06 16:42:14'),
(16, 'Shorts', 'men', 'shorts', 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800', 'Comfortable shorts for casual wear', NULL, 'active', 0, '2026-01-06 02:25:00', '2026-01-06 16:17:37'),
(17, 'Shorts', 'women', 'shorts-1', 'categories/22salKcpwqebDcivHe3EQJolXMqFs4Y88llXefNx.webp', NULL, NULL, 'active', 11, '2026-01-06 16:43:15', '2026-01-06 16:43:15'),
(18, 'Jackets', 'women', 'jackets', NULL, NULL, NULL, 'active', 12, '2026-01-07 07:48:23', '2026-01-07 07:48:23'),
(19, 'Sweaters', 'kids', 'kids-sweaters', NULL, NULL, NULL, 'active', 13, '2026-01-07 07:54:34', '2026-01-07 09:15:48'),
(20, 'Shirts', 'kids', 'shirts', NULL, NULL, NULL, 'active', 14, '2026-01-07 08:00:33', '2026-01-07 08:00:33'),
(21, 'Jackets', 'kids', 'kids-jackets', NULL, NULL, NULL, 'active', 15, '2026-01-07 09:21:13', '2026-01-07 09:22:48');

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
(3, 3, 'ORD-20251213-693D47609625E', 'pending', 220.00, 17.60, 10.00, 0.00, 247.60, 'stripe', 'pending', 'cs_test_b15RNDIxD5LnmWgGeVKlnV4vzb3QDR7XI3O8IlfE9BOss0WFfc4MM1gTcm', NULL, NULL, NULL, NULL, NULL, 3, 3, NULL, NULL, '2025-12-13 09:30:48', '2025-12-13 09:30:49'),
(4, 3, 'ORD-20251213-693D487B3BF24', 'confirmed', 220.00, 17.60, 10.00, 0.00, 247.60, 'stripe', 'paid', 'cs_test_b1eZlFJrEAkB5roBaHT1h1snu9Un6ToFy8W4d2aXjsYR5mvxN1QsePr2wp', '2025-12-13 09:38:54', NULL, NULL, NULL, NULL, 4, 4, NULL, NULL, '2025-12-13 09:35:31', '2025-12-20 06:22:18'),
(6, 3, 'ORD-20251223-694A1C57A99B0', 'pending', 60.00, 4.80, 10.00, 0.00, 74.80, 'stripe', 'pending', 'cs_test_b19HGzkuhP5wxmR2w40cL5x9s9raudhmE0OQrkgQtVLR2ZvJLGRzPNfCjs', NULL, NULL, NULL, NULL, NULL, 1, 1, 'Anim placeat volupt', NULL, '2025-12-23 03:06:39', '2025-12-23 03:06:40'),
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
(3, 3, 2, 'Cruz Hendrix', '\"{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Quia esse sed libero-V1\\\"}\"', 1, 220.00, 220.00, '2025-12-13 09:30:48', '2025-12-13 09:30:48'),
(4, 4, 2, 'Cruz Hendrix', '\"{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Quia esse sed libero-V1\\\"}\"', 1, 220.00, 220.00, '2025-12-13 09:35:31', '2025-12-13 09:35:31'),
(6, 6, 5, 'Janna Mcfarland', '\"{\\\"size\\\":\\\"S\\\",\\\"color\\\":\\\"White\\\",\\\"sku\\\":\\\"Non culpa do iste qu-S-WHI\\\"}\"', 1, 60.00, 60.00, '2025-12-23 03:06:39', '2025-12-23 03:06:39'),
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
(2, 'Cruz Hendrix', 'cruz-hendrix', 7, 'Dolorem suscipit acc', 'ZARA', NULL, 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 5.00, 1, 157, '2025-12-13 07:16:08', '2026-01-07 12:16:10'),
(3, 'Janna Mcfarland', 'janna-mcfarland', 7, 'Perferendis quod rei', 'ZARA', 'cotton', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, 213, '2025-12-13 11:12:42', '2026-01-07 12:15:33'),
(4, 'Classic Denim Jeans', 'classic-denim-jeans', 6, 'Comfortable straight-fit denim jeans perfect for everyday wear', 'Urban Style Co', 'denim', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 4.50, 12, 342, '2026-01-06 01:00:00', '2026-01-07 11:52:25'),
(5, 'Slim Fit Chinos', 'slim-fit-chinos', 6, 'Modern slim-fit chinos in versatile colors', 'Premium Threads', 'cotton-blend', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.20, 8, 158, '2026-01-06 01:05:00', '2026-01-07 11:41:14'),
(6, 'Oxford Button-Down Shirt', 'oxford-button-down-shirt', 20, 'Classic oxford shirt with button-down collar', 'Gentleman\'s Choice', 'cotton', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 4.80, 15, 423, '2026-01-06 01:10:00', '2026-01-07 08:16:26'),
(7, 'Striped Polo Shirt', 'striped-polo-shirt', 20, 'Casual polo shirt with horizontal stripes', 'SportLine', 'cotton-polyester', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.30, 6, 189, '2026-01-06 01:15:00', '2026-01-07 08:00:43'),
(8, 'Graphic Print T-Shirt', 'graphic-print-t-shirt', 8, 'Trendy graphic print t-shirt for casual occasions', 'StreetWear Hub', 'cotton', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.10, 9, 267, '2026-01-06 01:20:00', '2026-01-07 07:58:07'),
(9, 'Basic Cotton Tee', 'basic-cotton-tee', 8, 'Essential cotton t-shirt in solid colors', 'Basics Daily', 'cotton', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 4.60, 24, 513, '2026-01-06 01:25:00', '2026-01-07 07:37:08'),
(10, 'Floral Summer Dress', 'floral-summer-dress', 9, 'Light and breezy floral print dress for summer', 'Blossom Fashion', 'rayon', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.70, 18, 399, '2026-01-06 01:30:00', '2026-01-07 09:07:22'),
(11, 'Elegant Evening Dress', 'elegant-evening-dress', 9, 'Sophisticated evening dress for special occasions', 'Luxe Couture', 'silk-blend', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 4.90, 7, 235, '2026-01-06 01:35:00', '2026-01-07 07:28:18'),
(12, 'ZIP-UP MIDI DRESS', 'zip-up-midi-dress', 9, 'Comfortable tank top for layering or solo wear', 'EasyWear', 'cotton-spandex', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.00, 11, 178, '2026-01-06 01:40:00', '2026-01-07 07:25:50'),
(13, 'Ruffled Blouse', 'ruffled-blouse', 10, 'Feminine blouse with elegant ruffle details', 'Chic Style', 'polyester', 'active', 0, 0, NULL, NULL, NULL, NULL, 0, 4.40, 13, 301, '2026-01-06 01:45:00', '2026-01-07 07:22:11'),
(14, 'Leather Bomber Jacket', 'leather-bomber-jacket', 18, 'Classic bomber jacket in genuine leather', 'Heritage Leather', 'leather', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 4.80, 22, 567, '2026-01-06 02:00:00', '2026-01-07 07:53:32'),
(15, 'Denim Jacket', 'denim-jacket', 11, 'Versatile denim jacket for all seasons', 'Blue Vintage', 'denim', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.40, 14, 291, '2026-01-06 02:02:00', '2026-01-07 12:12:53'),
(16, 'Windbreaker', 'windbreaker', 18, 'Lightweight windbreaker for outdoor activities', 'ActiveSport', 'nylon', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.10, 9, 178, '2026-01-06 02:03:00', '2026-01-07 07:53:49'),
(20, 'Pleated Midi Skirt', 'pleated-midi-skirt', 13, 'Elegant pleated skirt in midi length', 'Feminine Touch', 'polyester', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.50, 16, 423, '2026-01-06 02:10:00', '2026-01-07 07:03:40'),
(21, 'Denim Mini Skirt', 'denim-mini-skirt', 13, 'Casual denim mini skirt', 'Casual Chic', 'denim', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.20, 12, 268, '2026-01-06 02:11:00', '2026-01-07 10:06:15'),
(22, 'Pencil Skirt', 'pencil-skirt', 13, 'Professional pencil skirt for office wear', 'Office Elegance', 'cotton-blend', 'active', 0, 0, NULL, NULL, NULL, NULL, 0, 4.40, 20, 389, '2026-01-06 02:12:00', '2026-01-06 02:12:00'),
(26, 'Cable Knit Sweater', 'cable-knit-sweater', 19, 'Classic cable knit sweater', 'Knit Masters', 'wool-blend', 'active', 0, 0, NULL, NULL, NULL, NULL, 0, 4.80, 28, 678, '2026-01-06 02:20:00', '2026-01-07 07:54:53'),
(27, 'Cardigan', 'cardigan', 15, 'Comfortable button-up cardigan', 'Comfort Knits', 'cotton-acrylic', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.40, 17, 414, '2026-01-06 02:21:00', '2026-01-07 12:47:47'),
(28, 'HIGH NECK JUMPER', 'high-neck-jumper', 15, 'Elegant turtleneck sweater', 'ZARA', 'merino-wool', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.60, 21, 524, '2026-01-06 02:22:00', '2026-01-06 17:12:10'),
(29, 'Cargo Shorts', 'cargo-shorts', 16, 'Practical cargo shorts with multiple pockets', 'Outdoor Gear', 'cotton-canvas', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.20, 15, 346, '2026-01-06 02:25:00', '2026-01-06 17:04:55'),
(31, 'Denim Shorts', 'denim-shorts', 16, 'Classic denim shorts for summer', 'Summer Vibes', 'denim', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.30, 19, 495, '2026-01-06 02:27:00', '2026-01-07 12:08:01'),
(33, 'Denim Jacket kids', 'denim-jacket-kids', 21, 'Versatile denim jacket for all seasons', 'Blue Vintage', 'denim', 'active', 1, 0, NULL, NULL, NULL, NULL, 0, 4.40, 14, 289, '2026-01-06 02:02:00', '2026-01-07 09:45:57'),
(34, 'Windbreaker-Kid', 'windbreaker-kid', 21, 'Lightweight windbreaker for outdoor activities', 'ActiveSport', 'nylon', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.10, 9, 178, '2026-01-06 02:03:00', '2026-01-07 09:41:56'),
(36, 'STRIPED CHUNKY KNIT CARDIGAN', 'striped-chunky-knit-cardigan', 19, 'Comfortable button-up cardigan', 'Comfort Knits', 'cotton-acrylic', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.40, 17, 412, '2026-01-06 02:21:00', '2026-01-07 09:47:42'),
(37, 'Turtleneck Sweater', 'turtleneck-sweater', 19, 'Elegant turtleneck sweater', 'Sophisticate', 'merino-wool', 'active', 0, 1, NULL, NULL, NULL, NULL, 0, 4.60, 21, 523, '2026-01-06 02:22:00', '2026-01-07 09:37:43');

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
(20, 3, 'products/images/69UaOWYV0fqbVpwbHceBw5A4mDFFH8eXIjiJWLio.webp', NULL, 0, 1, '2025-12-28 12:08:50', '2026-01-07 12:15:54'),
(21, 3, 'products/images/4Cvtv0aX2zZUf851pSvpg7LyNBiFAosMLBWRXNUJ.webp', NULL, 0, 1, '2025-12-28 12:09:07', '2026-01-07 12:15:54'),
(22, 3, 'products/images/8VkN5wBiH7OkmnXXjGmR7NrIHMA2365chMgBGvul.webp', NULL, 0, 2, '2025-12-28 12:09:07', '2026-01-07 12:15:54'),
(23, 2, 'products/images/nlgTaoSS6zBgCyYvoZfzM1i2HR5XnTdRIjPMJHkt.webp', NULL, 0, 1, '2025-12-28 12:18:01', '2026-01-07 12:16:34'),
(94, 31, 'products/images/zEj9m6yxCujhVzJu2th0BmX76E07it6IclmzoIRW.jpg', NULL, 1, 1, '2026-01-06 16:05:57', '2026-01-07 10:06:47'),
(95, 31, 'products/images/qZPSCZqrjMgEWkv3mO9OV4E0S9QMjIDInh0L4Wse.webp', NULL, 0, 1, '2026-01-06 16:17:56', '2026-01-07 10:06:47'),
(96, 31, 'products/images/PKlYmwRNoGuOqM1IKs3LQcDSsLLF165TfCLxTYo2.webp', NULL, 0, 2, '2026-01-06 16:17:56', '2026-01-07 10:06:47'),
(97, 29, 'products/images/KCrUTCD2AZ34ji2s8wsgP3BkN0Cu4MWe0MfBbQR6.webp', NULL, 1, 1, '2026-01-06 16:24:55', '2026-01-06 16:25:03'),
(98, 29, 'products/images/mLH1iAMBbQowp9ENwxhcdTRkAAOyEApoX4VtRnbT.webp', NULL, 0, 2, '2026-01-06 16:24:55', '2026-01-06 16:25:03'),
(99, 29, 'products/images/SZSz9iVbbRAPJj6YmRVAn1SR1xFgLcF1CLoEcqkF.webp', NULL, 0, 3, '2026-01-06 16:24:55', '2026-01-06 16:25:03'),
(100, 28, 'products/images/a9koKKS0FgUJWKahP3JOVg7GBq3JF5DuDjUlMNtS.jpg', NULL, 1, 1, '2026-01-06 16:40:35', '2026-01-06 17:11:31'),
(101, 28, 'products/images/27Nxoz2H1lrPnrDXU4ugLVeVC3Wq2rm970Do1odo.jpg', NULL, 0, 2, '2026-01-06 16:40:35', '2026-01-06 17:11:31'),
(102, 28, 'products/images/vwWJoSNN5AQaPpbuPygTQKQLPrpBsryA4uSO0Qhe.jpg', NULL, 0, 3, '2026-01-06 16:40:35', '2026-01-06 17:11:31'),
(103, 2, 'products/images/AmFkVznmzl1nYtih830Ug67txboNmB3d7s5pDsBN.webp', NULL, 0, 1, '2026-01-06 16:57:37', '2026-01-07 12:16:34'),
(104, 2, 'products/images/x1rKG4QwomiMuwbKwo24V8jNroGQ2RFW7VeUhnBe.jpg', NULL, 1, 1, '2026-01-06 17:03:06', '2026-01-07 12:16:34'),
(105, 3, 'products/images/6mtIEWFdoBSpmY8ygIyScwEailPVgMWuYdncegoS.jpg', NULL, 1, 1, '2026-01-06 17:04:00', '2026-01-07 12:15:54'),
(106, 27, 'products/images/CCjFOq8gSmrt0MAC3gar2U0wXQfWHjZVcYMLuz1d.jpg', NULL, 1, 1, '2026-01-07 06:40:40', '2026-01-07 06:40:51'),
(107, 27, 'products/images/cbChJtrCYuizocY6jGKsgb044L0x6HWhYECiA4AA.jpg', NULL, 0, 2, '2026-01-07 06:40:40', '2026-01-07 06:40:51'),
(108, 27, 'products/images/9W7VMEZmAOw202nODqAUWv1PVecSzRPZZFxcEAOf.jpg', NULL, 0, 3, '2026-01-07 06:40:40', '2026-01-07 06:40:51'),
(109, 26, 'products/images/GIUZCwlOsKZ5PuY8TqQ1XVxAuII8dd9xuiooXSzV.jpg', NULL, 1, 1, '2026-01-07 06:50:41', '2026-01-07 07:54:53'),
(110, 26, 'products/images/LrP2xSXpzgQVsq9UjGc4XvPj8PlINl45KKcAuvSU.jpg', NULL, 0, 2, '2026-01-07 06:50:41', '2026-01-07 07:54:53'),
(111, 26, 'products/images/NgWp1xh2naChBxMjNb4iXn93MmDEJ40qHO7d0uYK.jpg', NULL, 0, 3, '2026-01-07 06:50:41', '2026-01-07 07:54:53'),
(112, 22, 'products/images/2EoppE2zQGBbnXUqFwLO5c50VH8lMEWAw5fpWwn8.jpg', NULL, 1, 1, '2026-01-07 06:59:00', '2026-01-07 06:59:08'),
(113, 22, 'products/images/J3yDXFk5jLJsYtkcKq3WutZzk9PTAH9i9jZA5Ykh.jpg', NULL, 0, 2, '2026-01-07 06:59:00', '2026-01-07 06:59:08'),
(114, 22, 'products/images/shZnHs12kQpRhcy3FalaShCYWKI7Dj8qFd3mlkOz.jpg', NULL, 0, 3, '2026-01-07 06:59:00', '2026-01-07 06:59:08'),
(115, 21, 'products/images/skuoin8ThLg7Mxs5RDZzHgrTKg3LeNyiMfiuMspy.jpg', NULL, 1, 1, '2026-01-07 07:01:07', '2026-01-07 07:01:15'),
(116, 21, 'products/images/Z9iT6jX9EQeAVQyS1ejiiEW4kIkH41zTcV7Nd9oF.jpg', NULL, 0, 2, '2026-01-07 07:01:07', '2026-01-07 07:01:15'),
(117, 21, 'products/images/42XkIcDWUDKtC1D7iXvQyYbndMvJYupGw6rwHBHD.jpg', NULL, 0, 3, '2026-01-07 07:01:07', '2026-01-07 07:01:15'),
(118, 20, 'products/images/OczxxsPR3OrqKTghCNaMuvgh51fwfGXCXgguoxxg.jpg', NULL, 1, 1, '2026-01-07 07:03:40', '2026-01-07 07:03:48'),
(119, 20, 'products/images/r7cGrLSeSMIZdWqMi15GTkRBSLgpTulPQmYNTRMH.jpg', NULL, 0, 2, '2026-01-07 07:03:40', '2026-01-07 07:03:48'),
(120, 20, 'products/images/oqMqFbswcWeDSX0Sad8EHQWUeOdRW2sT9Gayk3a7.jpg', NULL, 0, 3, '2026-01-07 07:03:40', '2026-01-07 07:03:48'),
(121, 15, 'products/images/P11Bk2zzNvQuatUNDBk1ONaotov2BKLg3kuwflEx.jpg', NULL, 1, 1, '2026-01-07 07:06:11', '2026-01-07 07:06:36'),
(122, 15, 'products/images/AXhCekG2CsIyqyuLGGwfmDPhaITGxq60K6dBeBoV.jpg', NULL, 0, 2, '2026-01-07 07:06:11', '2026-01-07 07:06:36'),
(123, 15, 'products/images/2ka7xSkU7ImbyQqPOd4TN0qhEcrFglZ8s3be7x6V.jpg', NULL, 0, 3, '2026-01-07 07:06:11', '2026-01-07 07:06:36'),
(124, 14, 'products/images/ajGgYvUXS3Bekk7g6AMMoelnp4ZC6Ll6fUoYqtZX.jpg', NULL, 1, 1, '2026-01-07 07:13:12', '2026-01-07 07:53:32'),
(125, 14, 'products/images/KoePusZ4viPxlXHe9VY9KByp5sV4TOBNFDh7beQo.jpg', NULL, 0, 2, '2026-01-07 07:13:12', '2026-01-07 07:53:32'),
(126, 14, 'products/images/GfuYr2NH0oRKxBLbWGPQpfen9fHEI5FQAx06luFt.jpg', NULL, 0, 3, '2026-01-07 07:13:12', '2026-01-07 07:53:32'),
(127, 13, 'products/images/dPzqWrvSHW5Tv4P9WREdpKFGH1a6yz2XHdqM1HfJ.jpg', NULL, 1, 1, '2026-01-07 07:22:11', '2026-01-07 07:22:19'),
(128, 13, 'products/images/WVNzUoHnmLV8UOfLQ7YB5T0dcns7vNFIkpM1PIyQ.jpg', NULL, 0, 2, '2026-01-07 07:22:11', '2026-01-07 07:22:19'),
(129, 13, 'products/images/AN0rfCQgAATdKAMyvy6L5b6JKDEsYapj2BQ7M1gJ.jpg', NULL, 0, 3, '2026-01-07 07:22:11', '2026-01-07 07:22:19'),
(130, 12, 'products/images/8asBthO9cgIBRECktIuU8pdSMAR6TxYSc7XUAIKl.jpg', NULL, 1, 1, '2026-01-07 07:25:50', '2026-01-07 07:25:50'),
(131, 12, 'products/images/BM6uSStMytloeZcEl6PgcVAvYTT3dmKJMga5s40X.jpg', NULL, 0, 2, '2026-01-07 07:25:50', '2026-01-07 07:25:50'),
(132, 12, 'products/images/dTtgmok0sod3ghKrNK5DOhmIIvxJRjLiIA4Wya56.jpg', NULL, 0, 3, '2026-01-07 07:25:50', '2026-01-07 07:25:50'),
(133, 11, 'products/images/izl92YZJEEDucVWhiOK3D99JmwRFjL99FyqhND07.jpg', NULL, 1, 1, '2026-01-07 07:28:18', '2026-01-07 07:28:18'),
(134, 11, 'products/images/r66rTR35qC41q9j1eR73JzNb7T32ZuZamIIx4Rq7.jpg', NULL, 0, 2, '2026-01-07 07:28:18', '2026-01-07 07:28:18'),
(135, 11, 'products/images/nPEruttKWycSmC4Udn3nTbA98ozbSad5rL22xiPz.jpg', NULL, 0, 3, '2026-01-07 07:28:18', '2026-01-07 07:28:18'),
(136, 10, 'products/images/yhqP04AexyPSNblR6M5reYBVlGhQ9Gad359eE7wO.jpg', NULL, 1, 1, '2026-01-07 07:34:41', '2026-01-07 09:07:22'),
(137, 10, 'products/images/ad2CwkOONYm7Y85fOCjAyftzqqvkqKxYRxhK8K9L.jpg', NULL, 0, 2, '2026-01-07 07:34:41', '2026-01-07 09:07:22'),
(138, 10, 'products/images/CIw47Prt4vcjQocBBvezIyPYbvBlm8Scwq2w3zHz.jpg', NULL, 0, 3, '2026-01-07 07:34:41', '2026-01-07 09:07:22'),
(139, 9, 'products/images/CoKVsl81inlJsn8pVacjfYpfHiPKEdIUcrulXP6T.jpg', NULL, 1, 1, '2026-01-07 07:37:08', '2026-01-07 07:37:08'),
(140, 9, 'products/images/0C7aWnLx1Mb5F1xMF8U4pmX6nVcsV9VK4likLWtB.jpg', NULL, 0, 2, '2026-01-07 07:37:08', '2026-01-07 07:37:08'),
(141, 9, 'products/images/ngb0TiUfqiqZPa90PljhhaBxfaZlDY1yD5ZG1Dau.jpg', NULL, 0, 3, '2026-01-07 07:37:08', '2026-01-07 07:37:08'),
(142, 16, 'products/images/THWeI97H7QfzxL777t1HuosDYk9YTBEPixkt7UMU.jpg', NULL, 1, 1, '2026-01-07 07:42:11', '2026-01-07 07:53:49'),
(143, 16, 'products/images/nQSJpafpLgywhiyWN3qPIAqJYcHWg86GX6z424b1.jpg', NULL, 0, 2, '2026-01-07 07:42:11', '2026-01-07 07:53:49'),
(144, 16, 'products/images/7iP5BGNEmx0HyPd01vKVEbkqXtRUgnnd00eveygG.jpg', NULL, 0, 3, '2026-01-07 07:42:11', '2026-01-07 07:53:49'),
(145, 8, 'products/images/8fcG5V5yyLS5qjZ8kQ3lvKuaNTysJ2dgJM9cuxhG.jpg', NULL, 1, 1, '2026-01-07 07:58:07', '2026-01-07 07:58:07'),
(146, 8, 'products/images/hR7L62ethoBBy5Er9ief8NI560IA8fumbeFcysGZ.jpg', NULL, 0, 2, '2026-01-07 07:58:07', '2026-01-07 07:58:07'),
(147, 7, 'products/images/fEPUKDim1xQLXY9w3oqFXWCPjTqilgEpFSaE2WQU.jpg', NULL, 1, 1, '2026-01-07 08:00:01', '2026-01-07 08:00:44'),
(148, 7, 'products/images/ZV1HPFrGQzVIKo447eMQ40HdNt4vcdV1Lz3NEvzy.jpg', NULL, 0, 2, '2026-01-07 08:00:01', '2026-01-07 08:00:44'),
(149, 7, 'products/images/JYYeq2JdtYJ9WTYnU3Hs6dipqTR11KRXiQMdmBhV.jpg', NULL, 0, 3, '2026-01-07 08:00:01', '2026-01-07 08:00:44'),
(150, 6, 'products/images/hCz05qzC6ORxN3U4X4KIQjX10iYDklcAPx3ENiUj.jpg', NULL, 1, 1, '2026-01-07 08:02:48', '2026-01-07 08:16:26'),
(151, 6, 'products/images/Cdwvasj5N1pIjVFsX0hcVecpCQkVVMwBiYo8d1ji.jpg', NULL, 0, 2, '2026-01-07 08:02:48', '2026-01-07 08:16:26'),
(152, 6, 'products/images/LeOnerwQZQdcxTwNxtws45zt4iyoGvVwgWa8fZ4y.jpg', NULL, 0, 3, '2026-01-07 08:02:48', '2026-01-07 08:16:26'),
(153, 5, 'products/images/ddjEmKvWqQxTXqwiMK0PJAnISpPo1GAvSN6SPAIW.jpg', NULL, 1, 1, '2026-01-07 08:59:57', '2026-01-07 08:59:57'),
(154, 5, 'products/images/Rhsh9mZwgTJ9xGQyH7RPObKlG1TnKq2vUYVxvHXX.jpg', NULL, 0, 2, '2026-01-07 08:59:57', '2026-01-07 08:59:57'),
(155, 5, 'products/images/sPIlBSWqodXZC4QOVuUzRlKicsvrjqz4iGOzpEeQ.jpg', NULL, 0, 3, '2026-01-07 08:59:57', '2026-01-07 08:59:57'),
(156, 4, 'products/images/FuB4qrcQHVgmrKS0nemtgEDpSV0a6A5VoSGvqa2Q.jpg', NULL, 1, 1, '2026-01-07 09:01:41', '2026-01-07 09:01:41'),
(157, 4, 'products/images/6GDE0bNkWVsFSMtiqMd3edhlIphQTRWg3emX2jiz.jpg', NULL, 0, 2, '2026-01-07 09:01:41', '2026-01-07 09:01:41'),
(158, 4, 'products/images/gMm0wyIM9LhW4DxaGn9xzaMvp1RXKRWLnMECwFCl.jpg', NULL, 0, 3, '2026-01-07 09:01:41', '2026-01-07 09:01:41'),
(159, 37, 'products/images/SdFGiYoxRI9856XMtmyKJzqYVQOS0rQCx7NhhGSK.jpg', NULL, 1, 1, '2026-01-07 09:37:35', '2026-01-07 09:37:43'),
(160, 37, 'products/images/3sIzaZxz1Pnk9WJalal0DI6Q1uqIE1TlkvbgzVA1.jpg', NULL, 0, 2, '2026-01-07 09:37:35', '2026-01-07 09:37:43'),
(161, 37, 'products/images/7PDu8gVu2Ba9AxKFlbqZKSmShOO1DvYxc8qPclvX.jpg', NULL, 0, 3, '2026-01-07 09:37:35', '2026-01-07 09:37:43'),
(162, 34, 'products/images/eDMfunq7aR4pIWyx5gqobZ5NdU9ZoxODIA3yCCnV.jpg', NULL, 1, 1, '2026-01-07 09:42:24', '2026-01-07 09:42:24'),
(163, 34, 'products/images/2bBAaMv7PwiTX5jVsATDH4KFub5MtB3JCZYriFaP.jpg', NULL, 0, 2, '2026-01-07 09:42:24', '2026-01-07 09:42:24'),
(164, 33, 'products/images/ICQOm3JpcN7thWWRgtTnALHX2xVGpLj1AHACd36V.jpg', NULL, 1, 1, '2026-01-07 09:45:57', '2026-01-07 09:45:57'),
(165, 33, 'products/images/RGPw78UYLKUmiscmQ2kJAw0gjoPWqr6Vvg7LUh9L.jpg', NULL, 0, 2, '2026-01-07 09:45:57', '2026-01-07 09:45:57'),
(166, 33, 'products/images/IsGqXYjQZFmUtHUWLvBsMd9PGDnYRRwElxqTTNq0.jpg', NULL, 0, 3, '2026-01-07 09:45:57', '2026-01-07 09:45:57'),
(167, 36, 'products/images/ii5EdGmzCSYFYz5FMKZyR4Od8o9OkDvVvqCmTmVN.jpg', NULL, 1, 1, '2026-01-07 09:47:42', '2026-01-07 09:47:42'),
(168, 36, 'products/images/gCyqtVhPKECI5Q0dBz9gs90zaKRP5jzJcta059dN.jpg', NULL, 0, 2, '2026-01-07 09:47:42', '2026-01-07 09:47:42'),
(169, 36, 'products/images/f7E0cGWr6aM44g90SQSSibwYyNtEvXAvTCq0zxdD.jpg', NULL, 0, 3, '2026-01-07 09:47:42', '2026-01-07 09:47:42');

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
(2, 2, 'Quia esse sed libero-V1', 'L', 'White', '#000000', 119.97, NULL, NULL, NULL, NULL, NULL, NULL, 11, 10, NULL, 1, '2025-12-13 07:16:08', '2026-01-07 12:16:34', 0),
(3, 3, 'Non culpa do iste qu-V1', 'L', 'Black', '#000000', 77.00, NULL, NULL, NULL, NULL, NULL, NULL, 27, 10, NULL, 1, '2025-12-13 11:12:42', '2026-01-02 01:06:59', 0),
(4, 3, 'Non culpa do iste qu-V2', 'XL', 'White', '#ffffff', 80.00, NULL, NULL, NULL, NULL, NULL, NULL, 10, 10, NULL, 1, '2025-12-13 11:12:42', '2026-01-02 01:06:59', 0),
(5, 3, 'Non culpa do iste qu-S-WHI', 'S', 'Black', '#000000', 60.00, NULL, 'percentage', 10.00, '2025-12-28 10:31:00', '2026-01-10 10:31:00', NULL, 25, 10, NULL, 1, '2025-12-13 11:36:55', '2026-01-07 12:15:54', 1),
(6, 4, 'CDJ-M-BLUE', 'M', 'Blue', '#1e3a8a', 89.99, NULL, 'percentage', 15.00, '2025-12-31 17:00:00', '2026-01-31 16:59:00', NULL, 25, 10, 0.65, 1, '2026-01-06 01:00:00', '2026-01-07 09:01:41', 1),
(7, 4, 'CDJ-L-BLUE', 'L', 'Blue', '#1e3a8a', 89.99, NULL, 'percentage', 15.00, '2025-12-31 17:00:00', '2026-01-31 16:59:00', NULL, 30, 10, 0.68, 1, '2026-01-06 01:00:00', '2026-01-07 09:01:41', 1),
(8, 4, 'CDJ-XL-BLACK', 'XL', 'Black', '#000000', 89.99, NULL, 'percentage', 15.00, '2025-12-31 17:00:00', '2026-01-31 16:59:00', NULL, 18, 10, 0.70, 1, '2026-01-06 01:00:00', '2026-01-07 09:01:41', 1),
(9, 5, 'SFC-M-KHAKI', 'M', 'Khaki', '#c3b091', 69.99, NULL, NULL, NULL, NULL, NULL, NULL, 22, 10, 0.55, 1, '2026-01-06 01:05:00', '2026-01-07 08:59:57', 0),
(10, 5, 'SFC-L-KHAKI', 'L', 'Khaki', '#c3b091', 69.99, NULL, NULL, NULL, NULL, NULL, NULL, 28, 10, 0.58, 1, '2026-01-06 01:05:00', '2026-01-07 08:59:57', 0),
(11, 5, 'SFC-L-NAVY', 'L', 'Navy', '#000080', 69.99, NULL, NULL, NULL, NULL, NULL, NULL, 20, 10, 0.58, 1, '2026-01-06 01:05:00', '2026-01-07 08:59:57', 0),
(12, 6, 'OBD-M-WHITE', 'M', 'White', '#ffffff', 54.99, NULL, NULL, NULL, NULL, NULL, NULL, 35, 10, 0.30, 1, '2026-01-06 01:10:00', '2026-01-07 08:02:48', 0),
(13, 6, 'OBD-L-WHITE', 'L', 'White', '#ffffff', 54.99, NULL, NULL, NULL, NULL, NULL, NULL, 40, 10, 0.32, 1, '2026-01-06 01:10:00', '2026-01-07 08:02:48', 0),
(14, 6, 'OBD-L-BLUE', 'L', 'Light Blue', '#add8e6', 54.99, NULL, NULL, NULL, NULL, NULL, NULL, 32, 10, 0.32, 1, '2026-01-06 01:10:00', '2026-01-07 08:02:48', 0),
(16, 7, 'SPS-M-RED', 'M', 'Red/White', '#dc143c', 39.99, NULL, 'fixed', 5.00, '2026-01-04 10:00:00', '2026-01-20 09:59:00', NULL, 45, 10, 0.28, 1, '2026-01-06 01:15:00', '2026-01-07 08:00:43', 1),
(17, 7, 'SPS-L-BLUE', 'L', 'Blue/White', '#0000ff', 39.99, NULL, 'fixed', 5.00, '2026-01-04 10:00:00', '2026-01-20 09:59:00', NULL, 38, 10, 0.30, 1, '2026-01-06 01:15:00', '2026-01-07 08:00:44', 1),
(18, 7, 'SPS-XL-GREEN', 'XL', 'Green/White', '#008000', 39.99, NULL, 'fixed', 5.00, '2026-01-04 10:00:00', '2026-01-20 09:59:00', NULL, 25, 10, 0.32, 1, '2026-01-06 01:15:00', '2026-01-07 08:00:44', 1),
(19, 8, 'GPT-S-BLACK', 'S', 'Black', '#000000', 29.99, NULL, NULL, NULL, NULL, NULL, NULL, 50, 10, 0.20, 1, '2026-01-06 01:20:00', '2026-01-07 07:58:07', 0),
(20, 8, 'GPT-M-BLACK', 'M', 'Black', '#000000', 29.99, NULL, NULL, NULL, NULL, NULL, NULL, 60, 10, 0.22, 1, '2026-01-06 01:20:00', '2026-01-07 07:58:07', 0),
(21, 8, 'GPT-L-WHITE', 'L', 'White', '#ffffff', 29.99, NULL, NULL, NULL, NULL, NULL, NULL, 42, 10, 0.22, 1, '2026-01-06 01:20:00', '2026-01-07 07:58:07', 0),
(22, 9, 'BCT-S-GRAY', 'S', 'White', '#ffffff', 19.99, NULL, 'percentage', 20.00, '2025-12-31 17:00:00', '2026-01-15 16:59:00', NULL, 100, 10, 0.18, 1, '2026-01-06 01:25:00', '2026-01-07 07:37:08', 1),
(23, 9, 'BCT-M-GRAY', 'M', 'Gray', '#808080', 19.99, NULL, 'percentage', 20.00, '2025-12-31 17:00:00', '2026-01-15 16:59:00', NULL, 120, 10, 0.20, 1, '2026-01-06 01:25:00', '2026-01-07 07:37:08', 1),
(24, 9, 'BCT-L-BLACK', 'L', 'Black', '#000000', 19.99, NULL, 'percentage', 20.00, '2025-12-31 17:00:00', '2026-01-15 16:59:00', NULL, 90, 10, 0.20, 1, '2026-01-06 01:25:00', '2026-01-07 07:37:08', 1),
(25, 9, 'BCT-XL-WHITE', 'XL', 'White', '#ffffff', 19.99, NULL, 'percentage', 20.00, '2025-12-31 17:00:00', '2026-01-15 16:59:00', NULL, 75, 10, 0.22, 1, '2026-01-06 01:25:00', '2026-01-07 07:37:08', 1),
(26, 10, 'FSD-S-PINK', 'S', 'Pink Floral', '#ffb6c1', 79.99, NULL, NULL, NULL, NULL, NULL, NULL, 18, 10, 0.35, 1, '2026-01-06 01:30:00', '2026-01-07 07:34:41', 0),
(27, 10, 'FSD-M-PINK', 'M', 'Pink Floral', '#ffb6c1', 79.99, NULL, NULL, NULL, NULL, NULL, NULL, 22, 10, 0.37, 1, '2026-01-06 01:30:00', '2026-01-07 07:34:41', 0),
(28, 10, 'FSD-L-BLUE', 'L', 'Blue Floral', '#87ceeb', 79.99, NULL, NULL, NULL, NULL, NULL, NULL, 15, 10, 0.37, 1, '2026-01-06 01:30:00', '2026-01-07 07:34:41', 0),
(29, 11, 'EED-S-BLACK', 'S', 'Black', '#000000', 149.99, NULL, 'percentage', 25.00, '2026-01-09 17:00:00', '2026-02-10 16:59:00', NULL, 8, 5, 0.45, 1, '2026-01-06 01:35:00', '2026-01-07 07:28:18', 1),
(30, 11, 'EED-M-BLACK', 'M', 'Black', '#000000', 149.99, NULL, 'percentage', 25.00, '2026-01-09 17:00:00', '2026-02-10 16:59:00', NULL, 10, 5, 0.47, 1, '2026-01-06 01:35:00', '2026-01-07 07:28:18', 1),
(31, 11, 'EED-M-RED', 'M', 'Red', '#ff0000', 149.99, NULL, 'percentage', 25.00, '2026-01-09 17:00:00', '2026-02-10 16:59:00', NULL, 12, 5, 0.47, 1, '2026-01-06 01:35:00', '2026-01-07 07:28:18', 1),
(32, 12, 'CTT-S-WHITE', 'S', 'White', '#ffffff', 24.99, NULL, NULL, NULL, NULL, NULL, NULL, 65, 10, 0.15, 1, '2026-01-06 01:40:00', '2026-01-07 07:25:50', 0),
(33, 12, 'CTT-M-BLACK', 'M', 'Black', '#000000', 24.99, NULL, NULL, NULL, NULL, NULL, NULL, 58, 10, 0.16, 1, '2026-01-06 01:40:00', '2026-01-07 07:25:50', 0),
(34, 12, 'CTT-L-GRAY', 'L', 'Gray', '#808080', 24.99, NULL, NULL, NULL, NULL, NULL, NULL, 45, 10, 0.16, 1, '2026-01-06 01:40:00', '2026-01-07 07:25:50', 0),
(35, 13, 'RB-S-CREAM', 'S', 'Cream', '#fffdd0', 44.99, NULL, 'fixed', 8.00, '2026-01-05 10:00:00', '2026-01-25 09:59:00', NULL, 28, 10, 0.25, 1, '2026-01-06 01:45:00', '2026-01-07 07:22:19', 1),
(36, 13, 'RB-M-CREAM', 'M', 'Cream', '#fffdd0', 44.99, NULL, 'fixed', 8.00, '2026-01-05 10:00:00', '2026-01-25 09:59:00', NULL, 32, 10, 0.26, 1, '2026-01-06 01:45:00', '2026-01-07 07:22:19', 1),
(37, 13, 'RB-L-LAVENDER', 'L', 'Lavender', '#e6e6fa', 44.99, NULL, 'fixed', 8.00, '2026-01-05 10:00:00', '2026-01-25 09:59:00', NULL, 20, 10, 0.26, 1, '2026-01-06 01:45:00', '2026-01-07 07:22:19', 1),
(38, 14, 'LBJ-M-BLACK', 'M', 'Black', '#000000', 199.99, NULL, 'percentage', 30.00, '2026-01-05 03:00:00', '2026-01-31 02:59:00', NULL, 12, 5, 1.20, 1, '2026-01-06 02:00:00', '2026-01-07 07:53:32', 1),
(39, 14, 'LBJ-L-BLACK', 'L', 'Black', '#000000', 199.99, NULL, 'percentage', 30.00, '2026-01-05 03:00:00', '2026-01-31 02:59:00', NULL, 15, 5, 1.25, 1, '2026-01-06 02:00:00', '2026-01-07 07:53:32', 1),
(40, 14, 'LBJ-XL-BROWN', 'XL', 'Brown', '#8b4513', 199.99, NULL, 'percentage', 30.00, '2026-01-05 03:00:00', '2026-01-31 02:59:00', NULL, 8, 5, 1.30, 1, '2026-01-06 02:00:00', '2026-01-07 07:53:32', 1),
(41, 15, 'DJ-M-BLUE', 'M', 'Blue', '#4169e1', 79.99, NULL, NULL, NULL, NULL, NULL, NULL, 28, 10, 0.85, 1, '2026-01-06 02:02:00', '2026-01-07 07:06:11', 0),
(42, 15, 'DJ-L-BLUE', 'L', 'Blue', '#4169e1', 79.99, NULL, NULL, NULL, NULL, NULL, NULL, 32, 10, 0.90, 1, '2026-01-06 02:02:00', '2026-01-07 07:06:11', 0),
(43, 15, 'DJ-XL-BLACK', 'XL', 'Black', '#000000', 79.99, NULL, NULL, NULL, NULL, NULL, NULL, 20, 10, 0.95, 1, '2026-01-06 02:02:00', '2026-01-07 07:06:11', 0),
(44, 16, 'WB-M-NAVY', 'M', 'Navy', '#000080', 59.99, NULL, 'fixed', 10.00, '2026-01-04 10:00:00', '2026-01-20 09:59:00', NULL, 35, 10, 0.45, 1, '2026-01-06 02:03:00', '2026-01-07 07:53:49', 1),
(45, 16, 'WB-L-RED', 'L', 'Red', '#ff0000', 59.99, NULL, 'fixed', 10.00, '2026-01-04 10:00:00', '2026-01-20 09:59:00', NULL, 30, 10, 0.47, 1, '2026-01-06 02:03:00', '2026-01-07 07:53:49', 1),
(46, 16, 'WB-XL-BLACK', 'XL', 'Black', '#000000', 59.99, NULL, 'fixed', 10.00, '2026-01-04 10:00:00', '2026-01-20 09:59:00', NULL, 25, 10, 0.50, 1, '2026-01-06 02:03:00', '2026-01-07 07:53:49', 1),
(55, 20, 'PMS-S-BLACK', 'S', 'Black', '#000000', 54.99, NULL, NULL, NULL, NULL, NULL, NULL, 30, 10, 0.35, 1, '2026-01-06 02:10:00', '2026-01-07 07:03:40', 0),
(56, 20, 'PMS-M-NAVY', 'M', 'Navy', '#000080', 54.99, NULL, NULL, NULL, NULL, NULL, NULL, 35, 10, 0.35, 1, '2026-01-06 02:10:00', '2026-01-07 07:03:40', 0),
(57, 20, 'PMS-L-BURGUNDY', 'L', 'Burgundy', '#800020', 54.99, NULL, NULL, NULL, NULL, NULL, NULL, 25, 10, 0.37, 1, '2026-01-06 02:10:00', '2026-01-07 07:03:40', 0),
(58, 21, 'DMS-S-BLUE', 'S', 'Blue', '#4169e1', 39.99, NULL, 'percentage', 15.00, '2026-01-05 10:00:00', '2026-01-30 09:59:00', NULL, 40, 10, 0.30, 1, '2026-01-06 02:11:00', '2026-01-07 07:01:15', 1),
(59, 21, 'DMS-M-BLUE', 'M', 'Blue', '#4169e1', 39.99, NULL, 'percentage', 15.00, '2026-01-05 10:00:00', '2026-01-30 09:59:00', NULL, 45, 10, 0.30, 1, '2026-01-06 02:11:00', '2026-01-07 07:01:15', 1),
(60, 21, 'DMS-L-BLACK', 'L', 'Black', '#000000', 39.99, NULL, 'percentage', 15.00, '2026-01-05 10:00:00', '2026-01-30 09:59:00', NULL, 35, 10, 0.32, 1, '2026-01-06 02:11:00', '2026-01-07 07:01:15', 1),
(61, 22, 'PS-S-BLACK', 'S', 'Black', '#000000', 49.99, NULL, NULL, NULL, NULL, NULL, NULL, 28, 10, 0.32, 1, '2026-01-06 02:12:00', '2026-01-07 06:59:00', 0),
(62, 22, 'PS-M-GRAY', 'M', 'Gray', '#808080', 49.99, NULL, NULL, NULL, NULL, NULL, NULL, 32, 10, 0.32, 1, '2026-01-06 02:12:00', '2026-01-07 06:59:00', 0),
(63, 22, 'PS-L-NAVY', 'L', 'Navy', '#000080', 49.99, NULL, NULL, NULL, NULL, NULL, NULL, 25, 10, 0.35, 1, '2026-01-06 02:12:00', '2026-01-07 06:59:00', 0),
(72, 26, 'CKS-M-CREAM', 'M', 'Cream', '#fffdd0', 74.99, NULL, NULL, NULL, NULL, NULL, NULL, 32, 10, 0.55, 1, '2026-01-06 02:20:00', '2026-01-06 17:06:05', 0),
(73, 26, 'CKS-L-NAVY', 'L', 'Navy', '#000080', 74.99, NULL, NULL, NULL, NULL, NULL, NULL, 28, 10, 0.58, 1, '2026-01-06 02:20:00', '2026-01-06 17:06:05', 0),
(74, 26, 'CKS-XL-GRAY', 'XL', 'Gray', '#808080', 74.99, NULL, NULL, NULL, NULL, NULL, NULL, 22, 10, 0.60, 1, '2026-01-06 02:20:00', '2026-01-06 17:06:05', 0),
(75, 27, 'CG-S-BEIGE', 'S', 'Beige', '#f5f5dc', 64.99, NULL, 'percentage', 20.00, '2026-01-05 10:00:00', '2026-01-28 09:59:00', NULL, 38, 10, 0.50, 1, '2026-01-06 02:21:00', '2026-01-07 06:40:51', 1),
(76, 27, 'CG-M-BLACK', 'M', 'Black', '#000000', 64.99, NULL, 'percentage', 20.00, '2026-01-05 10:00:00', '2026-01-28 09:59:00', NULL, 42, 10, 0.52, 1, '2026-01-06 02:21:00', '2026-01-07 06:40:51', 1),
(77, 27, 'CG-L-BURGUNDY', 'L', 'Burgundy', '#800020', 64.99, NULL, 'percentage', 20.00, '2026-01-05 10:00:00', '2026-01-28 09:59:00', NULL, 30, 10, 0.55, 1, '2026-01-06 02:21:00', '2026-01-07 06:40:51', 1),
(78, 28, 'TNS-S-BLACK', 'S', 'Black', '#000000', 69.99, NULL, NULL, NULL, NULL, NULL, NULL, 30, 10, 0.48, 1, '2026-01-06 02:22:00', '2026-01-06 16:40:35', 0),
(79, 28, 'TNS-M-CAMEL', 'M', 'Grey', '#8a8a8a', 69.99, NULL, NULL, NULL, NULL, NULL, NULL, 35, 10, 0.50, 1, '2026-01-06 02:22:00', '2026-01-06 16:41:15', 0),
(80, 28, 'TNS-L-NAVY', 'L', 'Navy', '#000080', 69.99, NULL, NULL, NULL, NULL, NULL, NULL, 28, 10, 0.52, 1, '2026-01-06 02:22:00', '2026-01-06 16:40:35', 0),
(81, 29, 'CGS-M-KHAKI', 'M', 'Khaki', '#c3b091', 44.99, NULL, NULL, NULL, NULL, NULL, NULL, 42, 10, 0.35, 1, '2026-01-06 02:25:00', '2026-01-06 16:24:55', 0),
(82, 29, 'CGS-L-OLIVE', 'L', 'Olive', '#808000', 44.99, NULL, NULL, NULL, NULL, NULL, NULL, 38, 10, 0.37, 1, '2026-01-06 02:25:00', '2026-01-06 16:24:55', 0),
(83, 29, 'CGS-XL-BLACK', 'XL', 'Black', '#000000', 44.99, NULL, NULL, NULL, NULL, NULL, NULL, 30, 10, 0.40, 1, '2026-01-06 02:25:00', '2026-01-06 16:24:55', 0),
(87, 31, 'DS-S-BLUE', 'S', 'Blue', '#4169e1', 42.99, NULL, 'percentage', 15.00, '2025-12-30 20:00:00', '2026-01-31 19:59:00', NULL, 45, 10, 0.32, 1, '2026-01-06 02:27:00', '2026-01-07 10:06:47', 1),
(88, 31, 'DS-M-BLUE', 'M', 'Blue', '#4169e1', 42.99, NULL, 'percentage', 15.00, '2025-12-30 20:00:00', '2026-01-31 19:59:00', NULL, 50, 10, 0.33, 1, '2026-01-06 02:27:00', '2026-01-07 10:06:47', 1),
(89, 31, 'DS-L-BLACK', 'L', 'Black', '#000000', 42.99, NULL, 'percentage', 15.00, '2025-12-30 20:00:00', '2026-01-31 19:59:00', NULL, 42, 10, 0.35, 1, '2026-01-06 02:27:00', '2026-01-07 10:06:47', 1),
(90, 37, '-M-#26', 'M', '#26121c', '#26121c', 12.00, NULL, NULL, NULL, NULL, NULL, NULL, 15, 10, 30.00, 1, '2026-01-07 09:37:35', '2026-01-07 09:37:35', 0),
(91, 34, '-M-BLA', 'M', 'Black', '#000000', 15.00, NULL, NULL, NULL, NULL, NULL, NULL, 12, 10, 35.00, 1, '2026-01-07 09:41:56', '2026-01-07 09:41:56', 0),
(92, 33, '-M-MID', 'M', 'Mid-blue', '#324970', 13.00, NULL, NULL, NULL, NULL, NULL, NULL, 12, 10, 24.92, 1, '2026-01-07 09:45:57', '2026-01-07 09:45:57', 0),
(93, 36, '-X-PIN', 'XS', 'Pink', '#e2366a', 10.00, NULL, NULL, NULL, NULL, NULL, NULL, 18, 10, NULL, 1, '2026-01-07 09:47:42', '2026-01-07 09:47:42', 0);

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
('s3aTO0ZzQ5OGTgCflqCnbGelO1ADUZSfoldc6KnC', 3, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTElFM0VKaEJYV0piNXh1R0oxbW45VmtaS3IwcE1Fd0R2Rnk5YUpRWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0L2NhcmRpZ2FuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjY6ImxvY2FsZSI7czoyOiJrbSI7fQ==', 1767795467);

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
(1, 'Admin User', 'team.818x@gmail.com', '2025-12-22 16:37:27', '$2y$12$JyHZKpSMqCCGlGJgNFVBjOFTEpvZ8HzDy.KSzL.9kukXOuTgRxg56', NULL, NULL, 'admin', 'admin', NULL, 0, 1, '1234567890', NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-12-12 18:18:21', '2025-12-12 18:18:21'),
(3, 'Vuthy', 'xawet22315@lawior.com', '2025-12-28 11:40:56', '$2y$12$/QiQ.SpxYdoXgnkLFwBCl.39ATcbuLf3K/Te6yGwazFmYEBCBOG/i', NULL, NULL, 'admin', 'admin', NULL, 0, 1, '012300204', 'user/profile_pictures/GcRPV170Ux20UodfWRqlHjjVG1WcdycGCZxzjfMz.png', '1998-10-13', 'male', 1, 1, NULL, NULL, '2025-12-12 18:01:26', '2025-12-30 11:03:58'),
(4, 'Cailin Golden', 'gedena7312@mekuron.com', '2025-12-24 10:16:45', '$2y$12$XaxjxP.1iYzGIfCRFmNzhOwrBA/4rhEvAKouNOcVqm5SmJNxVLFvC', NULL, NULL, 'customer', 'user', NULL, 0, 1, '012300107', NULL, '2000-06-13', 'female', 1, 1, NULL, NULL, '2025-12-19 08:37:31', '2025-12-28 11:19:26'),
(5, 'Leon Lii', 'mrrvothy@gmail.com', '2025-12-30 16:27:25', '$2y$12$xC4R3YF0zGTX/hqWn8uAF.9f6klG6j3a6EdAspRNhxyUkaVqbnYpa', NULL, NULL, 'customer', 'user', NULL, 0, 1, '012300104', NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-12-28 11:29:49', '2025-12-30 16:27:25');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

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