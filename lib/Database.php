<?php

class Database
{
  protected static \mysqli $DB;

  public static function parseEnv(): array | null
  {
    return parse_ini_file(dirname(__DIR__) . '/.env');
  }

  public static function initializeDb(): \mysqli | null
  {
    $db      = null;
    $charset = 'utf8mb4'; // Standard charset. Will help eliminate errors
                          // See https://phpdelusions.net/mysqli

    // We parse env here instead of passing arguments into the function
    // This is because if it errors, the function parameters, i.e. credentials 
    // will be shown in the stack trace, and thus be insecure
    [
      'HOSTNAME' => $hostname,
      'USERNAME' => $username,
      'PASSWORD' => $password,
      'DATABASE' => $database,
    ] = self::parseEnv();

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  // Enhance error reporting
    try {
      $db = new \mysqli($hostname, $username, $password);
      $db->set_charset($charset);
      $db->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

      // Checking if database exists
      $result = $db->query('SHOW DATABASES LIKE "' . $database . '"');
      if ($result->num_rows) {
        $db->select_db($database);
      }
    } catch (\mysqli_sql_exception $e) {
      // Throwing the listed error but rewrapping it first so it does not
      // include database credentials in the error
      throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
    }

    self::$DB = $db;
    return $db;
  }

  public static function getDb() {
    if (!isset(self::$DB)) self::initializeDb();
    return self::$DB;
  }

  private function __construct() {}

  function __destruct()
  {
    $this->DB->close();
  }

  /**
   * Function for general queries. Do not use
   * for prepared statements (i.e. statements with WHERE)
   */
  public static function query(string $query): mysqli_result | null {
    if (!isset(self::$DB)) self::initializeDb();
    try {
      return self::$DB->query($query);
    } catch (\mysqli_sql_exception $e) {
      throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
    }
  }

  private static function generateBindTypeString(mixed $arguments) {

    $reducer = function ($carry, string $item) {
      $types = [
        'integer' => 'i',  // Integer
        'double' => 'd',  // Float
        'string' => 's',  // String
      ];

      $carry = $carry . $types[gettype($item)];
      return $carry;
    }; 

    return array_reduce(
      $arguments, 
      $reducer,
      "",
    );
  }

  public static function preparedQuery(string $query, string ...$arguments) {
    if (!isset(self::$DB)) self::initializeDb();

    $bindTypeString = self::generateBindTypeString($arguments);

    try {
      $statement = self::$DB->prepare($query);
      $statement->bind_param($bindTypeString, ...$arguments);
      return $statement->execute();
    } catch (\mysqli_sql_exception $e) {
      throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
    }
  }
}