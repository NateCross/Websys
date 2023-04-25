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

    CREATE TABLE foo (
      id INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
      name VARCHAR(255) NOT NULL,
      timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
      PRIMARY KEY (id)
    );

    CREATE TABLE bar (
      id INTEGER NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
      name VARCHAR(255) NOT NULL
    );

    CREATE TABLE db_schema_version (
      id ENUM('1') NOT NULL UNIQUE PRIMARY KEY DEFAULT '1',
      version INTEGER NOT NULL UNIQUE
    );

    INSERT INTO db_schema_version VALUES (1);

    UPDATE db_schema_version SET version = 2 WHERE version = 1;
  ");
} catch (mysqli_sql_exception $e) {
  throw new mysqli_sql_exception($e->getMessage(), $e->getCode());
}

// Write migration code here