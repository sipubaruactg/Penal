-- ========================================================
-- ১. গ্রাহক তালিকা টেবিল (Customers Table)
-- ========================================================
CREATE TABLE IF NOT EXISTS `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `mobile_number` VARCHAR(20) NOT NULL UNIQUE,
    `email_id` VARCHAR(100) NULL,
    `location` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- ২. ইন্টারনেট ইউজার টেবিল (Internet Users Table)
-- ========================================================
CREATE TABLE IF NOT EXISTS `internet_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fifi_id` VARCHAR(50) NOT NULL UNIQUE,
    `user_name` VARCHAR(100) NOT NULL,
    `mobile_number` VARCHAR(20) NOT NULL,
    `address` TEXT NULL,
    `package_price` DECIMAL(10, 2) NOT NULL,
    `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- ৩. সিস্টেম এডমিন টেবিল (System Admins Table)
-- ========================================================
CREATE TABLE IF NOT EXISTS `system_admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `admin_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone_number` VARCHAR(20) NULL,
    `profile_picture` VARCHAR(255) DEFAULT 'default_avatar.png',
    `role` ENUM('SuperAdmin', 'Manager', 'Support') DEFAULT 'Manager',
    `status` ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `password_reset_token` VARCHAR(255) DEFAULT NULL,
    `token_expiry` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- ৪. গ্রীন এপিআই সেটিংস টেবিল (Green API Settings Table)
-- ========================================================
CREATE TABLE IF NOT EXISTS `green_api_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `instance_id` VARCHAR(100) NOT NULL,
    `api_token` VARCHAR(255) NOT NULL,
    `sender_phone` VARCHAR(20) DEFAULT NULL,
    `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ========================================================
-- ৫. প্রথমবার লগইন করার জন্য ডেমো সুপার এডমিন ডাটা (Password: 123456)
-- ========================================================
INSERT INTO `system_admins` (`admin_name`, `email`, `username`, `password_hash`, `phone_number`, `role`, `status`) 
VALUES 
('Super Admin', 'admin@gmail.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01700000000', 'SuperAdmin', 'Active')
ON DUPLICATE KEY UPDATE `username`=`username`;
