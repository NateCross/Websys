<?php

require_once 'Database.php';
require_once 'User.php';
require_once 'Category.php';
require_once 'Product.php';

class Seller extends User {
  protected static function getTableName(): string {
    return 'seller';
  }

  public static function currentUserIsSeller(): bool {
    return self::getCurrentUserType() === 'seller';
  }

  /**
   * Check file MIME type
   * Determines the true type of the file     
   * $file The file uploaded from a POST request
   */
  private static function getImageExtension($file): int | string | false {
    $fileinfo = new finfo(FILEINFO_MIME_TYPE);

    return array_search(
      $fileinfo->file($file['tmp_name']),

      // Array below determines filetypes to be checked
      [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
      ],

      true,
    );
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
      if (!self::getImageExtension($file))
        throw new Exception('Invalid file format');

      return true;
    } catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
  }

  private static function generateFilename($file): string {
    return sha1_file($file['tmp_name']) 
    . "." 
    . self::getImageExtension($file);
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

    $file_name = self::generateFilename($file);
    $file_path = "../../_assets/" . $file_name;

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
    if (!$product = Product::getProducts($product_id)) 
      return false;
    if (!$user = self::getCurrentUser()) return false;
    if (!self::currentUserIsSeller()) return false;
    return true;
  }

  public static function updateProductImage(
    int $id,
    array $image,
  ) {
    $product = Product::getProducts($id);
  }
}