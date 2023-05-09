<?php

class Wishlist {
  public static function getWishlist(
    int $member_id,
  ) {
    try {
      $result = Database::query("
          SELECT *
          FROM `product_wishlist`
          WHERE `member_id` = `$member_id`;
        ");
      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      return false;
    } 
  }

  public static function addToWishlist(
    int $member_id,
    int $product_id,
  ) {
    try {
      return Database::preparedQuery("
        INSERT INTO `product_wishlist` (
          `member_id`,
          `product_id`
        ) VALUES (
          ?,
          ?
        );
      ", $member_id, $product_id);
    } catch (Exception $e) {
      return false;
    }
  }
  
  public static function deleteFromWishlist(
    int $wishlist_id
  ) {
    try {
      return Database::preparedQuery("
        DELETE 
        FROM `product_wishlist`
        WHERE `id` = '$wishlist_id';
      ");
    } catch (Exception $e) {
      return false;
    }
  }
}