
<?php

require_once "../lib/require.php";
require_once "../lib/Cart.php";

if (!isset($_POST['submit'])) {
  Utils\redirectPage('ERROR: Invalid form');
}
if (!Member::getCurrentUser())
  Utils\redirectPage('ERROR: Not currently logged in');

if (!Cart::clearCart())
  Utils\redirectPage('ERROR: Unable to clear cart');

Utils\redirect('../cart.php');