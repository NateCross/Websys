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
      `address` VARCHAR(1000) NOT NULL,
      `contact_number` VARCHAR(255) NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP NOT NULL
    );

    CREATE TABLE `seller` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `name` VARCHAR(255) NOT NULL UNIQUE,
      `image_path` VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
      `password` VARCHAR(255) NOT NULL,
      `address` VARCHAR(1000) NOT NULL,
      `contact_number` VARCHAR(255) NOT NULL,
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
      `address` VARCHAR(1000),
      `contact_number` VARCHAR(255),
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
      `bank` ENUM ('bdo', 'bpi', 'other') DEFAULT 'bdo' NOT NULL,
      `bank_other` VARCHAR(1000),
      `address` VARCHAR(1000) NOT NULL,
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
      `is_reviewed` BOOLEAN NOT NULL DEFAULT false,
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

    CREATE TABLE `report` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `message` VARCHAR(10000) NOT NULL,
      `seller_id` INT NOT NULL,
      `member_id` INT NOT NULL,
      `status` ENUM ('open', 'closed') DEFAULT 'open' NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

      FOREIGN KEY (`seller_id`)
        REFERENCES `seller`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      FOREIGN KEY (`member_id`)
        REFERENCES `member`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `notification_member` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `message` VARCHAR(10000) NOT NULL,
      `member_id` INT NOT NULL,
      `is_read` BOOLEAN NOT NULL DEFAULT false,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

      FOREIGN KEY (`member_id`)
        REFERENCES `member`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `notification_seller` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `message` VARCHAR(10000) NOT NULL,
      `seller_id` INT NOT NULL,
      `is_read` BOOLEAN NOT NULL DEFAULT false,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

      FOREIGN KEY (`seller_id`)
        REFERENCES `seller`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `notification_admin` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `message` VARCHAR(10000) NOT NULL,
      `admin_id` INT NOT NULL,
      `is_read` BOOLEAN NOT NULL DEFAULT false,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

      FOREIGN KEY (`admin_id`)
        REFERENCES `admin`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TABLE `product_wishlist` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `member_id` INT NOT NULL,
      `product_id` INT NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

      FOREIGN KEY (`member_id`)
        REFERENCES `member`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      FOREIGN KEY (`product_id`)
        REFERENCES `product`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
    );

    CREATE TRIGGER notify_seller_review
    AFTER INSERT
    ON review FOR EACH ROW
    BEGIN
      INSERT INTO notification_seller(`seller_id`, `message`)
      VALUES (get_seller_id_of_product(new.product_id), CONCAT('Your product ', get_name_of_product(new.product_id), ' has been reviewed.'));
    END;

    CREATE TRIGGER update_product_quantity_after_sale
    AFTER INSERT
    ON product_bill FOR EACH ROW
    BEGIN
      UPDATE product SET
        quantity = quantity - new.quantity
      WHERE id = new.product_id;
    END;

    CREATE VIEW `report_with_users` AS
      SELECT
        report.*,
        seller.name seller_name,
        seller.suspended_until seller_suspended_until,
        member.name member_name
      FROM
        report
        INNER JOIN seller
          ON report.seller_id = seller.id
        INNER JOIN member
          ON report.member_id = member.id;

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

    CREATE FUNCTION get_review_rating_average(
      product_id INT
    )
    RETURNS DECIMAL(14, 4)
    DETERMINISTIC
    BEGIN
      DECLARE return_value DECIMAL(14, 4);
      SET return_value = (
        SELECT rating_average
        FROM review_average
        WHERE `product_id` = review_average.id
      );
      RETURN return_value;
    END;

    CREATE FUNCTION get_bill_total(
      bill_id_input INT
    )
    RETURNS INT
    DETERMINISTIC
    BEGIN
      DECLARE return_value INT;
      SET return_value = (
          SELECT bill_total.bill_total
          FROM bill_total
          WHERE bill_total.bill_id = bill_id_input
        );
      RETURN return_value;
    END;

    CREATE FUNCTION get_seller_id_of_product(
      product_id INT
    )
    RETURNS INT
    DETERMINISTIC
    BEGIN
      DECLARE return_value INT;
      SET return_value = (
        SELECT seller_id
        FROM product
        WHERE id = product_id
      );
      RETURN return_value;
    END;

    CREATE FUNCTION get_name_of_product(
      product_id INT
    )
    RETURNS VARCHAR(255)
    DETERMINISTIC
    BEGIN
      DECLARE return_value VARCHAR(255);
      SET return_value = (
        SELECT name
        FROM product
        WHERE id = product_id
      );
      RETURN return_value;
    END;

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

    CREATE PROCEDURE select_bills_of_member
      (IN member_id INT)
    BEGIN
      UPDATE `seller` SET 
        `suspended_until` = DATE_ADD(NOW(), INTERVAL `days_suspended` DAY)
      WHERE `id` = `seller_id`;
    END;

    CREATE PROCEDURE toggle_report_status
      (IN report_id INT)
    BEGIN
      UPDATE `report` SET
        `status` = IF(`status` = 'open', 'closed', 'open')
      WHERE `id` = `report_id`;
    END;

    CREATE PROCEDURE toggle_reviewed_status
      (IN product_bill_id INT)
    BEGIN
      UPDATE `product_bill` SET
        `is_reviewed` = 1 - `is_reviewed`
      WHERE `id` = `product_bill_id`;
    END;

    CREATE PROCEDURE toggle_notification_read_member
      (IN notification_member_id INT)
    BEGIN
      UPDATE `notification_member` SET
        `is_read` = 1 - `is_read`
      WHERE `id` = `notification_member_id`;
    END;

    CREATE PROCEDURE toggle_notification_read_seller
      (IN notification_seller_id INT)
    BEGIN
      UPDATE `notification_seller` SET
        `is_read` = 1 - `is_read`
      WHERE `id` = `notification_seller_id`;
    END;

    CREATE PROCEDURE toggle_notification_read_admin
      (IN notification_admin_id INT)
    BEGIN
      UPDATE `notification_admin` SET
        `is_read` = 1 - `is_read`
      WHERE `id` = `notification_admin_id`;
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