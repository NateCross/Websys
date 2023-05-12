
<?php

require_once "../lib/require.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  ErrorHandler::handleError("Invalid form");
}
if (!Member::getCurrentUser())
  ErrorHandler::handleError("Not currently logged in");

if (!Cart::clearCart())
  ErrorHandler::handleError('Unable to clear cart');
Utils\redirect('../cart.php');