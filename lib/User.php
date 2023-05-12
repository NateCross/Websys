<?php

require_once 'require.php';
require_once 'Utils.php';

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
    return null;
  }

  public static function getUserViaName(string $name): array | null {
    $result = Database::query("
      SELECT * FROM "
      . static::getTableName()
      . " WHERE name = '{$name}';
    ");
    if ($result)
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    return null;
  }

  public static function getUserViaId(int $id): array | null {
    $result = Database::query("
      SELECT * FROM "
      . static::getTableName()
      . " WHERE id = '{$id}';
    ");
    if ($result)
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    return null;
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
    string $address,
    string $contact_number,
  ): bool {
    try {
      // Hash password first for better security
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

      return Database::preparedQuery("
        INSERT INTO "
        . static::getTableName()
        . " (email, name, password, address, contact_number) 
        VALUES (?, ?, ?, ?, ?);
        ", 
        $email, 
        $username, 
        $hashedPassword,
        $address,
        $contact_number,
      );
    } catch (Exception $e) {
      return false;
    }
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
      return false;
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
      return false;
    }
  }

  /**
   * Logs the current user out
   * Also removes the type of user
   */
  public static function logout(): bool {
    if (Session::delete('user') && Session::delete('type'))
      return true;
    return false;
  }

  public static function getUserIdAttribute($user): int {
    return $user['id'];
  }

  public static function getUserNameAttribute($user) {
    return $user['name'];
  }

  public static function getUserEmailAttribute($user) {
    return $user['email'];
  }

  public static function getUserAddressAttribute($user) {
    return $user['address'];
  }

  public static function getUserContactNumberAttribute($user) {
    return $user['contact_number'];
  }

  public static function getUserImageAttribute($user) {
    return $user['image_path'];
  }

  public static function updateUser(
    int $id,
    string $email,
    string $username,
    string $password,
    string $address,
    string $contact_number,
  ) {
    try {
      // Hash password first for better security
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

      $result = Database::preparedQuery("
        UPDATE " 
        . static::getTableName()
        . " SET 
          email = ?,
          name = ?,
          password = ?,
          address = ?,
          contact_number = ?
        WHERE id = ?
        ", 
        $email, 
        $username, 
        $hashedPassword, 
        $address,
        $contact_number,
        $id,
      );

      if (!$result) return false;

      if (!self::relogin($id)) return false;

      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Helps to reflect changes in session after editing
   * the details of a user
   */
  public static function relogin(int $id) {
    try {
      return self::setCurrentUser(self::getUserViaId($id));
    } catch (Exception $e) {
      return false;
    }
  }

  public static function updateUserImage(
    int $user_id,
    array $image,
  ) {
    try {
      if (!$user = self::getUserViaId($user_id)) return false;

      $old_image = self::getUserImageAttribute($user);

      if ($old_image !== 'default.jpg') {
        $old_file_path = "../_assets/$old_image";
        if (!unlink($old_file_path)) return false;
      }

      $image_name = \Utils\generateFilename($image);
      $image_path = "../_assets/$image_name";

      Utils\createAssetsFolderIfNotExists();

      if (!move_uploaded_file(
        $image['tmp_name'], $image_path
      )) return false;

      $result = Database::preparedQuery("
        UPDATE "
        . static::getTableName() 
        . " SET
          image_path = ?
        WHERE id = ?;
      ", $image_name, $user_id);

      if (!$result) return false;

      if (!self::relogin($user_id)) return false;

      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getImagePath($user) {
    return "_assets/" . self::getUserImageAttribute($user);
  }
}