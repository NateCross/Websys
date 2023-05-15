<?php

require_once "Database.php";

/**
 * Handles the categories
 */
class Category {
  /**
   * Returns an array of categories
   * Without a parameter, returns all
   * With a parameter, it returns the category of that id
   * 
   * Parameters:
   *  $id
   *    - The id of the category to get.
   *      Without this, it gets all categories
   *  $randomize
   *    - Randomizes the order of results. Best with limit
   *  $limit
   *    - Limits the amount of results returned to a certain number
   */
  public static function getCategories(
    int $id = null, 
    bool $randomize = false,
    int $limit = null,
  ) {
    if ($id) {
      $result = Database::query("
        SELECT * FROM category WHERE id = '$id';
      ");
      return $result->fetch_all(MYSQLI_ASSOC)[0];
    } else {
      $result = Database::query(
        "SELECT * FROM category "
        . ($randomize ? "ORDER BY rand() " : "")
        . ($limit ? "LIMIT {$limit} " : "")
        . "; "
      );
      return $result->fetch_all(MYSQLI_ASSOC);
    }
  }

  /**
   * Gets all products in a category
   */
  public static function getProducts(int $id) {
    // $result = Database::query("
    //   SELECT product.*
    //   FROM product
    //     LEFT JOIN product_category
    //     ON product_category.product_id = product.id
    //     LEFT JOIN category
    //     ON product_category.category_id = category.id
    //     AND category.id = $id;
    // ");
    $result = Database::query("
      SELECT product.*
      FROM category
        INNER JOIN product_category
          ON product_category.category_id = category.id
          AND category.id = $id
        INNER JOIN product
          ON product_category.product_id = product.id;
    ");
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  /**
   * Creates a new category if it doesn't exist;
   * does nothing if the category exists
   */
  public static function createCategory(string $name) {
    $result = Database::preparedQuery("
      INSERT INTO category (
        name
      ) VALUES (?) ON DUPLICATE KEY UPDATE name=name;
    ", $name);
    return $result;
  }

  public static function getCategoryByName(string $name) {
    $result = Database::query("
      SELECT id FROM category WHERE name = '$name';
    ");
    return $result->fetch_all(MYSQLI_ASSOC)[0];
  }

  public static function linkProductToCategory(
    int $product_id,
    int $category_id,
  ) {
    $result = Database::preparedQuery("
      INSERT INTO product_category (
        product_id,
        category_id
      ) VALUES (?, ?);
    ", $product_id, $category_id);
    return $result;
  }

  public static function getId($category) {
    return $category['id'];
  }

  public static function getName($category) {
    return $category['name'];
  }

  /**
   * Used for clearing the categories listed on a product
   * Do this whenever a product and its categories
   * are updated
   */
  public static function removeCategoriesFromProduct(
    int $product_id
  ) {
    try {
      return Database::preparedQuery("
        DELETE FROM product_category 
        WHERE product_id = ?;
      ", $product_id);
    } catch (Exception $e) {
      return false;
    }
  }
}