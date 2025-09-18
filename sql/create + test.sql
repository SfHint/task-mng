CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `status` TINYINT(1) NOT NULL DEFAULT 0, -- 0 = not done, 1 = done
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


use task_manager;
select * from tasks;


CREATE DATABASE IF NOT EXISTS `finance_manager` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `finance_manager`;

CREATE TABLE IF NOT EXISTS `finances` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `type` ENUM('доход','расход') NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

select * from finances;
