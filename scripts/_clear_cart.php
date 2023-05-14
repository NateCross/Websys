
<?php

require_once "../lib/require.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  Redirect::handleError("Invalid form");
}
if (!Member::getCurrentUser())
  Redirect::handleError("Not currently logged in");

if (!Cart::clearCart())
  Redirect::handleError('Unable to clear cart');
Utils\redirect('../cart.php');