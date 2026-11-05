/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `about_item_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `about_item_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `about_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `about_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `subheading` varchar(255) DEFAULT NULL,
  `experience_year` varchar(255) DEFAULT NULL,
  `experience_text` varchar(255) DEFAULT NULL,
  `youtube_id` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `phone_text` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `heading_fa` varchar(255) DEFAULT NULL,
  `subheading_fa` varchar(255) DEFAULT NULL,
  `experience_text_fa` varchar(255) DEFAULT NULL,
  `phone_text_fa` varchar(255) DEFAULT NULL,
  `button_text_fa` varchar(255) DEFAULT NULL,
  `text_fa` longtext DEFAULT NULL,
  `heading_fr` text DEFAULT NULL,
  `subheading_fr` text DEFAULT NULL,
  `experience_text_fr` text DEFAULT NULL,
  `phone_text_fr` text DEFAULT NULL,
  `button_text_fr` text DEFAULT NULL,
  `text_fr` text DEFAULT NULL,
  `heading_de` text DEFAULT NULL,
  `subheading_de` text DEFAULT NULL,
  `experience_text_de` text DEFAULT NULL,
  `phone_text_de` text DEFAULT NULL,
  `button_text_de` text DEFAULT NULL,
  `text_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'super_admin',
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `campaign_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_fr` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaign_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_category_id` bigint(20) unsigned DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `slug_fa` varchar(255) DEFAULT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `excerpt_fa` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `content_fa` longtext DEFAULT NULL,
  `status` enum('active','won','closed') NOT NULL DEFAULT 'active',
  `goal` varchar(255) DEFAULT NULL,
  `goal_fa` varchar(255) DEFAULT NULL,
  `cta_text` varchar(255) DEFAULT NULL,
  `cta_text_fa` varchar(255) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL,
  `stat_number` varchar(255) DEFAULT NULL,
  `stat_label` varchar(255) DEFAULT NULL,
  `stat_label_fa` varchar(255) DEFAULT NULL,
  `countries` varchar(255) DEFAULT NULL,
  `countries_fa` varchar(255) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title_fr` text DEFAULT NULL,
  `slug_fr` text DEFAULT NULL,
  `excerpt_fr` text DEFAULT NULL,
  `content_fr` text DEFAULT NULL,
  `goal_fr` text DEFAULT NULL,
  `cta_text_fr` text DEFAULT NULL,
  `stat_label_fr` text DEFAULT NULL,
  `countries_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `title_de` text DEFAULT NULL,
  `slug_de` text DEFAULT NULL,
  `excerpt_de` text DEFAULT NULL,
  `content_de` text DEFAULT NULL,
  `goal_de` text DEFAULT NULL,
  `cta_text_de` text DEFAULT NULL,
  `stat_label_de` text DEFAULT NULL,
  `countries_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaigns_slug_unique` (`slug`),
  KEY `campaigns_campaign_category_id_index` (`campaign_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_page_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_page_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `custom_page_id` bigint(20) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `caption_fa` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `caption_fr` text DEFAULT NULL,
  `caption_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_page_images_custom_page_id_foreign` (`custom_page_id`),
  CONSTRAINT `custom_page_images_custom_page_id_foreign` FOREIGN KEY (`custom_page_id`) REFERENCES `custom_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `slug_fa` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Show',
  `content` longtext DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `excerpt_fa` text DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` varchar(255) DEFAULT NULL,
  `content_fa` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `home_bg_image` varchar(255) DEFAULT NULL,
  `slug_fr` text DEFAULT NULL,
  `title_fr` text DEFAULT NULL,
  `excerpt_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `content_fr` text DEFAULT NULL,
  `slug_de` text DEFAULT NULL,
  `title_de` text DEFAULT NULL,
  `excerpt_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  `content_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `donor_name` varchar(255) NOT NULL,
  `donor_email` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `message` text DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'PayPal',
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_fr` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `question_fa` longtext DEFAULT NULL,
  `answer_fa` longtext DEFAULT NULL,
  `question_fr` text DEFAULT NULL,
  `answer_fr` text DEFAULT NULL,
  `question_de` text DEFAULT NULL,
  `answer_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_faqs_event_id_index` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_category_id` bigint(20) unsigned DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `slug_fa` varchar(255) DEFAULT NULL,
  `description` longtext NOT NULL,
  `show_on_home` varchar(255) NOT NULL,
  `event_location` varchar(255) NOT NULL,
  `event_location_detail` varchar(255) NOT NULL,
  `event_date` varchar(255) NOT NULL,
  `event_time` varchar(255) NOT NULL,
  `ticket_price` varchar(255) DEFAULT NULL,
  `youtube_id` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `event_location_fa` varchar(255) DEFAULT NULL,
  `event_location_detail_fa` varchar(255) DEFAULT NULL,
  `button_text_fa` varchar(255) DEFAULT NULL,
  `description_fa` longtext DEFAULT NULL,
  `slug_fr` text DEFAULT NULL,
  `title_fr` text DEFAULT NULL,
  `event_location_fr` text DEFAULT NULL,
  `event_location_detail_fr` text DEFAULT NULL,
  `button_text_fr` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `slug_de` text DEFAULT NULL,
  `title_de` text DEFAULT NULL,
  `event_location_de` text DEFAULT NULL,
  `event_location_detail_de` text DEFAULT NULL,
  `button_text_de` text DEFAULT NULL,
  `description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_event_category_id_foreign` (`event_category_id`),
  CONSTRAINT `events_event_category_id_foreign` FOREIGN KEY (`event_category_id`) REFERENCES `event_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `question_fa` varchar(255) DEFAULT NULL,
  `answer_fa` longtext DEFAULT NULL,
  `question_fr` text DEFAULT NULL,
  `answer_fr` text DEFAULT NULL,
  `question_de` text DEFAULT NULL,
  `answer_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `home_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_texts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `value_fa` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `value_fr` text DEFAULT NULL,
  `value_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `home_texts_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `member_awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_awards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `name_fr` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_awards_member_id_index` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `member_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_fr` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_category_id` bigint(20) unsigned DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `slug_fa` varchar(255) DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `description` longtext DEFAULT NULL,
  `show_on_home` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `designation_fa` varchar(255) DEFAULT NULL,
  `short_description_fa` varchar(255) DEFAULT NULL,
  `description_fa` longtext DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `show_contact` tinyint(1) NOT NULL DEFAULT 0,
  `name_fr` text DEFAULT NULL,
  `slug_fr` text DEFAULT NULL,
  `designation_fr` text DEFAULT NULL,
  `short_description_fr` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  `slug_de` text DEFAULT NULL,
  `designation_de` text DEFAULT NULL,
  `short_description_de` text DEFAULT NULL,
  `description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_slug_unique` (`slug`),
  KEY `members_member_category_id_foreign` (`member_category_id`),
  CONSTRAINT `members_member_category_id_foreign` FOREIGN KEY (`member_category_id`) REFERENCES `member_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `membership_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_invitations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `invited_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membership_invitations_token_unique` (`token`),
  KEY `membership_invitations_invited_by_foreign` (`invited_by`),
  CONSTRAINT `membership_invitations_invited_by_foreign` FOREIGN KEY (`invited_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `label_fa` varchar(255) DEFAULT NULL,
  `link_type` enum('route','custom_page','url') NOT NULL DEFAULT 'url',
  `route_name` varchar(255) DEFAULT NULL,
  `custom_page_id` bigint(20) unsigned DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `open_new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `is_button` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_footer` tinyint(1) NOT NULL DEFAULT 1,
  `footer_column` varchar(20) NOT NULL DEFAULT 'about',
  `card_image` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `status` enum('Show','Hide') NOT NULL DEFAULT 'Show',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `label_fr` text DEFAULT NULL,
  `label_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_parent_id_foreign` (`parent_id`),
  KEY `menu_items_custom_page_id_foreign` (`custom_page_id`),
  CONSTRAINT `menu_items_custom_page_id_foreign` FOREIGN KEY (`custom_page_id`) REFERENCES `custom_pages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_home_name` text DEFAULT NULL,
  `menu_home_status` text DEFAULT NULL,
  `menu_about_name` text DEFAULT NULL,
  `menu_about_status` text DEFAULT NULL,
  `menu_event_name` text DEFAULT NULL,
  `menu_event_status` text DEFAULT NULL,
  `menu_team_member_name` text DEFAULT NULL,
  `menu_team_member_status` text DEFAULT NULL,
  `menu_gallery_name` text DEFAULT NULL,
  `menu_gallery_status` text DEFAULT NULL,
  `menu_faq_name` text DEFAULT NULL,
  `menu_faq_status` text DEFAULT NULL,
  `menu_blog_name` text DEFAULT NULL,
  `menu_blog_status` text DEFAULT NULL,
  `menu_contact_name` text DEFAULT NULL,
  `menu_contact_status` text DEFAULT NULL,
  `menu_login_name` text DEFAULT NULL,
  `menu_login_status` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `menu_donate_name` text DEFAULT NULL,
  `menu_donate_status` text DEFAULT NULL,
  `menu_home_name_fa` varchar(255) DEFAULT NULL,
  `menu_about_name_fa` varchar(255) DEFAULT NULL,
  `menu_event_name_fa` varchar(255) DEFAULT NULL,
  `menu_team_member_name_fa` varchar(255) DEFAULT NULL,
  `menu_gallery_name_fa` varchar(255) DEFAULT NULL,
  `menu_faq_name_fa` varchar(255) DEFAULT NULL,
  `menu_blog_name_fa` varchar(255) DEFAULT NULL,
  `menu_contact_name_fa` varchar(255) DEFAULT NULL,
  `menu_login_name_fa` varchar(255) DEFAULT NULL,
  `menu_donate_name_fa` varchar(255) DEFAULT NULL,
  `menu_home_name_fr` text DEFAULT NULL,
  `menu_about_name_fr` text DEFAULT NULL,
  `menu_event_name_fr` text DEFAULT NULL,
  `menu_team_member_name_fr` text DEFAULT NULL,
  `menu_gallery_name_fr` text DEFAULT NULL,
  `menu_faq_name_fr` text DEFAULT NULL,
  `menu_blog_name_fr` text DEFAULT NULL,
  `menu_contact_name_fr` text DEFAULT NULL,
  `menu_login_name_fr` text DEFAULT NULL,
  `menu_donate_name_fr` text DEFAULT NULL,
  `menu_home_name_de` text DEFAULT NULL,
  `menu_about_name_de` text DEFAULT NULL,
  `menu_event_name_de` text DEFAULT NULL,
  `menu_team_member_name_de` text DEFAULT NULL,
  `menu_gallery_name_de` text DEFAULT NULL,
  `menu_faq_name_de` text DEFAULT NULL,
  `menu_blog_name_de` text DEFAULT NULL,
  `menu_contact_name_de` text DEFAULT NULL,
  `menu_login_name_de` text DEFAULT NULL,
  `menu_donate_name_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_about_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_about_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `about_status` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_blog_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_blog_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_contact_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_contact_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `phone_number` text DEFAULT NULL,
  `email_address` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `map_iframe_code` text DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `location_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `location_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `location_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_donate_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_donate_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `preset_amounts` varchar(255) DEFAULT NULL,
  `goal_amount` decimal(12,2) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `heading_fa` varchar(255) DEFAULT NULL,
  `description_fa` longtext DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `heading_fr` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `heading_de` text DEFAULT NULL,
  `description_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_event_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_event_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_faq_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_faq_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_gallery_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_gallery_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_home_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_home_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `about_status` text DEFAULT NULL,
  `team_subheading` text DEFAULT NULL,
  `team_heading` text DEFAULT NULL,
  `team_status` text DEFAULT NULL,
  `event_subheading` text DEFAULT NULL,
  `event_heading` text DEFAULT NULL,
  `event_status` text DEFAULT NULL,
  `blog_subheading` text DEFAULT NULL,
  `blog_heading` text DEFAULT NULL,
  `blog_status` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team_subheading_fa` varchar(255) DEFAULT NULL,
  `team_heading_fa` varchar(255) DEFAULT NULL,
  `event_subheading_fa` varchar(255) DEFAULT NULL,
  `event_heading_fa` varchar(255) DEFAULT NULL,
  `blog_subheading_fa` varchar(255) DEFAULT NULL,
  `blog_heading_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `team_subheading_fr` text DEFAULT NULL,
  `team_heading_fr` text DEFAULT NULL,
  `event_subheading_fr` text DEFAULT NULL,
  `event_heading_fr` text DEFAULT NULL,
  `blog_subheading_fr` text DEFAULT NULL,
  `blog_heading_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `team_subheading_de` text DEFAULT NULL,
  `team_heading_de` text DEFAULT NULL,
  `event_subheading_de` text DEFAULT NULL,
  `event_heading_de` text DEFAULT NULL,
  `blog_subheading_de` text DEFAULT NULL,
  `blog_heading_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_login_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_login_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login_page_title` varchar(255) DEFAULT NULL,
  `register_page_title` varchar(255) DEFAULT NULL,
  `forget_password_page_title` varchar(255) DEFAULT NULL,
  `reset_password_page_title` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `login_page_title_fa` varchar(255) DEFAULT NULL,
  `register_page_title_fa` varchar(255) DEFAULT NULL,
  `forget_password_page_title_fa` varchar(255) DEFAULT NULL,
  `reset_password_page_title_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `login_page_title_fr` text DEFAULT NULL,
  `register_page_title_fr` text DEFAULT NULL,
  `forget_password_page_title_fr` text DEFAULT NULL,
  `reset_password_page_title_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `login_page_title_de` text DEFAULT NULL,
  `register_page_title_de` text DEFAULT NULL,
  `forget_password_page_title_de` text DEFAULT NULL,
  `reset_password_page_title_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_privacy_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_privacy_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `page_content` longtext DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `page_content_fa` longtext DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `page_content_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `page_content_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_team_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_team_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `intro` text DEFAULT NULL,
  `intro_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `intro_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  `intro_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_terms_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_terms_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `page_content` longtext DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_title_fa` varchar(255) DEFAULT NULL,
  `page_content_fa` longtext DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `page_title_fr` text DEFAULT NULL,
  `page_content_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `page_title_de` text DEFAULT NULL,
  `page_content_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `page_user_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_user_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dashboard_page_title` varchar(255) DEFAULT NULL,
  `profile_page_title` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dashboard_page_title_fa` varchar(255) DEFAULT NULL,
  `profile_page_title_fa` varchar(255) DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` text DEFAULT NULL,
  `dashboard_page_title_fr` text DEFAULT NULL,
  `profile_page_title_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `dashboard_page_title_de` text DEFAULT NULL,
  `profile_page_title_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `logo` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Show',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_fr` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `caption_fa` varchar(255) DEFAULT NULL,
  `caption_fr` text DEFAULT NULL,
  `caption_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `poll_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `poll_response_id` bigint(20) unsigned NOT NULL,
  `poll_question_id` bigint(20) unsigned NOT NULL,
  `poll_option_id` bigint(20) unsigned DEFAULT NULL,
  `rating` tinyint(3) unsigned DEFAULT NULL,
  `text` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_answers_poll_response_id_foreign` (`poll_response_id`),
  KEY `poll_answers_poll_question_id_foreign` (`poll_question_id`),
  KEY `poll_answers_poll_option_id_foreign` (`poll_option_id`),
  CONSTRAINT `poll_answers_poll_option_id_foreign` FOREIGN KEY (`poll_option_id`) REFERENCES `poll_options` (`id`) ON DELETE CASCADE,
  CONSTRAINT `poll_answers_poll_question_id_foreign` FOREIGN KEY (`poll_question_id`) REFERENCES `poll_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `poll_answers_poll_response_id_foreign` FOREIGN KEY (`poll_response_id`) REFERENCES `poll_responses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `poll_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `poll_question_id` bigint(20) unsigned NOT NULL,
  `label` varchar(500) NOT NULL,
  `label_fa` varchar(500) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `label_fr` text DEFAULT NULL,
  `label_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_options_poll_question_id_foreign` (`poll_question_id`),
  CONSTRAINT `poll_options_poll_question_id_foreign` FOREIGN KEY (`poll_question_id`) REFERENCES `poll_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `poll_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` bigint(20) unsigned NOT NULL,
  `question` varchar(500) NOT NULL,
  `question_fa` varchar(500) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'single',
  `required` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `question_fr` text DEFAULT NULL,
  `question_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_questions_poll_id_foreign` (`poll_id`),
  CONSTRAINT `poll_questions_poll_id_foreign` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `poll_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_responses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` bigint(20) unsigned NOT NULL,
  `voter_token` varchar(64) NOT NULL,
  `ip_hash` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `poll_responses_poll_id_voter_token_unique` (`poll_id`,`voter_token`),
  KEY `poll_responses_poll_id_ip_hash_index` (`poll_id`,`ip_hash`),
  CONSTRAINT `poll_responses_poll_id_foreign` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `slug_fa` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_fa` text DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'site',
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `results_visibility` varchar(255) NOT NULL DEFAULT 'after_vote',
  `ends_at` timestamp NULL DEFAULT NULL,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `google_form_url` varchar(1000) DEFAULT NULL,
  `google_csv_url` varchar(1000) DEFAULT NULL,
  `google_columns` longtext DEFAULT NULL,
  `google_cache` longtext DEFAULT NULL,
  `google_synced_at` timestamp NULL DEFAULT NULL,
  `google_sync_error` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title_fr` text DEFAULT NULL,
  `slug_fr` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `title_de` text DEFAULT NULL,
  `slug_de` text DEFAULT NULL,
  `description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `polls_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `post_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `slug_fa` varchar(255) DEFAULT NULL,
  `show_in_menu` varchar(255) NOT NULL DEFAULT 'Hide',
  `menu_label` varchar(255) DEFAULT NULL,
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `menu_label_fa` varchar(255) DEFAULT NULL,
  `slug_fr` text DEFAULT NULL,
  `name_fr` text DEFAULT NULL,
  `menu_label_fr` text DEFAULT NULL,
  `slug_de` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  `menu_label_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_category_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `slug_fa` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `description` longtext NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'published',
  `show_on_home` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `published_at` timestamp NULL DEFAULT NULL,
  `author_name` varchar(120) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_meta_description` varchar(300) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `excerpt_fa` text DEFAULT NULL,
  `description_fa` longtext DEFAULT NULL,
  `seo_title_fa` varchar(255) DEFAULT NULL,
  `seo_meta_description_fa` varchar(300) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug_fr` text DEFAULT NULL,
  `title_fr` text DEFAULT NULL,
  `excerpt_fr` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `seo_title_fr` text DEFAULT NULL,
  `seo_meta_description_fr` text DEFAULT NULL,
  `slug_de` text DEFAULT NULL,
  `title_de` text DEFAULT NULL,
  `excerpt_de` text DEFAULT NULL,
  `description_de` text DEFAULT NULL,
  `seo_title_de` text DEFAULT NULL,
  `seo_meta_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_post_category_id_index` (`post_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `publication_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_fr` text DEFAULT NULL,
  `name_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `publication_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `publications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `description_fa` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `video_file` varchar(255) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `status` enum('Show','Hide') NOT NULL DEFAULT 'Show',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title_fr` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `title_de` text DEFAULT NULL,
  `description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `publications_slug_unique` (`slug`),
  KEY `publications_publication_category_id_foreign` (`publication_category_id`),
  CONSTRAINT `publications_publication_category_id_foreign` FOREIGN KEY (`publication_category_id`) REFERENCES `publication_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `replies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reply` text NOT NULL,
  `reply_by` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `replies_comment_id_index` (`comment_id`),
  KEY `replies_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logo_1` varchar(255) DEFAULT NULL,
  `logo_2` varchar(255) DEFAULT NULL,
  `footer_about_text` text DEFAULT NULL,
  `footer_about_text_fa` text DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `top_bar_email` varchar(255) DEFAULT NULL,
  `top_bar_phone` varchar(255) DEFAULT NULL,
  `top_bar_address` varchar(255) DEFAULT NULL,
  `top_bar_facebook` varchar(255) DEFAULT NULL,
  `top_bar_twitter` varchar(255) DEFAULT NULL,
  `top_bar_linkedin` varchar(255) DEFAULT NULL,
  `top_bar_instagram` varchar(255) DEFAULT NULL,
  `footer_facebook` varchar(255) DEFAULT NULL,
  `footer_twitter` varchar(255) DEFAULT NULL,
  `footer_linkedin` varchar(255) DEFAULT NULL,
  `footer_instagram` varchar(255) DEFAULT NULL,
  `footer_phone` varchar(255) DEFAULT NULL,
  `footer_email` varchar(255) DEFAULT NULL,
  `footer_website` varchar(255) DEFAULT NULL,
  `footer_copyright` varchar(255) DEFAULT NULL,
  `theme_color` text DEFAULT NULL,
  `theme_ink` varchar(255) NOT NULL DEFAULT '#000000',
  `theme_signal` varchar(255) NOT NULL DEFAULT '#C94B18',
  `theme_hope` varchar(255) NOT NULL DEFAULT '#2E8B57',
  `layout_direction` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `paypal_mode` varchar(255) DEFAULT 'sandbox',
  `paypal_client_id` text DEFAULT NULL,
  `paypal_client_secret` text DEFAULT NULL,
  `paypal_currency` varchar(255) DEFAULT 'USD',
  `membership_dues_amount` decimal(10,2) DEFAULT NULL,
  `top_bar_address_fa` varchar(255) DEFAULT NULL,
  `footer_copyright_fa` varchar(255) DEFAULT NULL,
  `seo_title_template` varchar(255) DEFAULT NULL,
  `seo_default_description` text DEFAULT NULL,
  `seo_default_description_fa` text DEFAULT NULL,
  `seo_og_image` varchar(255) DEFAULT NULL,
  `seo_robots_indexable` tinyint(1) NOT NULL DEFAULT 0,
  `seo_google_verification` varchar(255) DEFAULT NULL,
  `seo_analytics_id` varchar(255) DEFAULT NULL,
  `sitemap_include_static` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_include_pages` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_include_events` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_include_posts` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_include_campaigns` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_include_members` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_priority_static` varchar(255) NOT NULL DEFAULT '0.7',
  `sitemap_priority_pages` varchar(255) NOT NULL DEFAULT '0.5',
  `sitemap_priority_events` varchar(255) NOT NULL DEFAULT '0.6',
  `sitemap_priority_posts` varchar(255) NOT NULL DEFAULT '0.6',
  `sitemap_priority_campaigns` varchar(255) NOT NULL DEFAULT '0.6',
  `sitemap_priority_members` varchar(255) NOT NULL DEFAULT '0.5',
  `footer_about_text_fr` text DEFAULT NULL,
  `top_bar_address_fr` text DEFAULT NULL,
  `footer_copyright_fr` text DEFAULT NULL,
  `seo_default_description_fr` text DEFAULT NULL,
  `footer_about_text_de` text DEFAULT NULL,
  `top_bar_address_de` text DEFAULT NULL,
  `footer_copyright_de` text DEFAULT NULL,
  `seo_default_description_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `site_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(60) NOT NULL,
  `native` varchar(60) NOT NULL,
  `dir` varchar(3) NOT NULL DEFAULT 'ltr',
  `status` varchar(10) NOT NULL DEFAULT 'off',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_languages_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `heading` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `heading_fa` varchar(255) DEFAULT NULL,
  `text_fa` varchar(255) DEFAULT NULL,
  `button_text_fa` varchar(255) DEFAULT NULL,
  `heading_fr` text DEFAULT NULL,
  `text_fr` text DEFAULT NULL,
  `button_text_fr` text DEFAULT NULL,
  `heading_de` text DEFAULT NULL,
  `text_de` text DEFAULT NULL,
  `button_text_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_committee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_committee` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `member_category_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_committee_user_id_member_category_id_unique` (`user_id`,`member_category_id`),
  KEY `user_committee_member_category_id_foreign` (`member_category_id`),
  CONSTRAINT `user_committee_member_category_id_foreign` FOREIGN KEY (`member_category_id`) REFERENCES `member_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_committee_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `state` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `zip` text DEFAULT NULL,
  `membership_status` varchar(255) NOT NULL DEFAULT 'none',
  `membership_type` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `position_fa` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `occupation_fa` varchar(255) DEFAULT NULL,
  `membership_source` varchar(255) DEFAULT NULL,
  `membership_id` varchar(255) DEFAULT NULL,
  `membership_applied_at` timestamp NULL DEFAULT NULL,
  `membership_approved_at` timestamp NULL DEFAULT NULL,
  `membership_rejected_reason` text DEFAULT NULL,
  `dues_paid` tinyint(1) NOT NULL DEFAULT 0,
  `dues_exempt_override` tinyint(1) DEFAULT NULL,
  `card_status` varchar(255) NOT NULL DEFAULT 'not_requested',
  `card_requested_at` timestamp NULL DEFAULT NULL,
  `card_issued_at` timestamp NULL DEFAULT NULL,
  `card_expires_at` timestamp NULL DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `position_fr` text DEFAULT NULL,
  `occupation_fr` text DEFAULT NULL,
  `position_de` text DEFAULT NULL,
  `occupation_de` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_membership_id_unique` (`membership_id`),
  UNIQUE KEY `users_verification_token_unique` (`verification_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2026_03_08_204725_create_admins_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2026_03_15_091040_create_sliders_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2026_03_15_125629_create_photos_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2026_03_15_133633_create_faqs_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2026_03_15_165257_create_about_items_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_03_16_084907_create_events_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_03_16_101627_create_event_faqs_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_03_17_072042_create_members_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_03_17_102918_create_member_awards_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_03_17_134231_create_post_categories_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_03_18_042005_create_posts_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_03_18_090815_create_comments_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_03_18_102511_create_replies_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2026_03_21_054255_create_page_home_items_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2026_03_21_100000_create_page_about_items_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2026_03_21_130000_create_page_event_items_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2026_03_21_130001_create_page_blog_items_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2026_03_21_140000_create_page_team_items_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2026_03_21_140001_create_page_gallery_items_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_03_21_140002_create_page_faq_items_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_03_20_000001_create_page_login_items_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2026_03_20_000002_create_page_user_items_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2026_03_21_000001_create_page_terms_and_privacy_items_tables',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_03_21_000002_create_page_contact_items_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2026_03_22_041145_create_subscribers_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2026_03_22_053441_create_settings_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2026_03_22_103808_create_custom_pages_table',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2026_03_22_172011_create_menus_table',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_03_24_000000_drop_shop_columns_from_menus_and_page_user_items',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_03_25_000000_create_donate_feature',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2026_09_11_000000_remove_service_project_testimonial_member_skill_features',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2026_09_14_103147_create_menu_items_table',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2026_09_14_103538_add_show_in_footer_to_menu_items_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2026_09_14_163830_create_publications_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2026_09_14_164404_add_image_to_custom_pages_table',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2026_09_14_165203_add_show_on_home_to_posts_table',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2026_09_14_172306_add_theme_colors_to_settings_table',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2026_09_14_173627_add_seo_sv_columns_to_page_item_tables',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2026_09_14_181206_create_event_categories_table',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2026_09_14_181207_add_event_category_id_to_events_table',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2026_09_14_181207_create_publication_categories_table',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2026_09_14_181208_add_publication_category_id_to_publications_table',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2026_09_14_182159_drop_legacy_category_columns_from_publications_table',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2026_09_20_061141_add_slug_sv_to_content_tables',63);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2026_09_20_063306_add_home_bg_image_to_custom_pages_table',64);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2026_09_20_072531_create_home_texts_table',65);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2026_09_20_074612_create_member_categories_and_extend_members',66);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2026_09_20_075042_make_member_description_nullable',67);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2026_09_23_180201_add_excerpt_and_gallery_to_custom_pages',68);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2026_09_24_062355_create_campaigns_table',69);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2026_09_24_065525_add_membership_fields_to_users_table',70);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2026_09_24_065525_create_membership_invitations_table',70);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2026_09_24_071704_add_membership_dues_amount_to_settings_table',71);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2026_09_24_074317_add_card_fields_to_users_table',72);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2026_09_24_141932_add_footer_website_to_settings_table',73);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2026_09_24_145633_add_role_to_admins_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2026_09_24_150313_add_seo_fields_to_settings_table',75);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2026_09_26_062357_add_card_details_to_users_table',76);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2026_09_26_175553_add_dues_exempt_override_to_users_table',77);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2026_09_26_181310_create_user_committee_table',78);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2026_10_12_021639_create_partners_table',79);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2026_10_12_030953_add_sitemap_settings_to_settings_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2026_10_21_000001_create_about_item_images_table',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2026_10_21_000002_add_video_to_publications_table',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2026_10_21_000003_add_status_to_custom_pages_table',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2026_10_22_000001_create_campaign_categories_table',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2026_10_23_000001_create_polls_tables',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2026_10_23_000002_rename_sv_columns_to_fa',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2026_10_23_000003_add_french_german_translation_columns',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2026_11_04_000001_create_site_languages_table',82);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2026_11_05_000001_add_card_image_to_menu_items_table',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2026_11_05_000002_widen_footer_column_on_menu_items_table',84);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2026_11_05_000003_add_missing_indexes',85);
