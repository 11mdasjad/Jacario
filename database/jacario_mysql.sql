-- --------------------------------------------------------
-- Jacario E-Commerce Database SQL Dump (MySQL / MariaDB)
-- Generated on: 2026-09-23 05:34:32
-- Compatible with: MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+
-- --------------------------------------------------------

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- ========================================================
-- TABLE STRUCTURES (DDL)
-- ========================================================

DROP TABLE IF EXISTS `addresses`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `carts`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `colors`;
DROP TABLE IF EXISTS `coupon_usages`;
DROP TABLE IF EXISTS `coupons`;
DROP TABLE IF EXISTS `customizable_product_variants`;
DROP TABLE IF EXISTS `customizable_products`;
DROP TABLE IF EXISTS `customization_settings`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `newsletter_subscribers`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `saved_custom_designs`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `sizes`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `wishlists`;

create table `users` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `email` varchar(255) not null, `phone` varchar(255) null, `role` varchar(255) not null default 'customer', `avatar` varchar(255) null, `is_active` tinyint(1) not null default '1', `email_verified_at` timestamp null, `password` varchar(255) not null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `users` add unique `users_email_unique`(`email`);
create table `password_reset_tokens` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` timestamp null, primary key (`email`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `sessions` (`id` varchar(255) not null, `user_id` bigint unsigned null, `ip_address` varchar(45) null, `user_agent` text null, `payload` longtext not null, `last_activity` int not null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `sessions` add index `sessions_user_id_index`(`user_id`);
alter table `sessions` add index `sessions_last_activity_index`(`last_activity`);
create table `cache` (`key` varchar(255) not null, `value` mediumtext not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `cache` add index `cache_expiration_index`(`expiration`);
create table `cache_locks` (`key` varchar(255) not null, `owner` varchar(255) not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `cache_locks` add index `cache_locks_expiration_index`(`expiration`);
create table `jobs` (`id` bigint unsigned not null auto_increment primary key, `queue` varchar(255) not null, `payload` longtext not null, `attempts` smallint unsigned not null, `reserved_at` int unsigned null, `available_at` int unsigned not null, `created_at` int unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `jobs` add index `jobs_queue_index`(`queue`);
create table `job_batches` (`id` varchar(255) not null, `name` varchar(255) not null, `total_jobs` int not null, `pending_jobs` int not null, `failed_jobs` int not null, `failed_job_ids` longtext not null, `options` mediumtext null, `cancelled_at` int null, `created_at` int not null, `finished_at` int null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `failed_jobs` (`id` bigint unsigned not null auto_increment primary key, `uuid` varchar(255) not null, `connection` varchar(255) not null, `queue` varchar(255) not null, `payload` longtext not null, `exception` longtext not null, `failed_at` timestamp not null default CURRENT_TIMESTAMP) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `failed_jobs` add index `failed_jobs_connection_queue_failed_at_index`(`connection`, `queue`, `failed_at`);
alter table `failed_jobs` add unique `failed_jobs_uuid_unique`(`uuid`);
create table `categories` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `slug` varchar(255) not null, `description` text null, `image` varchar(255) null, `is_active` tinyint(1) not null default '1', `sort_order` int not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `categories` add unique `categories_slug_unique`(`slug`);
create table `sizes` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `code` varchar(255) not null, `chest` varchar(255) null, `length` varchar(255) null, `shoulder` varchar(255) null, `sort_order` int not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `sizes` add unique `sizes_code_unique`(`code`);
create table `colors` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `slug` varchar(255) not null, `hex_code` varchar(255) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `colors` add unique `colors_slug_unique`(`slug`);
create table `products` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `slug` varchar(255) not null, `sku` varchar(255) not null, `category_id` bigint unsigned not null, `short_description` text null, `description` longtext null, `base_price` decimal(10, 2) not null, `sale_price` decimal(10, 2) null, `fabric` varchar(255) not null default '100% Supima Cotton', `fit` varchar(255) not null default 'Classic Fit', `pattern` varchar(255) not null default 'Solid', `collar_type` varchar(255) not null default 'Ribbed Polo Collar', `sleeve_type` varchar(255) not null default 'Short Sleeve', `wash_care` varchar(255) not null default 'Machine wash cold, tumble dry low', `country_of_origin` varchar(255) not null default 'India', `is_bestseller` tinyint(1) not null default '0', `is_new_arrival` tinyint(1) not null default '0', `is_featured` tinyint(1) not null default '0', `is_active` tinyint(1) not null default '1', `size_chart` json null, `seo_title` varchar(255) null, `seo_description` text null, `deleted_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `products` add constraint `products_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete cascade;
alter table `products` add unique `products_slug_unique`(`slug`);
alter table `products` add unique `products_sku_unique`(`sku`);
alter table `products` add index `products_is_bestseller_index`(`is_bestseller`);
alter table `products` add index `products_is_new_arrival_index`(`is_new_arrival`);
alter table `products` add index `products_is_featured_index`(`is_featured`);
alter table `products` add index `products_is_active_index`(`is_active`);
create table `product_variants` (`id` bigint unsigned not null auto_increment primary key, `product_id` bigint unsigned not null, `size_id` bigint unsigned not null, `color_id` bigint unsigned not null, `sku` varchar(255) not null, `price` decimal(10, 2) null, `sale_price` decimal(10, 2) null, `stock_quantity` int not null default '0', `is_active` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `product_variants` add constraint `product_variants_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade;
alter table `product_variants` add constraint `product_variants_size_id_foreign` foreign key (`size_id`) references `sizes` (`id`) on delete cascade;
alter table `product_variants` add constraint `product_variants_color_id_foreign` foreign key (`color_id`) references `colors` (`id`) on delete cascade;
alter table `product_variants` add unique `prod_variant_unique`(`product_id`, `size_id`, `color_id`);
alter table `product_variants` add unique `product_variants_sku_unique`(`sku`);
create table `product_images` (`id` bigint unsigned not null auto_increment primary key, `product_id` bigint unsigned not null, `color_id` bigint unsigned null, `image_path` varchar(255) not null, `alt_text` varchar(255) null, `is_primary` tinyint(1) not null default '0', `sort_order` int not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `product_images` add constraint `product_images_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade;
alter table `product_images` add constraint `product_images_color_id_foreign` foreign key (`color_id`) references `colors` (`id`) on delete set null;
create table `carts` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned null, `session_id` varchar(255) null, `coupon_code` varchar(255) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `carts` add constraint `carts_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete set null;
alter table `carts` add index `carts_session_id_index`(`session_id`);
create table `cart_items` (`id` bigint unsigned not null auto_increment primary key, `cart_id` bigint unsigned not null, `product_variant_id` bigint unsigned not null, `quantity` int not null default '1', `unit_price` decimal(10, 2) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `cart_items` add constraint `cart_items_cart_id_foreign` foreign key (`cart_id`) references `carts` (`id`) on delete cascade;
alter table `cart_items` add constraint `cart_items_product_variant_id_foreign` foreign key (`product_variant_id`) references `product_variants` (`id`) on delete cascade;
alter table `cart_items` add unique `cart_items_cart_id_product_variant_id_unique`(`cart_id`, `product_variant_id`);
create table `wishlists` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `product_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `wishlists` add constraint `wishlists_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
alter table `wishlists` add constraint `wishlists_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade;
alter table `wishlists` add unique `wishlists_user_id_product_id_unique`(`user_id`, `product_id`);
create table `addresses` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `full_name` varchar(255) not null, `phone` varchar(255) not null, `address_line_1` varchar(255) not null, `address_line_2` varchar(255) null, `landmark` varchar(255) null, `city` varchar(255) not null, `state` varchar(255) not null, `postal_code` varchar(255) not null, `country` varchar(255) not null default 'India', `address_type` varchar(255) not null default 'home', `is_default` tinyint(1) not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `addresses` add constraint `addresses_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
create table `coupons` (`id` bigint unsigned not null auto_increment primary key, `code` varchar(255) not null, `description` text null, `discount_type` varchar(255) not null default 'percentage', `value` decimal(10, 2) not null, `min_order_value` decimal(10, 2) not null default '0', `max_discount` decimal(10, 2) null, `usage_limit` int null, `usage_limit_per_user` int not null default '1', `used_count` int not null default '0', `starts_at` timestamp null, `expires_at` timestamp null, `is_active` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `coupons` add unique `coupons_code_unique`(`code`);
create table `coupon_usages` (`id` bigint unsigned not null auto_increment primary key, `coupon_id` bigint unsigned not null, `user_id` bigint unsigned null, `order_id` bigint unsigned null, `discount_amount` decimal(10, 2) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `coupon_usages` add constraint `coupon_usages_coupon_id_foreign` foreign key (`coupon_id`) references `coupons` (`id`) on delete cascade;
alter table `coupon_usages` add constraint `coupon_usages_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete set null;
create table `orders` (`id` bigint unsigned not null auto_increment primary key, `order_number` varchar(255) not null, `user_id` bigint unsigned null, `subtotal` decimal(10, 2) not null, `discount_amount` decimal(10, 2) not null default '0', `shipping_amount` decimal(10, 2) not null default '0', `tax_amount` decimal(10, 2) not null default '0', `total_amount` decimal(10, 2) not null, `coupon_code` varchar(255) null, `status` varchar(255) not null default 'pending', `payment_method` varchar(255) not null default 'razorpay', `payment_status` varchar(255) not null default 'pending', `customer_name` varchar(255) not null, `customer_email` varchar(255) not null, `customer_phone` varchar(255) not null, `shipping_address` json not null, `billing_address` json null, `tracking_number` varchar(255) null, `courier_name` varchar(255) null, `notes` text null, `cancelled_reason` text null, `shipped_at` timestamp null, `delivered_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `orders` add constraint `orders_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete set null;
alter table `orders` add unique `orders_order_number_unique`(`order_number`);
alter table `orders` add index `orders_status_index`(`status`);
alter table `orders` add index `orders_payment_status_index`(`payment_status`);
create table `order_items` (`id` bigint unsigned not null auto_increment primary key, `order_id` bigint unsigned not null, `product_id` bigint unsigned null, `product_variant_id` bigint unsigned null, `product_name` varchar(255) not null, `size_name` varchar(255) not null, `color_name` varchar(255) not null, `color_hex` varchar(255) null, `sku` varchar(255) not null, `image_path` varchar(255) null, `unit_price` decimal(10, 2) not null, `quantity` int not null, `subtotal` decimal(10, 2) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `order_items` add constraint `order_items_order_id_foreign` foreign key (`order_id`) references `orders` (`id`) on delete cascade;
alter table `order_items` add constraint `order_items_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete set null;
alter table `order_items` add constraint `order_items_product_variant_id_foreign` foreign key (`product_variant_id`) references `product_variants` (`id`) on delete set null;
create table `payments` (`id` bigint unsigned not null auto_increment primary key, `order_id` bigint unsigned not null, `payment_id` varchar(255) null, `razorpay_order_id` varchar(255) null, `razorpay_signature` varchar(255) null, `payment_method` varchar(255) not null default 'razorpay', `currency` varchar(255) not null default 'INR', `amount` decimal(10, 2) not null, `status` varchar(255) not null default 'pending', `payload` json null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `payments` add constraint `payments_order_id_foreign` foreign key (`order_id`) references `orders` (`id`) on delete cascade;
alter table `payments` add index `payments_payment_id_index`(`payment_id`);
alter table `payments` add index `payments_razorpay_order_id_index`(`razorpay_order_id`);
create table `reviews` (`id` bigint unsigned not null auto_increment primary key, `product_id` bigint unsigned not null, `user_id` bigint unsigned not null, `order_id` bigint unsigned null, `rating` tinyint unsigned not null default '5', `title` varchar(255) null, `comment` text not null, `is_verified_purchase` tinyint(1) not null default '0', `is_approved` tinyint(1) not null default '1', `is_featured` tinyint(1) not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `reviews` add constraint `reviews_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade;
alter table `reviews` add constraint `reviews_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
alter table `reviews` add constraint `reviews_order_id_foreign` foreign key (`order_id`) references `orders` (`id`) on delete set null;
create table `newsletter_subscribers` (`id` bigint unsigned not null auto_increment primary key, `email` varchar(255) not null, `is_active` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `newsletter_subscribers` add unique `newsletter_subscribers_email_unique`(`email`);
create table `settings` (`id` bigint unsigned not null auto_increment primary key, `key` varchar(255) not null, `value` longtext null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `settings` add unique `settings_key_unique`(`key`);
create table `customizable_products` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `slug` varchar(255) not null, `type` enum('polo_tshirt', 'tshirt') not null default 'polo_tshirt', `short_description` text null, `description` longtext null, `base_price` decimal(10, 2) not null default '1499', `discount_price` decimal(10, 2) null, `sku` varchar(255) not null, `featured_image` varchar(255) null, `mockup_views` json null, `is_active` tinyint(1) not null default '1', `sort_order` int not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `customizable_products` add unique `customizable_products_slug_unique`(`slug`);
alter table `customizable_products` add unique `customizable_products_sku_unique`(`sku`);
create table `customizable_product_variants` (`id` bigint unsigned not null auto_increment primary key, `customizable_product_id` bigint unsigned not null, `size_id` bigint unsigned not null, `color_id` bigint unsigned not null, `stock_quantity` int not null default '100', `price_override` decimal(10, 2) null, `is_active` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `customizable_product_variants` add constraint `customizable_product_variants_customizable_product_id_foreign` foreign key (`customizable_product_id`) references `customizable_products` (`id`) on delete cascade;
alter table `customizable_product_variants` add constraint `customizable_product_variants_size_id_foreign` foreign key (`size_id`) references `sizes` (`id`) on delete cascade;
alter table `customizable_product_variants` add constraint `customizable_product_variants_color_id_foreign` foreign key (`color_id`) references `colors` (`id`) on delete cascade;
alter table `customizable_product_variants` add unique `cust_prod_variant_unique`(`customizable_product_id`, `size_id`, `color_id`);
create table `customization_settings` (`id` bigint unsigned not null auto_increment primary key, `customizable_product_id` bigint unsigned not null, `allow_front` tinyint(1) not null default '1', `allow_back` tinyint(1) not null default '0', `allowed_file_types` json null, `max_file_size_mb` int not null default '15', `allowed_fonts` json null, `min_text_len` int not null default '1', `max_text_len` int not null default '40', `max_images` int not null default '4', `print_area` json null, `base_customization_fee` decimal(10, 2) not null default '0', `extra_image_fee` decimal(10, 2) not null default '100', `extra_text_fee` decimal(10, 2) not null default '50', `bulk_pricing` json null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `customization_settings` add constraint `customization_settings_customizable_product_id_foreign` foreign key (`customizable_product_id`) references `customizable_products` (`id`) on delete cascade;
alter table `customization_settings` add unique `customization_settings_customizable_product_id_unique`(`customizable_product_id`);
create table `saved_custom_designs` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `customizable_product_id` bigint unsigned not null, `color_id` bigint unsigned null, `size_id` bigint unsigned null, `title` varchar(255) not null default 'My Custom Design', `design_data` longtext not null, `preview_path` varchar(255) null, `artwork_paths` json null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `saved_custom_designs` add constraint `saved_custom_designs_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
alter table `saved_custom_designs` add constraint `saved_custom_designs_customizable_product_id_foreign` foreign key (`customizable_product_id`) references `customizable_products` (`id`) on delete cascade;
alter table `saved_custom_designs` add constraint `saved_custom_designs_color_id_foreign` foreign key (`color_id`) references `colors` (`id`) on delete set null;
alter table `saved_custom_designs` add constraint `saved_custom_designs_size_id_foreign` foreign key (`size_id`) references `sizes` (`id`) on delete set null;
alter table `cart_items` modify `product_variant_id` bigint unsigned null;
alter table `cart_items` add `is_customized` tinyint(1) not null default '0' after `product_variant_id`;
alter table `cart_items` add `customizable_product_id` bigint unsigned null after `is_customized`;
alter table `cart_items` add constraint `cart_items_customizable_product_id_foreign` foreign key (`customizable_product_id`) references `customizable_products` (`id`) on delete set null;
alter table `cart_items` add `custom_color_id` bigint unsigned null after `customizable_product_id`;
alter table `cart_items` add constraint `cart_items_custom_color_id_foreign` foreign key (`custom_color_id`) references `colors` (`id`) on delete set null;
alter table `cart_items` add `custom_size_id` bigint unsigned null after `custom_color_id`;
alter table `cart_items` add constraint `cart_items_custom_size_id_foreign` foreign key (`custom_size_id`) references `sizes` (`id`) on delete set null;
alter table `cart_items` add `customization_data` json null after `custom_size_id`;
alter table `cart_items` add `customization_preview_path` varchar(255) null after `customization_data`;
alter table `cart_items` add `artwork_file_path` varchar(255) null after `customization_preview_path`;
alter table `cart_items` add `artwork_files` json null after `artwork_file_path`;
alter table `cart_items` add `customization_fee` decimal(10, 2) not null default '0' after `artwork_files`;
alter table `order_items` modify `product_id` bigint unsigned null;
alter table `order_items` modify `product_variant_id` bigint unsigned null;
alter table `order_items` add `is_customized` tinyint(1) not null default '0' after `product_variant_id`;
alter table `order_items` add `customizable_product_id` bigint unsigned null after `is_customized`;
alter table `order_items` add constraint `order_items_customizable_product_id_foreign` foreign key (`customizable_product_id`) references `customizable_products` (`id`) on delete set null;
alter table `order_items` add `customization_data` json null after `customizable_product_id`;
alter table `order_items` add `customization_preview_path` varchar(255) null after `customization_data`;
alter table `order_items` add `artwork_file_path` varchar(255) null after `customization_preview_path`;
alter table `order_items` add `artwork_files` json null after `artwork_file_path`;
alter table `order_items` add `customization_fee` decimal(10, 2) not null default '0' after `artwork_files`;
alter table `order_items` add `custom_production_status` varchar(255) not null default 'pending' after `customization_fee`;
create table `banners` (`id` bigint unsigned not null auto_increment primary key, `title` varchar(255) not null, `subtitle` varchar(255) null, `badge_text` varchar(255) null, `cta_text` varchar(255) not null default 'Shop Now', `cta_url` varchar(255) not null default '/shop', `image_path` varchar(255) not null, `mobile_image_path` varchar(255) null, `sort_order` int not null default '1', `is_active` tinyint(1) not null default '1', `text_alignment` varchar(255) not null default 'left', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `banners` add index `banners_sort_order_index`(`sort_order`);
alter table `banners` add index `banners_is_active_index`(`is_active`);
alter table `banners` add `position` varchar(255) not null default 'hero' after `sort_order`;
alter table `banners` add index `banners_position_index`(`position`);

-- ========================================================
-- TABLE DATA INSERTS
-- ========================================================

-- --------------------------------------------------------
-- Data for table `addresses` (1 rows)
-- --------------------------------------------------------
INSERT INTO `addresses` (`id`, `user_id`, `full_name`, `phone`, `address_line_1`, `address_line_2`, `landmark`, `city`, `state`, `postal_code`, `country`, `address_type`, `is_default`, `created_at`, `updated_at`) VALUES
  (1, 3, 'Julian Montgomery', '+91 98200 12345', 'hhhf', 'xjj', 'jhjdhd', 'shshhs', 'bsbsb', '123456', 'India', 'home', 1, '2026-08-17 20:48:57', '2026-08-17 20:48:57');

-- --------------------------------------------------------
-- Data for table `banners` (8 rows)
-- --------------------------------------------------------
INSERT INTO `banners` (`id`, `title`, `subtitle`, `badge_text`, `cta_text`, `cta_url`, `image_path`, `mobile_image_path`, `sort_order`, `is_active`, `text_alignment`, `created_at`, `updated_at`, `position`) VALUES
  (1, 'The Polo Collection', 'Meticulously tailored from 100% American Supima® cotton, Mulberry silk blends, and stay-flat collar technology.', '✦ 50 Bespoke Silhouettes', 'Shop Supima® Series', '/shop?fabric=Supima', '/images/banners/banner-studio-haute.jpg', NULL, 1, 1, 'left', '2026-08-19 16:38:52', '2026-08-21 16:05:10', 'shop'),
  (2, 'Mulberry Silk & 24-Gauge Knits', 'Ultra-fine natural luster meets athletic breathability for effortless evening elegance.', '★ Rare Silk Collection', 'Explore Silk Knits', '/shop?fabric=Silk', '/images/banners/banner-silk-atelier.jpg', NULL, 2, 1, 'left', '2026-08-19 16:38:52', '2026-08-21 16:05:10', 'shop'),
  (3, 'Riviera Earth & Espresso Drop', '14 new season colorways inspired by Portofino coastlines and dyed with eco-friendly reactive pigments.', '◆ New Season Arrivals', 'Shop New Arrivals', '/shop?collection=new-arrivals', '/images/banners/banner-riviera-earth.jpg', NULL, 3, 1, 'left', '2026-08-19 16:38:52', '2026-08-21 16:05:10', 'shop'),
  (4, 'Loved. Worn. Repeated.', 'Our highest-rated customer icons, engineered with genuine Australian mother-of-pearl hardware.', '● 4.9/5 Star Customer Icons', 'Shop Best Sellers', '/shop?collection=bestsellers', '/images/banners/banner-classic-bestseller.jpg', NULL, 4, 1, 'right', '2026-08-19 16:38:52', '2026-08-21 15:57:36', 'hero'),
  (5, 'Collar Engineering That Never Curls.', 'Internal micro-fused interlining and reinforced plackets engineered to stay razor-sharp through 50+ washes.', '✦ Atelier Craftsmanship', 'Explore Stay-Flat Series', '/shop?fabric=Supima', '/images/banners/banner-studio-haute.jpg', NULL, 5, 1, 'right', '2026-08-19 16:38:52', '2026-08-21 15:57:36', 'hero'),
  (6, 'The Polo, Perfected.', 'Meticulously tailored from 100% long-staple American Supima® cotton with our stay-flat collar guarantee.', '✦ The Haute Collection', 'Shop Now', '/shop?fabric=Supima', '/images/banners/banner-studio-haute.jpg', NULL, 1, 1, 'right', '2026-08-21 16:06:39', '2026-08-21 16:06:39', 'hero'),
  (7, 'Mulberry Silk & Supima®', 'Silken drape meets athletic breathability. An ultra-fine 24-gauge knit engineered for effortless evening elegance.', '★ Rare 24-Gauge Silk Knit', 'Explore Haute Knits', '/shop?fabric=Silk', '/images/banners/banner-silk-atelier.jpg', NULL, 2, 1, 'right', '2026-08-21 16:06:39', '2026-08-21 16:06:39', 'hero'),
  (8, 'Riviera Earth & Espresso', '14 new season colorways inspired by Portofino coastlines and dyed with eco-friendly reactive pigments.', '◆ New Season Drop', 'Shop New Arrivals', '/shop?collection=new-arrivals', '/images/banners/banner-riviera-earth.jpg', NULL, 3, 1, 'right', '2026-08-21 16:06:39', '2026-08-21 16:06:39', 'hero');

-- --------------------------------------------------------
-- Data for table `cache` (4 rows)
-- --------------------------------------------------------
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
  ('laravel-cache-setting_free_shipping_threshold', 's:4:"1999";', 2102360893),
  ('laravel-cache-setting_standard_shipping_rate', 's:3:"150";', 2102360893),
  ('laravel-cache-setting_tax_rate', 's:1:"0";', 2102360893),
  ('laravel-cache-setting_contact_phone', 's:20:"+91 (0) 22 8900 1200";', 2102362085);

-- --------------------------------------------------------
-- Data for table `cart_items` (3 rows)
-- --------------------------------------------------------
INSERT INTO `cart_items` (`id`, `cart_id`, `product_variant_id`, `quantity`, `unit_price`, `created_at`, `updated_at`, `is_customized`, `customizable_product_id`, `custom_color_id`, `custom_size_id`, `customization_data`, `customization_preview_path`, `artwork_file_path`, `artwork_files`, `customization_fee`) VALUES
  (1, 19, 4476, 1, 1499, '2026-09-07 18:07:43', '2026-09-07 18:07:43', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
  (2, 19, 4500, 1, 1799, '2026-09-07 18:07:50', '2026-09-07 18:07:50', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
  (3, 19, 5102, 1, 2299, '2026-09-07 18:16:59', '2026-09-07 18:16:59', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------
-- Data for table `carts` (29 rows)
-- --------------------------------------------------------
INSERT INTO `carts` (`id`, `user_id`, `session_id`, `coupon_code`, `created_at`, `updated_at`) VALUES
  (1, NULL, 'st5aH4IXC0TDRMBC9Q4TKLcmSkdQIdQHNMTe6VFW', NULL, '2026-08-17 20:47:11', '2026-08-17 20:47:11'),
  (2, 1, NULL, NULL, '2026-08-17 20:47:14', '2026-08-17 20:47:14'),
  (3, NULL, 'v8xAOvVbpnk4yXSibDGgLDhAwAQH7WAreHuCkk6V', NULL, '2026-08-17 20:47:46', '2026-08-17 20:47:46'),
  (4, 3, NULL, NULL, '2026-08-17 20:48:21', '2026-08-17 20:48:21'),
  (5, NULL, 'YBU03o75g4HFfbgEQM0ZH9xOOt97R5eK2SR1cPvd', NULL, '2026-08-17 20:50:30', '2026-08-17 20:50:30'),
  (6, NULL, 'jzeV1tQVpkCjNLE3saZgN4OYMXRvEZ7FVb2fC9fV', NULL, '2026-08-17 20:57:17', '2026-08-17 20:57:17'),
  (7, NULL, 'QAbn8B2f3RUYprnQqZqkDSWJqj2zqPQRb85wLeke', NULL, '2026-08-17 21:01:23', '2026-08-17 21:01:23'),
  (8, NULL, 'ygQlzHD1MgY4Ne58rEUiYwEiUqx6L1gwh1owHIGb', NULL, '2026-08-18 06:21:20', '2026-08-18 06:21:20'),
  (9, NULL, '8o6Gck1nTyUe6gxybgn7dILwOu9mozPegODhMqwG', NULL, '2026-08-18 13:40:20', '2026-08-18 13:47:19'),
  (10, NULL, 'zP5r1o7SM7MoebdPmEXSP6qMNd1d6r4Zfh1seILZ', NULL, '2026-08-18 13:54:03', '2026-08-18 13:54:03'),
  (11, NULL, 'HPLV2s8Aex0CfIoOv9PzCH1uUcoeYRBdgwsngl5P', NULL, '2026-08-19 16:54:38', '2026-08-19 16:54:38'),
  (12, NULL, 'lj4tM5Bj0xsTSumFJ5FwrrYgYooWakAEU5G2sCdy', NULL, '2026-08-20 03:03:54', '2026-08-20 03:03:54'),
  (13, NULL, 'iyy56FpKNTJSsv4vTiigNdko4QPwNgmS9CA3Rtr4', NULL, '2026-08-21 15:11:13', '2026-08-21 15:11:13'),
  (14, NULL, 'qwJe0vBYUNTj5ho8X5UMrRQEhgjIz4IchuVoSBCq', NULL, '2026-08-21 15:21:20', '2026-08-21 15:21:20'),
  (15, NULL, '2Z1TczfO1ngQQmsK3s5SNtOjbhgVo3bId1sKQFLk', NULL, '2026-08-22 16:45:30', '2026-08-22 16:45:30'),
  (16, NULL, 'jjkM6Dvd7n3uZRV8zM4o9TbJAyGyQmYXpKhBdc52', NULL, '2026-08-28 18:42:03', '2026-08-28 18:42:03'),
  (17, NULL, 'dft5WCIYcj71yXCvR6UQr4g1BK4wwwOF24gYOYHk', NULL, '2026-08-28 19:01:59', '2026-08-28 19:01:59'),
  (18, NULL, 'UdWEoBGU1u3htrFzkuEFyDECkbIYzOD15OJQNPJA', NULL, '2026-09-07 17:46:12', '2026-09-07 17:46:12'),
  (19, NULL, 'WFWWwSs9mUUyvh77XjhWnoo5dt9OsRDL2PDASmWG', NULL, '2026-09-07 18:03:31', '2026-09-07 18:03:31'),
  (20, NULL, 'D7X3Zf4Zu1hNj2QbYPk6RmPAOi15i7hBmBULbnB1', NULL, '2026-09-07 23:23:51', '2026-09-07 23:23:51'),
  (21, NULL, 'p8hDNGT8E44HEW5KmCOo0PlheUbslsLcmH1pzFLh', NULL, '2026-09-08 08:09:42', '2026-09-08 08:09:42'),
  (22, NULL, 'pBqymFMQqVzdGJBwLySJTDNR4qRRDy8SCLUWw8iI', NULL, '2026-09-08 15:38:01', '2026-09-08 15:38:01'),
  (23, NULL, 'Fp6bvVkGjJ3SysCVyHYCIMB02EHIZROIKbrPZdiC', NULL, '2026-09-08 17:48:27', '2026-09-08 17:48:27'),
  (24, NULL, 'PFpCh6z1XeOwU84vL2rYEkj6mGxCfsK02laImGoi', NULL, '2026-09-08 22:17:25', '2026-09-08 22:17:25'),
  (25, NULL, 'LGD2yyReDjYIpLAVdZdCMma16Dm17ZGZirnXn7iy', NULL, '2026-09-09 08:44:47', '2026-09-09 08:44:47'),
  (26, NULL, 'yOhwlAgqYMjImlEGaSKOjser7YM08R8B44IwQiGc', NULL, '2026-09-09 15:54:41', '2026-09-09 15:54:41'),
  (27, NULL, 'XnwBbiwqFV6P5QHYQWOkluA0V8DM4yzHrIox4mia', NULL, '2026-09-09 19:51:10', '2026-09-09 19:51:10'),
  (28, NULL, 'cPg5NdC6S4tMCxSSnX2VSNIA51YqD7AcCou3OsLY', NULL, '2026-09-10 05:35:59', '2026-09-10 05:35:59'),
  (29, NULL, 'D0XCnASKrEv4UFg1wq9sdNkd2Tpij7sGU7TPpJg9', NULL, '2026-09-10 09:17:36', '2026-09-10 09:17:36');

-- --------------------------------------------------------
-- Data for table `categories` (3 rows)
-- --------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  (1, 'Men''s Polo T-Shirts', 'mens-polo-t-shirts', 'Meticulously tailored luxury polo t-shirts engineered from 100% Supima® cotton, Mulberry silk blends, and stay-flat structured collars.', 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop&q=80', 1, 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (2, 'Round Neck T-Shirts', 'round-neck-t-shirts', 'Featherweight premium combed cotton round neck tees designed with a sculpted drape, reinforced ribbing, and ultra-soft tactile feel.', 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop&q=80', 1, 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (3, 'New Arrival T-Shirts', 'new-arrival-t-shirts', 'Latest seasonal drops featuring limited-edition colorways, performance knit textures, and bespoke sartorial finishes.', 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800&auto=format&fit=crop&q=80', 1, 3, '2026-08-17 20:46:25', '2026-08-17 20:46:25');

-- --------------------------------------------------------
-- Data for table `colors` (15 rows)
-- --------------------------------------------------------
INSERT INTO `colors` (`id`, `name`, `slug`, `hex_code`, `created_at`, `updated_at`) VALUES
  (1, 'Obsidian Black', 'obsidian-black', '#18181B', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (2, 'Pure White', 'pure-white', '#F8FAFC', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (3, 'Royal Navy', 'royal-navy', '#1E293B', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (4, 'Olive Dusk', 'olive-dusk', '#3F4E3F', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (5, 'Heather Grey', 'heather-grey', '#64748B', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (6, 'Burgundy Royale', 'burgundy-royale', '#5C1D24', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (7, 'Sand Beige', 'desert-sand', '#D6C7B2', '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (8, 'Forest Green', 'forest-emerald', '#1B4332', '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (9, 'Slate Charcoal', 'slate-charcoal', '#334155', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (10, 'Sapphire Blue', 'sapphire-blue', '#1D4ED8', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (11, 'Royal Blue', 'royal-blue', '#1D4ED8', '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (12, 'Sky Blue', 'sky-blue', '#7DD3FC', '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (13, 'Classic Maroon', 'classic-maroon', '#800000', '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (14, 'Warm Mustard', 'warm-mustard', '#D97706', '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (15, 'Ivory Cream', 'ivory-cream', '#FFFBEB', '2026-08-19 16:38:52', '2026-08-19 16:38:52');

-- --------------------------------------------------------
-- Data for table `coupon_usages` (1 rows)
-- --------------------------------------------------------
INSERT INTO `coupon_usages` (`id`, `coupon_id`, `user_id`, `order_id`, `discount_amount`, `created_at`, `updated_at`) VALUES
  (1, 2, NULL, 3, 300, '2026-08-18 13:47:19', NULL);

-- --------------------------------------------------------
-- Data for table `coupons` (4 rows)
-- --------------------------------------------------------
INSERT INTO `coupons` (`id`, `code`, `description`, `discount_type`, `value`, `min_order_value`, `max_discount`, `usage_limit`, `usage_limit_per_user`, `used_count`, `starts_at`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES
  (1, 'JACARIO10', '10% off on all orders above ₹1,000', 'percentage', 10, 1000, 500, 1000, 3, 0, '2026-07-28 18:59:02', '2027-08-28 18:59:02', 1, '2026-08-17 20:46:25', '2026-08-28 18:59:02'),
  (2, 'FIRSTPOLO', 'Flat ₹300 off on your first JACARIO order above ₹1,499', 'fixed', 300, 1499, NULL, 500, 1, 1, '2026-07-28 18:59:02', '2027-08-28 18:59:02', 1, '2026-08-17 20:46:25', '2026-08-28 18:59:02'),
  (3, 'LUXURY20', '20% off on premium orders above ₹2,999 (Max ₹1,000)', 'percentage', 20, 2999, 1000, 200, 2, 0, '2026-07-28 18:59:02', '2027-08-28 18:59:02', 1, '2026-08-17 20:46:25', '2026-08-28 18:59:02'),
  (4, 'VIP500', 'Flat ₹500 off on luxury orders above ₹3,500', 'fixed', 500, 3500, NULL, 100, 1, 0, '2026-07-28 18:59:02', '2027-08-28 18:59:02', 1, '2026-08-17 20:46:25', '2026-08-28 18:59:02');

-- --------------------------------------------------------
-- Data for table `customizable_product_variants` (180 rows)
-- --------------------------------------------------------
INSERT INTO `customizable_product_variants` (`id`, `customizable_product_id`, `size_id`, `color_id`, `stock_quantity`, `price_override`, `is_active`, `created_at`, `updated_at`) VALUES
  (1, 1, 1, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (2, 1, 1, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (3, 1, 1, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (4, 1, 1, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (5, 1, 1, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (6, 1, 1, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (7, 1, 1, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (8, 1, 1, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (9, 1, 1, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (10, 1, 1, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (11, 1, 1, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (12, 1, 1, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (13, 1, 1, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (14, 1, 1, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (15, 1, 1, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (16, 1, 2, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (17, 1, 2, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (18, 1, 2, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (19, 1, 2, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (20, 1, 2, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (21, 1, 2, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (22, 1, 2, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (23, 1, 2, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (24, 1, 2, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (25, 1, 2, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (26, 1, 2, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (27, 1, 2, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (28, 1, 2, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (29, 1, 2, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (30, 1, 2, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (31, 1, 3, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (32, 1, 3, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (33, 1, 3, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (34, 1, 3, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (35, 1, 3, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (36, 1, 3, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (37, 1, 3, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (38, 1, 3, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (39, 1, 3, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (40, 1, 3, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (41, 1, 3, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (42, 1, 3, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (43, 1, 3, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (44, 1, 3, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (45, 1, 3, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (46, 1, 4, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (47, 1, 4, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (48, 1, 4, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (49, 1, 4, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (50, 1, 4, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57');
INSERT INTO `customizable_product_variants` (`id`, `customizable_product_id`, `size_id`, `color_id`, `stock_quantity`, `price_override`, `is_active`, `created_at`, `updated_at`) VALUES
  (51, 1, 4, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (52, 1, 4, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (53, 1, 4, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (54, 1, 4, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (55, 1, 4, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (56, 1, 4, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (57, 1, 4, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (58, 1, 4, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (59, 1, 4, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (60, 1, 4, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (61, 1, 5, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (62, 1, 5, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (63, 1, 5, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (64, 1, 5, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (65, 1, 5, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (66, 1, 5, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (67, 1, 5, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (68, 1, 5, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (69, 1, 5, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (70, 1, 5, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (71, 1, 5, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (72, 1, 5, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (73, 1, 5, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (74, 1, 5, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (75, 1, 5, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (76, 1, 6, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (77, 1, 6, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (78, 1, 6, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (79, 1, 6, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (80, 1, 6, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (81, 1, 6, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (82, 1, 6, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (83, 1, 6, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (84, 1, 6, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (85, 1, 6, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (86, 1, 6, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (87, 1, 6, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (88, 1, 6, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (89, 1, 6, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (90, 1, 6, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (91, 2, 1, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (92, 2, 1, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (93, 2, 1, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (94, 2, 1, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (95, 2, 1, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (96, 2, 1, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (97, 2, 1, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (98, 2, 1, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (99, 2, 1, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (100, 2, 1, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57');
INSERT INTO `customizable_product_variants` (`id`, `customizable_product_id`, `size_id`, `color_id`, `stock_quantity`, `price_override`, `is_active`, `created_at`, `updated_at`) VALUES
  (101, 2, 1, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (102, 2, 1, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (103, 2, 1, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (104, 2, 1, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (105, 2, 1, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (106, 2, 2, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (107, 2, 2, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (108, 2, 2, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (109, 2, 2, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (110, 2, 2, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (111, 2, 2, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (112, 2, 2, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (113, 2, 2, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (114, 2, 2, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (115, 2, 2, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (116, 2, 2, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (117, 2, 2, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (118, 2, 2, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (119, 2, 2, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (120, 2, 2, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (121, 2, 3, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (122, 2, 3, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (123, 2, 3, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (124, 2, 3, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (125, 2, 3, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (126, 2, 3, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (127, 2, 3, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (128, 2, 3, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (129, 2, 3, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (130, 2, 3, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (131, 2, 3, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (132, 2, 3, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (133, 2, 3, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (134, 2, 3, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (135, 2, 3, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (136, 2, 4, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (137, 2, 4, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (138, 2, 4, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (139, 2, 4, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (140, 2, 4, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (141, 2, 4, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (142, 2, 4, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (143, 2, 4, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (144, 2, 4, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (145, 2, 4, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (146, 2, 4, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (147, 2, 4, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (148, 2, 4, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (149, 2, 4, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (150, 2, 4, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57');
INSERT INTO `customizable_product_variants` (`id`, `customizable_product_id`, `size_id`, `color_id`, `stock_quantity`, `price_override`, `is_active`, `created_at`, `updated_at`) VALUES
  (151, 2, 5, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (152, 2, 5, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (153, 2, 5, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (154, 2, 5, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (155, 2, 5, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (156, 2, 5, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (157, 2, 5, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (158, 2, 5, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (159, 2, 5, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (160, 2, 5, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (161, 2, 5, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (162, 2, 5, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (163, 2, 5, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (164, 2, 5, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (165, 2, 5, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (166, 2, 6, 1, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (167, 2, 6, 2, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (168, 2, 6, 3, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (169, 2, 6, 4, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (170, 2, 6, 5, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (171, 2, 6, 6, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (172, 2, 6, 7, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (173, 2, 6, 8, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (174, 2, 6, 9, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (175, 2, 6, 10, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (176, 2, 6, 11, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (177, 2, 6, 12, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (178, 2, 6, 13, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (179, 2, 6, 14, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (180, 2, 6, 15, 150, NULL, 1, '2026-08-28 18:49:57', '2026-08-28 18:49:57');

-- --------------------------------------------------------
-- Data for table `customizable_products` (2 rows)
-- --------------------------------------------------------
INSERT INTO `customizable_products` (`id`, `name`, `slug`, `type`, `short_description`, `description`, `base_price`, `discount_price`, `sku`, `featured_image`, `mockup_views`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  (1, 'Customize Polo T-Shirt', 'polo-t-shirt', 'polo_tshirt', 'Engineered from signature 240 GSM double-pique cotton with reinforced knit collar and mother-of-pearl buttons. Bespoke embroidery & HD printing ready.', 'Craft your bespoke JACARIO Polo T-Shirt. Crafted from 100% long-staple Supima® double-pique cotton, featuring our structured anti-curl collar, tailored sleeves, and refined split-hem. Personalize with your custom logo artwork, brand initials, or typography with state-of-the-art print precision.', 1499, 1299, 'JAC-CUST-POLO', 'images/customizer/polo/black.jpg', '{"front":"images\/customizer\/polo\/{color}.jpg"}', 1, 1, '2026-08-28 18:49:57', '2026-08-29 09:40:33'),
  (2, 'Customize T-Shirt', 't-shirt', 'tshirt', 'Ultra-luxurious 220 GSM combed Supima® crewneck jersey. Ultra-soft touch, tailored modern fit, zero shrinkage.', 'Create your signature luxury Crewneck T-Shirt. Made with premium combed compact organic cotton, designed for seamless drape, incredible breathability, and long-lasting durability. Upload your graphics or craft custom typography with precision placement.', 999, 849, 'JAC-CUST-TSHIRT', 'images/customizer/tshirt/black.jpg', '{"front":"images\/customizer\/tshirt\/{color}.jpg"}', 1, 2, '2026-08-28 18:49:57', '2026-08-29 09:40:33');

-- --------------------------------------------------------
-- Data for table `customization_settings` (2 rows)
-- --------------------------------------------------------
INSERT INTO `customization_settings` (`id`, `customizable_product_id`, `allow_front`, `allow_back`, `allowed_file_types`, `max_file_size_mb`, `allowed_fonts`, `min_text_len`, `max_text_len`, `max_images`, `print_area`, `base_customization_fee`, `extra_image_fee`, `extra_text_fee`, `bulk_pricing`, `created_at`, `updated_at`) VALUES
  (1, 1, 1, 0, '["png","jpg","jpeg","svg","webp"]', 15, '["Cinzel","Plus Jakarta Sans","Inter","Playfair Display","Montserrat","Bebas Neue","Outfit","Space Grotesk"]', 1, 50, 4, '{"top":32,"left":30,"width":40,"height":42}', 0, 99, 49, '[{"min_qty":5,"discount_percent":10},{"min_qty":15,"discount_percent":18},{"min_qty":30,"discount_percent":25},{"min_qty":50,"discount_percent":35}]', '2026-08-28 18:49:57', '2026-08-28 18:49:57'),
  (2, 2, 1, 0, '["png","jpg","jpeg","svg","webp"]', 15, '["Cinzel","Plus Jakarta Sans","Inter","Playfair Display","Montserrat","Bebas Neue","Outfit","Space Grotesk"]', 1, 50, 4, '{"top":28,"left":28,"width":44,"height":46}', 0, 99, 49, '[{"min_qty":5,"discount_percent":10},{"min_qty":15,"discount_percent":18},{"min_qty":30,"discount_percent":25},{"min_qty":50,"discount_percent":35}]', '2026-08-28 18:49:57', '2026-08-28 18:49:57');

-- --------------------------------------------------------
-- Data for table `migrations` (15 rows)
-- --------------------------------------------------------
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
  (1, '0001_01_01_000000_create_users_table', 1),
  (2, '0001_01_01_000001_create_cache_table', 1),
  (3, '0001_01_01_000002_create_jobs_table', 1),
  (4, '2026_01_01_000010_create_categories_table', 1),
  (5, '2026_01_01_000020_create_sizes_and_colors_tables', 1),
  (6, '2026_01_01_000030_create_products_and_variants_tables', 1),
  (7, '2026_01_01_000040_create_carts_and_items_tables', 1),
  (8, '2026_01_01_000050_create_wishlists_and_addresses_tables', 1),
  (9, '2026_01_01_000060_create_coupons_and_usages_tables', 1),
  (10, '2026_01_01_000070_create_orders_and_payments_tables', 1),
  (11, '2026_01_01_000080_create_reviews_and_newsletters_and_settings_tables', 1),
  (12, '2026_08_19_163733_create_banners_table', 2),
  (13, '2026_08_21_160146_add_position_to_banners_table', 3),
  (14, '2026_01_01_000090_create_customizable_products_and_settings_tables', 4),
  (15, '2026_01_01_000091_add_customization_fields_to_cart_and_order_items', 4);

-- --------------------------------------------------------
-- Data for table `order_items` (3 rows)
-- --------------------------------------------------------
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_variant_id`, `product_name`, `size_name`, `color_name`, `color_hex`, `sku`, `image_path`, `unit_price`, `quantity`, `subtotal`, `created_at`, `updated_at`, `is_customized`, `customizable_product_id`, `customization_data`, `customization_preview_path`, `artwork_file_path`, `artwork_files`, `customization_fee`, `custom_production_status`) VALUES
  (1, 1, 1, NULL, 'JACARIO Signature Obsidian Supima Polo', 'S', 'Obsidian Black', '#18181B', 'JAC-POLO-001-obsidian-black-S', 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 1999, 1, 1999, '2026-08-17 20:48:57', '2026-08-17 20:48:57', 0, NULL, NULL, NULL, NULL, NULL, 0, 'pending'),
  (2, 2, 2, NULL, 'JACARIO Amalfi Navy Silk-Cotton Polo', 'S', 'Obsidian Black', '#18181B', 'JAC-POLO-002-obsidian-black-S', 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 2399, 1, 2399, '2026-08-17 20:57:51', '2026-08-17 20:57:51', 0, NULL, NULL, NULL, NULL, NULL, 0, 'pending'),
  (3, 3, 1, NULL, 'JACARIO Signature Obsidian Supima Polo', 'M', 'Obsidian Black', '#18181B', 'JAC-POLO-001-obsidian-black-M', 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 1999, 1, 1999, '2026-08-18 13:47:19', '2026-08-18 13:47:19', 0, NULL, NULL, NULL, NULL, NULL, 0, 'pending');

-- --------------------------------------------------------
-- Data for table `orders` (3 rows)
-- --------------------------------------------------------
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `subtotal`, `discount_amount`, `shipping_amount`, `tax_amount`, `total_amount`, `coupon_code`, `status`, `payment_method`, `payment_status`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `billing_address`, `tracking_number`, `courier_name`, `notes`, `cancelled_reason`, `shipped_at`, `delivered_at`, `created_at`, `updated_at`) VALUES
  (1, 'JAC-2026-RW6N11', 3, 1999, 0, 0, 0, 1999, NULL, 'cancelled', 'cod', 'pending', 'Julian Montgomery', 'customer@jacario.com', '+91 98200 12345', '{"full_name":"Julian Montgomery","phone":"+91 98200 12345","address_line_1":"hhhf","address_line_2":"xjj","landmark":"jhjdhd","city":"shshhs","state":"bsbsb","postal_code":"123456","country":"India"}', '{"full_name":"Julian Montgomery","phone":"+91 98200 12345","address_line_1":"hhhf","address_line_2":"xjj","landmark":"jhjdhd","city":"shshhs","state":"bsbsb","postal_code":"123456","country":"India"}', NULL, NULL, NULL, 'Other', NULL, NULL, '2026-08-17 20:48:57', '2026-08-17 21:01:15'),
  (2, 'JAC-2026-W5TTWT', 3, 2399, 0, 0, 0, 2399, NULL, 'confirmed', 'cod', 'pending', 'Julian Montgomery', 'customer@jacario.com', '+91 98200 12345', '{"full_name":"Julian Montgomery","phone":"+91 98200 12345","address_line_1":"hhhf","address_line_2":"xjj","landmark":"jhjdhd","city":"shshhs","state":"bsbsb","postal_code":"123456","country":"India"}', '{"full_name":"Julian Montgomery","phone":"+91 98200 12345","address_line_1":"hhhf","address_line_2":"xjj","landmark":"jhjdhd","city":"shshhs","state":"bsbsb","postal_code":"123456","country":"India"}', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-17 20:57:50', '2026-08-17 20:57:51'),
  (3, 'JAC-2026-VHS3UI', NULL, 1999, 300, 0, 0, 1699, 'FIRSTPOLO', 'pending', 'cod', 'pending', 'John Doe', 'johndoe@example.com', '9820012345', '{"full_name":"John Doe","phone":"9820012345","address_line_1":"101 Taj Mansion, Taj Road","address_line_2":"Colaba","landmark":"Near Gateway of India","city":"Mumbai","state":"Maharashtra","postal_code":"400001","country":"India"}', '{"full_name":"John Doe","phone":"9820012345","address_line_1":"101 Taj Mansion, Taj Road","address_line_2":"Colaba","landmark":"Near Gateway of India","city":"Mumbai","state":"Maharashtra","postal_code":"400001","country":"India"}', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-18 13:47:19', '2026-08-18 13:50:50');

-- --------------------------------------------------------
-- Data for table `payments` (3 rows)
-- --------------------------------------------------------
INSERT INTO `payments` (`id`, `order_id`, `payment_id`, `razorpay_order_id`, `razorpay_signature`, `payment_method`, `currency`, `amount`, `status`, `payload`, `created_at`, `updated_at`) VALUES
  (1, 1, NULL, NULL, NULL, 'cod', 'INR', 1999, 'pending', NULL, '2026-08-17 20:48:57', '2026-08-17 20:48:57'),
  (2, 2, NULL, NULL, NULL, 'cod', 'INR', 2399, 'pending', NULL, '2026-08-17 20:57:51', '2026-08-17 20:57:51'),
  (3, 3, NULL, NULL, NULL, 'cod', 'INR', 1699, 'pending', NULL, '2026-08-18 13:47:19', '2026-08-18 13:47:19');

-- --------------------------------------------------------
-- Data for table `product_images` (113 rows)
-- --------------------------------------------------------
INSERT INTO `product_images` (`id`, `product_id`, `color_id`, `image_path`, `alt_text`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
  (13, 7, NULL, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Pitch Black Round Neck Tee', 1, 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (14, 7, NULL, 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Pitch Black Round Neck Tee', 0, 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (15, 8, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Heavyweight Minimalist White Round Neck Tee', 1, 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (16, 8, NULL, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80', 'JACARIO Heavyweight Minimalist White Round Neck Tee', 0, 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (17, 9, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Nautical Deep Navy Round Neck Tee', 1, 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (18, 9, NULL, 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 'JACARIO Nautical Deep Navy Round Neck Tee', 0, 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (19, 10, NULL, 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80', 'JACARIO Heather Grey Melange Round Neck Tee', 1, 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (20, 10, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Heather Grey Melange Round Neck Tee', 0, 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (21, 11, NULL, 'https://images.unsplash.com/photo-1625910513413-5fc5d2a67e4e?w=900&auto=format&fit=crop&q=80', 'JACARIO Royal Sapphire Silk-Knit Polo', 1, 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (22, 11, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Royal Sapphire Silk-Knit Polo', 0, 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (23, 12, NULL, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80', 'JACARIO Forest Emerald Textured Knit Tee', 1, 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (24, 12, NULL, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80', 'JACARIO Forest Emerald Textured Knit Tee', 0, 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (25, 13, NULL, 'products/T0TXZeks4byIg1MZduyt1O4LnDh0JF1XxTAiEzGo.webp', NULL, 1, 0, '2026-08-17 21:27:54', '2026-08-17 21:27:54'),
  (426, 1, NULL, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=900&auto=format&fit=crop&q=80', 'JACARIO Riviera Amalfi Silk Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (427, 1, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Riviera Amalfi Silk Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (428, 2, NULL, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80', 'JACARIO Tuscan Olive Honeycomb Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (429, 2, NULL, 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80', 'JACARIO Tuscan Olive Honeycomb Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (430, 3, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Pure Ivory Mercerized Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (431, 3, NULL, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80', 'JACARIO Pure Ivory Mercerized Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (432, 4, NULL, 'https://images.unsplash.com/photo-1618354691229-88d47f285158?w=900&auto=format&fit=crop&q=80', 'JACARIO Forest Emerald Textured Knit Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (433, 4, NULL, 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80', 'JACARIO Forest Emerald Textured Knit Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (434, 5, NULL, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Royal Sapphire Johnny Collar Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (435, 5, NULL, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80', 'JACARIO Royal Sapphire Johnny Collar Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (436, 6, NULL, 'https://images.unsplash.com/photo-1622445268462-328fb1660d00?w=900&auto=format&fit=crop&q=80', 'JACARIO Sand Dune Waffle Knit Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (437, 6, NULL, 'https://images.unsplash.com/photo-1589902860314-e910697dea18?w=900&auto=format&fit=crop&q=80', 'JACARIO Sand Dune Waffle Knit Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (438, 14, NULL, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80', 'JACARIO Burgundy Royale Tailored Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (439, 14, NULL, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Burgundy Royale Tailored Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (440, 15, NULL, 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=900&auto=format&fit=crop&q=80', 'JACARIO Slate Charcoal Performance Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (441, 15, NULL, 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80', 'JACARIO Slate Charcoal Performance Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (442, 16, NULL, 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 'JACARIO Signature Obsidian Supima Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (443, 16, NULL, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80', 'JACARIO Signature Obsidian Supima Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (444, 17, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Monaco Navy Piqué Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (445, 17, NULL, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80', 'JACARIO Monaco Navy Piqué Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (446, 18, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Oxford White Classic Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (447, 18, NULL, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80', 'JACARIO Oxford White Classic Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (448, 19, NULL, 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80', 'JACARIO Milano Heather Grey Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (449, 19, NULL, 'https://images.unsplash.com/photo-1625910513413-5fc5d2a67e4e?w=900&auto=format&fit=crop&q=80', 'JACARIO Milano Heather Grey Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (450, 20, NULL, 'https://images.unsplash.com/photo-1618354691229-88d47f285158?w=900&auto=format&fit=crop&q=80', 'JACARIO British Racing Green Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (451, 20, NULL, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80', 'JACARIO British Racing Green Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (452, 21, NULL, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80', 'JACARIO Midnight Blue Luxury Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (453, 21, NULL, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=900&auto=format&fit=crop&q=80', 'JACARIO Midnight Blue Luxury Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (454, 22, NULL, 'https://images.unsplash.com/photo-1622445268462-328fb1660d00?w=900&auto=format&fit=crop&q=80', 'JACARIO Desert Sand Pima Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (455, 22, NULL, 'https://images.unsplash.com/photo-1589902860314-e910697dea18?w=900&auto=format&fit=crop&q=80', 'JACARIO Desert Sand Pima Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (456, 23, NULL, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80', 'JACARIO Venetian Maroon Ribbed Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (457, 23, NULL, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Venetian Maroon Ribbed Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (458, 24, NULL, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=900&auto=format&fit=crop&q=80', 'JACARIO Capri Sky Blue Piqué Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (459, 24, NULL, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Capri Sky Blue Piqué Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (460, 25, NULL, 'https://images.unsplash.com/photo-1503342452485-86b7f54527ef?w=900&auto=format&fit=crop&q=80', 'JACARIO Tuscan Mustard Knit Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (461, 25, NULL, 'https://images.unsplash.com/photo-1622445268462-328fb1660d00?w=900&auto=format&fit=crop&q=80', 'JACARIO Tuscan Mustard Knit Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (462, 26, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO French Navy Stripe-Tipped Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_images` (`id`, `product_id`, `color_id`, `image_path`, `alt_text`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
  (463, 26, NULL, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80', 'JACARIO French Navy Stripe-Tipped Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (464, 27, NULL, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80', 'JACARIO Alpine White Performance Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (465, 27, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Alpine White Performance Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (466, 28, NULL, 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=900&auto=format&fit=crop&q=80', 'JACARIO Vintage Charcoal Enzyme Polo - View 1', 1, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (467, 28, NULL, 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80', 'JACARIO Vintage Charcoal Enzyme Polo - View 2', 0, 2, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (468, 29, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Portofino Ivory Silk-Blend Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (469, 29, NULL, 'https://images.unsplash.com/photo-1589902860314-e910697dea18?w=900&auto=format&fit=crop&q=80', 'JACARIO Portofino Ivory Silk-Blend Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (470, 30, NULL, 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80', 'JACARIO Olive Green Tactile Knit Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (471, 30, NULL, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80', 'JACARIO Olive Green Tactile Knit Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (472, 31, NULL, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Royal Cobalt Stretch Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (473, 31, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Royal Cobalt Stretch Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (474, 32, NULL, 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 'JACARIO Haute Atelier Mulberry Silk Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (475, 32, NULL, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80', 'JACARIO Haute Atelier Mulberry Silk Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (476, 33, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Giza 87 Egyptian Cotton Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (477, 33, NULL, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=900&auto=format&fit=crop&q=80', 'JACARIO Giza 87 Egyptian Cotton Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (478, 34, NULL, 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80', 'JACARIO Cashmere-Touch Supima Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (479, 34, NULL, 'https://images.unsplash.com/photo-1625910513413-5fc5d2a67e4e?w=900&auto=format&fit=crop&q=80', 'JACARIO Cashmere-Touch Supima Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (480, 35, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Florence Heritage Pearl Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (481, 35, NULL, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80', 'JACARIO Florence Heritage Pearl Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (482, 36, NULL, 'https://images.unsplash.com/photo-1618354691229-88d47f285158?w=900&auto=format&fit=crop&q=80', 'JACARIO Como Silk-Knit Open Collar Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (483, 36, NULL, 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80', 'JACARIO Como Silk-Knit Open Collar Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (484, 37, NULL, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80', 'JACARIO Imperial Bordeaux Pima Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (485, 37, NULL, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Imperial Bordeaux Pima Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (486, 38, NULL, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80', 'JACARIO Monaco Gold Monogram Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (487, 38, NULL, 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 'JACARIO Monaco Gold Monogram Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (488, 39, NULL, 'https://images.unsplash.com/photo-1622445268462-328fb1660d00?w=900&auto=format&fit=crop&q=80', 'JACARIO Riviera Sandstone Silk Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (489, 39, NULL, 'https://images.unsplash.com/photo-1589902860314-e910697dea18?w=900&auto=format&fit=crop&q=80', 'JACARIO Riviera Sandstone Silk Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (490, 40, NULL, 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Pitch Black Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (491, 40, NULL, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Pitch Black Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (492, 41, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Crisp White Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (493, 41, NULL, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Crisp White Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (494, 42, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Deep Navy Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (495, 42, NULL, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Deep Navy Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (496, 43, NULL, 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Marl Grey Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (497, 43, NULL, 'https://images.unsplash.com/photo-1625910513413-5fc5d2a67e4e?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Marl Grey Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (498, 44, NULL, 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Charcoal Heather Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (499, 44, NULL, 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Charcoal Heather Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (500, 45, NULL, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Olive Drab Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (501, 45, NULL, 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Olive Drab Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (502, 46, NULL, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Maroon Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (503, 46, NULL, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Maroon Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (504, 47, NULL, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Sky Breeze Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (505, 47, NULL, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Essential Sky Breeze Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (506, 48, NULL, 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80', 'JACARIO Contrast Collar Club Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (507, 48, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Contrast Collar Club Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (508, 49, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Riviera Nautical Stripe Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (509, 49, NULL, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Riviera Nautical Stripe Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (510, 50, NULL, 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=900&auto=format&fit=crop&q=80', 'JACARIO Aero Coolmax Tech Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (511, 50, NULL, 'https://images.unsplash.com/photo-1625910513413-5fc5d2a67e4e?w=900&auto=format&fit=crop&q=80', 'JACARIO Aero Coolmax Tech Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (512, 51, NULL, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Amalfi Sunset Terracotta Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_images` (`id`, `product_id`, `color_id`, `image_path`, `alt_text`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
  (513, 51, NULL, 'https://images.unsplash.com/photo-1622445268462-328fb1660d00?w=900&auto=format&fit=crop&q=80', 'JACARIO Amalfi Sunset Terracotta Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (514, 52, NULL, 'https://images.unsplash.com/photo-1618354691229-88d47f285158?w=900&auto=format&fit=crop&q=80', 'JACARIO Cypress Forest Heavy Piqué Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (515, 52, NULL, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80', 'JACARIO Cypress Forest Heavy Piqué Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (516, 53, NULL, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80', 'JACARIO Midnight Shadow Matte Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (517, 53, NULL, 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80', 'JACARIO Midnight Shadow Matte Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (518, 54, NULL, 'https://images.unsplash.com/photo-1589902860314-e910697dea18?w=900&auto=format&fit=crop&q=80', 'JACARIO Vintage Sand Heather Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (519, 54, NULL, 'https://images.unsplash.com/photo-1622445268462-328fb1660d00?w=900&auto=format&fit=crop&q=80', 'JACARIO Vintage Sand Heather Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (520, 55, NULL, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=900&auto=format&fit=crop&q=80', 'JACARIO Azure Blue Micro-Piqué Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (521, 55, NULL, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=900&auto=format&fit=crop&q=80', 'JACARIO Azure Blue Micro-Piqué Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (522, 56, NULL, 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80', 'JACARIO Sage Mist Relaxed Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (523, 56, NULL, 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80', 'JACARIO Sage Mist Relaxed Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (524, 57, NULL, 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80', 'JACARIO Grand Prix Royal Navy Polo - View 1', 1, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (525, 57, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80', 'JACARIO Grand Prix Royal Navy Polo - View 2', 0, 2, '2026-08-28 18:59:02', '2026-08-28 18:59:02');

-- --------------------------------------------------------
-- Data for table `product_variants` (1200 rows)
-- --------------------------------------------------------
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4446, 1, 1, 12, 'JAC-POLO-001-sky-blue-S', NULL, NULL, 27, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4447, 1, 2, 12, 'JAC-POLO-001-sky-blue-M', NULL, NULL, 27, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4448, 1, 3, 12, 'JAC-POLO-001-sky-blue-L', NULL, NULL, 34, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4449, 1, 4, 12, 'JAC-POLO-001-sky-blue-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4450, 1, 5, 12, 'JAC-POLO-001-sky-blue-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4451, 1, 6, 12, 'JAC-POLO-001-sky-blue-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4452, 1, 1, 1, 'JAC-POLO-001-obsidian-black-S', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4453, 1, 2, 1, 'JAC-POLO-001-obsidian-black-M', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4454, 1, 3, 1, 'JAC-POLO-001-obsidian-black-L', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4455, 1, 4, 1, 'JAC-POLO-001-obsidian-black-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4456, 1, 5, 1, 'JAC-POLO-001-obsidian-black-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4457, 1, 6, 1, 'JAC-POLO-001-obsidian-black-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4458, 1, 1, 2, 'JAC-POLO-001-pure-white-S', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4459, 1, 2, 2, 'JAC-POLO-001-pure-white-M', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4460, 1, 3, 2, 'JAC-POLO-001-pure-white-L', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4461, 1, 4, 2, 'JAC-POLO-001-pure-white-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4462, 1, 5, 2, 'JAC-POLO-001-pure-white-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4463, 1, 6, 2, 'JAC-POLO-001-pure-white-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4464, 1, 1, 3, 'JAC-POLO-001-royal-navy-S', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4465, 1, 2, 3, 'JAC-POLO-001-royal-navy-M', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4466, 1, 3, 3, 'JAC-POLO-001-royal-navy-L', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4467, 1, 4, 3, 'JAC-POLO-001-royal-navy-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4468, 1, 5, 3, 'JAC-POLO-001-royal-navy-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4469, 1, 6, 3, 'JAC-POLO-001-royal-navy-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4470, 2, 1, 4, 'JAC-POLO-002-olive-dusk-S', NULL, NULL, 31, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4471, 2, 2, 4, 'JAC-POLO-002-olive-dusk-M', NULL, NULL, 28, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4472, 2, 3, 4, 'JAC-POLO-002-olive-dusk-L', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4473, 2, 4, 4, 'JAC-POLO-002-olive-dusk-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4474, 2, 5, 4, 'JAC-POLO-002-olive-dusk-XXL', NULL, NULL, 26, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4475, 2, 6, 4, 'JAC-POLO-002-olive-dusk-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4476, 2, 1, 1, 'JAC-POLO-002-obsidian-black-S', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4477, 2, 2, 1, 'JAC-POLO-002-obsidian-black-M', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4478, 2, 3, 1, 'JAC-POLO-002-obsidian-black-L', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4479, 2, 4, 1, 'JAC-POLO-002-obsidian-black-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4480, 2, 5, 1, 'JAC-POLO-002-obsidian-black-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4481, 2, 6, 1, 'JAC-POLO-002-obsidian-black-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4482, 2, 1, 2, 'JAC-POLO-002-pure-white-S', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4483, 2, 2, 2, 'JAC-POLO-002-pure-white-M', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4484, 2, 3, 2, 'JAC-POLO-002-pure-white-L', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4485, 2, 4, 2, 'JAC-POLO-002-pure-white-XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4486, 2, 5, 2, 'JAC-POLO-002-pure-white-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4487, 2, 6, 2, 'JAC-POLO-002-pure-white-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4488, 2, 1, 3, 'JAC-POLO-002-royal-navy-S', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4489, 2, 2, 3, 'JAC-POLO-002-royal-navy-M', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4490, 2, 3, 3, 'JAC-POLO-002-royal-navy-L', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4491, 2, 4, 3, 'JAC-POLO-002-royal-navy-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4492, 2, 5, 3, 'JAC-POLO-002-royal-navy-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4493, 2, 6, 3, 'JAC-POLO-002-royal-navy-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4494, 3, 1, 2, 'JAC-POLO-003-pure-white-S', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4495, 3, 2, 2, 'JAC-POLO-003-pure-white-M', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4496, 3, 3, 2, 'JAC-POLO-003-pure-white-L', NULL, NULL, 32, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4497, 3, 4, 2, 'JAC-POLO-003-pure-white-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4498, 3, 5, 2, 'JAC-POLO-003-pure-white-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4499, 3, 6, 2, 'JAC-POLO-003-pure-white-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4500, 3, 1, 1, 'JAC-POLO-003-obsidian-black-S', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4501, 3, 2, 1, 'JAC-POLO-003-obsidian-black-M', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4502, 3, 3, 1, 'JAC-POLO-003-obsidian-black-L', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4503, 3, 4, 1, 'JAC-POLO-003-obsidian-black-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4504, 3, 5, 1, 'JAC-POLO-003-obsidian-black-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4505, 3, 6, 1, 'JAC-POLO-003-obsidian-black-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4506, 3, 1, 3, 'JAC-POLO-003-royal-navy-S', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4507, 3, 2, 3, 'JAC-POLO-003-royal-navy-M', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4508, 3, 3, 3, 'JAC-POLO-003-royal-navy-L', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4509, 3, 4, 3, 'JAC-POLO-003-royal-navy-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4510, 3, 5, 3, 'JAC-POLO-003-royal-navy-XXL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4511, 3, 6, 3, 'JAC-POLO-003-royal-navy-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4512, 3, 1, 4, 'JAC-POLO-003-olive-dusk-S', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4513, 3, 2, 4, 'JAC-POLO-003-olive-dusk-M', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4514, 3, 3, 4, 'JAC-POLO-003-olive-dusk-L', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4515, 3, 4, 4, 'JAC-POLO-003-olive-dusk-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4516, 3, 5, 4, 'JAC-POLO-003-olive-dusk-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4517, 3, 6, 4, 'JAC-POLO-003-olive-dusk-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4518, 4, 1, 8, 'JAC-POLO-004-forest-emerald-S', NULL, NULL, 31, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4519, 4, 2, 8, 'JAC-POLO-004-forest-emerald-M', NULL, NULL, 28, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4520, 4, 3, 8, 'JAC-POLO-004-forest-emerald-L', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4521, 4, 4, 8, 'JAC-POLO-004-forest-emerald-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4522, 4, 5, 8, 'JAC-POLO-004-forest-emerald-XXL', NULL, NULL, 29, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4523, 4, 6, 8, 'JAC-POLO-004-forest-emerald-3XL', NULL, NULL, 27, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4524, 4, 1, 1, 'JAC-POLO-004-obsidian-black-S', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4525, 4, 2, 1, 'JAC-POLO-004-obsidian-black-M', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4526, 4, 3, 1, 'JAC-POLO-004-obsidian-black-L', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4527, 4, 4, 1, 'JAC-POLO-004-obsidian-black-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4528, 4, 5, 1, 'JAC-POLO-004-obsidian-black-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4529, 4, 6, 1, 'JAC-POLO-004-obsidian-black-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4530, 4, 1, 2, 'JAC-POLO-004-pure-white-S', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4531, 4, 2, 2, 'JAC-POLO-004-pure-white-M', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4532, 4, 3, 2, 'JAC-POLO-004-pure-white-L', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4533, 4, 4, 2, 'JAC-POLO-004-pure-white-XL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4534, 4, 5, 2, 'JAC-POLO-004-pure-white-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4535, 4, 6, 2, 'JAC-POLO-004-pure-white-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4536, 4, 1, 3, 'JAC-POLO-004-royal-navy-S', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4537, 4, 2, 3, 'JAC-POLO-004-royal-navy-M', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4538, 4, 3, 3, 'JAC-POLO-004-royal-navy-L', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4539, 4, 4, 3, 'JAC-POLO-004-royal-navy-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4540, 4, 5, 3, 'JAC-POLO-004-royal-navy-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4541, 4, 6, 3, 'JAC-POLO-004-royal-navy-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4542, 5, 1, 11, 'JAC-POLO-005-royal-blue-S', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4543, 5, 2, 11, 'JAC-POLO-005-royal-blue-M', NULL, NULL, 33, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4544, 5, 3, 11, 'JAC-POLO-005-royal-blue-L', NULL, NULL, 30, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4545, 5, 4, 11, 'JAC-POLO-005-royal-blue-XL', NULL, NULL, 31, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4546, 5, 5, 11, 'JAC-POLO-005-royal-blue-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4547, 5, 6, 11, 'JAC-POLO-005-royal-blue-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4548, 5, 1, 1, 'JAC-POLO-005-obsidian-black-S', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4549, 5, 2, 1, 'JAC-POLO-005-obsidian-black-M', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4550, 5, 3, 1, 'JAC-POLO-005-obsidian-black-L', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4551, 5, 4, 1, 'JAC-POLO-005-obsidian-black-XL', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4552, 5, 5, 1, 'JAC-POLO-005-obsidian-black-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4553, 5, 6, 1, 'JAC-POLO-005-obsidian-black-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4554, 5, 1, 2, 'JAC-POLO-005-pure-white-S', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4555, 5, 2, 2, 'JAC-POLO-005-pure-white-M', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4556, 5, 3, 2, 'JAC-POLO-005-pure-white-L', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4557, 5, 4, 2, 'JAC-POLO-005-pure-white-XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4558, 5, 5, 2, 'JAC-POLO-005-pure-white-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4559, 5, 6, 2, 'JAC-POLO-005-pure-white-3XL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4560, 5, 1, 3, 'JAC-POLO-005-royal-navy-S', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4561, 5, 2, 3, 'JAC-POLO-005-royal-navy-M', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4562, 5, 3, 3, 'JAC-POLO-005-royal-navy-L', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4563, 5, 4, 3, 'JAC-POLO-005-royal-navy-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4564, 5, 5, 3, 'JAC-POLO-005-royal-navy-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4565, 5, 6, 3, 'JAC-POLO-005-royal-navy-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4566, 6, 1, 7, 'JAC-POLO-006-desert-sand-S', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4567, 6, 2, 7, 'JAC-POLO-006-desert-sand-M', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4568, 6, 3, 7, 'JAC-POLO-006-desert-sand-L', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4569, 6, 4, 7, 'JAC-POLO-006-desert-sand-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4570, 6, 5, 7, 'JAC-POLO-006-desert-sand-XXL', NULL, NULL, 35, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4571, 6, 6, 7, 'JAC-POLO-006-desert-sand-3XL', NULL, NULL, 29, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4572, 6, 1, 1, 'JAC-POLO-006-obsidian-black-S', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4573, 6, 2, 1, 'JAC-POLO-006-obsidian-black-M', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4574, 6, 3, 1, 'JAC-POLO-006-obsidian-black-L', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4575, 6, 4, 1, 'JAC-POLO-006-obsidian-black-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4576, 6, 5, 1, 'JAC-POLO-006-obsidian-black-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4577, 6, 6, 1, 'JAC-POLO-006-obsidian-black-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4578, 6, 1, 2, 'JAC-POLO-006-pure-white-S', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4579, 6, 2, 2, 'JAC-POLO-006-pure-white-M', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4580, 6, 3, 2, 'JAC-POLO-006-pure-white-L', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4581, 6, 4, 2, 'JAC-POLO-006-pure-white-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4582, 6, 5, 2, 'JAC-POLO-006-pure-white-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4583, 6, 6, 2, 'JAC-POLO-006-pure-white-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4584, 6, 1, 3, 'JAC-POLO-006-royal-navy-S', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4585, 6, 2, 3, 'JAC-POLO-006-royal-navy-M', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4586, 6, 3, 3, 'JAC-POLO-006-royal-navy-L', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4587, 6, 4, 3, 'JAC-POLO-006-royal-navy-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4588, 6, 5, 3, 'JAC-POLO-006-royal-navy-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4589, 6, 6, 3, 'JAC-POLO-006-royal-navy-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4590, 14, 1, 6, 'JAC-POLO-007-burgundy-royale-S', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4591, 14, 2, 6, 'JAC-POLO-007-burgundy-royale-M', NULL, NULL, 29, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4592, 14, 3, 6, 'JAC-POLO-007-burgundy-royale-L', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4593, 14, 4, 6, 'JAC-POLO-007-burgundy-royale-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4594, 14, 5, 6, 'JAC-POLO-007-burgundy-royale-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4595, 14, 6, 6, 'JAC-POLO-007-burgundy-royale-3XL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4596, 14, 1, 1, 'JAC-POLO-007-obsidian-black-S', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4597, 14, 2, 1, 'JAC-POLO-007-obsidian-black-M', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4598, 14, 3, 1, 'JAC-POLO-007-obsidian-black-L', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4599, 14, 4, 1, 'JAC-POLO-007-obsidian-black-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4600, 14, 5, 1, 'JAC-POLO-007-obsidian-black-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4601, 14, 6, 1, 'JAC-POLO-007-obsidian-black-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4602, 14, 1, 2, 'JAC-POLO-007-pure-white-S', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4603, 14, 2, 2, 'JAC-POLO-007-pure-white-M', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4604, 14, 3, 2, 'JAC-POLO-007-pure-white-L', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4605, 14, 4, 2, 'JAC-POLO-007-pure-white-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4606, 14, 5, 2, 'JAC-POLO-007-pure-white-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4607, 14, 6, 2, 'JAC-POLO-007-pure-white-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4608, 14, 1, 3, 'JAC-POLO-007-royal-navy-S', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4609, 14, 2, 3, 'JAC-POLO-007-royal-navy-M', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4610, 14, 3, 3, 'JAC-POLO-007-royal-navy-L', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4611, 14, 4, 3, 'JAC-POLO-007-royal-navy-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4612, 14, 5, 3, 'JAC-POLO-007-royal-navy-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4613, 14, 6, 3, 'JAC-POLO-007-royal-navy-3XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4614, 15, 1, 9, 'JAC-POLO-008-slate-charcoal-S', NULL, NULL, 29, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4615, 15, 2, 9, 'JAC-POLO-008-slate-charcoal-M', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4616, 15, 3, 9, 'JAC-POLO-008-slate-charcoal-L', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4617, 15, 4, 9, 'JAC-POLO-008-slate-charcoal-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4618, 15, 5, 9, 'JAC-POLO-008-slate-charcoal-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4619, 15, 6, 9, 'JAC-POLO-008-slate-charcoal-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4620, 15, 1, 1, 'JAC-POLO-008-obsidian-black-S', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4621, 15, 2, 1, 'JAC-POLO-008-obsidian-black-M', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4622, 15, 3, 1, 'JAC-POLO-008-obsidian-black-L', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4623, 15, 4, 1, 'JAC-POLO-008-obsidian-black-XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4624, 15, 5, 1, 'JAC-POLO-008-obsidian-black-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4625, 15, 6, 1, 'JAC-POLO-008-obsidian-black-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4626, 15, 1, 2, 'JAC-POLO-008-pure-white-S', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4627, 15, 2, 2, 'JAC-POLO-008-pure-white-M', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4628, 15, 3, 2, 'JAC-POLO-008-pure-white-L', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4629, 15, 4, 2, 'JAC-POLO-008-pure-white-XL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4630, 15, 5, 2, 'JAC-POLO-008-pure-white-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4631, 15, 6, 2, 'JAC-POLO-008-pure-white-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4632, 15, 1, 3, 'JAC-POLO-008-royal-navy-S', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4633, 15, 2, 3, 'JAC-POLO-008-royal-navy-M', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4634, 15, 3, 3, 'JAC-POLO-008-royal-navy-L', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4635, 15, 4, 3, 'JAC-POLO-008-royal-navy-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4636, 15, 5, 3, 'JAC-POLO-008-royal-navy-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4637, 15, 6, 3, 'JAC-POLO-008-royal-navy-3XL', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4638, 16, 1, 1, 'JAC-POLO-009-obsidian-black-S', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4639, 16, 2, 1, 'JAC-POLO-009-obsidian-black-M', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4640, 16, 3, 1, 'JAC-POLO-009-obsidian-black-L', NULL, NULL, 30, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4641, 16, 4, 1, 'JAC-POLO-009-obsidian-black-XL', NULL, NULL, 29, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4642, 16, 5, 1, 'JAC-POLO-009-obsidian-black-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4643, 16, 6, 1, 'JAC-POLO-009-obsidian-black-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4644, 16, 1, 2, 'JAC-POLO-009-pure-white-S', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4645, 16, 2, 2, 'JAC-POLO-009-pure-white-M', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4646, 16, 3, 2, 'JAC-POLO-009-pure-white-L', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4647, 16, 4, 2, 'JAC-POLO-009-pure-white-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4648, 16, 5, 2, 'JAC-POLO-009-pure-white-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4649, 16, 6, 2, 'JAC-POLO-009-pure-white-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4650, 16, 1, 3, 'JAC-POLO-009-royal-navy-S', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4651, 16, 2, 3, 'JAC-POLO-009-royal-navy-M', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4652, 16, 3, 3, 'JAC-POLO-009-royal-navy-L', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4653, 16, 4, 3, 'JAC-POLO-009-royal-navy-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4654, 16, 5, 3, 'JAC-POLO-009-royal-navy-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4655, 16, 6, 3, 'JAC-POLO-009-royal-navy-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4656, 16, 1, 4, 'JAC-POLO-009-olive-dusk-S', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4657, 16, 2, 4, 'JAC-POLO-009-olive-dusk-M', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4658, 16, 3, 4, 'JAC-POLO-009-olive-dusk-L', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4659, 16, 4, 4, 'JAC-POLO-009-olive-dusk-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4660, 16, 5, 4, 'JAC-POLO-009-olive-dusk-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4661, 16, 6, 4, 'JAC-POLO-009-olive-dusk-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4662, 17, 1, 3, 'JAC-POLO-010-royal-navy-S', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4663, 17, 2, 3, 'JAC-POLO-010-royal-navy-M', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4664, 17, 3, 3, 'JAC-POLO-010-royal-navy-L', NULL, NULL, 32, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4665, 17, 4, 3, 'JAC-POLO-010-royal-navy-XL', NULL, NULL, 34, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4666, 17, 5, 3, 'JAC-POLO-010-royal-navy-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4667, 17, 6, 3, 'JAC-POLO-010-royal-navy-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4668, 17, 1, 1, 'JAC-POLO-010-obsidian-black-S', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4669, 17, 2, 1, 'JAC-POLO-010-obsidian-black-M', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4670, 17, 3, 1, 'JAC-POLO-010-obsidian-black-L', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4671, 17, 4, 1, 'JAC-POLO-010-obsidian-black-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4672, 17, 5, 1, 'JAC-POLO-010-obsidian-black-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4673, 17, 6, 1, 'JAC-POLO-010-obsidian-black-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4674, 17, 1, 2, 'JAC-POLO-010-pure-white-S', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4675, 17, 2, 2, 'JAC-POLO-010-pure-white-M', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4676, 17, 3, 2, 'JAC-POLO-010-pure-white-L', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4677, 17, 4, 2, 'JAC-POLO-010-pure-white-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4678, 17, 5, 2, 'JAC-POLO-010-pure-white-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4679, 17, 6, 2, 'JAC-POLO-010-pure-white-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4680, 17, 1, 4, 'JAC-POLO-010-olive-dusk-S', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4681, 17, 2, 4, 'JAC-POLO-010-olive-dusk-M', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4682, 17, 3, 4, 'JAC-POLO-010-olive-dusk-L', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4683, 17, 4, 4, 'JAC-POLO-010-olive-dusk-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4684, 17, 5, 4, 'JAC-POLO-010-olive-dusk-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4685, 17, 6, 4, 'JAC-POLO-010-olive-dusk-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4686, 18, 1, 2, 'JAC-POLO-011-pure-white-S', NULL, NULL, 28, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4687, 18, 2, 2, 'JAC-POLO-011-pure-white-M', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4688, 18, 3, 2, 'JAC-POLO-011-pure-white-L', NULL, NULL, 27, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4689, 18, 4, 2, 'JAC-POLO-011-pure-white-XL', NULL, NULL, 26, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4690, 18, 5, 2, 'JAC-POLO-011-pure-white-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4691, 18, 6, 2, 'JAC-POLO-011-pure-white-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4692, 18, 1, 1, 'JAC-POLO-011-obsidian-black-S', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4693, 18, 2, 1, 'JAC-POLO-011-obsidian-black-M', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4694, 18, 3, 1, 'JAC-POLO-011-obsidian-black-L', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4695, 18, 4, 1, 'JAC-POLO-011-obsidian-black-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4696, 18, 5, 1, 'JAC-POLO-011-obsidian-black-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4697, 18, 6, 1, 'JAC-POLO-011-obsidian-black-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4698, 18, 1, 3, 'JAC-POLO-011-royal-navy-S', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4699, 18, 2, 3, 'JAC-POLO-011-royal-navy-M', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4700, 18, 3, 3, 'JAC-POLO-011-royal-navy-L', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4701, 18, 4, 3, 'JAC-POLO-011-royal-navy-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4702, 18, 5, 3, 'JAC-POLO-011-royal-navy-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4703, 18, 6, 3, 'JAC-POLO-011-royal-navy-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4704, 18, 1, 4, 'JAC-POLO-011-olive-dusk-S', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4705, 18, 2, 4, 'JAC-POLO-011-olive-dusk-M', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4706, 18, 3, 4, 'JAC-POLO-011-olive-dusk-L', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4707, 18, 4, 4, 'JAC-POLO-011-olive-dusk-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4708, 18, 5, 4, 'JAC-POLO-011-olive-dusk-XXL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4709, 18, 6, 4, 'JAC-POLO-011-olive-dusk-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4710, 19, 1, 5, 'JAC-POLO-012-heather-grey-S', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4711, 19, 2, 5, 'JAC-POLO-012-heather-grey-M', NULL, NULL, 33, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4712, 19, 3, 5, 'JAC-POLO-012-heather-grey-L', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4713, 19, 4, 5, 'JAC-POLO-012-heather-grey-XL', NULL, NULL, 33, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4714, 19, 5, 5, 'JAC-POLO-012-heather-grey-XXL', NULL, NULL, 32, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4715, 19, 6, 5, 'JAC-POLO-012-heather-grey-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4716, 19, 1, 1, 'JAC-POLO-012-obsidian-black-S', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4717, 19, 2, 1, 'JAC-POLO-012-obsidian-black-M', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4718, 19, 3, 1, 'JAC-POLO-012-obsidian-black-L', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4719, 19, 4, 1, 'JAC-POLO-012-obsidian-black-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4720, 19, 5, 1, 'JAC-POLO-012-obsidian-black-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4721, 19, 6, 1, 'JAC-POLO-012-obsidian-black-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4722, 19, 1, 2, 'JAC-POLO-012-pure-white-S', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4723, 19, 2, 2, 'JAC-POLO-012-pure-white-M', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4724, 19, 3, 2, 'JAC-POLO-012-pure-white-L', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4725, 19, 4, 2, 'JAC-POLO-012-pure-white-XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4726, 19, 5, 2, 'JAC-POLO-012-pure-white-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4727, 19, 6, 2, 'JAC-POLO-012-pure-white-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4728, 19, 1, 3, 'JAC-POLO-012-royal-navy-S', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4729, 19, 2, 3, 'JAC-POLO-012-royal-navy-M', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4730, 19, 3, 3, 'JAC-POLO-012-royal-navy-L', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4731, 19, 4, 3, 'JAC-POLO-012-royal-navy-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4732, 19, 5, 3, 'JAC-POLO-012-royal-navy-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4733, 19, 6, 3, 'JAC-POLO-012-royal-navy-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4734, 20, 1, 8, 'JAC-POLO-013-forest-emerald-S', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4735, 20, 2, 8, 'JAC-POLO-013-forest-emerald-M', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4736, 20, 3, 8, 'JAC-POLO-013-forest-emerald-L', NULL, NULL, 33, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4737, 20, 4, 8, 'JAC-POLO-013-forest-emerald-XL', NULL, NULL, 34, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4738, 20, 5, 8, 'JAC-POLO-013-forest-emerald-XXL', NULL, NULL, 35, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4739, 20, 6, 8, 'JAC-POLO-013-forest-emerald-3XL', NULL, NULL, 26, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4740, 20, 1, 1, 'JAC-POLO-013-obsidian-black-S', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4741, 20, 2, 1, 'JAC-POLO-013-obsidian-black-M', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4742, 20, 3, 1, 'JAC-POLO-013-obsidian-black-L', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4743, 20, 4, 1, 'JAC-POLO-013-obsidian-black-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4744, 20, 5, 1, 'JAC-POLO-013-obsidian-black-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4745, 20, 6, 1, 'JAC-POLO-013-obsidian-black-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4746, 20, 1, 2, 'JAC-POLO-013-pure-white-S', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4747, 20, 2, 2, 'JAC-POLO-013-pure-white-M', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4748, 20, 3, 2, 'JAC-POLO-013-pure-white-L', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4749, 20, 4, 2, 'JAC-POLO-013-pure-white-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4750, 20, 5, 2, 'JAC-POLO-013-pure-white-XXL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4751, 20, 6, 2, 'JAC-POLO-013-pure-white-3XL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4752, 20, 1, 3, 'JAC-POLO-013-royal-navy-S', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4753, 20, 2, 3, 'JAC-POLO-013-royal-navy-M', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4754, 20, 3, 3, 'JAC-POLO-013-royal-navy-L', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4755, 20, 4, 3, 'JAC-POLO-013-royal-navy-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4756, 20, 5, 3, 'JAC-POLO-013-royal-navy-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4757, 20, 6, 3, 'JAC-POLO-013-royal-navy-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4758, 21, 1, 3, 'JAC-POLO-014-royal-navy-S', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4759, 21, 2, 3, 'JAC-POLO-014-royal-navy-M', NULL, NULL, 35, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4760, 21, 3, 3, 'JAC-POLO-014-royal-navy-L', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4761, 21, 4, 3, 'JAC-POLO-014-royal-navy-XL', NULL, NULL, 30, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4762, 21, 5, 3, 'JAC-POLO-014-royal-navy-XXL', NULL, NULL, 30, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4763, 21, 6, 3, 'JAC-POLO-014-royal-navy-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4764, 21, 1, 1, 'JAC-POLO-014-obsidian-black-S', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4765, 21, 2, 1, 'JAC-POLO-014-obsidian-black-M', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4766, 21, 3, 1, 'JAC-POLO-014-obsidian-black-L', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4767, 21, 4, 1, 'JAC-POLO-014-obsidian-black-XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4768, 21, 5, 1, 'JAC-POLO-014-obsidian-black-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4769, 21, 6, 1, 'JAC-POLO-014-obsidian-black-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4770, 21, 1, 2, 'JAC-POLO-014-pure-white-S', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4771, 21, 2, 2, 'JAC-POLO-014-pure-white-M', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4772, 21, 3, 2, 'JAC-POLO-014-pure-white-L', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4773, 21, 4, 2, 'JAC-POLO-014-pure-white-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4774, 21, 5, 2, 'JAC-POLO-014-pure-white-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4775, 21, 6, 2, 'JAC-POLO-014-pure-white-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4776, 21, 1, 4, 'JAC-POLO-014-olive-dusk-S', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4777, 21, 2, 4, 'JAC-POLO-014-olive-dusk-M', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4778, 21, 3, 4, 'JAC-POLO-014-olive-dusk-L', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4779, 21, 4, 4, 'JAC-POLO-014-olive-dusk-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4780, 21, 5, 4, 'JAC-POLO-014-olive-dusk-XXL', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4781, 21, 6, 4, 'JAC-POLO-014-olive-dusk-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4782, 22, 1, 7, 'JAC-POLO-015-desert-sand-S', NULL, NULL, 33, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4783, 22, 2, 7, 'JAC-POLO-015-desert-sand-M', NULL, NULL, 29, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4784, 22, 3, 7, 'JAC-POLO-015-desert-sand-L', NULL, NULL, 26, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4785, 22, 4, 7, 'JAC-POLO-015-desert-sand-XL', NULL, NULL, 32, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4786, 22, 5, 7, 'JAC-POLO-015-desert-sand-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4787, 22, 6, 7, 'JAC-POLO-015-desert-sand-3XL', NULL, NULL, 28, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4788, 22, 1, 1, 'JAC-POLO-015-obsidian-black-S', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4789, 22, 2, 1, 'JAC-POLO-015-obsidian-black-M', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4790, 22, 3, 1, 'JAC-POLO-015-obsidian-black-L', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4791, 22, 4, 1, 'JAC-POLO-015-obsidian-black-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4792, 22, 5, 1, 'JAC-POLO-015-obsidian-black-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4793, 22, 6, 1, 'JAC-POLO-015-obsidian-black-3XL', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4794, 22, 1, 2, 'JAC-POLO-015-pure-white-S', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4795, 22, 2, 2, 'JAC-POLO-015-pure-white-M', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4796, 22, 3, 2, 'JAC-POLO-015-pure-white-L', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4797, 22, 4, 2, 'JAC-POLO-015-pure-white-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4798, 22, 5, 2, 'JAC-POLO-015-pure-white-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4799, 22, 6, 2, 'JAC-POLO-015-pure-white-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4800, 22, 1, 3, 'JAC-POLO-015-royal-navy-S', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4801, 22, 2, 3, 'JAC-POLO-015-royal-navy-M', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4802, 22, 3, 3, 'JAC-POLO-015-royal-navy-L', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4803, 22, 4, 3, 'JAC-POLO-015-royal-navy-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4804, 22, 5, 3, 'JAC-POLO-015-royal-navy-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4805, 22, 6, 3, 'JAC-POLO-015-royal-navy-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4806, 23, 1, 13, 'JAC-POLO-016-classic-maroon-S', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4807, 23, 2, 13, 'JAC-POLO-016-classic-maroon-M', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4808, 23, 3, 13, 'JAC-POLO-016-classic-maroon-L', NULL, NULL, 34, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4809, 23, 4, 13, 'JAC-POLO-016-classic-maroon-XL', NULL, NULL, 28, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4810, 23, 5, 13, 'JAC-POLO-016-classic-maroon-XXL', NULL, NULL, 27, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4811, 23, 6, 13, 'JAC-POLO-016-classic-maroon-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4812, 23, 1, 1, 'JAC-POLO-016-obsidian-black-S', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4813, 23, 2, 1, 'JAC-POLO-016-obsidian-black-M', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4814, 23, 3, 1, 'JAC-POLO-016-obsidian-black-L', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4815, 23, 4, 1, 'JAC-POLO-016-obsidian-black-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4816, 23, 5, 1, 'JAC-POLO-016-obsidian-black-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4817, 23, 6, 1, 'JAC-POLO-016-obsidian-black-3XL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4818, 23, 1, 2, 'JAC-POLO-016-pure-white-S', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4819, 23, 2, 2, 'JAC-POLO-016-pure-white-M', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4820, 23, 3, 2, 'JAC-POLO-016-pure-white-L', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4821, 23, 4, 2, 'JAC-POLO-016-pure-white-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4822, 23, 5, 2, 'JAC-POLO-016-pure-white-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4823, 23, 6, 2, 'JAC-POLO-016-pure-white-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4824, 23, 1, 3, 'JAC-POLO-016-royal-navy-S', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4825, 23, 2, 3, 'JAC-POLO-016-royal-navy-M', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4826, 23, 3, 3, 'JAC-POLO-016-royal-navy-L', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4827, 23, 4, 3, 'JAC-POLO-016-royal-navy-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4828, 23, 5, 3, 'JAC-POLO-016-royal-navy-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4829, 23, 6, 3, 'JAC-POLO-016-royal-navy-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4830, 24, 1, 12, 'JAC-POLO-017-sky-blue-S', NULL, NULL, 32, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4831, 24, 2, 12, 'JAC-POLO-017-sky-blue-M', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4832, 24, 3, 12, 'JAC-POLO-017-sky-blue-L', NULL, NULL, 34, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4833, 24, 4, 12, 'JAC-POLO-017-sky-blue-XL', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4834, 24, 5, 12, 'JAC-POLO-017-sky-blue-XXL', NULL, NULL, 26, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4835, 24, 6, 12, 'JAC-POLO-017-sky-blue-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4836, 24, 1, 1, 'JAC-POLO-017-obsidian-black-S', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4837, 24, 2, 1, 'JAC-POLO-017-obsidian-black-M', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4838, 24, 3, 1, 'JAC-POLO-017-obsidian-black-L', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4839, 24, 4, 1, 'JAC-POLO-017-obsidian-black-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4840, 24, 5, 1, 'JAC-POLO-017-obsidian-black-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4841, 24, 6, 1, 'JAC-POLO-017-obsidian-black-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4842, 24, 1, 2, 'JAC-POLO-017-pure-white-S', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4843, 24, 2, 2, 'JAC-POLO-017-pure-white-M', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4844, 24, 3, 2, 'JAC-POLO-017-pure-white-L', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4845, 24, 4, 2, 'JAC-POLO-017-pure-white-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4846, 24, 5, 2, 'JAC-POLO-017-pure-white-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4847, 24, 6, 2, 'JAC-POLO-017-pure-white-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4848, 24, 1, 3, 'JAC-POLO-017-royal-navy-S', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4849, 24, 2, 3, 'JAC-POLO-017-royal-navy-M', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4850, 24, 3, 3, 'JAC-POLO-017-royal-navy-L', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4851, 24, 4, 3, 'JAC-POLO-017-royal-navy-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4852, 24, 5, 3, 'JAC-POLO-017-royal-navy-XXL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4853, 24, 6, 3, 'JAC-POLO-017-royal-navy-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4854, 25, 1, 14, 'JAC-POLO-018-warm-mustard-S', NULL, NULL, 35, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4855, 25, 2, 14, 'JAC-POLO-018-warm-mustard-M', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4856, 25, 3, 14, 'JAC-POLO-018-warm-mustard-L', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4857, 25, 4, 14, 'JAC-POLO-018-warm-mustard-XL', NULL, NULL, 27, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4858, 25, 5, 14, 'JAC-POLO-018-warm-mustard-XXL', NULL, NULL, 32, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4859, 25, 6, 14, 'JAC-POLO-018-warm-mustard-3XL', NULL, NULL, 28, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4860, 25, 1, 1, 'JAC-POLO-018-obsidian-black-S', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4861, 25, 2, 1, 'JAC-POLO-018-obsidian-black-M', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4862, 25, 3, 1, 'JAC-POLO-018-obsidian-black-L', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4863, 25, 4, 1, 'JAC-POLO-018-obsidian-black-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4864, 25, 5, 1, 'JAC-POLO-018-obsidian-black-XXL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4865, 25, 6, 1, 'JAC-POLO-018-obsidian-black-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4866, 25, 1, 2, 'JAC-POLO-018-pure-white-S', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4867, 25, 2, 2, 'JAC-POLO-018-pure-white-M', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4868, 25, 3, 2, 'JAC-POLO-018-pure-white-L', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4869, 25, 4, 2, 'JAC-POLO-018-pure-white-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4870, 25, 5, 2, 'JAC-POLO-018-pure-white-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4871, 25, 6, 2, 'JAC-POLO-018-pure-white-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4872, 25, 1, 3, 'JAC-POLO-018-royal-navy-S', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4873, 25, 2, 3, 'JAC-POLO-018-royal-navy-M', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4874, 25, 3, 3, 'JAC-POLO-018-royal-navy-L', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4875, 25, 4, 3, 'JAC-POLO-018-royal-navy-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4876, 25, 5, 3, 'JAC-POLO-018-royal-navy-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4877, 25, 6, 3, 'JAC-POLO-018-royal-navy-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4878, 26, 1, 3, 'JAC-POLO-019-royal-navy-S', NULL, NULL, 24, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4879, 26, 2, 3, 'JAC-POLO-019-royal-navy-M', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4880, 26, 3, 3, 'JAC-POLO-019-royal-navy-L', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4881, 26, 4, 3, 'JAC-POLO-019-royal-navy-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4882, 26, 5, 3, 'JAC-POLO-019-royal-navy-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4883, 26, 6, 3, 'JAC-POLO-019-royal-navy-3XL', NULL, NULL, 33, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4884, 26, 1, 1, 'JAC-POLO-019-obsidian-black-S', NULL, NULL, 21, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4885, 26, 2, 1, 'JAC-POLO-019-obsidian-black-M', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4886, 26, 3, 1, 'JAC-POLO-019-obsidian-black-L', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4887, 26, 4, 1, 'JAC-POLO-019-obsidian-black-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4888, 26, 5, 1, 'JAC-POLO-019-obsidian-black-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4889, 26, 6, 1, 'JAC-POLO-019-obsidian-black-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4890, 26, 1, 2, 'JAC-POLO-019-pure-white-S', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4891, 26, 2, 2, 'JAC-POLO-019-pure-white-M', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4892, 26, 3, 2, 'JAC-POLO-019-pure-white-L', NULL, NULL, 17, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4893, 26, 4, 2, 'JAC-POLO-019-pure-white-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4894, 26, 5, 2, 'JAC-POLO-019-pure-white-XXL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4895, 26, 6, 2, 'JAC-POLO-019-pure-white-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4896, 26, 1, 4, 'JAC-POLO-019-olive-dusk-S', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4897, 26, 2, 4, 'JAC-POLO-019-olive-dusk-M', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4898, 26, 3, 4, 'JAC-POLO-019-olive-dusk-L', NULL, NULL, 10, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4899, 26, 4, 4, 'JAC-POLO-019-olive-dusk-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4900, 26, 5, 4, 'JAC-POLO-019-olive-dusk-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4901, 26, 6, 4, 'JAC-POLO-019-olive-dusk-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4902, 27, 1, 2, 'JAC-POLO-020-pure-white-S', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4903, 27, 2, 2, 'JAC-POLO-020-pure-white-M', NULL, NULL, 22, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4904, 27, 3, 2, 'JAC-POLO-020-pure-white-L', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4905, 27, 4, 2, 'JAC-POLO-020-pure-white-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4906, 27, 5, 2, 'JAC-POLO-020-pure-white-XXL', NULL, NULL, 34, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4907, 27, 6, 2, 'JAC-POLO-020-pure-white-3XL', NULL, NULL, 35, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4908, 27, 1, 1, 'JAC-POLO-020-obsidian-black-S', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4909, 27, 2, 1, 'JAC-POLO-020-obsidian-black-M', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4910, 27, 3, 1, 'JAC-POLO-020-obsidian-black-L', NULL, NULL, 19, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4911, 27, 4, 1, 'JAC-POLO-020-obsidian-black-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4912, 27, 5, 1, 'JAC-POLO-020-obsidian-black-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4913, 27, 6, 1, 'JAC-POLO-020-obsidian-black-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4914, 27, 1, 3, 'JAC-POLO-020-royal-navy-S', NULL, NULL, 8, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4915, 27, 2, 3, 'JAC-POLO-020-royal-navy-M', NULL, NULL, 9, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4916, 27, 3, 3, 'JAC-POLO-020-royal-navy-L', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4917, 27, 4, 3, 'JAC-POLO-020-royal-navy-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4918, 27, 5, 3, 'JAC-POLO-020-royal-navy-XXL', NULL, NULL, 13, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4919, 27, 6, 3, 'JAC-POLO-020-royal-navy-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4920, 27, 1, 4, 'JAC-POLO-020-olive-dusk-S', NULL, NULL, 7, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4921, 27, 2, 4, 'JAC-POLO-020-olive-dusk-M', NULL, NULL, 14, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4922, 27, 3, 4, 'JAC-POLO-020-olive-dusk-L', NULL, NULL, 11, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4923, 27, 4, 4, 'JAC-POLO-020-olive-dusk-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4924, 27, 5, 4, 'JAC-POLO-020-olive-dusk-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4925, 27, 6, 4, 'JAC-POLO-020-olive-dusk-3XL', NULL, NULL, 25, 1, '2026-08-28 18:59:01', '2026-08-28 18:59:01'),
  (4926, 28, 1, 9, 'JAC-POLO-021-slate-charcoal-S', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4927, 28, 2, 9, 'JAC-POLO-021-slate-charcoal-M', NULL, NULL, 27, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4928, 28, 3, 9, 'JAC-POLO-021-slate-charcoal-L', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4929, 28, 4, 9, 'JAC-POLO-021-slate-charcoal-XL', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4930, 28, 5, 9, 'JAC-POLO-021-slate-charcoal-XXL', NULL, NULL, 32, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4931, 28, 6, 9, 'JAC-POLO-021-slate-charcoal-3XL', NULL, NULL, 32, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4932, 28, 1, 1, 'JAC-POLO-021-obsidian-black-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4933, 28, 2, 1, 'JAC-POLO-021-obsidian-black-M', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4934, 28, 3, 1, 'JAC-POLO-021-obsidian-black-L', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4935, 28, 4, 1, 'JAC-POLO-021-obsidian-black-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4936, 28, 5, 1, 'JAC-POLO-021-obsidian-black-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4937, 28, 6, 1, 'JAC-POLO-021-obsidian-black-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4938, 28, 1, 2, 'JAC-POLO-021-pure-white-S', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4939, 28, 2, 2, 'JAC-POLO-021-pure-white-M', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4940, 28, 3, 2, 'JAC-POLO-021-pure-white-L', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4941, 28, 4, 2, 'JAC-POLO-021-pure-white-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4942, 28, 5, 2, 'JAC-POLO-021-pure-white-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4943, 28, 6, 2, 'JAC-POLO-021-pure-white-3XL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4944, 28, 1, 3, 'JAC-POLO-021-royal-navy-S', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4945, 28, 2, 3, 'JAC-POLO-021-royal-navy-M', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4946, 28, 3, 3, 'JAC-POLO-021-royal-navy-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4947, 28, 4, 3, 'JAC-POLO-021-royal-navy-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4948, 28, 5, 3, 'JAC-POLO-021-royal-navy-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4949, 28, 6, 3, 'JAC-POLO-021-royal-navy-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4950, 29, 1, 15, 'JAC-POLO-022-ivory-cream-S', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4951, 29, 2, 15, 'JAC-POLO-022-ivory-cream-M', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4952, 29, 3, 15, 'JAC-POLO-022-ivory-cream-L', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4953, 29, 4, 15, 'JAC-POLO-022-ivory-cream-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4954, 29, 5, 15, 'JAC-POLO-022-ivory-cream-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4955, 29, 6, 15, 'JAC-POLO-022-ivory-cream-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4956, 29, 1, 1, 'JAC-POLO-022-obsidian-black-S', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4957, 29, 2, 1, 'JAC-POLO-022-obsidian-black-M', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4958, 29, 3, 1, 'JAC-POLO-022-obsidian-black-L', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4959, 29, 4, 1, 'JAC-POLO-022-obsidian-black-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4960, 29, 5, 1, 'JAC-POLO-022-obsidian-black-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4961, 29, 6, 1, 'JAC-POLO-022-obsidian-black-3XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4962, 29, 1, 2, 'JAC-POLO-022-pure-white-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4963, 29, 2, 2, 'JAC-POLO-022-pure-white-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4964, 29, 3, 2, 'JAC-POLO-022-pure-white-L', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4965, 29, 4, 2, 'JAC-POLO-022-pure-white-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4966, 29, 5, 2, 'JAC-POLO-022-pure-white-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4967, 29, 6, 2, 'JAC-POLO-022-pure-white-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4968, 29, 1, 3, 'JAC-POLO-022-royal-navy-S', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4969, 29, 2, 3, 'JAC-POLO-022-royal-navy-M', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4970, 29, 3, 3, 'JAC-POLO-022-royal-navy-L', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4971, 29, 4, 3, 'JAC-POLO-022-royal-navy-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4972, 29, 5, 3, 'JAC-POLO-022-royal-navy-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4973, 29, 6, 3, 'JAC-POLO-022-royal-navy-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4974, 30, 1, 4, 'JAC-POLO-023-olive-dusk-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4975, 30, 2, 4, 'JAC-POLO-023-olive-dusk-M', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4976, 30, 3, 4, 'JAC-POLO-023-olive-dusk-L', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4977, 30, 4, 4, 'JAC-POLO-023-olive-dusk-XL', NULL, NULL, 32, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4978, 30, 5, 4, 'JAC-POLO-023-olive-dusk-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4979, 30, 6, 4, 'JAC-POLO-023-olive-dusk-3XL', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4980, 30, 1, 1, 'JAC-POLO-023-obsidian-black-S', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4981, 30, 2, 1, 'JAC-POLO-023-obsidian-black-M', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4982, 30, 3, 1, 'JAC-POLO-023-obsidian-black-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4983, 30, 4, 1, 'JAC-POLO-023-obsidian-black-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4984, 30, 5, 1, 'JAC-POLO-023-obsidian-black-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4985, 30, 6, 1, 'JAC-POLO-023-obsidian-black-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4986, 30, 1, 2, 'JAC-POLO-023-pure-white-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4987, 30, 2, 2, 'JAC-POLO-023-pure-white-M', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4988, 30, 3, 2, 'JAC-POLO-023-pure-white-L', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4989, 30, 4, 2, 'JAC-POLO-023-pure-white-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4990, 30, 5, 2, 'JAC-POLO-023-pure-white-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4991, 30, 6, 2, 'JAC-POLO-023-pure-white-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4992, 30, 1, 3, 'JAC-POLO-023-royal-navy-S', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4993, 30, 2, 3, 'JAC-POLO-023-royal-navy-M', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4994, 30, 3, 3, 'JAC-POLO-023-royal-navy-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4995, 30, 4, 3, 'JAC-POLO-023-royal-navy-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (4996, 30, 5, 3, 'JAC-POLO-023-royal-navy-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4997, 30, 6, 3, 'JAC-POLO-023-royal-navy-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4998, 31, 1, 11, 'JAC-POLO-024-royal-blue-S', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (4999, 31, 2, 11, 'JAC-POLO-024-royal-blue-M', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5000, 31, 3, 11, 'JAC-POLO-024-royal-blue-L', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5001, 31, 4, 11, 'JAC-POLO-024-royal-blue-XL', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5002, 31, 5, 11, 'JAC-POLO-024-royal-blue-XXL', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5003, 31, 6, 11, 'JAC-POLO-024-royal-blue-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5004, 31, 1, 1, 'JAC-POLO-024-obsidian-black-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5005, 31, 2, 1, 'JAC-POLO-024-obsidian-black-M', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5006, 31, 3, 1, 'JAC-POLO-024-obsidian-black-L', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5007, 31, 4, 1, 'JAC-POLO-024-obsidian-black-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5008, 31, 5, 1, 'JAC-POLO-024-obsidian-black-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5009, 31, 6, 1, 'JAC-POLO-024-obsidian-black-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5010, 31, 1, 2, 'JAC-POLO-024-pure-white-S', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5011, 31, 2, 2, 'JAC-POLO-024-pure-white-M', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5012, 31, 3, 2, 'JAC-POLO-024-pure-white-L', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5013, 31, 4, 2, 'JAC-POLO-024-pure-white-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5014, 31, 5, 2, 'JAC-POLO-024-pure-white-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5015, 31, 6, 2, 'JAC-POLO-024-pure-white-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5016, 31, 1, 3, 'JAC-POLO-024-royal-navy-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5017, 31, 2, 3, 'JAC-POLO-024-royal-navy-M', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5018, 31, 3, 3, 'JAC-POLO-024-royal-navy-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5019, 31, 4, 3, 'JAC-POLO-024-royal-navy-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5020, 31, 5, 3, 'JAC-POLO-024-royal-navy-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5021, 31, 6, 3, 'JAC-POLO-024-royal-navy-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5022, 32, 1, 1, 'JAC-POLO-025-obsidian-black-S', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5023, 32, 2, 1, 'JAC-POLO-025-obsidian-black-M', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5024, 32, 3, 1, 'JAC-POLO-025-obsidian-black-L', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5025, 32, 4, 1, 'JAC-POLO-025-obsidian-black-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5026, 32, 5, 1, 'JAC-POLO-025-obsidian-black-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5027, 32, 6, 1, 'JAC-POLO-025-obsidian-black-3XL', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5028, 32, 1, 2, 'JAC-POLO-025-pure-white-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5029, 32, 2, 2, 'JAC-POLO-025-pure-white-M', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5030, 32, 3, 2, 'JAC-POLO-025-pure-white-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5031, 32, 4, 2, 'JAC-POLO-025-pure-white-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5032, 32, 5, 2, 'JAC-POLO-025-pure-white-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5033, 32, 6, 2, 'JAC-POLO-025-pure-white-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5034, 32, 1, 3, 'JAC-POLO-025-royal-navy-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5035, 32, 2, 3, 'JAC-POLO-025-royal-navy-M', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5036, 32, 3, 3, 'JAC-POLO-025-royal-navy-L', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5037, 32, 4, 3, 'JAC-POLO-025-royal-navy-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5038, 32, 5, 3, 'JAC-POLO-025-royal-navy-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5039, 32, 6, 3, 'JAC-POLO-025-royal-navy-3XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5040, 32, 1, 4, 'JAC-POLO-025-olive-dusk-S', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5041, 32, 2, 4, 'JAC-POLO-025-olive-dusk-M', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5042, 32, 3, 4, 'JAC-POLO-025-olive-dusk-L', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5043, 32, 4, 4, 'JAC-POLO-025-olive-dusk-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5044, 32, 5, 4, 'JAC-POLO-025-olive-dusk-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5045, 32, 6, 4, 'JAC-POLO-025-olive-dusk-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5046, 33, 1, 3, 'JAC-POLO-026-royal-navy-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5047, 33, 2, 3, 'JAC-POLO-026-royal-navy-M', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5048, 33, 3, 3, 'JAC-POLO-026-royal-navy-L', NULL, NULL, 27, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5049, 33, 4, 3, 'JAC-POLO-026-royal-navy-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5050, 33, 5, 3, 'JAC-POLO-026-royal-navy-XXL', NULL, NULL, 32, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5051, 33, 6, 3, 'JAC-POLO-026-royal-navy-3XL', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5052, 33, 1, 1, 'JAC-POLO-026-obsidian-black-S', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5053, 33, 2, 1, 'JAC-POLO-026-obsidian-black-M', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5054, 33, 3, 1, 'JAC-POLO-026-obsidian-black-L', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5055, 33, 4, 1, 'JAC-POLO-026-obsidian-black-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5056, 33, 5, 1, 'JAC-POLO-026-obsidian-black-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5057, 33, 6, 1, 'JAC-POLO-026-obsidian-black-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5058, 33, 1, 2, 'JAC-POLO-026-pure-white-S', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5059, 33, 2, 2, 'JAC-POLO-026-pure-white-M', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5060, 33, 3, 2, 'JAC-POLO-026-pure-white-L', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5061, 33, 4, 2, 'JAC-POLO-026-pure-white-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5062, 33, 5, 2, 'JAC-POLO-026-pure-white-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5063, 33, 6, 2, 'JAC-POLO-026-pure-white-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5064, 33, 1, 4, 'JAC-POLO-026-olive-dusk-S', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5065, 33, 2, 4, 'JAC-POLO-026-olive-dusk-M', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5066, 33, 3, 4, 'JAC-POLO-026-olive-dusk-L', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5067, 33, 4, 4, 'JAC-POLO-026-olive-dusk-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5068, 33, 5, 4, 'JAC-POLO-026-olive-dusk-XXL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5069, 33, 6, 4, 'JAC-POLO-026-olive-dusk-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5070, 34, 1, 5, 'JAC-POLO-027-heather-grey-S', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5071, 34, 2, 5, 'JAC-POLO-027-heather-grey-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5072, 34, 3, 5, 'JAC-POLO-027-heather-grey-L', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5073, 34, 4, 5, 'JAC-POLO-027-heather-grey-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5074, 34, 5, 5, 'JAC-POLO-027-heather-grey-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5075, 34, 6, 5, 'JAC-POLO-027-heather-grey-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5076, 34, 1, 1, 'JAC-POLO-027-obsidian-black-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5077, 34, 2, 1, 'JAC-POLO-027-obsidian-black-M', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5078, 34, 3, 1, 'JAC-POLO-027-obsidian-black-L', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5079, 34, 4, 1, 'JAC-POLO-027-obsidian-black-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5080, 34, 5, 1, 'JAC-POLO-027-obsidian-black-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5081, 34, 6, 1, 'JAC-POLO-027-obsidian-black-3XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5082, 34, 1, 2, 'JAC-POLO-027-pure-white-S', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5083, 34, 2, 2, 'JAC-POLO-027-pure-white-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5084, 34, 3, 2, 'JAC-POLO-027-pure-white-L', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5085, 34, 4, 2, 'JAC-POLO-027-pure-white-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5086, 34, 5, 2, 'JAC-POLO-027-pure-white-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5087, 34, 6, 2, 'JAC-POLO-027-pure-white-3XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5088, 34, 1, 3, 'JAC-POLO-027-royal-navy-S', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5089, 34, 2, 3, 'JAC-POLO-027-royal-navy-M', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5090, 34, 3, 3, 'JAC-POLO-027-royal-navy-L', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5091, 34, 4, 3, 'JAC-POLO-027-royal-navy-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5092, 34, 5, 3, 'JAC-POLO-027-royal-navy-XXL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5093, 34, 6, 3, 'JAC-POLO-027-royal-navy-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5094, 35, 1, 2, 'JAC-POLO-028-pure-white-S', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5095, 35, 2, 2, 'JAC-POLO-028-pure-white-M', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5096, 35, 3, 2, 'JAC-POLO-028-pure-white-L', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5097, 35, 4, 2, 'JAC-POLO-028-pure-white-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5098, 35, 5, 2, 'JAC-POLO-028-pure-white-XXL', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5099, 35, 6, 2, 'JAC-POLO-028-pure-white-3XL', NULL, NULL, 31, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5100, 35, 1, 1, 'JAC-POLO-028-obsidian-black-S', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5101, 35, 2, 1, 'JAC-POLO-028-obsidian-black-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5102, 35, 3, 1, 'JAC-POLO-028-obsidian-black-L', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5103, 35, 4, 1, 'JAC-POLO-028-obsidian-black-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5104, 35, 5, 1, 'JAC-POLO-028-obsidian-black-XXL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5105, 35, 6, 1, 'JAC-POLO-028-obsidian-black-3XL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5106, 35, 1, 3, 'JAC-POLO-028-royal-navy-S', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5107, 35, 2, 3, 'JAC-POLO-028-royal-navy-M', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5108, 35, 3, 3, 'JAC-POLO-028-royal-navy-L', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5109, 35, 4, 3, 'JAC-POLO-028-royal-navy-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5110, 35, 5, 3, 'JAC-POLO-028-royal-navy-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5111, 35, 6, 3, 'JAC-POLO-028-royal-navy-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5112, 35, 1, 4, 'JAC-POLO-028-olive-dusk-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5113, 35, 2, 4, 'JAC-POLO-028-olive-dusk-M', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5114, 35, 3, 4, 'JAC-POLO-028-olive-dusk-L', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5115, 35, 4, 4, 'JAC-POLO-028-olive-dusk-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5116, 35, 5, 4, 'JAC-POLO-028-olive-dusk-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5117, 35, 6, 4, 'JAC-POLO-028-olive-dusk-3XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5118, 36, 1, 8, 'JAC-POLO-029-forest-emerald-S', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5119, 36, 2, 8, 'JAC-POLO-029-forest-emerald-M', NULL, NULL, 26, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5120, 36, 3, 8, 'JAC-POLO-029-forest-emerald-L', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5121, 36, 4, 8, 'JAC-POLO-029-forest-emerald-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5122, 36, 5, 8, 'JAC-POLO-029-forest-emerald-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5123, 36, 6, 8, 'JAC-POLO-029-forest-emerald-3XL', NULL, NULL, 29, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5124, 36, 1, 1, 'JAC-POLO-029-obsidian-black-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5125, 36, 2, 1, 'JAC-POLO-029-obsidian-black-M', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5126, 36, 3, 1, 'JAC-POLO-029-obsidian-black-L', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5127, 36, 4, 1, 'JAC-POLO-029-obsidian-black-XL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5128, 36, 5, 1, 'JAC-POLO-029-obsidian-black-XXL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5129, 36, 6, 1, 'JAC-POLO-029-obsidian-black-3XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5130, 36, 1, 2, 'JAC-POLO-029-pure-white-S', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5131, 36, 2, 2, 'JAC-POLO-029-pure-white-M', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5132, 36, 3, 2, 'JAC-POLO-029-pure-white-L', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5133, 36, 4, 2, 'JAC-POLO-029-pure-white-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5134, 36, 5, 2, 'JAC-POLO-029-pure-white-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5135, 36, 6, 2, 'JAC-POLO-029-pure-white-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5136, 36, 1, 3, 'JAC-POLO-029-royal-navy-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5137, 36, 2, 3, 'JAC-POLO-029-royal-navy-M', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5138, 36, 3, 3, 'JAC-POLO-029-royal-navy-L', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5139, 36, 4, 3, 'JAC-POLO-029-royal-navy-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5140, 36, 5, 3, 'JAC-POLO-029-royal-navy-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5141, 36, 6, 3, 'JAC-POLO-029-royal-navy-3XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5142, 37, 1, 6, 'JAC-POLO-030-burgundy-royale-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5143, 37, 2, 6, 'JAC-POLO-030-burgundy-royale-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5144, 37, 3, 6, 'JAC-POLO-030-burgundy-royale-L', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5145, 37, 4, 6, 'JAC-POLO-030-burgundy-royale-XL', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5146, 37, 5, 6, 'JAC-POLO-030-burgundy-royale-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5147, 37, 6, 6, 'JAC-POLO-030-burgundy-royale-3XL', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5148, 37, 1, 1, 'JAC-POLO-030-obsidian-black-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5149, 37, 2, 1, 'JAC-POLO-030-obsidian-black-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5150, 37, 3, 1, 'JAC-POLO-030-obsidian-black-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5151, 37, 4, 1, 'JAC-POLO-030-obsidian-black-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5152, 37, 5, 1, 'JAC-POLO-030-obsidian-black-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5153, 37, 6, 1, 'JAC-POLO-030-obsidian-black-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5154, 37, 1, 2, 'JAC-POLO-030-pure-white-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5155, 37, 2, 2, 'JAC-POLO-030-pure-white-M', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5156, 37, 3, 2, 'JAC-POLO-030-pure-white-L', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5157, 37, 4, 2, 'JAC-POLO-030-pure-white-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5158, 37, 5, 2, 'JAC-POLO-030-pure-white-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5159, 37, 6, 2, 'JAC-POLO-030-pure-white-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5160, 37, 1, 3, 'JAC-POLO-030-royal-navy-S', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5161, 37, 2, 3, 'JAC-POLO-030-royal-navy-M', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5162, 37, 3, 3, 'JAC-POLO-030-royal-navy-L', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5163, 37, 4, 3, 'JAC-POLO-030-royal-navy-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5164, 37, 5, 3, 'JAC-POLO-030-royal-navy-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5165, 37, 6, 3, 'JAC-POLO-030-royal-navy-3XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5166, 38, 1, 1, 'JAC-POLO-031-obsidian-black-S', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5167, 38, 2, 1, 'JAC-POLO-031-obsidian-black-M', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5168, 38, 3, 1, 'JAC-POLO-031-obsidian-black-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5169, 38, 4, 1, 'JAC-POLO-031-obsidian-black-XL', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5170, 38, 5, 1, 'JAC-POLO-031-obsidian-black-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5171, 38, 6, 1, 'JAC-POLO-031-obsidian-black-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5172, 38, 1, 2, 'JAC-POLO-031-pure-white-S', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5173, 38, 2, 2, 'JAC-POLO-031-pure-white-M', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5174, 38, 3, 2, 'JAC-POLO-031-pure-white-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5175, 38, 4, 2, 'JAC-POLO-031-pure-white-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5176, 38, 5, 2, 'JAC-POLO-031-pure-white-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5177, 38, 6, 2, 'JAC-POLO-031-pure-white-3XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5178, 38, 1, 3, 'JAC-POLO-031-royal-navy-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5179, 38, 2, 3, 'JAC-POLO-031-royal-navy-M', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5180, 38, 3, 3, 'JAC-POLO-031-royal-navy-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5181, 38, 4, 3, 'JAC-POLO-031-royal-navy-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5182, 38, 5, 3, 'JAC-POLO-031-royal-navy-XXL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5183, 38, 6, 3, 'JAC-POLO-031-royal-navy-3XL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5184, 38, 1, 4, 'JAC-POLO-031-olive-dusk-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5185, 38, 2, 4, 'JAC-POLO-031-olive-dusk-M', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5186, 38, 3, 4, 'JAC-POLO-031-olive-dusk-L', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5187, 38, 4, 4, 'JAC-POLO-031-olive-dusk-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5188, 38, 5, 4, 'JAC-POLO-031-olive-dusk-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5189, 38, 6, 4, 'JAC-POLO-031-olive-dusk-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5190, 39, 1, 7, 'JAC-POLO-032-desert-sand-S', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5191, 39, 2, 7, 'JAC-POLO-032-desert-sand-M', NULL, NULL, 32, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5192, 39, 3, 7, 'JAC-POLO-032-desert-sand-L', NULL, NULL, 29, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5193, 39, 4, 7, 'JAC-POLO-032-desert-sand-XL', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5194, 39, 5, 7, 'JAC-POLO-032-desert-sand-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5195, 39, 6, 7, 'JAC-POLO-032-desert-sand-3XL', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5196, 39, 1, 1, 'JAC-POLO-032-obsidian-black-S', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5197, 39, 2, 1, 'JAC-POLO-032-obsidian-black-M', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5198, 39, 3, 1, 'JAC-POLO-032-obsidian-black-L', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5199, 39, 4, 1, 'JAC-POLO-032-obsidian-black-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5200, 39, 5, 1, 'JAC-POLO-032-obsidian-black-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5201, 39, 6, 1, 'JAC-POLO-032-obsidian-black-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5202, 39, 1, 2, 'JAC-POLO-032-pure-white-S', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5203, 39, 2, 2, 'JAC-POLO-032-pure-white-M', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5204, 39, 3, 2, 'JAC-POLO-032-pure-white-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5205, 39, 4, 2, 'JAC-POLO-032-pure-white-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5206, 39, 5, 2, 'JAC-POLO-032-pure-white-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5207, 39, 6, 2, 'JAC-POLO-032-pure-white-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5208, 39, 1, 3, 'JAC-POLO-032-royal-navy-S', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5209, 39, 2, 3, 'JAC-POLO-032-royal-navy-M', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5210, 39, 3, 3, 'JAC-POLO-032-royal-navy-L', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5211, 39, 4, 3, 'JAC-POLO-032-royal-navy-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5212, 39, 5, 3, 'JAC-POLO-032-royal-navy-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5213, 39, 6, 3, 'JAC-POLO-032-royal-navy-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5214, 40, 1, 1, 'JAC-POLO-033-obsidian-black-S', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5215, 40, 2, 1, 'JAC-POLO-033-obsidian-black-M', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5216, 40, 3, 1, 'JAC-POLO-033-obsidian-black-L', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5217, 40, 4, 1, 'JAC-POLO-033-obsidian-black-XL', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5218, 40, 5, 1, 'JAC-POLO-033-obsidian-black-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5219, 40, 6, 1, 'JAC-POLO-033-obsidian-black-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5220, 40, 1, 2, 'JAC-POLO-033-pure-white-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5221, 40, 2, 2, 'JAC-POLO-033-pure-white-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5222, 40, 3, 2, 'JAC-POLO-033-pure-white-L', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5223, 40, 4, 2, 'JAC-POLO-033-pure-white-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5224, 40, 5, 2, 'JAC-POLO-033-pure-white-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5225, 40, 6, 2, 'JAC-POLO-033-pure-white-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5226, 40, 1, 3, 'JAC-POLO-033-royal-navy-S', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5227, 40, 2, 3, 'JAC-POLO-033-royal-navy-M', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5228, 40, 3, 3, 'JAC-POLO-033-royal-navy-L', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5229, 40, 4, 3, 'JAC-POLO-033-royal-navy-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5230, 40, 5, 3, 'JAC-POLO-033-royal-navy-XXL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5231, 40, 6, 3, 'JAC-POLO-033-royal-navy-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5232, 40, 1, 4, 'JAC-POLO-033-olive-dusk-S', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5233, 40, 2, 4, 'JAC-POLO-033-olive-dusk-M', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5234, 40, 3, 4, 'JAC-POLO-033-olive-dusk-L', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5235, 40, 4, 4, 'JAC-POLO-033-olive-dusk-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5236, 40, 5, 4, 'JAC-POLO-033-olive-dusk-XXL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5237, 40, 6, 4, 'JAC-POLO-033-olive-dusk-3XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5238, 41, 1, 2, 'JAC-POLO-034-pure-white-S', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5239, 41, 2, 2, 'JAC-POLO-034-pure-white-M', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5240, 41, 3, 2, 'JAC-POLO-034-pure-white-L', NULL, NULL, 27, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5241, 41, 4, 2, 'JAC-POLO-034-pure-white-XL', NULL, NULL, 29, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5242, 41, 5, 2, 'JAC-POLO-034-pure-white-XXL', NULL, NULL, 31, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5243, 41, 6, 2, 'JAC-POLO-034-pure-white-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5244, 41, 1, 1, 'JAC-POLO-034-obsidian-black-S', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5245, 41, 2, 1, 'JAC-POLO-034-obsidian-black-M', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5246, 41, 3, 1, 'JAC-POLO-034-obsidian-black-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5247, 41, 4, 1, 'JAC-POLO-034-obsidian-black-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5248, 41, 5, 1, 'JAC-POLO-034-obsidian-black-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5249, 41, 6, 1, 'JAC-POLO-034-obsidian-black-3XL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5250, 41, 1, 3, 'JAC-POLO-034-royal-navy-S', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5251, 41, 2, 3, 'JAC-POLO-034-royal-navy-M', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5252, 41, 3, 3, 'JAC-POLO-034-royal-navy-L', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5253, 41, 4, 3, 'JAC-POLO-034-royal-navy-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5254, 41, 5, 3, 'JAC-POLO-034-royal-navy-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5255, 41, 6, 3, 'JAC-POLO-034-royal-navy-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5256, 41, 1, 4, 'JAC-POLO-034-olive-dusk-S', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5257, 41, 2, 4, 'JAC-POLO-034-olive-dusk-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5258, 41, 3, 4, 'JAC-POLO-034-olive-dusk-L', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5259, 41, 4, 4, 'JAC-POLO-034-olive-dusk-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5260, 41, 5, 4, 'JAC-POLO-034-olive-dusk-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5261, 41, 6, 4, 'JAC-POLO-034-olive-dusk-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5262, 42, 1, 3, 'JAC-POLO-035-royal-navy-S', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5263, 42, 2, 3, 'JAC-POLO-035-royal-navy-M', NULL, NULL, 32, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5264, 42, 3, 3, 'JAC-POLO-035-royal-navy-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5265, 42, 4, 3, 'JAC-POLO-035-royal-navy-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5266, 42, 5, 3, 'JAC-POLO-035-royal-navy-XXL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5267, 42, 6, 3, 'JAC-POLO-035-royal-navy-3XL', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5268, 42, 1, 1, 'JAC-POLO-035-obsidian-black-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5269, 42, 2, 1, 'JAC-POLO-035-obsidian-black-M', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5270, 42, 3, 1, 'JAC-POLO-035-obsidian-black-L', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5271, 42, 4, 1, 'JAC-POLO-035-obsidian-black-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5272, 42, 5, 1, 'JAC-POLO-035-obsidian-black-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5273, 42, 6, 1, 'JAC-POLO-035-obsidian-black-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5274, 42, 1, 2, 'JAC-POLO-035-pure-white-S', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5275, 42, 2, 2, 'JAC-POLO-035-pure-white-M', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5276, 42, 3, 2, 'JAC-POLO-035-pure-white-L', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5277, 42, 4, 2, 'JAC-POLO-035-pure-white-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5278, 42, 5, 2, 'JAC-POLO-035-pure-white-XXL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5279, 42, 6, 2, 'JAC-POLO-035-pure-white-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5280, 42, 1, 4, 'JAC-POLO-035-olive-dusk-S', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5281, 42, 2, 4, 'JAC-POLO-035-olive-dusk-M', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5282, 42, 3, 4, 'JAC-POLO-035-olive-dusk-L', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5283, 42, 4, 4, 'JAC-POLO-035-olive-dusk-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5284, 42, 5, 4, 'JAC-POLO-035-olive-dusk-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5285, 42, 6, 4, 'JAC-POLO-035-olive-dusk-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5286, 43, 1, 5, 'JAC-POLO-036-heather-grey-S', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5287, 43, 2, 5, 'JAC-POLO-036-heather-grey-M', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5288, 43, 3, 5, 'JAC-POLO-036-heather-grey-L', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5289, 43, 4, 5, 'JAC-POLO-036-heather-grey-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5290, 43, 5, 5, 'JAC-POLO-036-heather-grey-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5291, 43, 6, 5, 'JAC-POLO-036-heather-grey-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5292, 43, 1, 1, 'JAC-POLO-036-obsidian-black-S', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5293, 43, 2, 1, 'JAC-POLO-036-obsidian-black-M', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5294, 43, 3, 1, 'JAC-POLO-036-obsidian-black-L', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5295, 43, 4, 1, 'JAC-POLO-036-obsidian-black-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5296, 43, 5, 1, 'JAC-POLO-036-obsidian-black-XXL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5297, 43, 6, 1, 'JAC-POLO-036-obsidian-black-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5298, 43, 1, 2, 'JAC-POLO-036-pure-white-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5299, 43, 2, 2, 'JAC-POLO-036-pure-white-M', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5300, 43, 3, 2, 'JAC-POLO-036-pure-white-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5301, 43, 4, 2, 'JAC-POLO-036-pure-white-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5302, 43, 5, 2, 'JAC-POLO-036-pure-white-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5303, 43, 6, 2, 'JAC-POLO-036-pure-white-3XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5304, 43, 1, 3, 'JAC-POLO-036-royal-navy-S', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5305, 43, 2, 3, 'JAC-POLO-036-royal-navy-M', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5306, 43, 3, 3, 'JAC-POLO-036-royal-navy-L', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5307, 43, 4, 3, 'JAC-POLO-036-royal-navy-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5308, 43, 5, 3, 'JAC-POLO-036-royal-navy-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5309, 43, 6, 3, 'JAC-POLO-036-royal-navy-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5310, 44, 1, 9, 'JAC-POLO-037-slate-charcoal-S', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5311, 44, 2, 9, 'JAC-POLO-037-slate-charcoal-M', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5312, 44, 3, 9, 'JAC-POLO-037-slate-charcoal-L', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5313, 44, 4, 9, 'JAC-POLO-037-slate-charcoal-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5314, 44, 5, 9, 'JAC-POLO-037-slate-charcoal-XXL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5315, 44, 6, 9, 'JAC-POLO-037-slate-charcoal-3XL', NULL, NULL, 29, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5316, 44, 1, 1, 'JAC-POLO-037-obsidian-black-S', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5317, 44, 2, 1, 'JAC-POLO-037-obsidian-black-M', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5318, 44, 3, 1, 'JAC-POLO-037-obsidian-black-L', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5319, 44, 4, 1, 'JAC-POLO-037-obsidian-black-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5320, 44, 5, 1, 'JAC-POLO-037-obsidian-black-XXL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5321, 44, 6, 1, 'JAC-POLO-037-obsidian-black-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5322, 44, 1, 2, 'JAC-POLO-037-pure-white-S', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5323, 44, 2, 2, 'JAC-POLO-037-pure-white-M', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5324, 44, 3, 2, 'JAC-POLO-037-pure-white-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5325, 44, 4, 2, 'JAC-POLO-037-pure-white-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5326, 44, 5, 2, 'JAC-POLO-037-pure-white-XXL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5327, 44, 6, 2, 'JAC-POLO-037-pure-white-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5328, 44, 1, 3, 'JAC-POLO-037-royal-navy-S', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5329, 44, 2, 3, 'JAC-POLO-037-royal-navy-M', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5330, 44, 3, 3, 'JAC-POLO-037-royal-navy-L', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5331, 44, 4, 3, 'JAC-POLO-037-royal-navy-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5332, 44, 5, 3, 'JAC-POLO-037-royal-navy-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5333, 44, 6, 3, 'JAC-POLO-037-royal-navy-3XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5334, 45, 1, 4, 'JAC-POLO-038-olive-dusk-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5335, 45, 2, 4, 'JAC-POLO-038-olive-dusk-M', NULL, NULL, 31, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5336, 45, 3, 4, 'JAC-POLO-038-olive-dusk-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5337, 45, 4, 4, 'JAC-POLO-038-olive-dusk-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5338, 45, 5, 4, 'JAC-POLO-038-olive-dusk-XXL', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5339, 45, 6, 4, 'JAC-POLO-038-olive-dusk-3XL', NULL, NULL, 28, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5340, 45, 1, 1, 'JAC-POLO-038-obsidian-black-S', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5341, 45, 2, 1, 'JAC-POLO-038-obsidian-black-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5342, 45, 3, 1, 'JAC-POLO-038-obsidian-black-L', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5343, 45, 4, 1, 'JAC-POLO-038-obsidian-black-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5344, 45, 5, 1, 'JAC-POLO-038-obsidian-black-XXL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5345, 45, 6, 1, 'JAC-POLO-038-obsidian-black-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5346, 45, 1, 2, 'JAC-POLO-038-pure-white-S', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5347, 45, 2, 2, 'JAC-POLO-038-pure-white-M', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5348, 45, 3, 2, 'JAC-POLO-038-pure-white-L', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5349, 45, 4, 2, 'JAC-POLO-038-pure-white-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5350, 45, 5, 2, 'JAC-POLO-038-pure-white-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5351, 45, 6, 2, 'JAC-POLO-038-pure-white-3XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5352, 45, 1, 3, 'JAC-POLO-038-royal-navy-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5353, 45, 2, 3, 'JAC-POLO-038-royal-navy-M', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5354, 45, 3, 3, 'JAC-POLO-038-royal-navy-L', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5355, 45, 4, 3, 'JAC-POLO-038-royal-navy-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5356, 45, 5, 3, 'JAC-POLO-038-royal-navy-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5357, 45, 6, 3, 'JAC-POLO-038-royal-navy-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5358, 46, 1, 13, 'JAC-POLO-039-classic-maroon-S', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5359, 46, 2, 13, 'JAC-POLO-039-classic-maroon-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5360, 46, 3, 13, 'JAC-POLO-039-classic-maroon-L', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5361, 46, 4, 13, 'JAC-POLO-039-classic-maroon-XL', NULL, NULL, 26, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5362, 46, 5, 13, 'JAC-POLO-039-classic-maroon-XXL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5363, 46, 6, 13, 'JAC-POLO-039-classic-maroon-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5364, 46, 1, 1, 'JAC-POLO-039-obsidian-black-S', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5365, 46, 2, 1, 'JAC-POLO-039-obsidian-black-M', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5366, 46, 3, 1, 'JAC-POLO-039-obsidian-black-L', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5367, 46, 4, 1, 'JAC-POLO-039-obsidian-black-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5368, 46, 5, 1, 'JAC-POLO-039-obsidian-black-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5369, 46, 6, 1, 'JAC-POLO-039-obsidian-black-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5370, 46, 1, 2, 'JAC-POLO-039-pure-white-S', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5371, 46, 2, 2, 'JAC-POLO-039-pure-white-M', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5372, 46, 3, 2, 'JAC-POLO-039-pure-white-L', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5373, 46, 4, 2, 'JAC-POLO-039-pure-white-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5374, 46, 5, 2, 'JAC-POLO-039-pure-white-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5375, 46, 6, 2, 'JAC-POLO-039-pure-white-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5376, 46, 1, 3, 'JAC-POLO-039-royal-navy-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5377, 46, 2, 3, 'JAC-POLO-039-royal-navy-M', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5378, 46, 3, 3, 'JAC-POLO-039-royal-navy-L', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5379, 46, 4, 3, 'JAC-POLO-039-royal-navy-XL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5380, 46, 5, 3, 'JAC-POLO-039-royal-navy-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5381, 46, 6, 3, 'JAC-POLO-039-royal-navy-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5382, 47, 1, 12, 'JAC-POLO-040-sky-blue-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5383, 47, 2, 12, 'JAC-POLO-040-sky-blue-M', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5384, 47, 3, 12, 'JAC-POLO-040-sky-blue-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5385, 47, 4, 12, 'JAC-POLO-040-sky-blue-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5386, 47, 5, 12, 'JAC-POLO-040-sky-blue-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5387, 47, 6, 12, 'JAC-POLO-040-sky-blue-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5388, 47, 1, 1, 'JAC-POLO-040-obsidian-black-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5389, 47, 2, 1, 'JAC-POLO-040-obsidian-black-M', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5390, 47, 3, 1, 'JAC-POLO-040-obsidian-black-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5391, 47, 4, 1, 'JAC-POLO-040-obsidian-black-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5392, 47, 5, 1, 'JAC-POLO-040-obsidian-black-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5393, 47, 6, 1, 'JAC-POLO-040-obsidian-black-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5394, 47, 1, 2, 'JAC-POLO-040-pure-white-S', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5395, 47, 2, 2, 'JAC-POLO-040-pure-white-M', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5396, 47, 3, 2, 'JAC-POLO-040-pure-white-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5397, 47, 4, 2, 'JAC-POLO-040-pure-white-XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5398, 47, 5, 2, 'JAC-POLO-040-pure-white-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5399, 47, 6, 2, 'JAC-POLO-040-pure-white-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5400, 47, 1, 3, 'JAC-POLO-040-royal-navy-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5401, 47, 2, 3, 'JAC-POLO-040-royal-navy-M', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5402, 47, 3, 3, 'JAC-POLO-040-royal-navy-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5403, 47, 4, 3, 'JAC-POLO-040-royal-navy-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5404, 47, 5, 3, 'JAC-POLO-040-royal-navy-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5405, 47, 6, 3, 'JAC-POLO-040-royal-navy-3XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5406, 48, 1, 2, 'JAC-POLO-041-pure-white-S', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5407, 48, 2, 2, 'JAC-POLO-041-pure-white-M', NULL, NULL, 29, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5408, 48, 3, 2, 'JAC-POLO-041-pure-white-L', NULL, NULL, 26, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5409, 48, 4, 2, 'JAC-POLO-041-pure-white-XL', NULL, NULL, 27, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5410, 48, 5, 2, 'JAC-POLO-041-pure-white-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5411, 48, 6, 2, 'JAC-POLO-041-pure-white-3XL', NULL, NULL, 30, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5412, 48, 1, 1, 'JAC-POLO-041-obsidian-black-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5413, 48, 2, 1, 'JAC-POLO-041-obsidian-black-M', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5414, 48, 3, 1, 'JAC-POLO-041-obsidian-black-L', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5415, 48, 4, 1, 'JAC-POLO-041-obsidian-black-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5416, 48, 5, 1, 'JAC-POLO-041-obsidian-black-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5417, 48, 6, 1, 'JAC-POLO-041-obsidian-black-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5418, 48, 1, 3, 'JAC-POLO-041-royal-navy-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5419, 48, 2, 3, 'JAC-POLO-041-royal-navy-M', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5420, 48, 3, 3, 'JAC-POLO-041-royal-navy-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5421, 48, 4, 3, 'JAC-POLO-041-royal-navy-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5422, 48, 5, 3, 'JAC-POLO-041-royal-navy-XXL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5423, 48, 6, 3, 'JAC-POLO-041-royal-navy-3XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5424, 48, 1, 4, 'JAC-POLO-041-olive-dusk-S', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5425, 48, 2, 4, 'JAC-POLO-041-olive-dusk-M', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5426, 48, 3, 4, 'JAC-POLO-041-olive-dusk-L', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5427, 48, 4, 4, 'JAC-POLO-041-olive-dusk-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5428, 48, 5, 4, 'JAC-POLO-041-olive-dusk-XXL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5429, 48, 6, 4, 'JAC-POLO-041-olive-dusk-3XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5430, 49, 1, 3, 'JAC-POLO-042-royal-navy-S', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5431, 49, 2, 3, 'JAC-POLO-042-royal-navy-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5432, 49, 3, 3, 'JAC-POLO-042-royal-navy-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5433, 49, 4, 3, 'JAC-POLO-042-royal-navy-XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5434, 49, 5, 3, 'JAC-POLO-042-royal-navy-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5435, 49, 6, 3, 'JAC-POLO-042-royal-navy-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5436, 49, 1, 1, 'JAC-POLO-042-obsidian-black-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5437, 49, 2, 1, 'JAC-POLO-042-obsidian-black-M', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5438, 49, 3, 1, 'JAC-POLO-042-obsidian-black-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5439, 49, 4, 1, 'JAC-POLO-042-obsidian-black-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5440, 49, 5, 1, 'JAC-POLO-042-obsidian-black-XXL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5441, 49, 6, 1, 'JAC-POLO-042-obsidian-black-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5442, 49, 1, 2, 'JAC-POLO-042-pure-white-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5443, 49, 2, 2, 'JAC-POLO-042-pure-white-M', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5444, 49, 3, 2, 'JAC-POLO-042-pure-white-L', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5445, 49, 4, 2, 'JAC-POLO-042-pure-white-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5446, 49, 5, 2, 'JAC-POLO-042-pure-white-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5447, 49, 6, 2, 'JAC-POLO-042-pure-white-3XL', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5448, 49, 1, 4, 'JAC-POLO-042-olive-dusk-S', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5449, 49, 2, 4, 'JAC-POLO-042-olive-dusk-M', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5450, 49, 3, 4, 'JAC-POLO-042-olive-dusk-L', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5451, 49, 4, 4, 'JAC-POLO-042-olive-dusk-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5452, 49, 5, 4, 'JAC-POLO-042-olive-dusk-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5453, 49, 6, 4, 'JAC-POLO-042-olive-dusk-3XL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5454, 50, 1, 9, 'JAC-POLO-043-slate-charcoal-S', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5455, 50, 2, 9, 'JAC-POLO-043-slate-charcoal-M', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5456, 50, 3, 9, 'JAC-POLO-043-slate-charcoal-L', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5457, 50, 4, 9, 'JAC-POLO-043-slate-charcoal-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5458, 50, 5, 9, 'JAC-POLO-043-slate-charcoal-XXL', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5459, 50, 6, 9, 'JAC-POLO-043-slate-charcoal-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5460, 50, 1, 1, 'JAC-POLO-043-obsidian-black-S', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5461, 50, 2, 1, 'JAC-POLO-043-obsidian-black-M', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5462, 50, 3, 1, 'JAC-POLO-043-obsidian-black-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5463, 50, 4, 1, 'JAC-POLO-043-obsidian-black-XL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5464, 50, 5, 1, 'JAC-POLO-043-obsidian-black-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5465, 50, 6, 1, 'JAC-POLO-043-obsidian-black-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5466, 50, 1, 2, 'JAC-POLO-043-pure-white-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5467, 50, 2, 2, 'JAC-POLO-043-pure-white-M', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5468, 50, 3, 2, 'JAC-POLO-043-pure-white-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5469, 50, 4, 2, 'JAC-POLO-043-pure-white-XL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5470, 50, 5, 2, 'JAC-POLO-043-pure-white-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5471, 50, 6, 2, 'JAC-POLO-043-pure-white-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5472, 50, 1, 3, 'JAC-POLO-043-royal-navy-S', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5473, 50, 2, 3, 'JAC-POLO-043-royal-navy-M', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5474, 50, 3, 3, 'JAC-POLO-043-royal-navy-L', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5475, 50, 4, 3, 'JAC-POLO-043-royal-navy-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5476, 50, 5, 3, 'JAC-POLO-043-royal-navy-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5477, 50, 6, 3, 'JAC-POLO-043-royal-navy-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5478, 51, 1, 13, 'JAC-POLO-044-classic-maroon-S', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5479, 51, 2, 13, 'JAC-POLO-044-classic-maroon-M', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5480, 51, 3, 13, 'JAC-POLO-044-classic-maroon-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5481, 51, 4, 13, 'JAC-POLO-044-classic-maroon-XL', NULL, NULL, 26, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5482, 51, 5, 13, 'JAC-POLO-044-classic-maroon-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5483, 51, 6, 13, 'JAC-POLO-044-classic-maroon-3XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5484, 51, 1, 1, 'JAC-POLO-044-obsidian-black-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5485, 51, 2, 1, 'JAC-POLO-044-obsidian-black-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5486, 51, 3, 1, 'JAC-POLO-044-obsidian-black-L', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5487, 51, 4, 1, 'JAC-POLO-044-obsidian-black-XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5488, 51, 5, 1, 'JAC-POLO-044-obsidian-black-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5489, 51, 6, 1, 'JAC-POLO-044-obsidian-black-3XL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5490, 51, 1, 2, 'JAC-POLO-044-pure-white-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5491, 51, 2, 2, 'JAC-POLO-044-pure-white-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5492, 51, 3, 2, 'JAC-POLO-044-pure-white-L', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5493, 51, 4, 2, 'JAC-POLO-044-pure-white-XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5494, 51, 5, 2, 'JAC-POLO-044-pure-white-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5495, 51, 6, 2, 'JAC-POLO-044-pure-white-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5496, 51, 1, 3, 'JAC-POLO-044-royal-navy-S', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5497, 51, 2, 3, 'JAC-POLO-044-royal-navy-M', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5498, 51, 3, 3, 'JAC-POLO-044-royal-navy-L', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5499, 51, 4, 3, 'JAC-POLO-044-royal-navy-XL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5500, 51, 5, 3, 'JAC-POLO-044-royal-navy-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5501, 51, 6, 3, 'JAC-POLO-044-royal-navy-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5502, 52, 1, 8, 'JAC-POLO-045-forest-emerald-S', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5503, 52, 2, 8, 'JAC-POLO-045-forest-emerald-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5504, 52, 3, 8, 'JAC-POLO-045-forest-emerald-L', NULL, NULL, 31, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5505, 52, 4, 8, 'JAC-POLO-045-forest-emerald-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5506, 52, 5, 8, 'JAC-POLO-045-forest-emerald-XXL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5507, 52, 6, 8, 'JAC-POLO-045-forest-emerald-3XL', NULL, NULL, 31, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5508, 52, 1, 1, 'JAC-POLO-045-obsidian-black-S', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5509, 52, 2, 1, 'JAC-POLO-045-obsidian-black-M', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5510, 52, 3, 1, 'JAC-POLO-045-obsidian-black-L', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5511, 52, 4, 1, 'JAC-POLO-045-obsidian-black-XL', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5512, 52, 5, 1, 'JAC-POLO-045-obsidian-black-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5513, 52, 6, 1, 'JAC-POLO-045-obsidian-black-3XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5514, 52, 1, 2, 'JAC-POLO-045-pure-white-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5515, 52, 2, 2, 'JAC-POLO-045-pure-white-M', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5516, 52, 3, 2, 'JAC-POLO-045-pure-white-L', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5517, 52, 4, 2, 'JAC-POLO-045-pure-white-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5518, 52, 5, 2, 'JAC-POLO-045-pure-white-XXL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5519, 52, 6, 2, 'JAC-POLO-045-pure-white-3XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5520, 52, 1, 3, 'JAC-POLO-045-royal-navy-S', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5521, 52, 2, 3, 'JAC-POLO-045-royal-navy-M', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5522, 52, 3, 3, 'JAC-POLO-045-royal-navy-L', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5523, 52, 4, 3, 'JAC-POLO-045-royal-navy-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5524, 52, 5, 3, 'JAC-POLO-045-royal-navy-XXL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5525, 52, 6, 3, 'JAC-POLO-045-royal-navy-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5526, 53, 1, 1, 'JAC-POLO-046-obsidian-black-S', NULL, NULL, 26, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5527, 53, 2, 1, 'JAC-POLO-046-obsidian-black-M', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5528, 53, 3, 1, 'JAC-POLO-046-obsidian-black-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5529, 53, 4, 1, 'JAC-POLO-046-obsidian-black-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5530, 53, 5, 1, 'JAC-POLO-046-obsidian-black-XXL', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5531, 53, 6, 1, 'JAC-POLO-046-obsidian-black-3XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5532, 53, 1, 2, 'JAC-POLO-046-pure-white-S', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5533, 53, 2, 2, 'JAC-POLO-046-pure-white-M', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5534, 53, 3, 2, 'JAC-POLO-046-pure-white-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5535, 53, 4, 2, 'JAC-POLO-046-pure-white-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5536, 53, 5, 2, 'JAC-POLO-046-pure-white-XXL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5537, 53, 6, 2, 'JAC-POLO-046-pure-white-3XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5538, 53, 1, 3, 'JAC-POLO-046-royal-navy-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5539, 53, 2, 3, 'JAC-POLO-046-royal-navy-M', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5540, 53, 3, 3, 'JAC-POLO-046-royal-navy-L', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5541, 53, 4, 3, 'JAC-POLO-046-royal-navy-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5542, 53, 5, 3, 'JAC-POLO-046-royal-navy-XXL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5543, 53, 6, 3, 'JAC-POLO-046-royal-navy-3XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5544, 53, 1, 4, 'JAC-POLO-046-olive-dusk-S', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5545, 53, 2, 4, 'JAC-POLO-046-olive-dusk-M', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5546, 53, 3, 4, 'JAC-POLO-046-olive-dusk-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5547, 53, 4, 4, 'JAC-POLO-046-olive-dusk-XL', NULL, NULL, 8, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5548, 53, 5, 4, 'JAC-POLO-046-olive-dusk-XXL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5549, 53, 6, 4, 'JAC-POLO-046-olive-dusk-3XL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5550, 54, 1, 7, 'JAC-POLO-047-desert-sand-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5551, 54, 2, 7, 'JAC-POLO-047-desert-sand-M', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5552, 54, 3, 7, 'JAC-POLO-047-desert-sand-L', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5553, 54, 4, 7, 'JAC-POLO-047-desert-sand-XL', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5554, 54, 5, 7, 'JAC-POLO-047-desert-sand-XXL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5555, 54, 6, 7, 'JAC-POLO-047-desert-sand-3XL', NULL, NULL, 27, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5556, 54, 1, 1, 'JAC-POLO-047-obsidian-black-S', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5557, 54, 2, 1, 'JAC-POLO-047-obsidian-black-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5558, 54, 3, 1, 'JAC-POLO-047-obsidian-black-L', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5559, 54, 4, 1, 'JAC-POLO-047-obsidian-black-XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5560, 54, 5, 1, 'JAC-POLO-047-obsidian-black-XXL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5561, 54, 6, 1, 'JAC-POLO-047-obsidian-black-3XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5562, 54, 1, 2, 'JAC-POLO-047-pure-white-S', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5563, 54, 2, 2, 'JAC-POLO-047-pure-white-M', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5564, 54, 3, 2, 'JAC-POLO-047-pure-white-L', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5565, 54, 4, 2, 'JAC-POLO-047-pure-white-XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5566, 54, 5, 2, 'JAC-POLO-047-pure-white-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5567, 54, 6, 2, 'JAC-POLO-047-pure-white-3XL', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5568, 54, 1, 3, 'JAC-POLO-047-royal-navy-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5569, 54, 2, 3, 'JAC-POLO-047-royal-navy-M', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5570, 54, 3, 3, 'JAC-POLO-047-royal-navy-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5571, 54, 4, 3, 'JAC-POLO-047-royal-navy-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5572, 54, 5, 3, 'JAC-POLO-047-royal-navy-XXL', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5573, 54, 6, 3, 'JAC-POLO-047-royal-navy-3XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5574, 55, 1, 11, 'JAC-POLO-048-royal-blue-S', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5575, 55, 2, 11, 'JAC-POLO-048-royal-blue-M', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5576, 55, 3, 11, 'JAC-POLO-048-royal-blue-L', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5577, 55, 4, 11, 'JAC-POLO-048-royal-blue-XL', NULL, NULL, 35, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5578, 55, 5, 11, 'JAC-POLO-048-royal-blue-XXL', NULL, NULL, 31, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5579, 55, 6, 11, 'JAC-POLO-048-royal-blue-3XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5580, 55, 1, 1, 'JAC-POLO-048-obsidian-black-S', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5581, 55, 2, 1, 'JAC-POLO-048-obsidian-black-M', NULL, NULL, 11, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5582, 55, 3, 1, 'JAC-POLO-048-obsidian-black-L', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5583, 55, 4, 1, 'JAC-POLO-048-obsidian-black-XL', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5584, 55, 5, 1, 'JAC-POLO-048-obsidian-black-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5585, 55, 6, 1, 'JAC-POLO-048-obsidian-black-3XL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5586, 55, 1, 2, 'JAC-POLO-048-pure-white-S', NULL, NULL, 24, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5587, 55, 2, 2, 'JAC-POLO-048-pure-white-M', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5588, 55, 3, 2, 'JAC-POLO-048-pure-white-L', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5589, 55, 4, 2, 'JAC-POLO-048-pure-white-XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5590, 55, 5, 2, 'JAC-POLO-048-pure-white-XXL', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5591, 55, 6, 2, 'JAC-POLO-048-pure-white-3XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5592, 55, 1, 3, 'JAC-POLO-048-royal-navy-S', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5593, 55, 2, 3, 'JAC-POLO-048-royal-navy-M', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5594, 55, 3, 3, 'JAC-POLO-048-royal-navy-L', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5595, 55, 4, 3, 'JAC-POLO-048-royal-navy-XL', NULL, NULL, 22, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');
INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `sku`, `price`, `sale_price`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
  (5596, 55, 5, 3, 'JAC-POLO-048-royal-navy-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5597, 55, 6, 3, 'JAC-POLO-048-royal-navy-3XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5598, 56, 1, 4, 'JAC-POLO-049-olive-dusk-S', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5599, 56, 2, 4, 'JAC-POLO-049-olive-dusk-M', NULL, NULL, 34, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5600, 56, 3, 4, 'JAC-POLO-049-olive-dusk-L', NULL, NULL, 29, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5601, 56, 4, 4, 'JAC-POLO-049-olive-dusk-XL', NULL, NULL, 27, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5602, 56, 5, 4, 'JAC-POLO-049-olive-dusk-XXL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5603, 56, 6, 4, 'JAC-POLO-049-olive-dusk-3XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5604, 56, 1, 1, 'JAC-POLO-049-obsidian-black-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5605, 56, 2, 1, 'JAC-POLO-049-obsidian-black-M', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5606, 56, 3, 1, 'JAC-POLO-049-obsidian-black-L', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5607, 56, 4, 1, 'JAC-POLO-049-obsidian-black-XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5608, 56, 5, 1, 'JAC-POLO-049-obsidian-black-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5609, 56, 6, 1, 'JAC-POLO-049-obsidian-black-3XL', NULL, NULL, 19, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5610, 56, 1, 2, 'JAC-POLO-049-pure-white-S', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5611, 56, 2, 2, 'JAC-POLO-049-pure-white-M', NULL, NULL, 18, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5612, 56, 3, 2, 'JAC-POLO-049-pure-white-L', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5613, 56, 4, 2, 'JAC-POLO-049-pure-white-XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5614, 56, 5, 2, 'JAC-POLO-049-pure-white-XXL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5615, 56, 6, 2, 'JAC-POLO-049-pure-white-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5616, 56, 1, 3, 'JAC-POLO-049-royal-navy-S', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5617, 56, 2, 3, 'JAC-POLO-049-royal-navy-M', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5618, 56, 3, 3, 'JAC-POLO-049-royal-navy-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5619, 56, 4, 3, 'JAC-POLO-049-royal-navy-XL', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5620, 56, 5, 3, 'JAC-POLO-049-royal-navy-XXL', NULL, NULL, 6, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5621, 56, 6, 3, 'JAC-POLO-049-royal-navy-3XL', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5622, 57, 1, 3, 'JAC-POLO-050-royal-navy-S', NULL, NULL, 27, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5623, 57, 2, 3, 'JAC-POLO-050-royal-navy-M', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5624, 57, 3, 3, 'JAC-POLO-050-royal-navy-L', NULL, NULL, 17, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5625, 57, 4, 3, 'JAC-POLO-050-royal-navy-XL', NULL, NULL, 16, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5626, 57, 5, 3, 'JAC-POLO-050-royal-navy-XXL', NULL, NULL, 33, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5627, 57, 6, 3, 'JAC-POLO-050-royal-navy-3XL', NULL, NULL, 25, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5628, 57, 1, 1, 'JAC-POLO-050-obsidian-black-S', NULL, NULL, 12, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5629, 57, 2, 1, 'JAC-POLO-050-obsidian-black-M', NULL, NULL, 10, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5630, 57, 3, 1, 'JAC-POLO-050-obsidian-black-L', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5631, 57, 4, 1, 'JAC-POLO-050-obsidian-black-XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5632, 57, 5, 1, 'JAC-POLO-050-obsidian-black-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5633, 57, 6, 1, 'JAC-POLO-050-obsidian-black-3XL', NULL, NULL, 21, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5634, 57, 1, 2, 'JAC-POLO-050-pure-white-S', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5635, 57, 2, 2, 'JAC-POLO-050-pure-white-M', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5636, 57, 3, 2, 'JAC-POLO-050-pure-white-L', NULL, NULL, 14, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5637, 57, 4, 2, 'JAC-POLO-050-pure-white-XL', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5638, 57, 5, 2, 'JAC-POLO-050-pure-white-XXL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5639, 57, 6, 2, 'JAC-POLO-050-pure-white-3XL', NULL, NULL, 7, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5640, 57, 1, 4, 'JAC-POLO-050-olive-dusk-S', NULL, NULL, 13, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5641, 57, 2, 4, 'JAC-POLO-050-olive-dusk-M', NULL, NULL, 9, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5642, 57, 3, 4, 'JAC-POLO-050-olive-dusk-L', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5643, 57, 4, 4, 'JAC-POLO-050-olive-dusk-XL', NULL, NULL, 15, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5644, 57, 5, 4, 'JAC-POLO-050-olive-dusk-XXL', NULL, NULL, 23, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02'),
  (5645, 57, 6, 4, 'JAC-POLO-050-olive-dusk-3XL', NULL, NULL, 20, 1, '2026-08-28 18:59:02', '2026-08-28 18:59:02');

-- --------------------------------------------------------
-- Data for table `products` (57 rows)
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `category_id`, `short_description`, `description`, `base_price`, `sale_price`, `fabric`, `fit`, `pattern`, `collar_type`, `sleeve_type`, `wash_care`, `country_of_origin`, `is_bestseller`, `is_new_arrival`, `is_featured`, `is_active`, `size_chart`, `seo_title`, `seo_description`, `deleted_at`, `created_at`, `updated_at`) VALUES
  (1, 'JACARIO Riviera Amalfi Silk Polo', 'jacario-riviera-amalfi-silk-polo', 'JAC-POLO-001', 1, 'Featherweight silk-cotton knit inspired by coastal yachting elegance with hand-stitched mother-of-pearl buttons.', 'Featherweight silk-cotton knit inspired by coastal yachting elegance with hand-stitched mother-of-pearl buttons.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2499, 1999, '70% Egyptian Mako Cotton, 30% Mulberry Silk', 'Slim Fit', 'Solid', 'Italian Spread Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 1, 1, NULL, 'JACARIO Riviera Amalfi Silk Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Riviera Amalfi Silk Polo crafted from 70% Egyptian Mako Cotton, 30% Mulberry Silk. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (2, 'JACARIO Tuscan Olive Honeycomb Polo', 'jacario-tuscan-olive-honeycomb-polo', 'JAC-POLO-002', 1, 'Structured honeycomb weave with internal micro-interlining that guarantees zero collar curling through 50+ washes.', 'Structured honeycomb weave with internal micro-interlining that guarantees zero collar curling through 50+ washes.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1899, 1499, '100% Organic Piqué Cotton (230 GSM)', 'Regular Fit', 'Solid', 'Stay-Flat Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 1, 1, NULL, 'JACARIO Tuscan Olive Honeycomb Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Tuscan Olive Honeycomb Polo crafted from 100% Organic Piqué Cotton (230 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (3, 'JACARIO Pure Ivory Mercerized Polo', 'jacario-pure-ivory-mercerized-polo', 'JAC-POLO-003', 1, 'Brilliant non-translucent ivory tone with a silken sheen, reinforced side vents, and heat-sealed seams.', 'Brilliant non-translucent ivory tone with a silken sheen, reinforced side vents, and heat-sealed seams.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2199, 1799, 'Double-Mercerized Supima® Cotton', 'Slim Fit', 'Solid', 'Classic 3-Button Placket', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 1, 1, NULL, 'JACARIO Pure Ivory Mercerized Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Pure Ivory Mercerized Polo crafted from Double-Mercerized Supima® Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (4, 'JACARIO Forest Emerald Textured Knit Polo', 'jacario-forest-emerald-textured-knit-polo', 'JAC-POLO-004', 1, 'Deep emerald hue woven with flexible 4-way stretch for all-day comfort in tropical and temperate climates.', 'Deep emerald hue woven with flexible 4-way stretch for all-day comfort in tropical and temperate climates.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1999, 1599, 'Stretch Cotton Piqué (95% Cotton, 5% Elastane)', 'Regular Fit', 'Solid', 'Tailored Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 0, 1, NULL, 'JACARIO Forest Emerald Textured Knit Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Forest Emerald Textured Knit Polo crafted from Stretch Cotton Piqué (95% Cotton, 5% Elastane). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (5, 'JACARIO Royal Sapphire Johnny Collar Polo', 'jacario-royal-sapphire-johnny-collar-polo', 'JAC-POLO-005', 1, 'A relaxed open-collar silhouette paying homage to mid-century resort wear with a contemporary tapered hem.', 'A relaxed open-collar silhouette paying homage to mid-century resort wear with a contemporary tapered hem.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2399, 1899, '100% Extra-Long Staple Supima® Cotton', 'Relaxed Fit', 'Solid', 'Buttonless Johnny Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 1, 1, NULL, 'JACARIO Royal Sapphire Johnny Collar Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Royal Sapphire Johnny Collar Polo crafted from 100% Extra-Long Staple Supima® Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (6, 'JACARIO Sand Dune Waffle Knit Polo', 'jacario-sand-dune-waffle-knit-polo', 'JAC-POLO-006', 1, 'Warm earth tone with a tactile waffle texture, engineered to stay cool and wrinkle-free all day long.', 'Warm earth tone with a tactile waffle texture, engineered to stay cool and wrinkle-free all day long.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1399, 'Cotton Blend (80% Cotton, 20% Microfiber)', 'Regular Fit', 'Solid', 'Ribbed Knit Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 0, 1, NULL, 'JACARIO Sand Dune Waffle Knit Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Sand Dune Waffle Knit Polo crafted from Cotton Blend (80% Cotton, 20% Microfiber). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-17 20:46:25', '2026-08-19 16:38:52'),
  (7, 'JACARIO Essential Pitch Black Round Neck Tee', 'jacario-essential-pitch-black-round-neck-tee', 'JAC-RND-001', 2, 'The ultimate heavyweight black crew neck t-shirt. Clean, sculpted, and unyielding in collar shape.', 'Crafted from 100% organic long-staple combed cotton (220 GSM). Engineered with a high-density 1x1 rib neckline that maintains its crisp circle wash after wash without sagging.', 1499, 1199, '100% Organic Combed Cotton (220 GSM)', 'Tailored Sculpted Fit', 'Solid', 'Reinforced Seamless Crew Neck', 'Short Sleeve', 'Machine wash cold inside out', 'India', 1, 0, 1, 1, NULL, NULL, NULL, '2026-08-19 16:39:22', '2026-08-17 20:46:25', '2026-08-19 16:39:22'),
  (8, 'JACARIO Heavyweight Minimalist White Round Neck Tee', 'jacario-heavyweight-minimalist-white-round-neck-tee', 'JAC-RND-002', 2, 'Premium non-see-through heavyweight white crew neck tee with silky mercerized finish.', 'Designed for everyday luxury. Completely opaque and breathable with double-needle hems and blind-stitched sleeves for a pristine minimalist aesthetic.', 1499, 1199, '100% Mercerized Cotton (220 GSM)', 'Sculpted Regular Fit', 'Solid', 'Stay-Round Elastic Rib Collar', 'Short Sleeve', 'Machine wash cold with whites', 'India', 1, 0, 1, 1, NULL, NULL, NULL, '2026-08-19 16:39:22', '2026-08-17 20:46:25', '2026-08-19 16:39:22'),
  (9, 'JACARIO Nautical Deep Navy Round Neck Tee', 'jacario-nautical-deep-navy-round-neck-tee', 'JAC-RND-003', 2, 'Deep maritime navy crew neck with luminous cotton luster and ultra-soft handle.', 'Pre-shrunk, bio-washed cotton engineered to drape naturally over the shoulders without excess bulk around the midriff.', 1499, 1199, '100% Supima® Cotton', 'Tailored Fit', 'Solid', 'Reinforced Crew Neck', 'Short Sleeve', 'Machine wash cold', 'India', 0, 1, 0, 1, NULL, NULL, NULL, '2026-08-19 16:39:22', '2026-08-17 20:46:25', '2026-08-19 16:39:22'),
  (10, 'JACARIO Heather Grey Melange Round Neck Tee', 'jacario-heather-grey-melange-round-neck-tee', 'JAC-RND-004', 2, 'Classic tri-blend textured grey crew neck with athletic stretch and featherweight breathability.', 'Woven with multi-tonal grey fibers that offer subtle heathering and exceptional tactile comfort. Perfect under unbuttoned overshirts or jackets.', 1499, 1199, '95% Combed Cotton, 5% Elastane', 'Athletic Regular Fit', 'Solid', 'Ribbed Crew Collar', 'Short Sleeve', 'Machine wash cold', 'India', 0, 0, 1, 1, NULL, NULL, NULL, '2026-08-19 16:39:22', '2026-08-17 20:46:25', '2026-08-19 16:39:22'),
  (11, 'JACARIO Royal Sapphire Silk-Knit Polo', 'jacario-royal-sapphire-silk-knit-polo', 'JAC-NEW-001', 3, 'New Season Drop: Vibrant royal sapphire blue polo with fine-gauge knit and open Johnny collar.', 'An exquisite new arrival designed with a retro open collar (no buttons) and rib-hem finish. Crafted from pure Mulberry silk-cotton blend for unparalleled summer luxury.', 3199, 2699, '60% Mulberry Silk, 40% Egyptian Cotton', 'Relaxed Sartorial Fit', 'Solid', 'Open Riviera Johnny Collar', 'Short Sleeve with Ribbed Cuffs', 'Dry clean or gentle hand wash', 'India', 0, 1, 1, 1, NULL, NULL, NULL, '2026-08-19 16:39:22', '2026-08-17 20:46:25', '2026-08-19 16:39:22'),
  (12, 'JACARIO Forest Emerald Textured Knit Tee', 'jacario-forest-emerald-textured-knit-tee', 'JAC-NEW-002', 3, 'New Season Drop: Micro-waffle textured forest green crew neck tee with tailored European shoulders.', 'Our latest seasonal introduction featuring a subtle micro-waffle honeycomb weave that adds tactile dimensionality to a classic round neck silhouette.', 1799, 1499, '100% Micro-Waffle Pima Cotton (230 GSM)', 'Tailored Slim Fit', 'Solid', 'Ribbed Crew Neck', 'Short Sleeve', 'Machine wash cold', 'India', 0, 1, 1, 1, NULL, NULL, NULL, '2026-08-19 16:39:22', '2026-08-17 20:46:25', '2026-08-19 16:39:22'),
  (13, 'jac', 'jac', 'JAC-5TSAEQ', 1, 'ns', 'Meticulously crafted from extra-long staple American Supima® cotton with internal anti-curl collar interlining and hand-finished Australian mother-of-pearl buttons.', 2499, 1899, '100% Supima® Cotton (240 GSM)', 'Tailored Regular Fit', 'Solid', 'Stay-Flat Ribbed Collar', 'Short Sleeve with Ribbed Cuff', 'Machine wash cold with like colors. Dry flat.', 'India', 0, 1, 0, 1, NULL, 'jac | JACARIO Luxury Polo', 'ns', '2026-08-19 16:39:22', '2026-08-17 21:27:54', '2026-08-19 16:39:22'),
  (14, 'JACARIO Burgundy Royale Tailored Polo', 'jacario-burgundy-royale-tailored-polo', 'JAC-POLO-007', 1, 'Rich Bordeaux wine shade with Australian mother-of-pearl accents and high-tensile twin-needle stitching.', 'Rich Bordeaux wine shade with Australian mother-of-pearl accents and high-tensile twin-needle stitching.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2299, 1699, '100% Supima® Cotton (240 GSM)', 'Slim Fit', 'Solid', 'Structured Pointed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 1, 1, NULL, 'JACARIO Burgundy Royale Tailored Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Burgundy Royale Tailored Polo crafted from 100% Supima® Cotton (240 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (15, 'JACARIO Slate Charcoal Performance Polo', 'jacario-slate-charcoal-performance-polo', 'JAC-POLO-008', 1, 'Technical active polo engineered for effortless boardroom-to-golf-course versatility with antimicrobial finish.', 'Technical active polo engineered for effortless boardroom-to-golf-course versatility with antimicrobial finish.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1699, 1299, 'Performance Fabric (Moisture-Wicking Cotton Tech)', 'Regular Fit', 'Solid', 'Self-Fabric Structured Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 1, 0, 1, NULL, 'JACARIO Slate Charcoal Performance Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Slate Charcoal Performance Polo crafted from Performance Fabric (Moisture-Wicking Cotton Tech). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (16, 'JACARIO Signature Obsidian Supima Polo', 'jacario-signature-obsidian-supima-polo', 'JAC-POLO-009', 1, 'Our #1 bestseller worldwide. Jet black, thermally pre-shrunk, with genuine iridescent oyster shell hardware.', 'Our #1 bestseller worldwide. Jet black, thermally pre-shrunk, with genuine iridescent oyster shell hardware.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2499, 1999, '100% American Supima® Cotton (240 GSM)', 'Regular Fit', 'Solid', 'Anti-Curl Structured Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 1, 1, NULL, 'JACARIO Signature Obsidian Supima Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Signature Obsidian Supima Polo crafted from 100% American Supima® Cotton (240 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (17, 'JACARIO Monaco Navy Piqué Polo', 'jacario-monaco-navy-pique-polo', 'JAC-POLO-010', 1, 'Deep maritime navy with a tailored arm band and reinforced stepped side split hem for optimal tucking.', 'Deep maritime navy with a tailored arm band and reinforced stepped side split hem for optimal tucking.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1999, 1499, 'Premium Pique Cotton (230 GSM)', 'Slim Fit', 'Solid', 'Stay-Flat Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 1, 1, NULL, 'JACARIO Monaco Navy Piqué Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Monaco Navy Piqué Polo crafted from Premium Pique Cotton (230 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (18, 'JACARIO Oxford White Classic Polo', 'jacario-oxford-white-classic-polo', 'JAC-POLO-011', 1, 'The cornerstone of smart-casual dressing. Pristine optical white with double-stitched arm cuffs.', 'The cornerstone of smart-casual dressing. Pristine optical white with double-stitched arm cuffs.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1349, 'Premium Cotton (100% Combed Compact Yarn)', 'Regular Fit', 'Solid', 'Classic Polo Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 1, 1, NULL, 'JACARIO Oxford White Classic Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Oxford White Classic Polo crafted from Premium Cotton (100% Combed Compact Yarn). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (19, 'JACARIO Milano Heather Grey Polo', 'jacario-milano-heather-grey-polo', 'JAC-POLO-012', 1, 'Multi-tonal heather grey with athletic micro-stretch, offering sculptured drape without clinging.', 'Multi-tonal heather grey with athletic micro-stretch, offering sculptured drape without clinging.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1699, 1299, 'Stretch Cotton (95% Cotton, 5% Spandex)', 'Slim Fit', 'Solid', 'Ribbed Spread Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 0, 1, NULL, 'JACARIO Milano Heather Grey Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Milano Heather Grey Polo crafted from Stretch Cotton (95% Cotton, 5% Spandex). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (20, 'JACARIO British Racing Green Polo', 'jacario-british-racing-green-polo', 'JAC-POLO-013', 1, 'Heritage British racing green hue with subtle luster and unmatched colorfastness across 100 washes.', 'Heritage British racing green hue with subtle luster and unmatched colorfastness across 100 washes.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2099, 1599, '100% Long-Staple Pima Cotton', 'Regular Fit', 'Solid', 'Stay-Flat Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 1, 1, NULL, 'JACARIO British Racing Green Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO British Racing Green Polo crafted from 100% Long-Staple Pima Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (21, 'JACARIO Midnight Blue Luxury Polo', 'jacario-midnight-blue-luxury-polo', 'JAC-POLO-014', 1, 'A sartorial dark navy with Mulberry silk fibers providing an airy touch and natural temperature regulation.', 'A sartorial dark navy with Mulberry silk fibers providing an airy touch and natural temperature regulation.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2299, 1799, 'Supima® Cotton-Silk Blend', 'Slim Fit', 'Solid', 'Italian Micro-Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 1, 1, NULL, 'JACARIO Midnight Blue Luxury Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Midnight Blue Luxury Polo crafted from Supima® Cotton-Silk Blend. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (22, 'JACARIO Desert Sand Pima Polo', 'jacario-desert-sand-pima-polo', 'JAC-POLO-015', 1, 'Neutral beige tone perfect with linen trousers or tailored shorts for sun-drenched European getaways.', 'Neutral beige tone perfect with linen trousers or tailored shorts for sun-drenched European getaways.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1899, 1449, '100% Pima Cotton (220 GSM)', 'Relaxed Fit', 'Solid', 'Relaxed Open Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 0, 1, NULL, 'JACARIO Desert Sand Pima Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Desert Sand Pima Polo crafted from 100% Pima Cotton (220 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (23, 'JACARIO Venetian Maroon Ribbed Polo', 'jacario-venetian-maroon-ribbed-polo', 'JAC-POLO-016', 1, 'Regal deep maroon tone with contrasting micro-tipping along the placket for subtle sophistication.', 'Regal deep maroon tone with contrasting micro-tipping along the placket for subtle sophistication.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1999, 1499, 'Premium Cotton Blend', 'Regular Fit', 'Solid', 'Textured Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 1, 0, 0, 1, NULL, 'JACARIO Venetian Maroon Ribbed Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Venetian Maroon Ribbed Polo crafted from Premium Cotton Blend. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (24, 'JACARIO Capri Sky Blue Piqué Polo', 'jacario-capri-sky-blue-pique-polo', 'JAC-POLO-017', 1, 'Vibrant Mediterranean sky blue with anti-shrink thermal treatment and soft-feel mercerized cotton.', 'Vibrant Mediterranean sky blue with anti-shrink thermal treatment and soft-feel mercerized cotton.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1299, 'Premium Pique Cotton (230 GSM)', 'Regular Fit', 'Solid', 'Classic Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Capri Sky Blue Piqué Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Capri Sky Blue Piqué Polo crafted from Premium Pique Cotton (230 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (25, 'JACARIO Tuscan Mustard Knit Polo', 'jacario-tuscan-mustard-knit-polo', 'JAC-POLO-018', 1, 'Warm golden mustard shade with a touch of Mediterranean linen for airy texture and relaxed drape.', 'Warm golden mustard shade with a touch of Mediterranean linen for airy texture and relaxed drape.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1899, 1399, 'Cotton Blend (85% Cotton, 15% Linen)', 'Relaxed Fit', 'Solid', 'Camp Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Tuscan Mustard Knit Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Tuscan Mustard Knit Polo crafted from Cotton Blend (85% Cotton, 15% Linen). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (26, 'JACARIO French Navy Stripe-Tipped Polo', 'jacario-french-navy-stripe-tipped-polo', 'JAC-POLO-019', 1, 'Navy base accented with dual ivory tipping on the collar edge for a refined collegiate finish.', 'Navy base accented with dual ivory tipping on the collar edge for a refined collegiate finish.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2099, 1599, '100% Combed Compact Cotton', 'Slim Fit', 'Solid', 'Tipped Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO French Navy Stripe-Tipped Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO French Navy Stripe-Tipped Polo crafted from 100% Combed Compact Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (27, 'JACARIO Alpine White Performance Polo', 'jacario-alpine-white-performance-polo', 'JAC-POLO-020', 1, 'Engineered for golf, tennis, and warm city days. Hydrophilic micro-pores wick sweat in seconds.', 'Engineered for golf, tennis, and warm city days. Hydrophilic micro-pores wick sweat in seconds.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1699, 1199, 'Performance Fabric (Quick-Dry Breathable Piqué)', 'Regular Fit', 'Solid', 'Laser-Fused Flat Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Alpine White Performance Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Alpine White Performance Polo crafted from Performance Fabric (Quick-Dry Breathable Piqué). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (28, 'JACARIO Vintage Charcoal Enzyme Polo', 'jacario-vintage-charcoal-enzyme-polo', 'JAC-POLO-021', 1, 'Enzyme-washed for that perfectly lived-in feel on day one, with reinforced placket stitching.', 'Enzyme-washed for that perfectly lived-in feel on day one, with reinforced placket stitching.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1349, 'Enzyme-Washed Cotton (220 GSM)', 'Regular Fit', 'Solid', 'Soft-Roll Rib Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Vintage Charcoal Enzyme Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Vintage Charcoal Enzyme Polo crafted from Enzyme-Washed Cotton (220 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (29, 'JACARIO Portofino Ivory Silk-Blend Polo', 'jacario-portofino-ivory-silk-blend-polo', 'JAC-POLO-022', 1, 'Features hidden collar buttons that keep the collar neatly upright under blazers and sport coats.', 'Features hidden collar buttons that keep the collar neatly upright under blazers and sport coats.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2499, 1999, '75% Pima Cotton, 25% Silk', 'Slim Fit', 'Solid', 'Italian Button-Down Polo Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Portofino Ivory Silk-Blend Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Portofino Ivory Silk-Blend Polo crafted from 75% Pima Cotton, 25% Silk. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (30, 'JACARIO Olive Green Tactile Knit Polo', 'jacario-olive-green-tactile-knit-polo', 'JAC-POLO-023', 1, 'Muted army olive with high-density yarn weave for supreme durability and sharp silhouette retention.', 'Muted army olive with high-density yarn weave for supreme durability and sharp silhouette retention.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1899, 1399, 'Stretch Cotton Piqué', 'Slim Fit', 'Solid', 'Stay-Flat Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Olive Green Tactile Knit Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Olive Green Tactile Knit Polo crafted from Stretch Cotton Piqué. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (31, 'JACARIO Royal Cobalt Stretch Polo', 'jacario-royal-cobalt-stretch-polo', 'JAC-POLO-024', 1, 'High-impact electric cobalt blue that resists fading, treated with optical brightness enhancers.', 'High-impact electric cobalt blue that resists fading, treated with optical brightness enhancers.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1299, 'Stretch Cotton (96% Cotton, 4% Elastane)', 'Regular Fit', 'Solid', 'Ribbed Polo Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Royal Cobalt Stretch Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Royal Cobalt Stretch Polo crafted from Stretch Cotton (96% Cotton, 4% Elastane). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (32, 'JACARIO Haute Atelier Mulberry Silk Polo', 'jacario-haute-atelier-mulberry-silk-polo', 'JAC-POLO-025', 1, 'The crown jewel of the JACARIO atelier. Woven from Grade-6A Mulberry silk for an incomparable liquid drape.', 'The crown jewel of the JACARIO atelier. Woven from Grade-6A Mulberry silk for an incomparable liquid drape.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2499, NULL, '60% Grade-6A Mulberry Silk, 40% Giza Cotton', 'Slim Fit', 'Solid', 'Seamless Milano Knit Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Haute Atelier Mulberry Silk Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Haute Atelier Mulberry Silk Polo crafted from 60% Grade-6A Mulberry Silk, 40% Giza Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (33, 'JACARIO Giza 87 Egyptian Cotton Polo', 'jacario-giza-87-egyptian-cotton-polo', 'JAC-POLO-026', 1, 'Harvested along the Nile delta, Giza 87 is the world’s most uniform cotton yarn with a cashmere-like hand.', 'Harvested along the Nile delta, Giza 87 is the world’s most uniform cotton yarn with a cashmere-like hand.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2399, NULL, '100% Certified Egyptian Giza 87 Cotton', 'Regular Fit', 'Solid', 'Hand-Stitched Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Giza 87 Egyptian Cotton Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Giza 87 Egyptian Cotton Polo crafted from 100% Certified Egyptian Giza 87 Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (34, 'JACARIO Cashmere-Touch Supima Polo', 'jacario-cashmere-touch-supima-polo', 'JAC-POLO-027', 1, 'Micro-brushed surface yields a buttery soft texture reminiscent of fine Scottish cashmere.', 'Micro-brushed surface yields a buttery soft texture reminiscent of fine Scottish cashmere.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2499, 2199, '100% American Supima® Cotton (Brushed)', 'Regular Fit', 'Solid', 'Stay-Flat Structured Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Cashmere-Touch Supima Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Cashmere-Touch Supima Polo crafted from 100% American Supima® Cotton (Brushed). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (35, 'JACARIO Florence Heritage Pearl Polo', 'jacario-florence-heritage-pearl-polo', 'JAC-POLO-028', 1, 'Adorned with genuine 4mm thick Australian mother-of-pearl buttons with laser-etched JACARIO insignia.', 'Adorned with genuine 4mm thick Australian mother-of-pearl buttons with laser-etched JACARIO insignia.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2299, NULL, 'Double-Mercerized Combed Cotton', 'Slim Fit', 'Solid', 'Italian Spread Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Florence Heritage Pearl Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Florence Heritage Pearl Polo crafted from Double-Mercerized Combed Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (36, 'JACARIO Como Silk-Knit Open Collar Polo', 'jacario-como-silk-knit-open-collar-polo', 'JAC-POLO-029', 1, 'Knit on 18-gauge precision Japanese machines for seamless finish and ultra-clean contours.', 'Knit on 18-gauge precision Japanese machines for seamless finish and ultra-clean contours.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2499, 1999, '50% Mulberry Silk, 50% Supima® Cotton', 'Relaxed Fit', 'Solid', 'Open Riviera Johnny Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Como Silk-Knit Open Collar Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Como Silk-Knit Open Collar Polo crafted from 50% Mulberry Silk, 50% Supima® Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (37, 'JACARIO Imperial Bordeaux Pima Polo', 'jacario-imperial-bordeaux-pima-polo', 'JAC-POLO-030', 1, 'Hand-picked Peruvian Pima fibers known for exceptional staple length and resistance to pilling.', 'Hand-picked Peruvian Pima fibers known for exceptional staple length and resistance to pilling.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2199, NULL, '100% Peruvian Pima Cotton (240 GSM)', 'Regular Fit', 'Solid', 'Reinforced Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Imperial Bordeaux Pima Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Imperial Bordeaux Pima Polo crafted from 100% Peruvian Pima Cotton (240 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (38, 'JACARIO Monaco Gold Monogram Polo', 'jacario-monaco-gold-monogram-polo', 'JAC-POLO-031', 1, 'Subtle tonal metallic crest embroidery on left chest and gold-threaded collar interior.', 'Subtle tonal metallic crest embroidery on left chest and gold-threaded collar interior.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2399, 1899, '100% Supima® Cotton (240 GSM)', 'Slim Fit', 'Solid', 'Tailored Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Monaco Gold Monogram Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Monaco Gold Monogram Polo crafted from 100% Supima® Cotton (240 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (39, 'JACARIO Riviera Sandstone Silk Polo', 'jacario-riviera-sandstone-silk-polo', 'JAC-POLO-032', 1, 'Understated luxury in sandstone beige with a subtle sheen that reflects natural sunlight gorgeously.', 'Understated luxury in sandstone beige with a subtle sheen that reflects natural sunlight gorgeously.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2499, NULL, '70% Supima Cotton, 30% Mulberry Silk', 'Regular Fit', 'Solid', 'Italian Spread Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Riviera Sandstone Silk Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Riviera Sandstone Silk Polo crafted from 70% Supima Cotton, 30% Mulberry Silk. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (40, 'JACARIO Essential Pitch Black Polo', 'jacario-essential-pitch-black-polo', 'JAC-POLO-033', 1, 'Your daily workhorse polo. Breathable 100% combed cotton built for everyday rotation.', 'Your daily workhorse polo. Breathable 100% combed cotton built for everyday rotation.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 999, 799, '100% Combed Cotton (210 GSM)', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Pitch Black Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Pitch Black Polo crafted from 100% Combed Cotton (210 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (41, 'JACARIO Essential Crisp White Polo', 'jacario-essential-crisp-white-polo', 'JAC-POLO-034', 1, 'Pure white everyday tee with ribbed collar and comfortable, non-constricting arm bands.', 'Pure white everyday tee with ribbed collar and comfortable, non-constricting arm bands.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 999, 799, '100% Combed Cotton (210 GSM)', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Crisp White Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Crisp White Polo crafted from 100% Combed Cotton (210 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (42, 'JACARIO Essential Deep Navy Polo', 'jacario-essential-deep-navy-polo', 'JAC-POLO-035', 1, 'Rich everyday navy polo that pairs effortlessly with chinos, denim, and shorts.', 'Rich everyday navy polo that pairs effortlessly with chinos, denim, and shorts.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1099, 849, '100% Combed Cotton (210 GSM)', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Deep Navy Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Deep Navy Polo crafted from 100% Combed Cotton (210 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (43, 'JACARIO Essential Marl Grey Polo', 'jacario-essential-marl-grey-polo', 'JAC-POLO-036', 1, 'Light grey marl knit with heathered softness and natural stretch for all-day ease.', 'Light grey marl knit with heathered softness and natural stretch for all-day ease.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1099, 849, '90% Cotton, 10% Viscose', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Marl Grey Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Marl Grey Polo crafted from 90% Cotton, 10% Viscose. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (44, 'JACARIO Essential Charcoal Heather Polo', 'jacario-essential-charcoal-heather-polo', 'JAC-POLO-037', 1, 'Durable dark charcoal cotton with reactive dyes that prevent fading in harsh sunlight.', 'Durable dark charcoal cotton with reactive dyes that prevent fading in harsh sunlight.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1199, 899, '100% Ring-Spun Cotton', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Charcoal Heather Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Charcoal Heather Polo crafted from 100% Ring-Spun Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (45, 'JACARIO Essential Olive Drab Polo', 'jacario-essential-olive-drab-polo', 'JAC-POLO-038', 1, 'Versatile earthy olive tone designed for relaxed weekend outings and coffee runs.', 'Versatile earthy olive tone designed for relaxed weekend outings and coffee runs.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1199, 899, '100% Combed Cotton', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Olive Drab Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Olive Drab Polo crafted from 100% Combed Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (46, 'JACARIO Essential Maroon Polo', 'jacario-essential-maroon-polo', 'JAC-POLO-039', 1, 'Classic maroon polo with 2-button placket, pre-washed for shrink protection.', 'Classic maroon polo with 2-button placket, pre-washed for shrink protection.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1199, 899, '100% Cotton Piqué', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Maroon Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Maroon Polo crafted from 100% Cotton Piqué. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (47, 'JACARIO Essential Sky Breeze Polo', 'jacario-essential-sky-breeze-polo', 'JAC-POLO-040', 1, 'Crisp light pastel blue polo offering airy breathability and clean tailored seams.', 'Crisp light pastel blue polo offering airy breathability and clean tailored seams.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1199, 899, '100% Cotton Piqué', 'Regular Fit', 'Solid', 'Standard Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Essential Sky Breeze Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Essential Sky Breeze Polo crafted from 100% Cotton Piqué. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (48, 'JACARIO Contrast Collar Club Polo', 'jacario-contrast-collar-club-polo', 'JAC-POLO-041', 1, 'Sporty white body contrasted with deep navy collar and cuff trims for timeless athletic style.', 'Sporty white body contrasted with deep navy collar and cuff trims for timeless athletic style.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1399, '100% Combed Cotton Piqué', 'Slim Fit', 'Solid', 'Two-Tone Contrast Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Contrast Collar Club Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Contrast Collar Club Polo crafted from 100% Combed Cotton Piqué. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (49, 'JACARIO Riviera Nautical Stripe Polo', 'jacario-riviera-nautical-stripe-polo', 'JAC-POLO-042', 1, 'Classic Breton micro-stripes yarn-dyed before knitting to ensure crisp color integrity.', 'Classic Breton micro-stripes yarn-dyed before knitting to ensure crisp color integrity.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1899, 1449, 'Yarn-Dyed Piqué Cotton', 'Regular Fit', 'Solid', 'Solid Navy Rib Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Riviera Nautical Stripe Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Riviera Nautical Stripe Polo crafted from Yarn-Dyed Piqué Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (50, 'JACARIO Aero Coolmax Tech Polo', 'jacario-aero-coolmax-tech-polo', 'JAC-POLO-043', 1, 'Designed for 35°C+ summer heat with active evaporative cooling channels.', 'Designed for 35°C+ summer heat with active evaporative cooling channels.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1999, 1499, 'Performance Fabric (Coolmax® Infused Cotton Tech)', 'Slim Fit', 'Solid', 'Bonded Non-Curling Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Aero Coolmax Tech Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Aero Coolmax Tech Polo crafted from Performance Fabric (Coolmax® Infused Cotton Tech). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52');
INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `category_id`, `short_description`, `description`, `base_price`, `sale_price`, `fabric`, `fit`, `pattern`, `collar_type`, `sleeve_type`, `wash_care`, `country_of_origin`, `is_bestseller`, `is_new_arrival`, `is_featured`, `is_active`, `size_chart`, `seo_title`, `seo_description`, `deleted_at`, `created_at`, `updated_at`) VALUES
  (51, 'JACARIO Amalfi Sunset Terracotta Polo', 'jacario-amalfi-sunset-terracotta-polo', 'JAC-POLO-044', 1, 'Warm terracotta clay tone inspired by sun-drenched Italian coastlines.', 'Warm terracotta clay tone inspired by sun-drenched Italian coastlines.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1299, '100% Organic Piqué Cotton', 'Relaxed Fit', 'Solid', 'Stay-Flat Rib Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Amalfi Sunset Terracotta Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Amalfi Sunset Terracotta Polo crafted from 100% Organic Piqué Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (52, 'JACARIO Cypress Forest Heavy Piqué Polo', 'jacario-cypress-forest-heavy-pique-polo', 'JAC-POLO-045', 1, 'Substantial 260 GSM heavyweight knit providing an exceptionally clean, non-clinging structure.', 'Substantial 260 GSM heavyweight knit providing an exceptionally clean, non-clinging structure.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2099, 1599, 'Heavyweight Pique Cotton (260 GSM)', 'Regular Fit', 'Solid', 'Structured Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Cypress Forest Heavy Piqué Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Cypress Forest Heavy Piqué Polo crafted from Heavyweight Pique Cotton (260 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (53, 'JACARIO Midnight Shadow Matte Polo', 'jacario-midnight-shadow-matte-polo', 'JAC-POLO-046', 1, 'Deep matte black with tonal black buttons for an ultra-clean monochrome aesthetic.', 'Deep matte black with tonal black buttons for an ultra-clean monochrome aesthetic.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1699, 1299, '95% Cotton, 5% Spandex', 'Slim Fit', 'Solid', 'Matte Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Midnight Shadow Matte Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Midnight Shadow Matte Polo crafted from 95% Cotton, 5% Spandex. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (54, 'JACARIO Vintage Sand Heather Polo', 'jacario-vintage-sand-heather-polo', 'JAC-POLO-047', 1, 'Subtly mottled sand heather weave providing great texture underneath blazers.', 'Subtly mottled sand heather weave providing great texture underneath blazers.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1799, 1349, 'Tri-Blend Cotton Knit', 'Regular Fit', 'Solid', 'Ribbed Knit Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Vintage Sand Heather Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Vintage Sand Heather Polo crafted from Tri-Blend Cotton Knit. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (55, 'JACARIO Azure Blue Micro-Piqué Polo', 'jacario-azure-blue-micro-pique-polo', 'JAC-POLO-048', 1, 'Vivid azure hue with tight micro-piqué knit that stays smooth throughout the day.', 'Vivid azure hue with tight micro-piqué knit that stays smooth throughout the day.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1899, 1399, '100% Combed Mercerized Cotton', 'Slim Fit', 'Solid', 'Tailored Ribbed Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Azure Blue Micro-Piqué Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Azure Blue Micro-Piqué Polo crafted from 100% Combed Mercerized Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (56, 'JACARIO Sage Mist Relaxed Polo', 'jacario-sage-mist-relaxed-polo', 'JAC-POLO-049', 1, 'Calming pale sage green tone offering effortless styling with light grey or cream chinos.', 'Calming pale sage green tone offering effortless styling with light grey or cream chinos.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 1699, 1249, '100% Organic Pima Cotton', 'Relaxed Fit', 'Solid', 'Camp-Style Polo Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 0, 1, NULL, 'JACARIO Sage Mist Relaxed Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Sage Mist Relaxed Polo crafted from 100% Organic Pima Cotton. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52'),
  (57, 'JACARIO Grand Prix Royal Navy Polo', 'jacario-grand-prix-royal-navy-polo', 'JAC-POLO-050', 1, 'Commemorative 50th edition silhouette featuring reinforced triple-stitch placket and gold-rimmed pearl hardware.', 'Commemorative 50th edition silhouette featuring reinforced triple-stitch placket and gold-rimmed pearl hardware.

Engineered with JACARIO''s proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.', 2199, 1699, '100% Supima® Cotton (240 GSM)', 'Slim Fit', 'Solid', 'Engineered Stay-Flat Collar', 'Short Sleeve with Knitted Arm Ribbing', 'Machine wash cold with like colors, dry flat in shade, do not tumble dry', 'India', 0, 0, 1, 1, NULL, 'JACARIO Grand Prix Royal Navy Polo | JACARIO Luxury Polo T-Shirts', 'Shop JACARIO Grand Prix Royal Navy Polo crafted from 100% Supima® Cotton (240 GSM). Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.', NULL, '2026-08-19 16:38:52', '2026-08-19 16:38:52');

-- --------------------------------------------------------
-- Data for table `reviews` (50 rows)
-- --------------------------------------------------------
INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `comment`, `is_verified_purchase`, `is_approved`, `is_featured`, `created_at`, `updated_at`) VALUES
  (1, 1, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (2, 2, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (3, 3, 3, NULL, 5, 'Pure luxury silk-cotton blend', 'The Riviera Johnny collar polo is a work of art. The subtle sheen and buttery softness turn heads wherever I go. Essential summer knitwear.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (4, 4, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (5, 5, 3, NULL, 5, 'Unbeatable fabric and collar structure', 'I have bought polo shirts from Ralph Lauren, Sunspel, and Loro Piana over the years. The JACARIO Supima Polo genuinely stands toe-to-toe with luxury European houses. The collar does not roll or fray after multiple washes, and the drape is flawless.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (6, 6, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (7, 14, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (8, 15, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (9, 16, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (10, 17, 3, NULL, 5, 'Pure luxury silk-cotton blend', 'The Riviera Johnny collar polo is a work of art. The subtle sheen and buttery softness turn heads wherever I go. Essential summer knitwear.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (11, 18, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (12, 19, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (13, 20, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (14, 21, 3, NULL, 5, 'Pure luxury silk-cotton blend', 'The Riviera Johnny collar polo is a work of art. The subtle sheen and buttery softness turn heads wherever I go. Essential summer knitwear.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (15, 22, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (16, 23, 3, NULL, 5, 'Unbeatable fabric and collar structure', 'I have bought polo shirts from Ralph Lauren, Sunspel, and Loro Piana over the years. The JACARIO Supima Polo genuinely stands toe-to-toe with luxury European houses. The collar does not roll or fray after multiple washes, and the drape is flawless.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (17, 24, 3, NULL, 5, 'Unbeatable fabric and collar structure', 'I have bought polo shirts from Ralph Lauren, Sunspel, and Loro Piana over the years. The JACARIO Supima Polo genuinely stands toe-to-toe with luxury European houses. The collar does not roll or fray after multiple washes, and the drape is flawless.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (18, 25, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (19, 26, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (20, 27, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (21, 28, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (22, 29, 3, NULL, 5, 'Unbeatable fabric and collar structure', 'I have bought polo shirts from Ralph Lauren, Sunspel, and Loro Piana over the years. The JACARIO Supima Polo genuinely stands toe-to-toe with luxury European houses. The collar does not roll or fray after multiple washes, and the drape is flawless.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (23, 30, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (24, 31, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (25, 32, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (26, 33, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (27, 34, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (28, 35, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (29, 36, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (30, 37, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (31, 38, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (32, 39, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (33, 40, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (34, 41, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (35, 42, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (36, 43, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (37, 44, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (38, 45, 3, NULL, 5, 'Pure luxury silk-cotton blend', 'The Riviera Johnny collar polo is a work of art. The subtle sheen and buttery softness turn heads wherever I go. Essential summer knitwear.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (39, 46, 3, NULL, 5, 'Pure luxury silk-cotton blend', 'The Riviera Johnny collar polo is a work of art. The subtle sheen and buttery softness turn heads wherever I go. Essential summer knitwear.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (40, 47, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (41, 48, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (42, 49, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (43, 50, 3, NULL, 5, 'The absolute gold standard in polo shirts', 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (44, 51, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (45, 52, 3, NULL, 5, 'Golf performance is top tier', 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (46, 53, 3, NULL, 5, 'Unbeatable fabric and collar structure', 'I have bought polo shirts from Ralph Lauren, Sunspel, and Loro Piana over the years. The JACARIO Supima Polo genuinely stands toe-to-toe with luxury European houses. The collar does not roll or fray after multiple washes, and the drape is flawless.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (47, 54, 3, NULL, 5, 'Unbeatable fabric and collar structure', 'I have bought polo shirts from Ralph Lauren, Sunspel, and Loro Piana over the years. The JACARIO Supima Polo genuinely stands toe-to-toe with luxury European houses. The collar does not roll or fray after multiple washes, and the drape is flawless.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (48, 55, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (49, 56, 3, NULL, 4, 'Exceptional craftsmanship and packaging', 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!', 1, 1, 0, '2026-08-19 16:41:12', '2026-08-28 18:59:02'),
  (50, 57, 3, NULL, 5, 'Incredible handfeel and fit', 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.', 1, 1, 1, '2026-08-19 16:41:12', '2026-08-28 18:59:02');

-- --------------------------------------------------------
-- Data for table `sessions` (6 rows)
-- --------------------------------------------------------
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
  ('LGD2yyReDjYIpLAVdZdCMma16Dm17ZGZirnXn7iy', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqTEt3ZUhZYkI3d0pBcURmQ3BrOGloQUJieTNkOGtDZUJaMEp6d0hIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hcGlcL2NhcnRcL3N1bW1hcnkiLCJyb3V0ZSI6ImNhcnQuc3VtbWFyeS5hcGkifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1788944145),
  ('yOhwlAgqYMjImlEGaSKOjser7YM08R8B44IwQiGc', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJtTDVpODdSYUZqdmZTYWpVWFNZQld0ZE5LNnNtd2ptNE4waHFLNVRzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hcGlcL2NhcnRcL3N1bW1hcnkiLCJyb3V0ZSI6ImNhcnQuc3VtbWFyeS5hcGkifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1788975230),
  ('XnwBbiwqFV6P5QHYQWOkluA0V8DM4yzHrIox4mia', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJLaWxoR3JvS28wS1pUdmxkOGZoVXc1V3JGbDM2Y1BsNUxuY1F1TzBrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hcGlcL2NhcnRcL3N1bW1hcnkiLCJyb3V0ZSI6ImNhcnQuc3VtbWFyeS5hcGkifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1788983470),
  ('cPg5NdC6S4tMCxSSnX2VSNIA51YqD7AcCou3OsLY', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJaNTVkd0ZkQWswTUdKVThZVWtUaXVJWVF5aXY0SnBjaDNNOWZrelIyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hcGlcL2NhcnRcL3N1bW1hcnkiLCJyb3V0ZSI6ImNhcnQuc3VtbWFyeS5hcGkifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1789020196),
  ('D0XCnASKrEv4UFg1wq9sdNkd2Tpij7sGU7TPpJg9', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIwaWNuVjlRMU5WQVZYcERRUWM4ZGVYVFFlbmtJSmtPMzBMT0p1QXA1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hcGlcL2NhcnRcL3N1bW1hcnkiLCJyb3V0ZSI6ImNhcnQuc3VtbWFyeS5hcGkifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1789047678),
  ('QgyZXqZbEMxtMQOqIKRAaB9HmNwYe2vpzKoZ3Bjn', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0', 'eyJfdG9rZW4iOiJrbmhkTHpaS0p0UnQ4anRDeFIwVTl0UERRODVVaUVxS0Z5cUxhSUhXIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1789187160);

-- --------------------------------------------------------
-- Data for table `settings` (20 rows)
-- --------------------------------------------------------
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
  (1, 'store_name', 'JACARIO', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (2, 'store_tagline', 'Premium Polo T-Shirts, Made for Every Move', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (3, 'contact_email', 'concierge@jacario.com', '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (4, 'contact_phone', '+91 (0) 22 8900 1200', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (5, 'support_hours', 'Mon – Sat: 9:00 AM – 8:00 PM IST', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (6, 'store_address', 'JACARIO Flagship Atelier, 42 Heritage Boulevard, Bandra West, Mumbai, MH 400050, India', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (7, 'free_shipping_threshold', '1999', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (8, 'standard_shipping_rate', '150', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (9, 'tax_rate', '0', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (10, 'currency_symbol', '₹', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (11, 'currency_code', 'INR', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (12, 'instagram_url', 'https://instagram.com/jacario.official', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (13, 'twitter_url', 'https://twitter.com/jacario_brand', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (14, 'facebook_url', 'https://facebook.com/jacario.official', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (15, 'pinterest_url', 'https://pinterest.com/jacario_couture', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (16, 'brand_story_excerpt', 'At JACARIO, we do only one thing — and we do it to perfection. We obsess over the micro-details of the Polo T-Shirt: double-twisted long-staple yarns, mother-of-pearl hardware, and hand-finished collars that never curl.', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (17, 'shipping_policy', '### Shipping & Delivery
All JACARIO orders are packaged in our signature luxury presentation box and shipped via express courier.

- **Complimentary Express Shipping**: On all orders above ₹1,999.
- **Standard Express Delivery (₹150)**: Delivered within 2–4 business days across major metro cities, and 3–5 business days nationwide.
- **Order Tracking**: Real-time SMS and email notifications with live GPS tracking links as soon as your parcel departs our fulfillment atelier.', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (18, 'return_policy', '### Returns & Exchanges
We offer a 15-day complimentary exchange and return window on all unwashed, unworn Polo T-Shirts with original tags intact.

- **Hassle-free Doorstep Pickup**: Our courier partner will collect the parcel from your doorstep at no additional charge.
- **Instant Store Credit or Refund**: Refunds are processed back to the original payment method within 48 hours of quality inspection at our atelier.', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (19, 'privacy_policy', '### Privacy Policy
Your privacy is sacred at JACARIO. We protect your personal data using industry-standard 256-bit SSL encryption. We never sell, trade, or share your contact or transaction details with unauthorized third parties.', '2026-08-17 20:46:26', '2026-08-17 20:46:26'),
  (20, 'terms_conditions', '### Terms & Conditions
Welcome to JACARIO. By accessing our platform, you agree to our standard terms of purchase, authenticity guarantees, and fair usage guidelines.', '2026-08-17 20:46:26', '2026-08-17 20:46:26');

-- --------------------------------------------------------
-- Data for table `sizes` (6 rows)
-- --------------------------------------------------------
INSERT INTO `sizes` (`id`, `name`, `code`, `chest`, `length`, `shoulder`, `sort_order`, `created_at`, `updated_at`) VALUES
  (1, 'S', 'S', '38 in / 96 cm', '27.5 in / 70 cm', '17.0 in / 43 cm', 1, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (2, 'M', 'M', '40 in / 102 cm', '28.5 in / 72 cm', '17.7 in / 45 cm', 2, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (3, 'L', 'L', '42 in / 107 cm', '29.5 in / 75 cm', '18.5 in / 47 cm', 3, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (4, 'XL', 'XL', '44 in / 112 cm', '30.5 in / 77 cm', '19.3 in / 49 cm', 4, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (5, 'XXL', 'XXL', '46 in / 117 cm', '31.5 in / 80 cm', '20.1 in / 51 cm', 5, '2026-08-17 20:46:25', '2026-08-17 20:46:25'),
  (6, '3XL', '3XL', '48', '31', '21', 6, '2026-08-28 18:49:57', '2026-08-28 18:49:57');

-- --------------------------------------------------------
-- Data for table `users` (3 rows)
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `role`, `avatar`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
  (1, 'Jawed Hasan', 'admin@jacario.com', '+91 98765 43210', 'super_admin', NULL, 1, '2026-08-28 18:59:01', '$2y$12$R2iQZITO/Ko4pex1K6ugEu1Ym0tVYWM8FpnZq5O3Kg8Uu3ZgMuoQG', NULL, '2026-08-17 20:46:25', '2026-08-28 18:59:01'),
  (2, 'Elena Rostova (Product Lead)', 'manager@jacario.com', '+91 98765 43211', 'product_manager', NULL, 1, '2026-08-28 18:59:01', '$2y$12$kTnoLg3PjNpGnOguf8aeAebQgpii8fSn8ANMH4csGCwOjE2V8nZ3K', NULL, '2026-08-17 20:46:25', '2026-08-28 18:59:01'),
  (3, 'Julian Montgomery', 'customer@jacario.com', '+91 98200 12345', 'customer', NULL, 1, '2026-08-28 18:59:01', '$2y$12$BXO/8PAwivCWvphekdKMr.yy8dz..RtvKv2xMoLbkUoL6LRcYhEXy', NULL, '2026-08-17 20:46:25', '2026-08-28 18:59:01');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
