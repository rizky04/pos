-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table soto.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.cache: ~7 rows (approximately)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel_cache_admin@admin.com|127.0.0.1', 'i:1;', 1761947649),
	('laravel_cache_admin@admin.com|127.0.0.1:timer', 'i:1761947649;', 1761947649),
	('laravel_cache_admin2@gmail.com|127.0.0.1', 'i:1;', 1761394861),
	('laravel_cache_admin2@gmail.com|127.0.0.1:timer', 'i:1761394861;', 1761394861),
	('laravel_cache_analis@gmail.com|127.0.0.1', 'i:1;', 1761659186),
	('laravel_cache_analis@gmail.com|127.0.0.1:timer', 'i:1761659186;', 1761659186),
	('laravel_cache_spatie.permission.cache', 'a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:31:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:9:"role-list";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:11:"role-create";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:9:"role-edit";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:11:"role-delete";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:4;a:4:{s:1:"a";i:34;s:1:"b";s:11:"menu-barang";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:5;a:4:{s:1:"a";i:36;s:1:"b";s:14:"menu-penjualan";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:6;a:4:{s:1:"a";i:37;s:1:"b";s:16:"menu-master-data";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:7;a:4:{s:1:"a";i:38;s:1:"b";s:11:"menu-client";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:8;a:4:{s:1:"a";i:40;s:1:"b";s:9:"menu-user";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:9;a:4:{s:1:"a";i:41;s:1:"b";s:12:"menu-laporan";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:2;i:1;i:5;i:2;i:6;}}i:10;a:4:{s:1:"a";i:42;s:1:"b";s:19:"menu-penjualan-edit";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:11;a:4:{s:1:"a";i:43;s:1:"b";s:21:"menu-penjualan-delete";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:12;a:4:{s:1:"a";i:44;s:1:"b";s:21:"menu-penjualan-detail";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:13;a:4:{s:1:"a";i:45;s:1:"b";s:20:"menu-penjualan-print";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:14;a:4:{s:1:"a";i:46;s:1:"b";s:25:"menu-penjualan-pembayaran";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:15;a:4:{s:1:"a";i:51;s:1:"b";s:18:"master-data-barang";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:16;a:4:{s:1:"a";i:52;s:1:"b";s:29:"master-data-barang-harga-jual";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:2;i:1;i:5;i:2;i:6;}}i:17;a:4:{s:1:"a";i:53;s:1:"b";s:16:"master-data-stok";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:2;i:1;i:5;i:2;i:6;}}i:18;a:4:{s:1:"a";i:54;s:1:"b";s:21:"master-data-pembelian";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:19;a:4:{s:1:"a";i:57;s:1:"b";s:23:"master-data-barang-edit";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:2;i:1;i:5;i:2;i:6;}}i:20;a:4:{s:1:"a";i:58;s:1:"b";s:25:"master-data-barang-delete";s:1:"c";s:3:"web";s:1:"r";a:4:{i:0;i:2;i:1;i:3;i:2;i:5;i:3;i:6;}}i:21;a:4:{s:1:"a";i:59;s:1:"b";s:25:"master-data-barang-create";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:22;a:4:{s:1:"a";i:60;s:1:"b";s:28:"master-data-pembelian-create";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:2;i:1;i:3;i:2;i:6;}}i:23;a:4:{s:1:"a";i:61;s:1:"b";s:26:"master-data-pembelian-edit";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:24;a:4:{s:1:"a";i:62;s:1:"b";s:28:"master-data-pembelian-delete";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:25;a:4:{s:1:"a";i:63;s:1:"b";s:32:"master-data-pembelian-harga-baru";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:2;i:1;i:3;i:2;i:6;}}i:26;a:4:{s:1:"a";i:64;s:1:"b";s:15:"stok-opname-log";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:27;a:4:{s:1:"a";i:65;s:1:"b";s:17:"stok-keluar-masuk";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:28;a:4:{s:1:"a";i:66;s:1:"b";s:11:"stok-opname";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:29;a:4:{s:1:"a";i:67;s:1:"b";s:6:"profit";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}i:30;a:4:{s:1:"a";i:68;s:1:"b";s:5:"bayar";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:2;i:1;i:6;}}}s:5:"roles";a:4:{i:0;a:3:{s:1:"a";i:2;s:1:"b";s:10:"Superadmin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:6;s:1:"b";s:5:"Owner";s:1:"c";s:3:"web";}i:2;a:3:{s:1:"a";i:3;s:1:"b";s:5:"kasir";s:1:"c";s:3:"web";}i:3;a:3:{s:1:"a";i:5;s:1:"b";s:6:"admin2";s:1:"c";s:3:"web";}}}', 1763330529);

-- Dumping structure for table soto.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.cache_locks: ~0 rows (approximately)

-- Dumping structure for table soto.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','nonactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_tenant_id_index` (`tenant_id`),
  KEY `categories_status_index` (`status`),
  CONSTRAINT `categories_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.categories: ~4 rows (approximately)
INSERT INTO `categories` (`id`, `tenant_id`, `kode`, `nama`, `status`, `created_at`, `updated_at`) VALUES
	('019a8f25-8506-73d9-8e8b-2ca0db6cdbb7', '019a8bf5-236d-7151-bc39-79100576fbe2', '123', 'tes', 'active', '2025-11-17 00:09:52', '2025-11-17 00:09:52'),
	('019a8f25-be40-71ad-9c4b-6d53a369f3f8', '019a8bf5-236d-7151-bc39-79100576fbe2', 'jasa123', 'jasa', 'nonactive', '2025-11-17 00:10:06', '2025-11-17 00:10:58'),
	('019a8f26-0f7f-708d-adac-2bb773b06ebb', '019a8bf5-236d-7151-bc39-79100576fbe2', 'product123', 'product', 'active', '2025-11-17 00:10:27', '2025-11-17 00:10:27'),
	('019a8f31-8963-71ac-b358-d24c39733db8', '019a8f27-0986-73b8-bce9-059836e5eeb7', 'CAT-001', 'jasa', 'active', '2025-11-17 00:22:59', '2025-11-17 00:22:59');

-- Dumping structure for table soto.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_no_telp_unique` (`no_telp`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.customers: ~9 rows (approximately)
INSERT INTO `customers` (`id`, `name`, `no_telp`, `address`, `created_at`, `updated_at`) VALUES
	(7, 'Grace Fields', '+1 (832) 283-4137', 'At autem sapiente in', '2025-09-24 13:50:46', '2025-09-24 13:50:46'),
	(8, 'Rhona Shelton', '+1 (883) 252-9841', 'Incididunt sint eos', '2025-09-24 13:50:54', '2025-09-24 13:50:54'),
	(9, 'Halee Calderon', '+1 (906) 367-9845', 'In dolorem exercitat', '2025-09-24 13:51:04', '2025-09-24 13:51:04'),
	(10, 'Whitney Workman', '+1 (388) 672-5064', 'Earum dolorem consec', '2025-09-24 14:25:03', '2025-09-24 14:25:03'),
	(11, 'asdsa', 'asdsa', 'asdasd', '2025-09-24 22:22:57', '2025-09-24 22:22:57'),
	(12, 'Hanae William', '+1 (116) 385-6594', 'Qui aut quisquam qui', '2025-09-24 22:23:46', '2025-09-24 22:23:46'),
	(13, 'Frances Cooke', '+1 (322) 462-4879', 'Quia magna qui debit', '2025-09-24 22:23:54', '2025-09-24 22:23:54'),
	(14, 'Stuart Kaufman', '+1 (805) 615-9762', 'Dolorem ea unde moll', '2025-09-24 22:42:03', '2025-09-24 22:42:03'),
	(15, 'Reed Fleming', '+1 (459) 137-2283', 'Incidunt sint esse', '2025-09-24 22:42:10', '2025-09-24 22:42:10');

-- Dumping structure for table soto.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table soto.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.jobs: ~0 rows (approximately)

-- Dumping structure for table soto.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.job_batches: ~0 rows (approximately)

-- Dumping structure for table soto.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.migrations: ~39 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_06_28_102601_create_permission_tables', 1),
	(14, '2025_09_06_013335_create_transaction_items_table', 6),
	(16, '2025_09_05_003212_create_transactions_table', 7),
	(17, '2025_09_07_000357_create_promos_table', 8),
	(18, '2025_09_08_053241_add_plate_photo_to_transactions_table', 9),
	(20, '2025_09_17_100556_add_user_id_and_date_to_transactions_table', 11),
	(21, '2025_09_17_103505_add_user_id_create_and_date_to_transactions_table', 12),
	(23, '2025_09_20_170732_add_payment_method_to_transactions_table', 14),
	(25, '2025_06_28_102625_create_products_table', 15),
	(26, '2025_09_05_021947_create_customers_table', 16),
	(28, '2025_09_24_210819_create_mechanics_table', 18),
	(29, '2025_09_25_061448_create_services_table', 19),
	(30, '2025_09_25_061805_create_service_mechanics_table', 20),
	(31, '2025_09_25_061943_create_service_jobs_table', 21),
	(32, '2025_09_25_062815_create_service_spareparts_table', 22),
	(33, '2025_09_25_063803_add_created_by_updated_by_to_services_table', 23),
	(34, '2025_09_27_133754_add_service_progress_to_services_table', 24),
	(35, '2025_09_24_072942_create_vehicles_table', 25),
	(36, '2025_10_04_213015_add_nomor_service_to_services_table', 26),
	(37, '2025_10_08_050631_create_service_payments_table', 27),
	(39, '2025_10_12_081916_create_sales_items_table', 29),
	(40, '2025_10_12_083013_create_sales_paymnets_table', 30),
	(41, '2025_10_12_072749_create_sales_table', 31),
	(42, '2025_10_12_192013_create_sales_payments_table', 32),
	(44, '2025_10_16_045051_create_stok_opname_logs_table', 34),
	(45, '2025_10_16_050741_create_pembelians_table', 35),
	(46, '2025_10_16_071133_create_stok_transactions_table', 36),
	(48, '2025_10_18_220037_create_oil_services_table', 38),
	(49, '2025_11_15_113633_create_tenants_table', 39),
	(50, '2025_11_15_113645_create_outlets_table', 39),
	(51, '2025_11_15_123047_user_add_tenant', 39),
	(52, '2025_11_15_123142_create_settings_table', 39),
	(54, '2025_11_16_000001_create_suppliers_table', 40),
	(56, '2025_11_16_200241_create_units_table', 41),
	(57, '2025_11_17_064658_create_categories_table', 41),
	(58, '2025_11_17_103643_create_warehouses_table', 42);

-- Dumping structure for table soto.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table soto.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.model_has_roles: ~14 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(2, 'App\\Models\\User', 6),
	(3, 'App\\Models\\User', 9),
	(2, 'App\\Models\\User', 10),
	(3, 'App\\Models\\User', 12),
	(3, 'App\\Models\\User', 13),
	(3, 'App\\Models\\User', 14),
	(3, 'App\\Models\\User', 15),
	(2, 'App\\Models\\User', 16),
	(6, 'App\\Models\\User', 18),
	(6, 'App\\Models\\User', 21),
	(6, 'App\\Models\\User', 22),
	(6, 'App\\Models\\User', 23),
	(6, 'App\\Models\\User', 24),
	(6, 'App\\Models\\User', 25);

-- Dumping structure for table soto.outlets
CREATE TABLE IF NOT EXISTS `outlets` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outlet_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outlet_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `outlets_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `outlets_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.outlets: ~2 rows (approximately)
INSERT INTO `outlets` (`id`, `tenant_id`, `outlet_name`, `outlet_address`, `city`, `timezone`, `created_at`, `updated_at`) VALUES
	('019a8bf5-2477-715d-9ff5-9a5a1c75655f', '019a8bf5-236d-7151-bc39-79100576fbe2', 'amin jaya', 'PERUMAHAN GRIYA UTAMA', 'Bangkalan', 'Asia/Jakarta', '2025-11-16 09:18:10', '2025-11-16 09:18:10'),
	('019a8f27-0a89-709f-8023-d02d05c8091a', '019a8f27-0986-73b8-bce9-059836e5eeb7', 'bayu', 'PERUMAHAN GRIYA UTAMA', 'Bangkalan', 'Asia/Jakarta', '2025-11-17 00:11:31', '2025-11-17 00:11:31');

-- Dumping structure for table soto.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table soto.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.permissions: ~31 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'role-list', 'web', '2025-08-30 20:54:03', '2025-08-30 20:54:03'),
	(2, 'role-create', 'web', '2025-08-30 20:54:03', '2025-08-30 20:54:03'),
	(3, 'role-edit', 'web', '2025-08-30 20:54:03', '2025-08-30 20:54:03'),
	(4, 'role-delete', 'web', '2025-08-30 20:54:03', '2025-08-30 20:54:03'),
	(34, 'menu-barang', 'web', '2025-10-18 01:32:31', '2025-10-18 01:32:51'),
	(36, 'menu-penjualan', 'web', '2025-10-18 01:33:08', '2025-10-18 01:33:08'),
	(37, 'menu-master-data', 'web', '2025-10-18 01:33:20', '2025-10-18 01:33:20'),
	(38, 'menu-client', 'web', '2025-10-18 01:33:28', '2025-10-18 01:33:28'),
	(40, 'menu-user', 'web', '2025-10-18 01:33:47', '2025-10-18 01:33:47'),
	(41, 'menu-laporan', 'web', '2025-10-18 01:33:58', '2025-10-18 01:33:58'),
	(42, 'menu-penjualan-edit', 'web', '2025-10-18 01:41:07', '2025-10-18 01:41:07'),
	(43, 'menu-penjualan-delete', 'web', '2025-10-18 01:41:14', '2025-10-18 01:41:14'),
	(44, 'menu-penjualan-detail', 'web', '2025-10-18 01:41:22', '2025-10-18 01:41:22'),
	(45, 'menu-penjualan-print', 'web', '2025-10-18 01:41:30', '2025-10-18 01:41:30'),
	(46, 'menu-penjualan-pembayaran', 'web', '2025-10-18 01:41:47', '2025-10-18 01:41:47'),
	(51, 'master-data-barang', 'web', '2025-10-18 01:54:38', '2025-10-18 01:54:38'),
	(52, 'master-data-barang-harga-jual', 'web', '2025-10-18 01:57:45', '2025-10-18 01:57:45'),
	(53, 'master-data-stok', 'web', '2025-10-18 01:58:09', '2025-10-18 01:58:09'),
	(54, 'master-data-pembelian', 'web', '2025-10-18 01:58:26', '2025-10-18 01:58:26'),
	(57, 'master-data-barang-edit', 'web', '2025-10-18 02:47:39', '2025-10-18 02:47:39'),
	(58, 'master-data-barang-delete', 'web', '2025-10-18 02:47:49', '2025-10-18 02:47:49'),
	(59, 'master-data-barang-create', 'web', '2025-10-21 13:58:36', '2025-10-21 14:22:37'),
	(60, 'master-data-pembelian-create', 'web', '2025-10-21 14:25:25', '2025-10-21 14:25:39'),
	(61, 'master-data-pembelian-edit', 'web', '2025-10-21 14:26:27', '2025-10-21 14:26:27'),
	(62, 'master-data-pembelian-delete', 'web', '2025-10-21 14:26:43', '2025-10-21 14:26:43'),
	(63, 'master-data-pembelian-harga-baru', 'web', '2025-10-21 14:27:17', '2025-10-21 14:27:17'),
	(64, 'stok-opname-log', 'web', '2025-10-22 00:06:26', '2025-10-22 00:07:07'),
	(65, 'stok-keluar-masuk', 'web', '2025-10-22 00:06:46', '2025-10-22 00:10:42'),
	(66, 'stok-opname', 'web', '2025-10-22 00:08:52', '2025-10-22 00:08:52'),
	(67, 'profit', 'web', '2025-10-23 12:25:43', '2025-10-23 12:25:43'),
	(68, 'bayar', 'web', '2025-10-25 12:19:33', '2025-10-25 12:19:33');

-- Dumping structure for table soto.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.roles: ~4 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(2, 'Superadmin', 'web', '2025-08-30 20:57:38', '2025-11-15 22:02:09'),
	(3, 'kasir', 'web', '2025-09-17 09:57:13', '2025-09-17 09:57:13'),
	(5, 'admin2', 'web', '2025-10-21 13:13:43', '2025-10-21 13:13:43'),
	(6, 'Owner', 'web', '2025-10-21 13:13:43', '2025-10-21 13:13:43');

-- Dumping structure for table soto.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.role_has_permissions: ~92 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 2),
	(2, 2),
	(3, 2),
	(4, 2),
	(34, 2),
	(36, 2),
	(37, 2),
	(38, 2),
	(40, 2),
	(41, 2),
	(42, 2),
	(43, 2),
	(44, 2),
	(45, 2),
	(46, 2),
	(51, 2),
	(52, 2),
	(53, 2),
	(54, 2),
	(57, 2),
	(58, 2),
	(59, 2),
	(60, 2),
	(61, 2),
	(62, 2),
	(63, 2),
	(64, 2),
	(65, 2),
	(66, 2),
	(67, 2),
	(68, 2),
	(34, 3),
	(36, 3),
	(37, 3),
	(38, 3),
	(42, 3),
	(43, 3),
	(44, 3),
	(45, 3),
	(46, 3),
	(51, 3),
	(54, 3),
	(58, 3),
	(60, 3),
	(63, 3),
	(34, 5),
	(36, 5),
	(37, 5),
	(38, 5),
	(41, 5),
	(42, 5),
	(43, 5),
	(44, 5),
	(45, 5),
	(46, 5),
	(51, 5),
	(52, 5),
	(53, 5),
	(54, 5),
	(57, 5),
	(58, 5),
	(1, 6),
	(2, 6),
	(3, 6),
	(4, 6),
	(34, 6),
	(36, 6),
	(37, 6),
	(38, 6),
	(40, 6),
	(41, 6),
	(42, 6),
	(43, 6),
	(44, 6),
	(45, 6),
	(46, 6),
	(51, 6),
	(52, 6),
	(53, 6),
	(54, 6),
	(57, 6),
	(58, 6),
	(59, 6),
	(60, 6),
	(61, 6),
	(62, 6),
	(63, 6),
	(64, 6),
	(65, 6),
	(66, 6),
	(67, 6),
	(68, 6);

-- Dumping structure for table soto.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_client` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `id_user` bigint unsigned NOT NULL,
  `nomor_sales` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sales_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `status_bayar` enum('belum bayar','hutang','lunas','cicil') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum bayar',
  `total` decimal(15,2) NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_nomor_sales_unique` (`nomor_sales`),
  KEY `sales_id_client_foreign` (`id_client`),
  KEY `sales_id_transaksi_foreign` (`id_transaksi`),
  KEY `sales_id_user_foreign` (`id_user`),
  CONSTRAINT `sales_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `tbl_client` (`id_client`) ON DELETE CASCADE,
  CONSTRAINT `sales_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `tbl_transaksi` (`id_transaksi`) ON DELETE CASCADE,
  CONSTRAINT `sales_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.sales: ~1 rows (approximately)
INSERT INTO `sales` (`id`, `id_client`, `id_transaksi`, `id_user`, `nomor_sales`, `sales_date`, `due_date`, `status_bayar`, `total`, `note`, `created_at`, `updated_at`) VALUES
	(35, 126, 57, 6, 'PJL-20251101-001', '2025-11-01', NULL, 'lunas', 6000.00, 'oke', '2025-11-01 03:09:21', '2025-11-01 03:09:21');

-- Dumping structure for table soto.sales_items
CREATE TABLE IF NOT EXISTS `sales_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sales_id` bigint unsigned NOT NULL,
  `id_transaksi` int NOT NULL,
  `id_barang` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `purchase_price` decimal(15,2) DEFAULT NULL,
  `qty` int NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_items_sales_id_foreign` (`sales_id`),
  KEY `sales_items_id_transaksi_foreign` (`id_transaksi`),
  KEY `sales_items_id_barang_foreign` (`id_barang`),
  CONSTRAINT `sales_items_id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`) ON DELETE CASCADE,
  CONSTRAINT `sales_items_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `tbl_transaksi` (`id_transaksi`) ON DELETE CASCADE,
  CONSTRAINT `sales_items_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.sales_items: ~1 rows (approximately)
INSERT INTO `sales_items` (`id`, `sales_id`, `id_transaksi`, `id_barang`, `price`, `purchase_price`, `qty`, `subtotal`, `created_at`, `updated_at`) VALUES
	(84, 35, 57, 4118, 3000.00, 2000.00, 2, 6000.00, '2025-11-01 03:09:21', '2025-11-01 03:09:21');

-- Dumping structure for table soto.sales_payments
CREATE TABLE IF NOT EXISTS `sales_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_sales` bigint unsigned NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL,
  `change_amount` decimal(15,2) NOT NULL,
  `payment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_date` date NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_payments_id_sales_foreign` (`id_sales`),
  KEY `sales_payments_created_by_foreign` (`created_by`),
  CONSTRAINT `sales_payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `sales_payments_id_sales_foreign` FOREIGN KEY (`id_sales`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.sales_payments: ~1 rows (approximately)
INSERT INTO `sales_payments` (`id`, `id_sales`, `amount_paid`, `change_amount`, `payment_type`, `reference`, `note`, `payment_date`, `created_by`, `created_at`, `updated_at`) VALUES
	(29, 35, 6000.00, 0.00, 'cash', NULL, 'oke', '2025-11-01', 6, '2025-11-01 03:09:21', '2025-11-01 03:09:21');

-- Dumping structure for table soto.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('t58uMTwoVHOJGIQkkMKyH8ljXEXB71MUSzQ2WWeT', 25, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibDhEbDd5R3RRSVBYZzhVTG5WSUg3c3prOUs1M1pJNmt5MzdtSnMyRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC93YXJlaG91c2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjU7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzYzMzQ2MDI5O319', 1763355307);

-- Dumping structure for table soto.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_tax` decimal(5,2) NOT NULL DEFAULT '0.00',
  `prefix_sale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SL',
  `prefix_purchase` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PR',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `settings_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.settings: ~2 rows (approximately)
INSERT INTO `settings` (`id`, `tenant_id`, `default_tax`, `prefix_sale`, `prefix_purchase`, `created_at`, `updated_at`) VALUES
	('019a8bf5-2477-715d-9ff5-9a5a1d026e2a', '019a8bf5-236d-7151-bc39-79100576fbe2', 0.00, 'SL', 'PR', '2025-11-16 09:18:10', '2025-11-16 09:18:10'),
	('019a8f27-0a8a-7058-8bbb-ef26f0b2342a', '019a8f27-0986-73b8-bce9-059836e5eeb7', 0.00, 'SL', 'PR', '2025-11-17 00:11:31', '2025-11-17 00:11:31');

-- Dumping structure for table soto.stok_opname_logs
CREATE TABLE IF NOT EXISTS `stok_opname_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `stok_sistem` int NOT NULL DEFAULT '0',
  `stok_fisik` int NOT NULL DEFAULT '0',
  `selisih` int NOT NULL DEFAULT '0',
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `stok_opname_logs_id_barang_foreign` (`id_barang`),
  CONSTRAINT `stok_opname_logs_id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.stok_opname_logs: ~1 rows (approximately)
INSERT INTO `stok_opname_logs` (`id`, `id_barang`, `stok_sistem`, `stok_fisik`, `selisih`, `tanggal`) VALUES
	(2, 4118, 1000, 1500, 500, '2025-11-01 02:50:52');

-- Dumping structure for table soto.stok_transactions
CREATE TABLE IF NOT EXISTS `stok_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `jenis_transaksi` enum('masuk','keluar','rusak','return_pembelian','return_penjualan','koreksi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `stok_awal` int NOT NULL DEFAULT '0',
  `stok_akhir` int NOT NULL DEFAULT '0',
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stok_transactions_id_barang_foreign` (`id_barang`),
  CONSTRAINT `stok_transactions_id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.stok_transactions: ~1 rows (approximately)
INSERT INTO `stok_transactions` (`id`, `id_barang`, `jenis_transaksi`, `jumlah`, `stok_awal`, `stok_akhir`, `keterangan`, `created_by`, `created_at`, `updated_at`) VALUES
	(3, 4118, 'keluar', 500, 1500, 1000, 'rusak', '1', '2025-11-01 02:51:15', '2025-11-01 02:51:15');

-- Dumping structure for table soto.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_supplier` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_supplier` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termin_pembayaran` int NOT NULL DEFAULT '0' COMMENT 'dalam hari',
  `npwp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','nonactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_kode_supplier_unique` (`kode_supplier`),
  KEY `suppliers_tenant_id_index` (`tenant_id`),
  KEY `suppliers_status_index` (`status`),
  CONSTRAINT `suppliers_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.suppliers: ~2 rows (approximately)
INSERT INTO `suppliers` (`id`, `tenant_id`, `kode_supplier`, `nama_supplier`, `contact_person`, `telepon`, `email`, `alamat`, `kota`, `termin_pembayaran`, `npwp`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('019a8ce6-7303-712b-931e-fff1cc17885f', '019a8bf5-236d-7151-bc39-79100576fbe2', '23132', 'PT AMIN JAYA GROUP', 'MUHAMMAD RIZKY AMIN', '083119482925', 'aminrizky94@gmail.com', 'PERUMAHAN GRIYA UTAMA', 'Bangkalan', 0, '0000000123', 'active', '2025-11-16 13:41:44', '2025-11-16 13:41:44', NULL),
	('019a8fad-5113-7299-88ee-fa390540fe61', '019a8f27-0986-73b8-bce9-059836e5eeb7', 'SUP-001', 'amin jaya', 'amin jaya', '083119482925', 'aminrizky94@gmail.com', 'PERUMAHAN GRIYA UTAMA', 'Bangkalan', 30, '0000000123', 'active', '2025-11-17 02:38:11', '2025-11-17 02:38:11', NULL);

-- Dumping structure for table soto.tbl_barang
CREATE TABLE IF NOT EXISTS `tbl_barang` (
  `id_barang` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(100) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `merk_barang` varchar(255) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `stok_barang` int NOT NULL,
  `pagu` int NOT NULL,
  `harga_kulak` int NOT NULL,
  `harga_jual` int NOT NULL,
  `distributor` varchar(255) NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `hapus` int NOT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=4119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table soto.tbl_barang: ~1 rows (approximately)
INSERT INTO `tbl_barang` (`id_barang`, `kode_barang`, `nama_barang`, `merk_barang`, `keterangan`, `lokasi`, `stok_barang`, `pagu`, `harga_kulak`, `harga_jual`, `distributor`, `jenis`, `hapus`) VALUES
	(4118, '123', 'batako', 'blikon', 'bata oke', 'gudang', 1498, 100, 2000, 3000, 'rubicon', 'bata', 0);

-- Dumping structure for table soto.tbl_client
CREATE TABLE IF NOT EXISTS `tbl_client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `nama_client` varchar(255) NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `no_ktp` varchar(16) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `hapus` int NOT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table soto.tbl_client: ~5 rows (approximately)
INSERT INTO `tbl_client` (`id_client`, `nama_client`, `no_telp`, `no_ktp`, `alamat`, `hapus`) VALUES
	(123, 'aba amin', 'aba amin', 'aba amin', 'aba amin', 0),
	(124, 'amin jaya', '083119482925', 'amin jaya', 'amin jaya', 0),
	(125, 'amin jaya 123', '083119482925', 'amin jaya', 'asdasd', 0),
	(126, 'walkin', '0', '0', '0', 0),
	(127, 'ke tempat', '090934', '0394oi', 'slkf', 0);

-- Dumping structure for table soto.tbl_pembelian
CREATE TABLE IF NOT EXISTS `tbl_pembelian` (
  `id_pembelian` int NOT NULL AUTO_INCREMENT,
  `tgl_pembelian` datetime NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah_pembelian` int NOT NULL,
  `harga_kulak` int NOT NULL,
  `harga_jual` int NOT NULL,
  `id_pengguna` int NOT NULL,
  PRIMARY KEY (`id_pembelian`),
  KEY `id_barang` (`id_barang`),
  KEY `id_pengguna` (`id_pengguna`),
  CONSTRAINT `tbl_pembelian_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`),
  CONSTRAINT `tbl_pembelian_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=13192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table soto.tbl_pembelian: ~1 rows (approximately)
INSERT INTO `tbl_pembelian` (`id_pembelian`, `tgl_pembelian`, `id_barang`, `jumlah_pembelian`, `harga_kulak`, `harga_jual`, `id_pengguna`) VALUES
	(13191, '2025-11-01 09:54:06', 4118, 500, 2000, 3000, 1);

-- Dumping structure for table soto.tbl_pengguna
CREATE TABLE IF NOT EXISTS `tbl_pengguna` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  `rule` int NOT NULL,
  `hapus` int NOT NULL,
  PRIMARY KEY (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table soto.tbl_pengguna: ~2 rows (approximately)
INSERT INTO `tbl_pengguna` (`id_pengguna`, `nama`, `password`, `rule`, `hapus`) VALUES
	(1, 'admin', '$2y$10$HogUog/XCOkNX8p97kxxxO6LFIlnJ7bc3Y4DGWAowDsBHiPMk.Nbe', 1, 0),
	(2, 'kasir', '$2y$10$4FXgC3eMte1wl20jZh7w4eDu3MCBaUCD3Pbzk1nfeCSOWyzJRmmyS', 1, 0);

-- Dumping structure for table soto.tbl_penjualan
CREATE TABLE IF NOT EXISTS `tbl_penjualan` (
  `id_penjualan` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `jumlah_penjualan` int NOT NULL,
  `harga_jual` int NOT NULL,
  `harga_kulak` int NOT NULL,
  `id_transaksi` int NOT NULL,
  PRIMARY KEY (`id_penjualan`),
  KEY `id_barang` (`id_barang`),
  KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `tbl_penjualan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tbl_barang` (`id_barang`),
  CONSTRAINT `tbl_penjualan_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `tbl_transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table soto.tbl_penjualan: ~1 rows (approximately)
INSERT INTO `tbl_penjualan` (`id_penjualan`, `id_barang`, `jumlah_penjualan`, `harga_jual`, `harga_kulak`, `id_transaksi`) VALUES
	(104, 4118, 2, 3000, 2000, 57);

-- Dumping structure for table soto.tbl_piutang
CREATE TABLE IF NOT EXISTS `tbl_piutang` (
  `id_piutang` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `tgl_jatuh_tempo` date NOT NULL,
  `status_piutang` varchar(255) NOT NULL,
  `id_client` int NOT NULL,
  PRIMARY KEY (`id_piutang`),
  KEY `id_client` (`id_client`),
  KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `tbl_piutang_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `tbl_client` (`id_client`),
  CONSTRAINT `tbl_piutang_ibfk_3` FOREIGN KEY (`id_transaksi`) REFERENCES `tbl_transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table soto.tbl_piutang: ~0 rows (approximately)

-- Dumping structure for table soto.tbl_transaksi
CREATE TABLE IF NOT EXISTS `tbl_transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `tgl_transaksi` datetime NOT NULL,
  `id_pengguna` int NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `id_pengguna` (`id_pengguna`),
  CONSTRAINT `tbl_transaksi_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table soto.tbl_transaksi: ~1 rows (approximately)
INSERT INTO `tbl_transaksi` (`id_transaksi`, `tgl_transaksi`, `id_pengguna`) VALUES
	(57, '2025-11-01 10:09:21', 1);

-- Dumping structure for table soto.tenants
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Basic',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.tenants: ~2 rows (approximately)
INSERT INTO `tenants` (`id`, `name`, `owner_name`, `email`, `phone`, `package`, `created_at`, `updated_at`) VALUES
	('019a8bf5-236d-7151-bc39-79100576fbe2', 'amin jaya', 'muhammad rizky amin', 'carlivanhowten@gmail.com', '083119482925', 'Enterprise', '2025-11-16 09:18:09', '2025-11-16 09:18:09'),
	('019a8f27-0986-73b8-bce9-059836e5eeb7', 'bayu', 'bayu', 'bayu@gmail.com', '083119482925', 'Enterprise', '2025-11-17 00:11:31', '2025-11-17 00:11:31');

-- Dumping structure for table soto.units
CREATE TABLE IF NOT EXISTS `units` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unit',
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','nonactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_kode_unique` (`kode`),
  KEY `units_tenant_id_index` (`tenant_id`),
  KEY `units_status_index` (`status`),
  CONSTRAINT `units_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.units: ~1 rows (approximately)
INSERT INTO `units` (`id`, `tenant_id`, `nama`, `kode`, `tipe`, `deskripsi`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
	('019a8f16-0e36-7016-bd3a-6be4f40aaca3', '019a8bf5-236d-7151-bc39-79100576fbe2', 'pcs', 'pcs', 'unit', 'pcs', 'active', 1, '2025-11-16 23:52:58', '2025-11-16 23:52:58');

-- Dumping structure for table soto.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pengguna` int DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `user_pengguna` (`id_pengguna`),
  KEY `users_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `user_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table soto.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `tenant_id`, `name`, `email`, `id_pengguna`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(6, NULL, 'Admin', 'admin@gmail.com', 1, NULL, '$2y$12$SXg8BsIXXbn8Uc/0pbJebO7a69f1OaypMiyccnB.IWjTzrh8kd3Ze', 'RhEDPszUj6z7psyPfzn4bsfB4ZnDtvIV8MxQMIVnqEA18WdFGgXLIhWYyD6E', '2025-08-30 20:57:38', '2025-10-19 01:47:30'),
	(24, '019a8bf5-236d-7151-bc39-79100576fbe2', 'muhammad rizky amin', 'carlivanhowten@gmail.com', NULL, NULL, '$2y$12$PrIilNY.3Lw2gwwAhTv0Y.MpRJyqn7aYMSqhXVemL/KiVnimY4s8u', NULL, '2025-11-16 09:18:10', '2025-11-16 09:18:10'),
	(25, '019a8f27-0986-73b8-bce9-059836e5eeb7', 'bayu', 'bayu@gmail.com', NULL, NULL, '$2y$12$N7O1qPVig2g7/qpDNLSqkufIHyvhJn7sZdlfwFHsx43igMjug.2ty', NULL, '2025-11-17 00:11:31', '2025-11-17 00:11:31');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
