<?php

require_once 'Database.php';
require_once 'Session.php';
require_once 'Product.php';
require_once 'Member.php';

class Cart {
  public static function getCart() {
    if (!Session::has('cart'))
      self::updateCart([]);
    return Session::get('cart');
  }

  /**
   * Returns the index of the product
   */
  public static function getProductInCart(
    int $product_id,
  ): int | null {
    $cart = Session::get('cart');
    foreach ($cart as $index => $product) {
      if (
        $product_id === Product::getProductIdAttribute($product)
      ) {
        return $index;
      }
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
      $index = self::getProductInCart($product_id);

      if (isset($index)) {
        echo "nice";
        $cart[$index]['quantity_purchased'] += $quantity;
        return self::updateCart($cart);
        // $product = $cart[$index];
        // $product['quantity_purchased'] += $quantity;
        // return true;
      } else {
        $product = Product::getProducts($product_id)[0];
        if (!$product) return false;

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
      if (self::cartIsEmpty()) return false;
      $cart = self::getCart();
      if (!$cart[$index]) return false;

      if (
        $quantity >=
        Product::getProductQuantityAttribute($cart[$index])
      )
        $cart[$index]['quantity_purchased'] = 
          Product::getProductQuantityAttribute($cart[$index]);
      else 
        $cart[$index]['quantity_purchased'] = $quantity;

      return self::updateCart($cart);
    } catch (Exception $e) {
      return null;
    }
  }

  public static function deleteProduct(int $index) {
    try {
      $cart = self::getCart();

      // Preferred over unset because it reindexes array
      array_splice($cart, $index, 1);
      return self::updateCart($cart);
    } catch (Exception $e) {
      return null;
    }
  }

  public static function clearCart() {
    try {
      return Session::delete('cart');
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Function to place order after items have
   * been added to the cart
   */
  public static function placeOrder() {
    try {
      if (self::cartIsEmpty()) return false;
      $cart = self::getCart();

      $user = Member::getCurrentUser();
      if (!$user) return false;
      if (Member::getCurrentUserType() !== 'member')
        return false;

      $user_id = Member::getUserIdAttribute($user);

      // var_dump(Cart::getProductQuantityPurchased($cart[0]));
      // die();

      Database::preparedQuery(
        "INSERT INTO bill (member_id)
        VALUES (?);"
      , $user_id);

      $bill_id = Database::query("
        SELECT LAST_INSERT_ID();
      ")->fetch_all(MYSQLI_ASSOC)[0]['LAST_INSERT_ID()'];

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
      
      return Cart::clearCart();
    } catch (Exception $e) {
      return false;
    }
  }

  public static function cartIsEmpty() {
    $cart = self::getCart();
    if (!$cart) return true;
    return false;
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

