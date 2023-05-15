<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  Utils\redirectPage('ERROR: Invalid form');
}
if (!Member::getCurrentUser())
  Utils\redirectPage('ERROR: Not currently logged in');

[
  'index' => $index,
  'quantity_purchased' => $quantity_purchased,
] = filter_input_array(INPUT_POST, [
  'index' => FILTER_VALIDATE_INT,
  'quantity_purchased' => FILTER_VALIDATE_INT,
]);

if (!Cart::editProductQuantityPurchased($index, $quantity_purchased))
  Utils\redirectPage('ERROR: Unable to edit product in cart');

Utils\redirect('../cart.php');