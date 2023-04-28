<?php

// require_once 'User.php';
require_once 'Database.php';
require_once 'Session.php';

/**
 * Model for the member user type in the system
 * To store the member, sessions are used instead 
 * of static variables
 */
class Member {

  /**
   * Get a member through their email
   * This is possible because members have unique
   * emails
   * Used when logging in, since emails are input
   */
  public static function getMemberEmail(string $email) {
    $result = Database::query("
      SELECT * FROM member WHERE email = '{$email}';
    ");
    return $result->fetch_all(MYSQLI_ASSOC)[0];
  }

  /**
   * Get a member through their username.
   * An alternative to getting members through email 
   */
  public static function getMemberUsername(string $username) {
    $result = Database::query("
      SELECT * FROM member WHERE name = {$username};
    ");
    return $result->fetch_all(MYSQLI_ASSOC)[0];
  }

  /**
   * Get a member through their ID.
   * Possible because IDs are the primary key.
   */
  public static function getMemberId(int $id) {
    $result = Database::query("
      SELECT * FROM member WHERE id = {$id};
    ");
    return $result->fetch_all(MYSQLI_ASSOC)[0];
  }

  /**
   * Registers a new member account
   * string $username The username of the new account
   * string $password The password of the new account
   */
  public static function register(
    string $email,
    string $username,
    string $password,
  ) {
    // Hash password first for better security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    return Database::preparedQuery("
      INSERT INTO member 
      (email, name, password) 
      VALUES (?, ?, ?);
    ", $email, $username, $hashedPassword);
  }

  /**
   * Verifies if the password string in database
   * matches what was input
   * string $loginPassword The password used in login
   * string $memberPassword The encrypted password in database
   */
  public static function verifyPassword(
    string $loginPassword,
    string $memberPassword,
  ): bool {
    try {
      return password_verify($loginPassword, $memberPassword);
    } catch (Exception $e) {
      echo $e->getMessage();
      die;
    }
  }

  /**
   * Uses sessions to store current member
   */
  public static function setCurrentMember($member): bool {
    return Session::set('member', $member);
  }

  /**
   * Uses sessions to get current member
   */
  public static function getCurrentMember() {
    return Session::get('member');
  }

  /**
   * Handles logging in as a member
   * string $username The username of the member to locate
   * string $password The password supplied in the login form
   */
  public static function login(
    string $email, 
    string $password
  ): bool {
    try {
      $member = self::getMemberEmail($email);

      if (!$member) {
        return false;
      }

      [
        'password' => $memberPassword
      ] = $member;
      if (!self::verifyPassword($password, $memberPassword))
        throw new Exception('Incorrect password');
      
      // Remove password since this will not be necessary
      // and it could pose a security risk
      unset($member['password']);

      self::setCurrentMember($member);

      return true;
    } catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
  }
}