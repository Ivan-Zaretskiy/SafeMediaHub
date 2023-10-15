<?php
//////////////////////////////////////////////////////////
query("CREATE TABLE IF NOT EXIST `notes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(12) NOT NULL,
    `name` VARCHAR(1024) NOT NULL,
    `value` VARCHAR(1024) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP()
)")->execute();
//////////////////////////////////////////////////////////
query("CREATE TABLE IF NOT EXIST `users`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(128) NOT NULL,
    `email` VARCHAR(1024) NOT NULL,
    `password` VARCHAR(1024) NOT NULL,
    `dark_mode` INT DEFAULT 0,
    `have_key` INT DEFAULT 0,
    `key_hash` LONGTEXT DEFAULT NULL,
    `key_created_at` TIMESTAMP DEFAULT NULL,
    `firstPIN` VARCHAR(1024) DEFAULT NULL,
    `secondPIN` VARCHAR(1024) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)")->execute();
//////////////////////////////////////////////////////////
query("CREATE TABLE IF NOT EXIST `series`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `name` VARCHAR(1024) NOT NULL,
    `category` VARCHAR(1024) DEFAULT NULL,
    `last_season` INT DEFAULT NULL,
    `last_episode` INT DEFAULT NULL,
    `watch_status` INT DEFAULT 1,
    `last_episode_time` TIME DEFAULT NULL,
    `next_episode_date` DATE DEFAULT NULL,
    `addition_info` MEDIUMTEXT DEFAULT NULL,
    `iframe_html` VARCHAR(5124) DEFAULT NULL,
    `image_url` VARCHAR(5124) DEFAULT NULL,
    `url_to_watch` VARCHAR(5124) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)")->execute();
//////////////////////////////////////////////////////////
query('CREATE TABLE IF NOT EXIST `images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `name` VARCHAR(1024) NOT NULL,
    `file` LONGTEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)')->execute();
//////////////////////////////////////////////////////////
query('CREATE TABLE IF NOT EXIST `WatchStatuses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(1024) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)')->execute();
//////////////////////////////////////////////////////////
