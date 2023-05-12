<?php

require_once "../lib/require.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  ErrorHandler::handleError("Invalid form");
}
if (!Member::getCurrentUser())
  ErrorHandler::handleError("Not currently logged in");

if (!Cart::placeOrder())
  ErrorHandler::handleError('Unable to order items');

if (!Cart::clearCart())
  ErrorHandler::handleError('Unable to clear items in cart');

Utils\redirect('../index.php');