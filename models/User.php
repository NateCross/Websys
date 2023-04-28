<?php

require_once 'Database.php';
require_once 'Session.php';

/**
 * Model for the user type in the system
 * 
 * To store the user and their type, sessions are used instead 
 * of static variables
 * 
 * This class can be applied for Member and Seller
 */
abstract class User {

  // Implement this method which simply returns the
  // table name
  // This is used in the various functions
  abstract protected static function getTableName(): string;

  public static function getUserViaEmail(string $email): array | null {
    $result = Database::query("
      SELECT * FROM "
      . static::getTableName()
      . " WHERE email = '{$email}';
    ");
    if ($result)
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    return false;
  }

  public static function getUserViaName(string $name): array | null {
    $result = Database::query("
      SELECT * FROM "
      . static::getTableName()
      . " WHERE name = '{$name}';
    ");
    if ($result)
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    return false;
  }

  public static function getUserViaId(int $id): array | null {
    $result = Database::query("
      SELECT * FROM "
      . static::getTableName()
      . " WHERE id = '{$id}';
    ");
    if ($result)
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    return false;
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
      INSERT INTO "
      . static::getTableName()
      . " (email, name, password) 
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
   * Uses sessions to store current user
   */
  public static function setCurrentUser($member): bool {
    if (
      Session::set('user', $member)
      && Session::set('type', static::getTableName())
    )
      return true;
    return false;
  }

  /**
   * Uses sessions to get current user
   */
  public static function getCurrentUser() {
    return Session::get('user');
  }

  /**
   * Because the intention is to have different user types,
   * it must therefore be possible to get which type
   * a logged in user is
   */
  public static function getCurrentUserType() {
    return Session::get('type');
  }

  /**
   * Handles logging in as a user
   * string $username The username of the user to locate
   * string $password The password supplied in the login form
   */
  public static function login(
    string $email, 
    string $password
  ): bool {
    try {
      $user = self::getUserViaEmail($email);

      if (!$user) {
        return false;
      }

      [
        'password' => $memberPassword
      ] = $user;

      if (!self::verifyPassword($password, $memberPassword))
        throw new Exception('Incorrect password');
      
      // Remove password since this will not be necessary
      // and it could pose a security risk
      unset($user['password']);

      self::setCurrentUser($user);

      return true;
    } catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
  }
}