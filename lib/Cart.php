<?php

require_once 'Database.php';
require_once 'Session.php';
require_once 'Product.php';

class Cart {
  public static function getCart() {
    return Session::get('cart');
  }

  public static function cartContainsProductId(
    int $product_id,
  ): array | null {
    $cart = Session::get('cart');
    if (!$cart) return null;
    foreach ($cart as $index => $product) {
      if (
        $product_id === Product::getProductIdAttribute($product)
      )
        return $index;
    }
    return null;
  }

  public static function getProductQuantityPurchased($product) {
    return $product['quantity_purchased'];
  }

  public static function updateCart($cart) {
    try {
      return Session::set('cart', $cart);
    } catch (Exception $e) {
      return false;
    }
  }

  public static function addToCart(
    int $product_id,
    int $quantity,
  ): bool {
    try {
      $cart = self::getCart();
      if (!$cart) return false;
      if ($index = self::cartContainsProductId($product_id)) {
        $cart[$index]['quantity_purchased'] += $quantity;
        return self::updateCart($cart);
        // $product = $cart[$index];
        // $product['quantity_purchased'] += $quantity;
        // return true;
      } else {
        $product = Product::getProducts($product_id);

        // Manually get quantity purchased to the max quantity
        // Helps prevent user from purchasing too many items
        if (
          $quantity >=
          Product::getProductQuantityAttribute($product)
        )
          $product['quantity_purchased'] = 
            Product::getProductQuantityAttribute($product);
        else 
          $product['quantity_purchased'] = $quantity;

        $cart[] = $product;
        return self::updateCart($cart);
      }
    } catch (Exception $e) {
      return false;
    }
  }
}

