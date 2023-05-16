<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  Utils\redirectPage('ERROR: Invalid form');
}
if (!Member::getCurrentUser())
  Utils\redirect("../login.php");

if (Member::getCurrentUserType() !== 'member')
  Utils\redirect("../login.php");

[
  'product_id' => $product_id,
  'quantity_purchased' => $quantity_purchased,
] = filter_input_array(INPUT_POST, [
  'product_id' => FILTER_VALIDATE_INT,
  'quantity_purchased' => FILTER_VALIDATE_INT,
]);

if (!Cart::addToCart($product_id, $quantity_purchased))
  Utils\redirectPage('ERROR: Unable to add product to cart');
Utils\redirect('../cart.php');