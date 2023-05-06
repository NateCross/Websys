<?php

// This file must be run every time something is changed in the database

require_once '../lib/require.php';

$db = Database::getDb();

['DATABASE' => $database] = Database::parseEnv();

$hashedAdminPassword = password_hash('admin', PASSWORD_BCRYPT);

try {
  $db->multi_query("
    DROP DATABASE IF EXISTS `$database`;
    CREATE DATABASE `$database`;
    USE `$database`;

    CREATE TABLE `member` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `name` VARCHAR(255) NOT NULL UNIQUE,
      `image_path` VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
      `password` VARCHAR(255) NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL
    );

    CREATE TABLE `seller` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `name` VARCHAR(255) NOT NULL UNIQUE,
      `image_path` VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
      `password` VARCHAR(255) NOT NULL,
      `suspended_until` DATE DEFAULT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL
    );

    CREATE TABLE `admin` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `name` VARCHAR(255) NOT NULL UNIQUE,
      `image_path` VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
      `password` VARCHAR(255) NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL
    );

    CREATE TABLE `product` (
      `id` INT NOT NULL AUTO_INCREMENT , 
      `seller_id` INT NOT NULL ,
      `name` VARCHAR(255) NOT NULL UNIQUE , 
      `image_path` VARCHAR(255) NOT NULL , 
      `description` VARCHAR(10000) NOT NULL , 
      `quantity` INT NOT NULL DEFAULT 0 CHECK (`quantity` >= 0), 
      `price` DECIMAL(19, 2) NOT NULL DEFAULT 1 CHECK (`price` >= 1),
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL,

      PRIMARY KEY (`id`),
      FOREIGN KEY (`seller_id`)
        REFERENCES `seller`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `category` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(255) NOT NULL UNIQUE,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL
    );

    CREATE TABLE `product_category` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `product_id` INT NOT NULL,
      `category_id` INT NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL,

      FOREIGN KEY (`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      FOREIGN KEY (`category_id`)
        REFERENCES `category`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `bill` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `member_id` INT NOT NULL,
      `member_address` VARCHAR(1000) NOT NULL,
      `contact_number` VARCHAR(255) NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL,

      FOREIGN KEY (`member_id`)
        REFERENCES `member`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `product_bill` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `product_id` INT NOT NULL,
      `bill_id` INT NOT NULL,
      `quantity` INT NOT NULL DEFAULT 0, 
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL,

      FOREIGN KEY (`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      FOREIGN KEY (`bill_id`)
        REFERENCES `bill`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `review` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `comment` VARCHAR(10000),
      `rating` INT NOT NULL DEFAULT 3 CHECK (5 >= `rating` AND `rating` >= 1),
      `member_id` INT NOT NULL,
      `product_id` INT NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL,

      FOREIGN KEY (`member_id`)
        REFERENCES `member`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      FOREIGN KEY (`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE VIEW `review_average` AS
      SELECT
        p.`id`,
        p.`name`,
        AVG(r.`rating`) rating_average
      FROM
        review r
        LEFT JOIN
          product p
      ON
        r.`product_id` = p.`id`
      GROUP BY p.`id`;

    CREATE VIEW `bill_total` AS
      SELECT
        b.`id` bill_id,
        m.`id` member_id,
        m.`name` member_name,
        SUM(pb.`quantity` * p.`price`) bill_total
      FROM
        bill b
        LEFT JOIN
          product_bill pb
          ON
            b.`id` = pb.`bill_id`
        LEFT JOIN
          product p
          ON
            pb.`product_id` = p.`id`
        LEFT JOIN
          member m
          ON
            b.`member_id` = m.`id`
      GROUP BY b.`id`;

    CREATE PROCEDURE update_product_quantity
      (IN product_id INT, IN qty INT)
    BEGIN
      UPDATE `product`
      SET `quantity` = `quantity` + qty
      WHERE `id` = `product_id`;
    END;
  
    CREATE PROCEDURE unsuspend_seller
      (IN seller_id INT)
    BEGIN
      UPDATE `seller` SET 
      `suspended_until` = NULL
      WHERE `id` = `seller_id`;
    END;

    CREATE PROCEDURE suspend_seller
      (IN seller_id INT, IN days_suspended INT)
    BEGIN
      UPDATE `seller` SET 
      `suspended_until` = DATE_ADD(NOW(), INTERVAL `days_suspended` DAY)
      WHERE `id` = `seller_id`;
    END;

    -- Insert a default admin
    -- Must replace credentials immediately
    INSERT INTO admin
      (email, name, password)
    VALUES
      ('admin@site.com', 'admin', '$hashedAdminPassword');
  ");


  echo "Successfully migrated";
} catch (mysqli_sql_exception $e) {
  echo $e->getMessage();
}