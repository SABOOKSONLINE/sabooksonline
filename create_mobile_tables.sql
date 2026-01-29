-- =================================================================
-- Mobile App Management Database Tables
-- Run these SQL commands to create the required tables
-- =================================================================

-- 1. Mobile Banners Table
-- Stores banners for different mobile app screens
CREATE TABLE IF NOT EXISTS `Mobile_banners` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `image_url` VARCHAR(500) NOT NULL,
  `action_url` VARCHAR(500),
  `screen` ENUM('home', 'books', 'profile', 'cart', 'search') NOT NULL DEFAULT 'home',
  `priority` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `start_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `end_date` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_screen` (`screen`),
  INDEX `idx_active` (`is_active`),
  INDEX `idx_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Push Notifications Table
-- Stores notification campaigns and their details
CREATE TABLE IF NOT EXISTS `push_notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `image_url` VARCHAR(500),
  `action_url` VARCHAR(500),
  `target_type` ENUM('all', 'subscription', 'specific_users', 'publishers', 'customers') NOT NULL DEFAULT 'all',
  `target_criteria` JSON,
  `scheduled_at` DATETIME NULL,
  `sent_at` DATETIME NULL,
  `status` ENUM('draft', 'scheduled', 'sending', 'sent', 'failed') NOT NULL DEFAULT 'draft',
  `total_recipients` INT DEFAULT 0,
  `successful_sends` INT DEFAULT 0,
  `failed_sends` INT DEFAULT 0,
  `created_by` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`),
  INDEX `idx_target_type` (`target_type`),
  INDEX `idx_scheduled` (`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Device Tokens Table
-- Stores mobile device tokens for push notifications
CREATE TABLE IF NOT EXISTS `device_tokens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_email` VARCHAR(255) NOT NULL,
  `user_key` VARCHAR(255),
  `device_token` VARCHAR(500) NOT NULL,
  `platform` ENUM('android', 'ios') NOT NULL,
  `app_version` VARCHAR(50),
  `is_active` TINYINT(1) DEFAULT 1,
  `last_used` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_user_email` (`user_email`),
  INDEX `idx_device_token` (`device_token`),
  INDEX `idx_active` (`is_active`),
  UNIQUE KEY `unique_user_device` (`user_email`, `device_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Notification Logs Table
-- Tracks individual notification delivery status
CREATE TABLE IF NOT EXISTS `notification_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `notification_id` INT NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `device_token` VARCHAR(500) NOT NULL,
  `status` ENUM('pending', 'sent', 'failed', 'delivered', 'clicked') NOT NULL DEFAULT 'pending',
  `error_message` TEXT,
  `sent_at` TIMESTAMP NULL,
  `delivered_at` TIMESTAMP NULL,
  `clicked_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_notification_id` (`notification_id`),
  INDEX `idx_user_email` (`user_email`),
  INDEX `idx_status` (`status`),
  FOREIGN KEY (`notification_id`) REFERENCES `push_notifications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =================================================================
-- Sample Data (Optional)
-- =================================================================

-- Insert sample mobile banner
INSERT INTO `Mobile_banners` (`title`, `description`, `image_url`, `action_url`, `screen`, `priority`, `is_active`) VALUES
('Welcome to SABO Mobile!', 'Discover thousands of books at your fingertips', 'https://www.sabooksonline.co.za/img/banners/banner-download-mobile-app-new.png', 'https://www.sabooksonline.co.za/library', 'home', 5, 1);

-- Insert sample notification (draft)
INSERT INTO `push_notifications` (`title`, `message`, `target_type`, `status`, `created_by`) VALUES
('Welcome to SABO!', 'Thank you for downloading our mobile app. Start exploring thousands of books today!', 'all', 'draft', 'admin@sabooksonline.co.za');

-- =================================================================
-- Table Status Check
-- =================================================================
-- Run this to verify tables were created successfully:
-- SHOW TABLES LIKE '%mobile%';
-- SHOW TABLES LIKE '%push%';
-- SHOW TABLES LIKE '%device%';
-- SHOW TABLES LIKE '%notification%';

-- =================================================================
-- INSTRUCTIONS:
-- 1. Copy and paste this entire SQL into your database management tool
-- 2. Or run it via command line: mysql -u username -p database_name < create_mobile_tables.sql
-- 3. Or execute section by section in phpMyAdmin
-- =================================================================