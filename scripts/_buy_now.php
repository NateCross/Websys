<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  Utils\redirectPage("ERROR: Invalid form");
}
if (!Member::getCurrentUser())
  Utils\redirectPage("ERROR: Not currently logged in");

[
  'product_id' => $product_id,
  'buy_now_quantity' => $buy_now_quantity,
  'bank' => $bank,
  'bank_other' => $bank_other,
  'address' => $address,
  'contact_number' => $contact_number
] = filter_input_array(INPUT_POST, [
  'product_id' => FILTER_VALIDATE_INT,
  'buy_now_quantity' => FILTER_VALIDATE_INT,
  'bank' => FILTER_SANITIZE_SPECIAL_CHARS,
  'bank_other' => FILTER_SANITIZE_SPECIAL_CHARS,
  'address' => FILTER_SANITIZE_SPECIAL_CHARS,
  'contact_number' => FILTER_SANITIZE_SPECIAL_CHARS,
]);

Cart::clearCart();

if (!Cart::addToCart($product_id, $buy_now_quantity))
  Utils\redirectPage('ERROR: Unable to add item');

if (!Cart::placeOrder(
  $bank,
  $bank_other,
  $address,
  $contact_number,
))
  Utils\redirectPage("ERROR: Unable to order item");

Utils\redirectPage('Purchase successful!', 'purchases.php');