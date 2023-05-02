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
        SELECT * FROM product WHERE id = '$id';
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

  public static function getProductByName(string $name) {
    $result = Database::query("
      SELECT * FROM product WHERE name = '$name';
    ");
    return $result->fetch_all(MYSQLI_ASSOC)[0];
  }

  public static function getCategories(int $id) {
    $result = Database::query("
      SELECT category.id, category.name
      FROM category
        LEFT JOIN product_category
        ON product_category.category_id = category.id
        LEFT JOIN product
        ON product_category.product_id = product.id
        AND product.id = $id;
    ");
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public static function getBills() {

  }

  public static function getSellerById(int $id) {
    $result = Database::query("
      SELECT seller.id, seller.name
      FROM seller
        LEFT JOIN product
        ON product.seller_id = seller.id
        AND product.id = $id;
    ");
    return $result->fetch_all(MYSQLI_ASSOC)[0];
  }

  public static function getSellerByProduct($product) {
    return self::getSellerById($product['seller_id']);
  }

  public static function getImagePath($product) {
    return "_assets/" . self::getProductImageAttribute($product);
  }

  public static function getProductImageAttribute($product) {
    return $product['image_path'];
  }

  public static function getProductNameAttribute($product) {
    return $product['name'];
  }

  public static function getProductIdAttribute($product) {
    return $product['id'];
  }

  public static function getProductSellerIdAttribute($product) {
    return $product['seller_id'];
  }

  public static function getProductDescriptionAttribute($product) {
    return $product['description'];
  }

  public static function getProductCategoryAttribute($product) {
    return self::getCategories($product['id'])[0];
  }

  public static function getProductQuantityAttribute($product) {
    return $product['quantity'];
  }

  public static function getProductPriceAttribute($product) {
    return $product['price'];
  }

  public static function deleteProduct(int $id) {
    try {
      return Database::preparedQuery("
        DELETE FROM product WHERE id = $id;
      ");
    } catch (Exception $e) {
      return false;
    }
  }
  
  /**
   * To buy a product, we need to have a bill
   * Then we add products to that bill
   * When a product_bill is added, we subtract that much
   * to the quantity of the original product
   */
  public static function buyProduct(int $id, int $quantity) {
    return Database::preparedQuery();
  }
}