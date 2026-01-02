-- Enhanced Database Schema for Nova Studio
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `outfit_818_enhanced`
--
CREATE DATABASE IF NOT EXISTS `outfit_818_enhanced` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `outfit_818_enhanced`;

-- --------------------------------------------------------
-- Remove existing constraints to avoid errors
-- --------------------------------------------------------
-- SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Check and drop existing tables if they exist
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `wishlists`;
DROP TABLE IF EXISTS `product_reviews`;
DROP TABLE IF EXISTS `newsletter_subscriptions`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `user_addresses`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `carousels`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------
-- Enhanced tables structure
-- --------------------------------------------------------

--
-- Table structure for table `cache`
--
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `cache_locks`
--
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `carousels`
--
CREATE TABLE `carousels` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(255) NOT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(2048) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Enhanced Category Table
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `image` varchar(255) NOT NULL,
  `description` text,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `jobs`
--
CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `migrations`
--
CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `password_reset_tokens`
--
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `sessions`
--
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Enhanced User Table
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL UNIQUE,
  `profile_picture` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `default_address_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `default_address_id` (`default_address_id`),
  KEY `users_email_verified` (`email_verified_at`, `is_verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- User Addresses (Separate from users table)
-- --------------------------------------------------------
CREATE TABLE `user_addresses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('shipping','billing') NOT NULL DEFAULT 'shipping',
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` text NOT NULL,
  `address_line2` text DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) DEFAULT 'United States',
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_default` (`is_default`),
  CONSTRAINT `fk_user_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Enhanced Product Table
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `description` text,
  `short_description` varchar(500) DEFAULT NULL,
  `sku` varchar(100) NOT NULL UNIQUE,
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `is_featured` (`is_featured`),
  KEY `idx_products_status` (`status`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Product Images Table (Multiple images per product)
-- --------------------------------------------------------
CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Product Variants (Replaces product_sizes with color support)
-- --------------------------------------------------------
CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku_suffix` varchar(50) NOT NULL,
  `size` enum('XS','S','M','L','XL','XXL','XXXL') NOT NULL,
  `color` varchar(50) NOT NULL,
  `color_code` varchar(7) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_variant` (`product_id`, `size`, `color`),
  KEY `product_id` (`product_id`),
  KEY `stock_status` (`stock`, `is_active`),
  KEY `idx_product_variants_price` (`price`, `sale_price`),
  CONSTRAINT `fk_product_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Enhanced Cart Items (Now references product_variants)
-- --------------------------------------------------------
CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cart_item` (`user_id`, `product_variant_id`),
  KEY `user_id` (`user_id`),
  KEY `product_variant_id` (`product_variant_id`),
  KEY `idx_cart_items_updated_at` (`updated_at`),
  CONSTRAINT `fk_cart_items_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_product_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Enhanced Orders (With cart relationship) - FIXED
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `cart_items_snapshot` JSON,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `shipping_amount` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `shipping_address_id` bigint UNSIGNED DEFAULT NULL,
  `billing_address_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_order_number` (`order_number`),
  KEY `user_id` (`user_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_payment_status` (`payment_status`),
  KEY `idx_orders_created_at` (`created_at`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_orders_shipping_address` FOREIGN KEY (`shipping_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_orders_billing_address` FOREIGN KEY (`billing_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Order Items (References product_variants, not products directly)
-- --------------------------------------------------------
CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `variant_details` JSON NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_variant_id` (`product_variant_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Product Reviews
-- --------------------------------------------------------
CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL CHECK (rating >= 1 AND rating <= 5),
  `title` varchar(255) DEFAULT NULL,
  `comment` text,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_review` (`product_id`, `user_id`, `order_id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_product_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Wishlist Table
-- --------------------------------------------------------
CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_wishlists_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wishlists_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Newsletter Subscriptions - FIXED duplicate key
-- --------------------------------------------------------
CREATE TABLE `newsletter_subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL UNIQUE,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `is_subscribed` tinyint(1) DEFAULT '1',
  `subscribed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_newsletter_subscriptions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Now add the foreign key constraint to users table
-- --------------------------------------------------------
ALTER TABLE `users` 
ADD CONSTRAINT `fk_users_default_address` FOREIGN KEY (`default_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL;

-- --------------------------------------------------------
-- Insert existing data with transformations
-- --------------------------------------------------------

--
-- Insert users (keeping all data)
--
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `otp_code`, `is_verified`, `user_type`, `phone`, `profile_picture`, `dob`, `gender`) VALUES
(21, 'panda cord', 'dccord3@gmail.com', '2025-10-10 11:35:15', '$2y$12$mzj/0irJ5hdwtjheSCfVq.oHRPRgBnlQPRxyIZY9oVcJOCibOQV/i', NULL, '2025-10-10 11:34:42', '2025-10-10 11:35:15', NULL, 1, 'user', '9878787878', NULL, '2025-02-01', 'male'),
(22, 'Admin', 'team.818x@gmail.com', '2025-10-12 03:29:53', '$2y$12$JyHZKpSMqCCGlGJgNFVBjOFTEpvZ8HzDy.KSzL.9kukXOuTgRxg56', 'ZotMyxXLp4nSeVU8mMtIzjAPGvCyoqVLOOqReAuC8sT35K6TjS5kblP0d6K5', '2025-10-12 03:29:22', '2025-11-08 07:33:04', NULL, 1, 'admin', '3434222345', 'profile_pictures/fDKWfJ4M96NUCGBSEum5C553T7XVKRqrh73UeT3n.webp', '2007-04-06', 'male'),
(23, 'Vuthy', 'vuthytuon168@gmail.com', NULL, '$2y$12$POaRDrOn17YswG4Sj5sSPumJu2SV167/C1i2rUpHxNvIPt9.vcMJ.', NULL, '2025-12-04 09:46:16', '2025-12-04 09:53:17', '167781', 0, 'user', '0134354346', NULL, '2006-02-15', 'male'),
(24, 'Vuthy', 'mrrvothy@gmail.com', NULL, '$2y$12$m4Sm7OfXXuS027ipKryROOwryJ3jiZGRejsDClzwUWXaiV48leDSe', NULL, '2025-12-04 10:02:45', '2025-12-04 10:02:45', '345337', 0, 'user', '0134354344', NULL, '2003-05-06', 'male'),
(25, 'Vuthy', 'wopefel782@docsfy.com', '2025-12-04 10:04:35', '$2y$12$7inQS5DkhYFdvL4Rs6S.H.4p39p.zb63tcWeyxzY5Gyr5t9zwLPf6', NULL, '2025-12-04 10:04:08', '2025-12-04 10:04:35', NULL, 1, 'user', '0134354342', NULL, '2003-05-06', 'male');

--
-- Insert categories (enhanced with description)
--
INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Men', 'men', 'https://static.zara.net/assets/public/271d/8f90/93384415b608/d29c5bb69e67/image-web-e23758c2-4305-42d5-bfed-2911b6f732d8-default/image-web-e23758c2-4305-42d5-bfed-2911b6f732d8-default.jpg?ts=1761911121683&w=1888', 'Men''s clothing and accessories', 'active', '2025-07-10 02:36:01', '2025-07-10 02:36:01'),
(2, 'Women', 'women', 'https://static.zara.net/assets/public/7f8f/31e9/c6f54de7b828/1743439b03be/04813857800-h1/04813857800-h1.jpg?ts=1761899542478&w=1888', 'Women''s fashion and accessories', 'active', '2025-07-10 02:36:09', '2025-07-10 02:36:09'),
(3, 'Kids', 'kids', 'https://static.zara.net/assets/public/b87f/4ffa/d91b42b4b2bc/55726691dfde/image-web-5-204e051f-8609-4829-929b-91db4c71ee24-default/image-web-5-204e051f-8609-4829-929b-91db4c71ee24-default.jpg?ts=1761740019541&w=1888', 'Clothing for children', 'active', '2025-07-10 02:36:17', '2025-07-10 06:29:40');

-- --------------------------------------------------------
-- Create a simplified migration procedure
-- --------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE migrate_products_and_variants()
BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        SELECT 'Error occurred during product migration' AS message;
    END;
    
    START TRANSACTION;
    
    -- Insert products from original database
    INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `description`, `sku`, `status`, `created_at`, `updated_at`)
    SELECT 
        p.id,
        p.name,
        LOWER(REPLACE(REPLACE(REPLACE(REPLACE(p.name, ' ', '-'), '(', ''), ')', ''), '''', '')) AS slug,
        p.category_id,
        p.description,
        CONCAT('PROD-', p.id) AS sku,
        p.status,
        p.created_at,
        p.updated_at
    FROM outfit_818.products p
    ORDER BY p.id;
    
    -- Insert product images
    INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`, `sort_order`)
    SELECT 
        p.id,
        p.image,
        1,
        1
    FROM outfit_818.products p
    WHERE p.image IS NOT NULL AND p.image != 'products/NULL' AND p.image != '';
    
    -- Insert product variants from old product_sizes
    INSERT INTO `product_variants` (
        `product_id`, 
        `sku_suffix`, 
        `size`, 
        `color`, 
        `color_code`, 
        `price`, 
        `stock`
    )
    SELECT 
        ps.product_id,
        CONCAT('-', ps.size),
        CASE 
            WHEN ps.size = 'XS' THEN 'XS'
            WHEN ps.size = 'S' THEN 'S'
            WHEN ps.size = 'M' THEN 'M'
            WHEN ps.size = 'L' THEN 'L'
            WHEN ps.size = 'XL' THEN 'XL'
            WHEN ps.size = 'XXL' THEN 'XXL'
            WHEN ps.size = 'XXXL' THEN 'XXXL'
            ELSE 'M'
        END AS size,
        'Default' AS color,
        NULL AS color_code,
        ps.price,
        COALESCE(ps.stock, 0) AS stock
    FROM outfit_818.product_sizes ps
    WHERE ps.product_id IS NOT NULL;
    
    COMMIT;
    
    SELECT 'Products and variants migrated successfully!' AS message;
END$$

CREATE PROCEDURE migrate_orders_data()
BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        SELECT 'Error occurred during order migration' AS message;
    END;
    
    START TRANSACTION;
    
    -- First create user addresses from existing order data
    INSERT INTO `user_addresses` (
        `user_id`, 
        `type`, 
        `full_name`, 
        `phone`, 
        `address_line1`, 
        `city`, 
        `state`, 
        `zip_code`, 
        `country`,
        `is_default`
    )
    SELECT DISTINCT
        o.user_id,
        'shipping' AS type,
        COALESCE(o.name, u.name) AS full_name,
        COALESCE(o.phone, u.phone, '') AS phone,
        COALESCE(o.address, 'Address not specified') AS address_line1,
        COALESCE(o.city, 'Unknown') AS city,
        COALESCE(o.state, 'Unknown') AS state,
        COALESCE(o.zip, '00000') AS zip_code,
        'United States' AS country,
        1 AS is_default
    FROM outfit_818.orders o
    INNER JOIN outfit_818.users u ON o.user_id = u.id
    WHERE o.user_id IS NOT NULL;
    
    -- Now migrate orders
    INSERT INTO `orders` (
        `id`,
        `user_id`,
        `order_number`,
        `subtotal`,
        `total_amount`,
        `status`,
        `payment_method`,
        `payment_status`,
        `created_at`,
        `updated_at`
    )
    SELECT 
        o.id,
        o.user_id,
        o.order_id,
        o.total_amount,
        o.total_amount,
        CASE 
            WHEN o.status = 'paid' THEN 'processing'
            ELSE 'pending'
        END AS status,
        'stripe' AS payment_method,
        CASE 
            WHEN o.status = 'paid' THEN 'paid'
            ELSE 'pending'
        END AS payment_status,
        o.created_at,
        o.updated_at
    FROM outfit_818.orders o
    WHERE o.user_id IS NOT NULL;
    
    -- Migrate order items
    INSERT INTO `order_items` (
        `order_id`,
        `product_variant_id`,
        `product_name`,
        `variant_details`,
        `quantity`,
        `unit_price`,
        `total_price`,
        `created_at`
    )
    SELECT 
        oi.order_id,
        pv.id,
        oi.product_name,
        JSON_OBJECT(
            'size', oi.size,
            'color', 'Default'
        ) AS variant_details,
        oi.quantity,
        oi.price,
        (oi.quantity * oi.price) AS total_price,
        oi.created_at
    FROM outfit_818.order_items oi
    INNER JOIN product_variants pv ON pv.product_id = oi.product_id
    WHERE pv.size = oi.size AND oi.order_id IS NOT NULL;
    
    COMMIT;
    
    SELECT 'Orders migrated successfully!' AS message;
END$$

CREATE PROCEDURE migrate_other_data()
BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        SELECT 'Error occurred during other data migration' AS message;
    END;
    
    START TRANSACTION;
    
    -- Migrate carousels
    INSERT INTO `carousels` (`id`, `title`, `description`, `image_path`, `button_text`, `button_link`, `is_active`, `created_at`, `updated_at`)
    SELECT `id`, `title`, `description`, `image_path`, `button_text`, `button_link`, `is_active`, `created_at`, `updated_at`
    FROM outfit_818.carousels;
    
    -- Migrate newsletter subscriptions from emails table
    INSERT IGNORE INTO `newsletter_subscriptions` (`email`, `user_id`, `is_subscribed`, `subscribed_at`)
    SELECT e.email, e.user_id, 1, e.created_at 
    FROM outfit_818.emails e;
    
    -- Migrate other tables
    INSERT IGNORE INTO `cache` (`key`, `value`, `expiration`)
    SELECT `key`, `value`, `expiration`
    FROM outfit_818.cache;
    
    INSERT IGNORE INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`)
    SELECT `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`
    FROM outfit_818.jobs;
    
    INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`)
    SELECT `id`, `migration`, `batch`
    FROM outfit_818.migrations;
    
    INSERT IGNORE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`)
    SELECT `id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`
    FROM outfit_818.sessions;
    
    INSERT IGNORE INTO `password_reset_tokens` (`email`, `token`, `created_at`)
    SELECT `email`, `token`, `created_at`
    FROM outfit_818.password_reset_tokens;
    
    COMMIT;
    
    SELECT 'Other data migrated successfully!' AS message;
END$$

DELIMITER ;

-- --------------------------------------------------------
-- Execute migrations (only if source database exists)
-- --------------------------------------------------------
SET @db_exists = (SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = 'outfit_818');

IF @db_exists > 0 THEN
    CALL migrate_products_and_variants();
    CALL migrate_orders_data();
    CALL migrate_other_data();
ELSE
    SELECT 'Source database not found. Creating empty enhanced database.' AS message;
END IF;

-- --------------------------------------------------------
-- Clean up procedures
-- --------------------------------------------------------
DROP PROCEDURE IF EXISTS migrate_products_and_variants;
DROP PROCEDURE IF EXISTS migrate_orders_data;
DROP PROCEDURE IF EXISTS migrate_other_data;

-- --------------------------------------------------------
-- Add sample data for new features
-- --------------------------------------------------------
INSERT IGNORE INTO `product_reviews` (`product_id`, `user_id`, `rating`, `title`, `comment`, `is_approved`) VALUES
(1019, 22, 5, 'Great Shorts!', 'Very comfortable and perfect for summer.', 1),
(1020, 21, 4, 'Nice Hoodie', 'Good quality, could be a bit thicker.', 1),
(1021, 25, 5, 'Perfect Jacket', 'Fits perfectly and looks great.', 1);

INSERT IGNORE INTO `wishlists` (`user_id`, `product_id`) VALUES
(22, 1019),
(22, 1020),
(21, 1021);

-- Set some products as featured
UPDATE `products` SET `is_featured` = 1 WHERE `id` IN (1019, 1020, 1021);

-- --------------------------------------------------------
-- Create indexes for better performance
-- --------------------------------------------------------
CREATE INDEX IF NOT EXISTS `idx_product_variants_product_size` ON `product_variants`(`product_id`, `size`);
CREATE INDEX IF NOT EXISTS `idx_product_variants_price_range` ON `product_variants`(`price`, `sale_price`, `is_active`);
CREATE INDEX IF NOT EXISTS `idx_orders_user_status` ON `orders`(`user_id`, `status`, `created_at`);
CREATE INDEX IF NOT EXISTS `idx_order_items_order_product` ON `order_items`(`order_id`, `product_variant_id`);
CREATE INDEX IF NOT EXISTS `idx_cart_items_user_product` ON `cart_items`(`user_id`, `product_variant_id`, `updated_at`);
CREATE INDEX IF NOT EXISTS `idx_product_reviews_product_rating` ON `product_reviews`(`product_id`, `rating`, `is_approved`);
CREATE INDEX IF NOT EXISTS `idx_wishlists_user_product` ON `wishlists`(`user_id`, `product_id`);

-- --------------------------------------------------------
-- Create views for common queries
-- --------------------------------------------------------
CREATE OR REPLACE VIEW `product_details_view` AS
SELECT 
    p.id,
    p.name,
    p.slug,
    p.description,
    p.short_description,
    p.sku,
    p.status,
    p.is_featured,
    c.name as category_name,
    c.slug as category_slug,
    MIN(pi.image_path) as primary_image,
    COUNT(DISTINCT pv.id) as variant_count,
    MIN(pv.price) as min_price,
    MAX(pv.price) as max_price,
    MIN(pv.sale_price) as min_sale_price,
    SUM(pv.stock) as total_stock
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
LEFT JOIN product_variants pv ON p.id = pv.product_id AND pv.is_active = 1
GROUP BY p.id, p.name, p.slug, p.description, p.short_description, p.sku, p.status, p.is_featured, c.name, c.slug;

-- --------------------------------------------------------
-- Success message
-- --------------------------------------------------------
SELECT 'Enhanced database setup completed successfully!' AS message;
SELECT 'Total products migrated: ' AS message, COUNT(*) as count FROM products;
SELECT 'Total users migrated: ' AS message, COUNT(*) as count FROM users;
SELECT 'Total orders migrated: ' AS message, COUNT(*) as count FROM orders;
SELECT 'Total product variants migrated: ' AS message, COUNT(*) as count FROM product_variants;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_COLLATION_CONNECTION */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;