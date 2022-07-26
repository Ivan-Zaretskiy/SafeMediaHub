<?php
//////////////////////////////////////////////////////////
mq("CREATE TABLE `encryptedString` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(1024) NOT NULL,
    `encryptedText` VARCHAR(1024) NOT NULL
)");
mq('ALTER table `encryptedString` ADD COLUMN `user_id` INT(12) NOT NULL AFTER `id`');
mq('ALTER table `encryptedString` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP()');
mq('ALTER table `encryptedString` ADD COLUMN `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP()');
//////////////////////////////////////////////////////////
mq("CREATE TABLE `users`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(128) NOT NULL,
    `email` VARCHAR(1024) NOT NULL,
    `password` VARCHAR(1024) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
mq('ALTER table `users` ADD COLUMN `firstPIN` VARCHAR(4) DEFAULT NULL AFTER `password`');
mq('ALTER table `users` ADD COLUMN `secondPIN` VARCHAR(8) DEFAULT NULL AFTER `firstPIN`');
mq('ALTER table `users` ADD COLUMN `dark_mode` INT DEFAULT 0 AFTER `password`');
mq('ALTER table `users` MODIFY COLUMN `firstPIN` VARCHAR(1024) DEFAULT NULL;');
mq('ALTER table `users` MODIFY COLUMN `secondPIN` VARCHAR(1024) DEFAULT NULL;');
//////////////////////////////////////////////////////////
mq("CREATE TABLE `serials`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `name` VARCHAR(1024) NOT NULL,
    `category` VARCHAR(1024) DEFAULT NULL,
    `last_season` INT DEFAULT NULL,
    `last_episode` INT DEFAULT NULL,
    `is_planned` INT DEFAULT 0,
    `is_finished` INT DEFAULT 0,
    `last_episode_time` TIME DEFAULT NULL,
    `next_episode_date` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
mq('ALTER table `serials` ADD COLUMN `url_to_watch` VARCHAR(5124) DEFAULT NULL AFTER `next_episode_date`');
mq('ALTER table `serials` ADD COLUMN `image_url` VARCHAR(5124) DEFAULT NULL AFTER `next_episode_date`');
mq('ALTER table `serials` ADD COLUMN `iframe_html` VARCHAR(5124) DEFAULT NULL AFTER `next_episode_date`');
//////////////////////////////////////////////////////////
mq('CREATE TABLE `images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(1024) NOT NULL,
    `file` LONGTEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)');
mq('ALTER table `images` ADD COLUMN `user_id` INT NOT NULL AFTER `id`;');

