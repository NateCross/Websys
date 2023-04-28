<?php

require_once '../require/require.php';

$db = Database::getDb();

['DATABASE' => $database] = Database::parseEnv();

// This file must be run every time something is changed in the database

try {
  $db->multi_query("
    DROP DATABASE IF EXISTS $database;
    CREATE DATABASE $database;
    USE $database;

    CREATE TABLE `product` (
      `id` INT NOT NULL AUTO_INCREMENT , 
      `name` VARCHAR(255) NOT NULL , 
      `image_path` VARCHAR(255) NOT NULL , 
      `description` VARCHAR(10000) NOT NULL , 
      `quantity` INT NOT NULL , 
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
      PRIMARY KEY (`id`)
    );

    CREATE TABLE `member` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `name` VARCHAR(255) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL,
      `last_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL
    );
  ");

  echo "Successfully migrated";
} catch (mysqli_sql_exception $e) {
  throw new mysqli_sql_exception($e->getMessage(), $e->getCode());
}

// Write migration code here