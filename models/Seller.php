<?php

require_once 'User.php';

class Seller extends User {
  protected static function getTableName(): string {
    return 'seller';
  }

  public static function addProduct(
    string $name,
    string $description,
    int $quantity,
    float $price,
  ): bool {
    return true;
  }
}