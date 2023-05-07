<?php

require_once 'Database.php';
require_once 'User.php';
require_once 'Category.php';
require_once 'Product.php';
require_once 'Utils.php';
require_once 'Report.php';

class Seller extends User {
  protected static function getTableName(): string {
    return 'seller';
  }

  public static function currentUserIsSeller(): bool {
    return self::getCurrentUserType() === 'seller';
  }

  /** 
   * Creating a new login function for sellers
   * to account for suspended sellers
   */
  public static function login(
    string $email,
    string $password,
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

      if ($date_suspended = new DateTime(self::getSuspendedDateAttribute($user))) {
        $date_today = new DateTime();
        if ($date_today >= $date_suspended) {
          self::unsuspendSeller(
            self::getUserIdAttribute($user)
          );
        } else {
          $date_suspended_readable = $date_suspended->format('Y-m-d');
          throw new Exception("
            User is suspended until $date_suspended_readable
          ");
        }
      }
      
      // Remove password since this will not be necessary
      // and it could pose a security risk
      unset($user['password']);

      self::setCurrentUser($user);

      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function suspendSeller(
    int $seller_id, 
    int $days_suspended,
    int $report_id = null,
  ) {
    try {
      $result = Database::preparedQuery("
        CALL suspend_seller(?, ?);
      ", $seller_id, $days_suspended);

      if ($report_id) {
        if (!Report::toggleReportStatus($report_id))
          return false;
      }

      return $result;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function unsuspendSeller(
    int $id, 
  ) {
    try {
      return Database::preparedQuery("
        CALL unsuspend_seller(?);
      ", $id);
    } catch (Exception $e) {
      return false;
    }
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

  public static function getProducts(int $seller_id) {
    try {
      $result = Database::query("
        SELECT product.*
        FROM seller
          LEFT JOIN product
          ON product.seller_id = seller.id
          AND seller.id = $seller_id;
      ");
      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function searchSeller(string $query) {
    return Database::query("
      SELECT * FROM seller
      WHERE name LIKE '%$query%';
    ")->fetch_all(MYSQLI_ASSOC);
  }

  public static function getSuspendedDateAttribute($seller) {
    return $seller['suspended_until'];
  }
}