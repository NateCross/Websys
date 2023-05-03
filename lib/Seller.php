<?php

require_once 'Database.php';
require_once 'User.php';
require_once 'Category.php';
require_once 'Product.php';
require_once 'Utils.php';

class Seller extends User {
  protected static function getTableName(): string {
    return 'seller';
  }

  public static function currentUserIsSeller(): bool {
    return self::getCurrentUserType() === 'seller';
  }

  /**
   * Checks if the file is a valid upload
   * Ensures protection from attacks and errors
   */
  private static function verifyFileUpload($file): bool {
    try {
      // Prevents files with errors from proceeding
      if (
        !isset($file['error']) ||
        is_array($file['error'])
      ) {
        throw new Exception('Invalid parameters.');
      }

      if ($file['error'] !== UPLOAD_ERR_OK)
        throw new Exception('File upload has errors');

      if ($file['size'] > 10000000)
        throw new Exception('Exceeded filesize limit');
      if (!Utils\getImageExtension($file))
        throw new Exception('Invalid file format');

      return true;
    } catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
  }

  public static function addProduct(
    string $name,
    string $description,
    int $quantity,
    float $price,
    string $category,
    array $file,
  ): bool {
    if (!$user = self::getCurrentUser()) return false;
    if (!self::currentUserIsSeller()) return false;
    if (!self::verifyFileUpload($file)) return false;

    $file_name = Utils\generateFilename($file);
    $file_path = "../_assets/" . $file_name;

    if(!move_uploaded_file(
      $file['tmp_name'], $file_path
    )) return false;

    ['id' => $id] = $user;

    $result = Database::preparedQuery("
      INSERT INTO product (
        `name`, 
        `seller_id`,
        `description`,
        `quantity`,
        `price`,
        `image_path`
      ) VALUES (?, ?, ?, ?, ?, ?);
    ", $name, $id, $description, $quantity, $price, $file_name);

    if (!$result) return $result;

    if (!Category::createCategory($category)) return false;

    $category_id = Category::getCategoryByName($category)['id'];
    $product_id = Product::getProductByName($name)['id'];

    return Category::linkProductToCategory($product_id, $category_id);
  }

  public static function updateProduct(
    int $product_id,
    string $name,
    string $description,
    int $quantity,
    float $price,
    string $category,
  ): bool {
    try {
      if (!Product::getProducts($product_id)) 
        return false;
      if (!self::getCurrentUser()) return false;
      if (!self::currentUserIsSeller()) return false;

      $result = Database::preparedQuery("
        UPDATE product SET
          name = ?,
          description = ?,
          quantity = ?,
          price = ?
        WHERE id = ?;
      ", $name, $description, $quantity, $price, $product_id);

      if (!$result) return $result;

      if (!Category::createCategory($category)) return false;

      $category_id = Category::getCategoryByName($category)['id'];

      return Category::linkProductToCategory($product_id, $category_id);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function updateProductImage(
    int $id,
    array $image,
  ) {
    try {
      if (!$product = Product::getProducts($id)[0])
        return false;
      if (!self::getCurrentUser()) return false;
      if (!self::currentUserIsSeller()) return false;

      $old_file_path = "../_assets/" . Product::getProductImageAttribute($product);

      if (!unlink($old_file_path)) return false;

      $image_name = \Utils\generateFilename($image);
      $image_path = "../_assets/" . $image_name;

      if(!move_uploaded_file(
        $image['tmp_name'], $image_path
      )) return false;

      return Database::preparedQuery("
        UPDATE product SET
          image_path = ?
        WHERE id = ?;
      ", $image_name, $id);
    } catch (Exception $e) {
      return false;
    }
  }
}