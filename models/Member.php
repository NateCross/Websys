<?php

// require_once 'User.php';
require_once 'Database.php';

class Member {

  /**
   * Get a member through their username.
   * This is possible because members have unique
   * usernames.
   * Used when logging in, since usernames are input
   */
  public static function getMemberUsername(string $username) {
    $result = Database::query("
      SELECT * FROM member WHERE name = {$username};
    ");
    return $result->fetch_all()[0];
  }

  /**
   * Get a member through their ID.
   * Possible because IDs are the primary key.
   */
  public static function getMemberId(int $id) {
    $result = Database::query("
      SELECT * FROM member WHERE id = {$id};
    ");
    return $result->fetch_all()[0];
  }

  public static function createMember(
    string $username,
    string $password,
  ) {
    // Hash password first for better security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    Database::preparedQuery("
      INSERT INTO member 
      (name, password) 
      VALUES (?, ?);
    ", $username, $hashedPassword);
  }
}