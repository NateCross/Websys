<?php

require_once "../lib/require.php";
require_once "../lib/Member.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  ErrorHandler::handleError("Invalid form");
}
if (!Member::getCurrentUser())
  ErrorHandler::handleError("Not currently logged in");

[
  'index' => $index,
] = filter_input_array(INPUT_POST, [
  'index' => FILTER_VALIDATE_INT,
]);

if (!Cart::deleteProduct($index))
  ErrorHandler::handleError('Unable to delete product in cart');
Utils\redirect('../cart.php');