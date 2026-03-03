-- Admin-only application schema (MySQL)

CREATE TABLE IF NOT EXISTS `admins` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(190) NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_username_unique` (`username`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `application_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(190) NULL,
  `visit_date` DATE NOT NULL,
  `visit_time` TIME NOT NULL,
  `device_type` VARCHAR(20) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `item_master` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `ItemName` VARCHAR(100) NOT NULL,
  `Description` VARCHAR(100) NULL,
  `Status` VARCHAR(100) NOT NULL,
  `Created_by` VARCHAR(100) NOT NULL DEFAULT '',
  `Created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` VARCHAR(100) NOT NULL DEFAULT '',
  `updated_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Admin Credentials
-- Username: admin
-- Password: Admin@12345
INSERT INTO `admins` (`full_name`, `username`, `email`, `password`)
VALUES ('Admin', 'admin', 'admin@example.com', '$2y$10$WMftaTQP3amuVazKDG0ax..Un1Vq9wgdH9Eh2t8EiV9W6tAsuzqwq')
ON DUPLICATE KEY UPDATE `username` = `username`;
