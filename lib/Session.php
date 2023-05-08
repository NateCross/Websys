<?php

require_once 'Database.php';

// Call session_start to ensure the session superglobal
// can be accessed
session_start();

/**
 * Class that handles sessions in an object-oriented way
 */
class Session {

  /**
   * Sets the value to a given key in the session superglobal
   */
  public static function set($key, $value) {
    try {
      $_SESSION[$key] = $value;
      return true;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * Gets the value of a given key in the session superglobal
   */
  public static function get($key) {
    try {
      if (self::has($key)) return $_SESSION[$key];
      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Checks if a key in the session superglobal has been set
   */
  public static function has($key): bool {
    return session_id() && isset($_SESSION[$key]);
  }

  /**
   * Sets the value of a key in the session superglobal
   * to null
   */
  public static function delete($key): bool {
    if (self::has($key)) {
      unset($_SESSION[$key]);
      return true;
    }
    return false;
  }

  /**
   * Clears all session variables
   */
  public static function unset(): bool {
    return session_unset();
  }

  /**
   * Ends current session
   */
  public static function destroy() {
    // Remove the cookie handling the session
    setcookie(session_name(), "", 100);

    // Clears the session superglobal
    self::unset();

    // Destroys current instance of session
    session_destroy();
  }
}