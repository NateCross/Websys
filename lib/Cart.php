<?php

require_once 'Database.php';
require_once 'Session.php';
require_once 'Product.php';
require_once 'Member.php';

class Cart {
  public static function getCart() {
    return Session::get('cart');
  }

  /**
   * Returns the index of the product
   */
  public static function getProductInCart(
    int $product_id,
  ): int | null {
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

  /**
   * Execute this function after modifying the cart
   * so it saves in the session
   */
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
      if ($index = self::getProductInCart($product_id)) {
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

  public static function editProductQuantityPurchased(
    int $index,
    int $quantity,
  ) {
    try {
      $cart = self::getCart();
      if (!$cart) return false;

      $cart[$index]['quantity_purchased'] += $quantity;
      return self::updateCart($cart);
    } catch (Exception $e) {
      return null;
    }
  }

  public static function deleteProduct(int $index) {
    try {
      $cart = self::getCart();
      if (!$cart) return false;

      // Preferred over unset because it reindexes array
      array_splice($cart, $index, 1);
      return self::updateCart($cart);
    } catch (Exception $e) {
      return null;
    }
  }

  public static function placeOrder() {
    try {
      $cart = self::getCart();
      if (!$cart) return false;

      $user = Member::getCurrentUser();
      if (!$user) return false;
      if (Member::getCurrentUserType() !== 'member')
        return false;

      $user_id = Member::getUserIdAttribute($user);

      Database::preparedQuery(
        "INSERT INTO bill (member_id)
        VALUES (?);"
      , $user_id);

      $bill_id = Database::query("
        SELECT LAST_INSERT_ID();
      ")->fetch_all(MYSQLI_ASSOC)[0];

      foreach ($cart as $product) {
        Database::preparedQuery("
            INSERT INTO product_bill
              (product_id, bill_id, quantity)
            VALUES (?, ?, ?);
          ",
          Product::getProductIdAttribute($product),
          $bill_id,
          Cart::getProductQuantityPurchased($product),
        );
      }

    } catch (Exception $e) {
      return false;
    }
  }

  // public static function getProductsInCart() {
  //   try {
  //     $cart = self::getCart();
  //     if (!$cart) return null;

  //     $products = Database::preparedQuery();

  //   } catch (Exception $e) {
  //     return null;
  //   }
  // }
}

