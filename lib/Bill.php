<?php

require_once 'Database.php';
require_once 'Error.php';

class Bill {
  public static function getProducts(int $bill_id) {
    try {
      $result = Database::query("
        SELECT product.*
        FROM product
          LEFT JOIN product_bill
          ON product_bill.product_id = product.id
          LEFT JOIN bill
          ON product_bill.bill_id = bill.id
          AND bill.id = $bill_id;
      ");
      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return false;
    }
  }
}