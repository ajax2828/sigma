-- =============================================================================
--  SIGMA — Full Database Dump (MySQL / MariaDB)
-- =============================================================================
--  Berisi STRUKTUR + DATA. Dipakai untuk import ke phpMyAdmin di server.
--
--  CARA IMPORT (phpMyAdmin):
--    1. Buka phpMyAdmin -> pilih database tujuan
--    2. Tab "Import" -> pilih file ini -> Format: SQL -> Go
--   _atau CLI: mysql -u USER -p NAMA_DATABASE < database/schema.sql
--
--  PERINGATAN: file ini diawali DROP TABLE IF EXISTS. Data lama di database
--  tujuan akan DIHAPUS. Kalau itu tidak diinginkan, hapus blok DROP di bawah.
--
--  TARGET   : MySQL 5.7+ / MySQL 8 / MariaDB 10.3+
--  CHARSET  : utf8mb4 (biar emoji di ikon prestasi aman)
--
--  SETELAH IMPORT, di server wajib:
--    - .env: DB_CONNECTION=mysql, isi host/user/password/nama database
--    - .env: APP_KEY=<hasil php artisan key:generate>
--              APP_KEY TIDAK ada di file ini, dan wajib ada di server.
--              Tanpa APP_KEY, session/cookie tidak bisa didekripsi.
--
--  Data yang ikut: 1 admin, 132 key landing_contents (seluruh isi halaman publik),
--  4 member, 16 riwayat member, 5 prestasi, 10 post, 10 catatan migration.
--  Tabel sessions/cache/jobs sengaja dibiarkan kosong.
--
--  Catatan migration ikut di-import, jadi `php artisan migrate` di server
--  tidak akan menjalankan ulang migration yang sudah ada di sini.
--
-- ------------------------------------------------------------------
--  ATURAN YANG TIDAK TERLIHAT DARI DDL
--  (dipindahkan dari docs/database-schema.md yang sudah dihapus)
--
--  * posts.status adalah gerbang tayang. Landing page hanya menampilkan
--    'published'; 'draft' dan 'archived' disembunyikan di query DAN di route
--    detail (abort_unless($post->isPublished(), 404)), jadi URL post draft
--    tidak bisa ditebak.
--  * posts.link menimpa tujuan klik. Kalau diisi, klik "Baca" di Kabar
--    Terbaru dan judul kartu melompat ke blog eksternal (tab baru). Kosong
--    berarti pakai halaman detail internal. Skema selain http/https diabaikan.
--  * landing_contents bukan tabel domain: satu baris per field, key unik.
--    Tampilan selalu punya fallback ($contents['key']->value ?? 'default').
--    Menambah key baru berarti tambah di seeder DAN allowlist controller
--    DAN form admin DAN Blade; kalau satu lupa, field itu membeku.
--  * member_histories itu SALINAN, bukan referensi. Setiap kolom data
--    member diulang per baris supaya undo tetap utuh setelah member diubah
--    atau dihapus. Karena itu kolom baru di `members` WAJIB dicerminkan ke
--    sini, ke model MemberHistory, ke pencatat riwayat, dan ke jalur restore.
--  * members.motto bukan description. description = bio panjang,
--    motto = satu kalimat yang tampil di kartu.
--  * posts belum punya index (status, created_at) dan members/achievements
--    belum punya index sort_order. Data masih sedikit jadi belum terasa.
--    Kalau tumbuh besar, tambahkan lewat migration BARU, jangan sunting file ini.
--
--  Sumber: database/database.sqlite (10 migration) + isi data live.
--  Kalau ada migration atau data baru, regenerate file ini.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- Baris di bawah ini adalah instruksi website; hapus kalau tidak mau dihapus.
-- CREATE DATABASE `sigma` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `sigma`;

-- =============================================================================
--  DROP
-- =============================================================================
DROP TABLE IF EXISTS `header_slides`;DROP TABLE IF EXISTS `posts`;DROP TABLE IF EXISTS `member_histories`;DROP TABLE IF EXISTS `members`;DROP TABLE IF EXISTS `achievements`;DROP TABLE IF EXISTS `landing_contents`;DROP TABLE IF EXISTS `sessions`;DROP TABLE IF EXISTS `cache`;DROP TABLE IF EXISTS `cache_locks`;DROP TABLE IF EXISTS `jobs`;DROP TABLE IF EXISTS `job_batches`;DROP TABLE IF EXISTS `failed_jobs`;DROP TABLE IF EXISTS `password_reset_tokens`;DROP TABLE IF EXISTS `migrations`;DROP TABLE IF EXISTS `users`;
-- =============================================================================
--  STRUKTUR
-- =============================================================================
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL COMMENT 'sudah di-hash (bcrypt)',
  `remember_token` varchar(100) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;-- header_slides ---------------------------------------------------------
--  Satu baris = satu slide banner di header landing page. Banner berputar
--  otomatis setiap 6 detik; slide nonaktif (is_active = 0) dilewati.
--  Urutan tampil mengikuti sort_order, lalu id.
--  image = latar slide itu sendiri; kosong berarti pakai gradient/gambar
--  dari pengaturan Background.
--  link = tujuan tombol; NULL berarti slide tanpa tombol utama.
--  Catatan: NULL dan kosong artinya berbeda — link NULL berarti slide tidak
--  punya tombol utama, image NULL berarti pakai background global.
CREATE TABLE `header_slides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NULL,
  `image` varchar(255) NULL,
  `link` varchar(2048) NULL,
  `cta_label` varchar(255) NULL,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `header_slides_sort_order_index` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `link` varchar(2048) NULL COMMENT 'URL blog tujuan; NULL = pakai halaman detail internal',
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft' COMMENT 'published = tayang di landing page',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = tampil di banner hero yang berganti otomatis',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `landing_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL COMMENT 'nama field, mis. hero_title / about_gradient_start',
  `value` text NULL COMMENT 'kosong = pakai fallback di Blade',
  `type` varchar(255) NOT NULL DEFAULT 'text' COMMENT 'text | textarea | image',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `landing_contents_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `initial` varchar(20) NULL COMMENT 'fallback kalau photo kosong',
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NULL COMMENT 'mis. K Divisi Desain',
  `code` varchar(255) NULL COMMENT 'nomor anggota, mis. SIGMA/2025/006',
  `description` text NULL COMMENT 'bio panjang, tidak ditampilkan di kartu',
  `motto` varchar(255) NULL COMMENT 'kata motivasi, yang tampil di kartu',
  `photo` varchar(255) NULL,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `member_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NULL COMMENT 'NULL kalau member dihapus',
  `action` varchar(20) NOT NULL COMMENT 'created | updated | deleted | migrated | restored',
  `initial` varchar(20) NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NULL,
  `code` varchar(255) NULL,
  `description` text NULL,
  `motto` varchar(255) NULL,
  `photo` varchar(255) NULL,
  `created_by` bigint unsigned NULL COMMENT 'admin yang melakukan aksi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_histories_member_id_created_at_index` (`member_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `achievements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(20) NULL COMMENT 'dipakai kalau image kosong',
  `image` varchar(255) NULL,
  `title` varchar(255) NOT NULL,
  `year` varchar(20) NULL,
  `description` text NULL,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned NULL,
  `ip_address` varchar(45) NULL,
  `user_agent` text NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext NULL,
  `cancelled_at` int NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE `failed_jobs` (
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
-- =============================================================================
--  DATA
-- =============================================================================

-- users -----------------------------------------------------------------------
-- Password di bawah sudah berupa hash bcrypt milik admin ini. Password polosnya
-- tidak tersimpan di sini maupun di file mana pun.
-- users -----------------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
  (1, 'Admin SIGMA', 'admin@sigma.id', NULL, '$2y$12$.rORTZnNvJiRV1TwT.AIUunwLwO1bj2Soo1zKTqaFPmtm9qEV8nEy', NULL, '2026-09-25 13:57:30', '2026-09-25 13:57:30');
-- posts -----------------------------------------------------------------
INSERT INTO `posts` (`id`, `title`, `content`, `link`, `status`, `is_featured`, `user_id`, `created_at`, `updated_at`) VALUES
  (12, 'Laporan Tahunan SIGMA 2025', 'Ringkasan kegiatan dan pencapaian organisasi sepanjang tahun 2025.', NULL, 'published', 0, 1, '2026-09-26 03:39:34', '2026-09-27 02:42:12'),
  (13, 'Panduan Lengkap Proyek SIGMA', 'Dokumentasi teknis untuk，维护 repository SIGMA.', 'ajawx.xyz', 'published', 0, 1, '2026-09-26 03:39:34', '2026-09-27 02:46:27'),
  (14, 'SIGMA Raih Juara Nasional', 'Tim SIGMA berhasil meraih juara pada kompetisi nasional.', NULL, 'archived', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34'),
  (15, 'Workshop Machine Learning', 'Materi workshop dan slide yang dapat diunduh.', NULL, 'published', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34'),
  (16, 'Profil Anggota', 'Daftar anggota dan kontak organisasi.', NULL, 'draft', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34'),
  (17, 'Festival Teknologi 2024', 'Liputan acara teknologi tahunan.', NULL, 'published', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34'),
  (18, 'Pelatihan Public Speaking', 'Materi pelatihan komunikasi.', NULL, 'draft', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34'),
  (19, 'Hackathon SIGMA', 'Rekaponnais hackathon internal.', NULL, 'published', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34'),
  (20, 'Program Beasiswa', 'Informasi program beasiswa anggota.', NULL, 'published', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34'),
  (21, 'Rapat Kerja Tahunan', 'Notulen rapat kerja.', NULL, 'draft', 0, 1, '2026-09-26 03:39:34', '2026-09-26 03:39:34');
-- landing_contents ------------------------------------------------------
INSERT INTO `landing_contents` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
  (1, 'hero_title', 'GALERI INVESTASI UNIVERSITAS YATSI MADANI', 'text', '2026-09-25 13:57:31', '2026-09-27 02:29:29'),
  (2, 'hero_tagline', 'Prestasi organisasi', 'text', '2026-09-25 13:57:31', '2026-09-27 02:29:29'),
  (3, 'hero_description', 'Organisasi mahasiswa yang bergerak di bidang teknologi dan informasi, berkomitmen menghasilkan karya inovatif dan berkontribusi untuk kemajuan teknologi di Indonesia.', 'textarea', '2026-09-25 13:57:31', '2026-09-25 15:15:59'),
  (4, 'about_title', 'Tentang Kami', 'text', '2026-09-25 13:57:31', '2026-09-25 14:23:09'),
  (5, 'about_description', 'SIGMA (Sistem Informasi dan Manajemen Terpadu) adalah sebuah organisasi mahasiswa yang berfokus pada pengembangan teknologi informasi. Kami beranggotakan para mahasiswa yang memiliki passion di bidang pemrograman, desain, dan manajemen proyek teknologi.\r\n\r\nDidirikan sejak tahun 2024, SIGMA telah menghasilkan berbagai produk digital dan telah berkontribusi dalam berbagai kompetisi teknologi tingkat nasional.', 'textarea', '2026-09-25 13:57:32', '2026-09-27 02:27:32'),
  (6, 'vision', 'Menjadi organisasi teknologi terdepan yang menghasilkan inovator-inovator muda berintegritas.', 'textarea', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (7, 'mission', 'Membangun ekosistem pembelajaran teknologi yang kolaboratif, inklusif, dan berdampak positif bagi masyarakat.', 'textarea', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (8, 'stat_active_members', '25+', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (9, 'stat_projects', '12', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (10, 'stat_awards', '8', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (11, 'stat_years', '3', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (12, 'stat_partnerships', '15+', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (13, 'member_section_title', 'Anggota Kami', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (14, 'member_section_subtitle', 'Orang-orang hebat yang berada di balik setiap pencapaian organisasi.', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (15, 'member_initial_1', 'AI', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (16, 'member_name_1', 'Ahmad Ihsan', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (17, 'member_role_1', 'Ketua Organisasi', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (18, 'member_code_1', 'Giyama/2025/001', 'text', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (19, 'member_desc_1', 'Mahasiswa Teknik Informatika yang memiliki pengalaman memimpin tim pengembangan aplikasi web dan mobile selama 3 tahun.', 'textarea', '2026-09-25 13:57:32', '2026-09-25 13:57:32'),
  (20, 'member_initial_2', 'BN', 'text', '2026-09-25 13:57:33', '2026-09-25 13:57:33'),
  (21, 'member_name_2', 'Bella Nadira', 'text', '2026-09-25 13:57:33', '2026-09-25 13:57:33'),
  (22, 'member_role_2', 'Wakil Ketua', 'text', '2026-09-25 13:57:33', '2026-09-25 13:57:33'),
  (23, 'member_code_2', 'Giyama/2025/002', 'text', '2026-09-25 13:57:33', '2026-09-25 13:57:33'),
  (24, 'member_desc_2', 'Spesialis UI/UX Design dengan portfolio lebih dari 20 proyek desain untuk startup dan perusahaan lokal.', 'textarea', '2026-09-25 13:57:34', '2026-09-25 13:57:34'),
  (25, 'member_initial_3', 'CR', 'text', '2026-09-25 13:57:34', '2026-09-25 13:57:34'),
  (26, 'member_name_3', 'Candra Rizky', 'text', '2026-09-25 13:57:34', '2026-09-25 13:57:34'),
  (27, 'member_role_3', 'Sekretaris', 'text', '2026-09-25 13:57:34', '2026-09-25 13:57:34'),
  (28, 'member_code_3', 'Giyama/2025/003', 'text', '2026-09-25 13:57:34', '2026-09-25 13:57:34'),
  (29, 'member_desc_3', 'Mengelola administrasi organisasi dan koordinasi event, aktif di kepanitiaan tingkat universitas.', 'textarea', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (30, 'member_initial_4', 'DP', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (31, 'member_name_4', 'Dian Permata', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (32, 'member_role_4', 'Bendahara', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (33, 'member_code_4', 'Giyama/2025/004', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (34, 'member_desc_4', 'Ahli keuangan yang mengatur anggaran dan sponsorship, berpengalaman di organisasi kemahasiswaan.', 'textarea', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (35, 'member_initial_5', 'EH', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (36, 'member_name_5', 'Eka Hidayat', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (37, 'member_role_5', 'K Divisi Teknologi', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (38, 'member_code_5', 'Giyama/2025/005', 'text', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (39, 'member_desc_5', 'Full-stack developer dengan keahlian Laravel, React, dan Flutter. Juara 2 Hackathon Nasional 2025.', 'textarea', '2026-09-25 13:57:35', '2026-09-25 13:57:35'),
  (40, 'member_initial_6', 'FA', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (41, 'member_name_6', 'Fitri Aulia', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (42, 'member_role_6', 'K Divisi Desain', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (43, 'member_code_6', 'Giyama/2025/006', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (44, 'member_desc_6', 'Desainer grafis profesional, berpengalaman membuat branding dan konten visual untuk berbagai komunitas.', 'textarea', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (45, 'member_initial_7', 'GH', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (46, 'member_name_7', 'Galih Prasetyo', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (47, 'member_role_7', 'K Divisi Humas', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (48, 'member_code_7', 'Giyama/2025/007', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (49, 'member_desc_7', 'Menangani hubungan masyarakat, media sosial, dan kerjasama eksternal dengan berbagai stakeholder.', 'textarea', '2026-09-25 13:57:36', '2026-09-25 13:57:36'),
  (50, 'member_initial_8', 'HN', 'text', '2026-09-25 13:57:36', '2026-09-25 13:57:36');
INSERT INTO `landing_contents` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
  (51, 'member_name_8', 'Hana Nurhaliza', 'text', '2026-09-25 13:57:37', '2026-09-25 13:57:37'),
  (52, 'member_role_8', 'Staf Ahli', 'text', '2026-09-25 13:57:37', '2026-09-25 13:57:37'),
  (53, 'member_code_8', 'Giyama/2025/008', 'text', '2026-09-25 13:57:37', '2026-09-25 13:57:37'),
  (54, 'member_desc_8', 'Mahasiswa Ilmu Komputer yang aktif mengajar programming dasar untuk anggota baru setiap semester.', 'textarea', '2026-09-25 13:57:37', '2026-09-25 13:57:37'),
  (55, 'achievement_section_title', 'Prestasi Kami', 'text', '2026-09-25 13:57:37', '2026-09-25 13:57:37'),
  (56, 'achievement_section_subtitle', 'Bukti dedikasi dan kerja keras organisasi dalam mencapai berbagai penghargaan.', 'text', '2026-09-25 13:57:37', '2026-09-25 13:57:37'),
  (89, 'site_title', 'Sigma', 'text', '2026-09-25 13:58:40', '2026-09-27 02:30:12'),
  (90, 'hero_cta_label', 'Pelajari Lebih', 'text', '2026-09-25 13:58:40', '2026-09-25 14:46:33'),
  (91, 'nav_brand', 'GIYAMA', 'text', '2026-09-25 13:58:40', '2026-09-27 02:27:32'),
  (92, 'nav_about_label', 'About', 'text', '2026-09-25 13:58:40', '2026-09-25 13:58:40'),
  (93, 'nav_members_label', 'Member', 'text', '2026-09-25 13:58:41', '2026-09-25 14:41:02'),
  (94, 'nav_achievements_label', 'Prestasi', 'text', '2026-09-25 13:58:41', '2026-09-25 13:58:41'),
  (95, 'nav_login_label', 'Masuk', 'text', '2026-09-25 13:58:41', '2026-09-25 14:41:02'),
  (96, 'footer_text', 'Organisasi SIGMA. All rights reserved.', 'text', '2026-09-25 13:58:42', '2026-09-25 13:58:42'),
  (97, 'about_subtitle', 'Kenali lebih dalam tentang organisasi kami, visi, misi, dan nilai-nilai yang kami pegang.', 'text', '2026-09-25 13:58:42', '2026-09-25 14:00:18'),
  (98, 'about_organization_title', 'Organisasi SIGMA', 'text', '2026-09-25 13:58:42', '2026-09-25 13:58:42'),
  (99, 'about_slide_title', 'Tentang Kami', 'text', '2026-09-25 13:58:42', '2026-09-25 13:58:42'),
  (100, 'vision_mission_title', 'Visi & Misi', 'text', '2026-09-25 13:58:42', '2026-09-25 13:58:42'),
  (101, 'stat_dedication', '100%', 'text', '2026-09-25 13:58:42', '2026-09-25 13:58:42'),
  (102, 'stat_active_members_label', 'Anggota Aktif', 'text', '2026-09-25 13:58:42', '2026-09-25 13:58:42'),
  (103, 'stat_projects_label', 'Proyek Selesai', 'text', '2026-09-25 13:58:42', '2026-09-25 13:58:42'),
  (104, 'stat_awards_label', 'Penghargaan', 'text', '2026-09-25 13:58:43', '2026-09-25 13:58:43'),
  (105, 'stat_years_label', 'Tahun Berdiri', 'text', '2026-09-25 13:58:43', '2026-09-25 13:58:43'),
  (106, 'stat_partnerships_label', 'Kerjasama', 'text', '2026-09-25 13:58:43', '2026-09-25 13:58:43'),
  (107, 'stat_dedication_label', 'Dedikasi', 'text', '2026-09-25 13:58:43', '2026-09-25 13:58:43'),
  (108, 'hero_background_color', '#f4f0e6', 'text', '2026-09-25 14:46:25', '2026-09-25 14:46:44'),
  (109, 'hero_background_image', '/images/hero/hero-background-1790477331-88b11e20.webp', 'image', '2026-09-25 14:51:45', '2026-09-27 02:48:51'),
  (110, 'page_background_image', NULL, 'image', '2026-09-25 15:17:40', '2026-09-25 15:20:32'),
  (111, 'page_gradient_start', '#f4f0e6', 'text', '2026-09-25 15:17:40', '2026-09-25 15:19:03'),
  (112, 'page_gradient_end', '#e8e0d1', 'text', '2026-09-25 15:17:40', '2026-09-25 15:19:03'),
  (113, 'page_gradient_angle', '0', 'text', '2026-09-25 15:17:41', '2026-09-27 02:50:42'),
  (114, 'hero_gradient_start', '#2f2819', 'text', '2026-09-25 15:17:41', '2026-09-27 02:48:51'),
  (115, 'hero_gradient_end', '#383733', 'text', '2026-09-25 15:17:42', '2026-09-27 02:49:40'),
  (116, 'hero_gradient_angle', '0', 'text', '2026-09-25 15:17:42', '2026-09-27 02:50:13'),
  (117, 'about_background_image', NULL, 'image', '2026-09-25 15:17:42', '2026-09-25 15:17:42'),
  (118, 'about_gradient_start', '#fffaf0', 'text', '2026-09-25 15:17:42', '2026-09-25 15:17:42'),
  (119, 'about_gradient_end', '#f4f0e6', 'text', '2026-09-25 15:17:43', '2026-09-25 15:17:43'),
  (120, 'about_gradient_angle', '180', 'text', '2026-09-25 15:17:43', '2026-09-25 15:17:43'),
  (121, 'members_background_image', NULL, 'image', '2026-09-25 15:17:43', '2026-09-25 15:17:43'),
  (122, 'members_gradient_start', '#e8e0d1', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (123, 'members_gradient_end', '#f8f5ed', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (124, 'members_gradient_angle', '180', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (125, 'achievements_background_image', NULL, 'image', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (126, 'achievements_gradient_start', '#f8f5ed', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (127, 'achievements_gradient_end', '#f4f0e6', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (128, 'achievements_gradient_angle', '180', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (129, 'footer_background_image', NULL, 'image', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (130, 'footer_gradient_start', '#e8e0d1', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (131, 'footer_gradient_end', '#f4f0e6', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44'),
  (132, 'footer_gradient_angle', '90', 'text', '2026-09-25 15:17:44', '2026-09-25 15:17:44');
INSERT INTO `landing_contents` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
  (133, 'achievement_1_icon', '🏆', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (134, 'achievement_1_title', 'Juara 1 - Nasional Innovation Challenge 2025', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (135, 'achievement_1_year', '2025', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (136, 'achievement_1_desc', 'Kompetisi inovasi teknologi tingkat nasional. Tim SIGMA membawa aplikasi manajemen sampah berbasis AI.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (137, 'achievement_2_icon', '🥈', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (138, 'achievement_2_title', 'Juara 2 - Hackathon Indonesia Digital 2025', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (139, 'achievement_2_year', '2025', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (140, 'achievement_2_desc', 'Hackathon 48 jam dengan tema "Digital Solutions for Education". Platform e-learning interaktif.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (141, 'achievement_3_icon', '🥉', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (142, 'achievement_3_title', 'Juara 3 - Lomba Cipta Aplikasi 2024', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (143, 'achievement_3_year', '2024', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (144, 'achievement_3_desc', 'Lomba pembuatan aplikasi tingkat provinsi diikuti 50+ tim dari universitas ternama.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (145, 'achievement_4_icon', '🎖️', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (146, 'achievement_4_title', 'Best Innovation Award - Tech Fest 2025', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (147, 'achievement_4_year', '2025', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (148, 'achievement_4_desc', 'Penghargaan inovasi terbaik dalam acara festival teknologi.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (149, 'achievement_5_icon', '🌟', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (150, 'achievement_5_title', '1000 Startup Digital - Top 100', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (151, 'achievement_5_year', '2024', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (152, 'achievement_5_desc', 'Berhasil masuk 100 besar program pencetakan startup digital Kominfo Indonesia.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (153, 'achievement_6_icon', '📜', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (154, 'achievement_6_title', 'Sertifikasi Internasional - Google IT Support', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (155, 'achievement_6_year', '2025', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (156, 'achievement_6_desc', '15 anggota berhasil mendapatkan sertifikasi Google IT Support.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (157, 'achievement_7_icon', '🚀', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (158, 'achievement_7_title', 'Most Active Community - Developer Circle', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (159, 'achievement_7_year', '2024', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (160, 'achievement_7_desc', 'Diakui sebagai komunitas teknologi paling aktif oleh Facebook Developer Circle.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (161, 'achievement_8_icon', '💡', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (162, 'achievement_8_title', 'Best Paper - Seminar Nasional Informatika', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (163, 'achievement_8_year', '2024', 'text', '2026-09-27 01:48:41', '2026-09-27 01:48:41'),
  (164, 'achievement_8_desc', 'Publikasi jurnal ilmiah tentang machine learning klasifikasi penyakit tanaman.', 'textarea', '2026-09-27 01:48:41', '2026-09-27 01:48:41');
-- members ---------------------------------------------------------------
INSERT INTO `members` (`id`, `initial`, `name`, `role`, `code`, `description`, `motto`, `photo`, `sort_order`, `created_at`, `updated_at`) VALUES
  (6, 'FA', 'Fitri Aulia', 'K Divisi Desain', 'Giyama/2025/006', 'Desainer grafis profesional, berpengalaman membuat branding dan konten visual untuk berbagai komunitas.', 'Belajar dulu, askepan kemudian.', NULL, 6, '2026-09-25 15:44:10', '2026-09-26 04:16:38'),
  (7, 'GH', 'Galih Prasetyo', 'K Divisi Humas', 'Giyama/2025/007', 'Menangani hubungan masyarakat, media sosial, dan kerjasama eksternal dengan berbagai stakeholder.', 'Karya kecil hari ini, dampak besar besok.', NULL, 7, '2026-09-25 15:44:10', '2026-09-26 04:16:38'),
  (8, 'HN', 'Hana Nurhaliza', 'Staf Ahli', 'Giyama/2025/008', 'Mahasiswa Ilmu Komputer yang aktif mengajar programming dasar untuk anggota baru setiap semester.', 'Konsisten itu bos, bukan bosan.', NULL, 8, '2026-09-25 15:44:10', '2026-09-26 04:16:38'),
  (14, 'IH', 'Ihsan', 'Direktur IT', 'Giyama/09/23', 'eghjhngfsdghfgjfgfdagdhfgj', 'Teknologi berarti, humans tetap nomor satu.', '/images/members/member-1790351816-e75ededa.jpg', 9, '2026-09-25 15:56:56', '2026-09-26 04:16:38');
-- member_histories ------------------------------------------------------
INSERT INTO `member_histories` (`id`, `member_id`, `action`, `initial`, `name`, `role`, `code`, `description`, `motto`, `photo`, `created_by`, `created_at`, `updated_at`) VALUES
  (1, 1, 'migrated', 'AI', 'Ahmad Ihsan', 'Ketua Organisasi', 'Giyama/2025/001', 'Mahasiswa Teknik Informatika yang memiliki pengalaman memimpin tim pengembangan aplikasi web dan mobile selama 3 tahun.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (2, 2, 'migrated', 'BN', 'Bella Nadira', 'Wakil Ketua', 'Giyama/2025/002', 'Spesialis UI/UX Design dengan portfolio lebih dari 20 proyek desain untuk startup dan perusahaan lokal.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (3, 3, 'migrated', 'CR', 'Candra Rizky', 'Sekretaris', 'Giyama/2025/003', 'Mengelola administrasi organisasi dan koordinasi event, aktif di kepanitiaan tingkat universitas.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (4, 4, 'migrated', 'DP', 'Dian Permata', 'Bendahara', 'Giyama/2025/004', 'Ahli keuangan yang mengatur anggaran dan sponsorship, berpengalaman di organisasi kemahasiswaan.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (5, 5, 'migrated', 'EH', 'Eka Hidayat', 'K Divisi Teknologi', 'Giyama/2025/005', 'Full-stack developer dengan keahlian Laravel, React, dan Flutter. Juara 2 Hackathon Nasional 2025.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (6, 6, 'migrated', 'FA', 'Fitri Aulia', 'K Divisi Desain', 'Giyama/2025/006', 'Desainer grafis profesional, berpengalaman membuat branding dan konten visual untuk berbagai komunitas.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (7, 7, 'migrated', 'GH', 'Galih Prasetyo', 'K Divisi Humas', 'Giyama/2025/007', 'Menangani hubungan masyarakat, media sosial, dan kerjasama eksternal dengan berbagai stakeholder.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (8, 8, 'migrated', 'HN', 'Hana Nurhaliza', 'Staf Ahli', 'Giyama/2025/008', 'Mahasiswa Ilmu Komputer yang aktif mengajar programming dasar untuk anggota baru setiap semester.', NULL, NULL, NULL, '2026-09-25 15:44:10', '2026-09-25 15:44:10'),
  (19, 1, 'deleted', 'AI', 'Ahmad Ihsan', 'Ketua Organisasi', 'Giyama/2025/001', 'Mahasiswa Teknik Informatika yang memiliki pengalaman memimpin tim pengembangan aplikasi web dan mobile selama 3 tahun.', NULL, NULL, 1, '2026-09-25 15:51:17', '2026-09-25 15:51:17'),
  (20, 2, 'deleted', 'BN', 'Bella Nadira', 'Wakil Ketua', 'Giyama/2025/002', 'Spesialis UI/UX Design dengan portfolio lebih dari 20 proyek desain untuk startup dan perusahaan lokal.', NULL, NULL, 1, '2026-09-25 15:51:37', '2026-09-25 15:51:37'),
  (21, 8, 'updated', 'HN', 'Hana Nurhaliza', 'Staf Ahli', 'Giyama/2025/008', 'Mahasiswa Ilmu Komputer yang aktif mengajar programming dasar untuk anggota baru setiap semester.', NULL, NULL, 1, '2026-09-25 15:51:51', '2026-09-25 15:51:51'),
  (25, 14, 'created', 'IH', 'Ihsan', 'Direktur IT', 'Giyama/09/23', 'eghjhngfsdghfgjfgfdagdhfgj', NULL, '/images/members/member-1790351816-e75ededa.jpg', 1, '2026-09-25 15:56:56', '2026-09-25 15:56:56'),
  (26, 4, 'deleted', 'DP', 'Dian Permata', 'Bendahara', 'Giyama/2025/004', 'Ahli keuangan yang mengatur anggaran dan sponsorship, berpengalaman di organisasi kemahasiswaan.', NULL, NULL, 1, '2026-09-25 15:57:34', '2026-09-25 15:57:34'),
  (27, 3, 'deleted', 'CR', 'Candra Rizky', 'Sekretaris', 'Giyama/2025/003', 'Mengelola administrasi organisasi dan koordinasi event, aktif di kepanitiaan tingkat universitas.', NULL, NULL, 1, '2026-09-25 16:01:16', '2026-09-25 16:01:16'),
  (28, 5, 'deleted', 'EH', 'Eka Hidayat', 'K Divisi Teknologi', 'Giyama/2025/005', 'Full-stack developer dengan keahlian Laravel, React, dan Flutter. Juara 2 Hackathon Nasional 2025.', NULL, NULL, 1, '2026-09-25 16:01:44', '2026-09-25 16:01:44'),
  (29, 14, 'updated', 'IH', 'Ihsan', 'Direktur IT', 'Giyama/09/23', 'eghjhngfsdghfgjfgfdagdhfgj', 'Teknologi berarti, humans tetap nomor satu.', '/images/members/member-1790351816-e75ededa.jpg', 1, '2026-09-26 09:13:33', '2026-09-26 09:13:33');
-- achievements ----------------------------------------------------------
INSERT INTO `achievements` (`id`, `icon`, `image`, `title`, `year`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
  (1, '🏆', '/images/achievements/achievement-1790397936-65c93c76.webp', 'Juara 1 - Nasional Innovation Challenge 2025', '2025', 'Kompetisi inovasi teknologi tingkat nasional. Tim SIGMA membawa aplikasi manajemen sampah berbasis AI.', 1, '2026-09-26 01:59:31', '2026-09-26 04:45:36'),
  (2, '🥈', '/images/achievements/ach2.png', 'Juara 2 - Hackathon Indonesia Digital 2025', '2025', 'Hackathon 48 jam dengan tema "Digital Solutions for Education". Platform e-learning interaktif.', 2, '2026-09-26 01:59:31', '2026-09-26 04:16:54'),
  (3, '🥉', '/images/achievements/ach3.png', 'Juara 3 - Lomba Cipta Aplikasi 2024', '2024', 'Lomba pembuatan aplikasi tingkat provinsi diikuti 50+ tim dari universitas ternama.', 3, '2026-09-26 01:59:31', '2026-09-26 04:16:54'),
  (4, '🎖️', '/images/achievements/ach1.png', 'Best Innovation Award - Tech Fest 2025', '2025', 'Penghargaan inovasi terbaik dalam acara festival teknologi.', 4, '2026-09-26 01:59:31', '2026-09-26 04:16:55'),
  (6, '📜', '/images/achievements/ach2.png', 'Sertifikasi Internasional - Google IT Support', '2025', '15 anggota berhasil mendapatkan sertifikasi Google IT Support.', 6, '2026-09-26 01:59:31', '2026-09-26 04:16:55');
-- migrations ------------------------------------------------------------
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
  (1, '0001_01_01_000000_create_users_table', 1),
  (2, '0001_01_01_000001_create_cache_table', 1),
  (3, '0001_01_01_000002_create_jobs_table', 1),
  (4, '2026_09_21_000001_create_posts_table', 1),
  (5, '2026_09_23_072255_create_landing_contents_table', 1),
  (6, '2026_09_25_000003_create_member_tables', 2),
  (8, '2026_09_26_000004_create_achievements_table', 3),
  (9, '2026_09_26_000005_add_image_to_achievements_table', 4),
  (10, '2026_09_26_000006_add_motto_to_members_table', 4),
  (11, '2026_09_27_000007_add_link_to_posts_table', 5),
  (12, '2026_09_27_000008_add_is_featured_to_posts_table', 6),
  (13, '2026_09_27_000009_create_header_slides_table', 7);
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
--  Verifikasi setelah import
-- =============================================================================
--  SELECT COUNT(*) FROM landing_contents;   -- 132
--  SELECT COUNT(*) FROM members;            -- 4
--  SELECT COUNT(*) FROM achievements;       -- 5
--  SELECT COUNT(*) FROM posts;              -- 10
--  SELECT COUNT(*) FROM member_histories;   -- 16
--  SELECT migration FROM migrations ORDER BY id;   -- 10 baris
--
--  Lalu di server: php artisan optimize:clear
-- =============================================================================
