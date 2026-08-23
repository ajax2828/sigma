-- =============================================================
-- SPM Seminar - Full MySQL Schema
-- Generated from Laravel migrations (MySQL 8.0 / utf8mb4_unicode_ci)
-- Sumber: database/migrations/*.php + app/Models/*.php
-- Cara pakai: mysql -u spm -p spm < database/spm.sql
-- atau via Docker: docker compose exec db mysql -uspm -psecret spm < database/spm.sql
-- atau: docker compose exec -T db mysql -uspm -psecret spm < database/spm.sql
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

-- -------------------------------------------------------------
-- Tabel: migrations (dibuat otomatis Laravel, untuk tracking migrate)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: users (0001_01_01_000000_create_users_table)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: password_reset_tokens
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: sessions (SESSION_DRIVER=database)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: cache, cache_locks (CACHE_STORE=database)
-- 0001_01_01_000001_create_cache_table
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: jobs, job_batches, failed_jobs (QUEUE_CONNECTION=database)
-- 0001_01_01_000002_create_jobs_table
-- Dipakai untuk ProcessExcelImport, SendSeminarTicketEmail, SendSeminarCertificate
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: registrations (inti aplikasi)
-- 2025_07_17_192116_create_registrations_table
-- + 2025_07_20_144620_index_registrations (index name,is_scanned,scanned_at)
-- Model: App\Models\Registration & App\Models\Registrant (share tabel yang sama)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `Institute` varchar(255) NOT NULL COMMENT 'Nama institusi',
  `study` varchar(255) DEFAULT NULL,
  `user_status` varchar(255) DEFAULT NULL,
  `unique_code` varchar(255) NOT NULL,
  `qr_code_path` varchar(255) NOT NULL COMMENT 'path: qr-codes/qr_xxx.png -> storage/app/public',
  `is_scanned` tinyint(1) NOT NULL DEFAULT 0,
  `scanned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registrations_unique_code_unique` (`unique_code`),
  KEY `registrations_name_index` (`name`),
  KEY `registrations_is_scanned_index` (`is_scanned`),
  KEY `registrations_scanned_at_index` (`scanned_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Seed default (opsional) - sama dengan DatabaseSeeder.php
-- password default laravel: 'password' (Hash::make)
-- -------------------------------------------------------------
-- INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`) VALUES
-- ('Test User', 'test@example.com', '$2y$12$xxx_hashed_password_xxx', NOW(), NOW());

-- -------------------------------------------------------------
-- Contoh insert registrasi + QR (untuk test scan)
-- -------------------------------------------------------------
-- INSERT INTO `registrations` (`name`, `email`, `phone`, `Institute`, `study`, `user_status`, `unique_code`, `qr_code_path`, `is_scanned`, `created_at`, `updated_at`) VALUES
-- ('Budi Santoso', 'budi@example.com', '08123456789', 'Universitas Indonesia', 'Teknik Informatika', 'Mahasiswa', UUID(), 'qr-codes/qr_test.png', 0, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
