<?php

require_once 'User.php';

/**
 * Model for the member user type in the system
 * 
 * To store the member, sessions are used instead 
 * of static variables
 */
class Member extends User {
  protected static function getTableName(): string {
    return 'member';
  }

  public static function reportSeller(
    int $member_id,
    int $seller_id,
    string $message,
  ): bool {
    try {
      return Database::preparedQuery("
        INSERT INTO `report` (
          `message`,
          `member_id`,
          `seller_id`
        ) VALUES (?, ?, ?);
      ", $message, $member_id, $seller_id);
    } catch (Exception $e) {
      return false;
    }
  }

  // TODO
  public static function getBills(int $member_id) {
    $result = Database::query("
      SELECT * FROM bill
      WHERE member_id = $member_id;
    ");
    // $result = Database::query("
    //   SELECT product.*, product_bill.quantity
    //   FROM bill
    //     LEFT JOIN product_bill
    //       ON product_bill.bill_id = bill.id
    //       AND bill.member_id = $member_id
    //     LEFT JOIN 
    // ");
    // $result = Database::query("
    //   SELECT product.*, product_bill.quantity
    //   FROM product
    //     LEFT JOIN product_bill
    //       ON product_bill.product_id = product.id
    //     LEFT JOIN 
    // ");
  }

  public static function getBillsWithProducts(int $member_id) {
    try {
      $result = Database::query("
        SELECT 
          bill.id bill_id,
          bill.last_modified bill_timestamp,
          product.*, 
          product_bill.quantity quantity_purchased,
          product_bill.is_reviewed
        FROM
          bill
          LEFT JOIN product_bill
            ON product_bill.bill_id = bill.id
            AND bill.member_id = $member_id
          LEFT JOIN product
            ON product_bill.product_id = product.id
        ORDER BY bill_timestamp DESC;
      ");
      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return false;
    }
  }
}