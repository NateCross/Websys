<?php

require_once "Database.php";

/**
 * Handles the products
 * Note that adding products is handled in the Seller
 * model
 */
class Product {

  /**
   * Returns an array of products
   * Without a parameter, returns all
   * With a parameter, it returns the product of that id
   * 
   * Parameters:
   *  $id
   *    - The id of the product to get.
   *      Without this, it gets all products
   *  $randomize
   *    - Randomizes the order of results. Best with limit
   *  $limit
   *    - Limits the amount of results returned to a certain number
   */
  public static function getProducts(
    int $id = null, 
    bool $randomize = false,
    int $limit = null,
  ) {
    if ($id) {
      $result = Database::query("
        SELECT * FROM product WHERE id = '{$id}';
      ");
    } else {
      $result = Database::query(
        "SELECT * FROM product "
        . ($randomize ? "ORDER BY rand() " : "")
        . ($limit ? "LIMIT {$limit} " : "")
        . "WHERE quantity != 0"
        . "; "
      );
    }
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public static function getCategories() {

  }

  public static function getBills() {

  }

  public static function getSeller() {

  }
}