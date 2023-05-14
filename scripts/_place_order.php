<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  Redirect::handleError("Invalid form");
}
if (!Member::getCurrentUser())
  Redirect::handleError("Not currently logged in");

[
  'bank' => $bank,
  'bank_other' => $bank_other,
  'address' => $address,
  'contact_number' => $contact_number
] = filter_input_array(INPUT_POST, [
  'bank' => FILTER_SANITIZE_SPECIAL_CHARS,
  'bank_other' => FILTER_SANITIZE_SPECIAL_CHARS,
  'address' => FILTER_SANITIZE_SPECIAL_CHARS,
  'contact_number' => FILTER_SANITIZE_SPECIAL_CHARS,
]);

if (!Cart::placeOrder(
  $bank,
  $bank_other,
  $address,
  $contact_number,
))
  Redirect::handleError('Unable to order items');

if (!Cart::clearCart())
  Redirect::handleError('Unable to clear items in cart');

Utils\redirect('../index.php');